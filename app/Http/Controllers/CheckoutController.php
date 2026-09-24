<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Kikapu chako kipo wazi. Tafadhali chagua marashi kwanza.');
        }

        $subtotal = array_reduce($cart, function ($acc, $item) {
            return $acc + ($item['price'] * $item['quantity']);
        }, 0);

        $freeThreshold = config('payment.free_delivery_threshold', 100000);
        $shipping = ($subtotal >= $freeThreshold) ? 0 : config('payment.delivery_fee', 5000);
        $total = $subtotal + $shipping;

        $paymentConfig = config('payment');

        $savedAddresses = collect();
        $defaultAddress = null;

        if (Auth::check() && Auth::user() instanceof User) {
            /** @var User $user */
            $user = Auth::user();
            if (! $user->isAdmin()) {
                $savedAddresses = $user->addresses()->latest()->get();
                $defaultAddress = $user->defaultAddress;
            }
        }

        $checkoutMode = (string) old('checkout_mode', Auth::check() ? 'logged_in' : 'guest');
        $selectedAddressId = old('saved_address_id', $defaultAddress?->id);
        $prefill = $this->prefillFromAddress($selectedAddressId, $defaultAddress, $savedAddresses);

        return view('checkout.index', compact(
            'cart',
            'subtotal',
            'shipping',
            'total',
            'paymentConfig',
            'savedAddresses',
            'defaultAddress',
            'checkoutMode',
            'selectedAddressId',
            'prefill'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $mode = (string) $request->input('checkout_mode', 'guest');
        /** @var User|null $maybeUser */
        $maybeUser = Auth::user();
        $isLoggedIn = $maybeUser instanceof User && ! $maybeUser->isAdmin();

        if (! $isLoggedIn && $mode !== 'guest') {
            return redirect()->guest(route($mode === 'login' ? 'login' : 'register'))
                ->with('info', 'Please '.($mode === 'login' ? 'sign in' : 'create an account').' to continue with this checkout option.');
        }

        $useSavedAddress = false;
        $selectedAddress = null;

        if ($isLoggedIn) {
            $savedId = $request->input('saved_address_id');
            if (! empty($savedId)) {
                /** @var User $user */
                $user = Auth::user();
                $selectedAddress = $user->addresses()->find($savedId);
                $useSavedAddress = (bool) $selectedAddress;
            }
        }

        if ($useSavedAddress && $selectedAddress instanceof Address) {
            $request->merge([
                'customer_name' => $request->input('customer_name') ?: $selectedAddress->full_name,
                'customer_phone' => $request->input('customer_phone') ?: $selectedAddress->phone,
                'city' => $request->input('city') ?: $selectedAddress->city,
                'shipping_address' => $request->input('shipping_address') ?: $selectedAddress->street_address,
            ]);
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'nullable|email|max:255',
            'city' => 'required|string|max:100',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string|max:1000',
            'checkout_mode' => 'required|string|in:guest,login,register,logged_in',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Kikapu chako kipo wazi.');
        }

        $subtotal = array_reduce($cart, function ($acc, $item) {
            return $acc + ($item['price'] * $item['quantity']);
        }, 0);

        $freeThreshold = config('payment.free_delivery_threshold', 100000);
        $shipping = ($subtotal >= $freeThreshold) ? 0 : config('payment.delivery_fee', 5000);
        $total = $subtotal + $shipping;

        $orderNumber = Order::generateNextOrderNumber();

        $isWhatsapp = ($request->payment_method === 'whatsapp');

        /** @var User|null $authUser */
        $authUser = $isLoggedIn ? Auth::user() : null;

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $authUser?->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email ?? $authUser?->email,
            'city' => $request->city,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total_amount' => $total,
            'status' => 'new',
            'payment_method' => $request->payment_method,
            'whatsapp_ordered' => $isWhatsapp,
        ]);

        foreach ($cart as $item) {
            $variantSize = $item['size'] ?? $item['variant_size'] ?? '50ml Signature Bottle';

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'],
                'variant_size' => $variantSize,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');

        $flash = 'Oda yako imepokelewa kikamilifu! Order number: '.$order->order_number;

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', $flash)
            ->with('order_just_created', true);
    }

    private function prefillFromAddress(mixed $selectedId, mixed $default, mixed $all): array
    {
        $address = null;
        if (! empty($selectedId)) {
            $address = $all?->firstWhere('id', (int) $selectedId);
        }
        if (! $address instanceof Address && $default instanceof Address) {
            $address = $default;
        }

        if (! $address instanceof Address) {
            return [
                'customer_name' => old('customer_name', Auth::check() ? Auth::user()?->name : null),
                'customer_phone' => old('customer_phone', Auth::check() ? Auth::user()?->phone : null),
                'customer_email' => old('customer_email', Auth::check() ? Auth::user()?->email : null),
                'city' => old('city'),
                'shipping_address' => old('shipping_address'),
            ];
        }

        return [
            'customer_name' => old('customer_name', $address->full_name),
            'customer_phone' => old('customer_phone', $address->phone),
            'customer_email' => old('customer_email', Auth::check() ? Auth::user()?->email : null),
            'city' => old('city', $address->city),
            'shipping_address' => old('shipping_address', $address->street_address),
        ];
    }
}
