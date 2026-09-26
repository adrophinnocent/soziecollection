<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Support\WhatsappOrderMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private WhatsappOrderMessage $whatsappOrderMessage) {}

    public function show(string $orderNumber): View|RedirectResponse
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $this->authorizeViewOrder($order, request());

        $paymentConfig = config('payment');
        $waMessage = $this->whatsappOrderMessage->build($order);
        $whatsappPhone = preg_replace('/\D+/', '', (string) config('payment.whatsapp.phone_number', '255691980178'));
        $whatsappUrl = 'https://wa.me/'.$whatsappPhone.'?text='.urlencode($waMessage);

        return view('orders.show', [
            'order' => $order,
            'whatsappUrl' => $whatsappUrl,
            'shareUrl' => $this->whatsappOrderMessage->shareUrl($order),
            'paymentConfig' => $paymentConfig,
        ]);
    }

    public function share(Order $order): View
    {
        $order->load('items.product');

        return view('orders.share', [
            'order' => $order,
            'shareImageUrl' => $this->whatsappOrderMessage->primaryImageUrl($order),
        ]);
    }

    public function track(Request $request): View
    {
        $order = null;
        $lookupFailed = false;
        $orderNumber = trim((string) $request->query('order_number', ''));
        $contact = trim((string) $request->query('tracking_contact', ''));

        if (! empty($orderNumber)) {
            $lookup = Order::with('items.product')
                ->where('order_number', $orderNumber)
                ->first();

            if (! $lookup) {
                $lookupFailed = true;
            } elseif ($this->canViewOrder($lookup, $request, $contact)) {
                $order = $lookup;
            } else {
                $lookupFailed = true;
            }
        }

        return view('orders.track', compact('order', 'lookupFailed', 'orderNumber', 'contact'));
    }

    public function handleTrack(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'tracking_contact' => ['nullable', 'string', 'max:255'],
        ]);

        $orderNumber = trim($validated['order_number']);
        $contact = trim((string) ($validated['tracking_contact'] ?? ''));

        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return back()->withInput()
                ->withErrors([
                    'order_number' => 'No order found with number: '.$orderNumber,
                ]);
        }

        if (! $this->canViewOrder($order, $request, $contact)) {
            return back()->withInput()
                ->withErrors([
                    'tracking_contact' => 'For your privacy, please provide the phone number or email address used to place this order.',
                ]);
        }

        return redirect()->route('orders.show', $orderNumber)
            ->with([
                'tracking_contact' => $contact,
            ]);
    }

    private function canViewOrder(Order $order, Request $request, ?string $contactProvided = null): bool
    {
        if (Auth::check() && Auth::user() instanceof User) {
            /** @var User $viewer */
            $viewer = Auth::user();

            if ($viewer->isAdmin()) {
                return true;
            }

            if (! empty($order->user_id) && (int) $order->user_id === (int) $viewer->id) {
                return true;
            }
        }

        if ($contactProvided === null) {
            $candidates = [
                $request->query('tracking_contact'),
                $request->input('tracking_contact'),
                session('tracking_contact'),
            ];
            foreach ($candidates as $c) {
                if (is_string($c) && trim($c) !== '') {
                    $contactProvided = trim($c);
                    break;
                }
            }
            if ($contactProvided === null) {
                $contactProvided = '';
            }
        }

        $storedContacts = array_values(array_filter([
            (string) ($order->customer_phone ?? ''),
            (string) ($order->phone ?? ''),
            (string) ($order->customer_email ?? ''),
            (string) ($order->email ?? ''),
        ], fn ($s) => ! empty(trim((string) $s))));

        if (empty($storedContacts)) {
            /** @var User|null $maybeUser */
            $maybeUser = Auth::user();

            return $maybeUser instanceof User && $maybeUser->isAdmin();
        }

        if (empty($contactProvided)) {
            return false;
        }

        $normalizedContact = $this->normalizeContact($contactProvided);

        foreach ($storedContacts as $stored) {
            if ($this->normalizeContact((string) $stored) === $normalizedContact) {
                return true;
            }
        }

        return false;
    }

    private function normalizeContact(string $contact): string
    {
        $contact = trim(strtolower($contact));

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return $contact;
        }

        $digits = (string) preg_replace('/[^0-9]/', '', $contact);

        if (str_starts_with($digits, '255') && strlen($digits) === 12) {
            $digits = '0'.substr($digits, 3);
        } elseif (str_starts_with($digits, '00255') && strlen($digits) === 14) {
            $digits = '0'.substr($digits, 5);
        }

        return $digits;
    }

    private function authorizeViewOrder(Order $order, Request $request): void
    {
        if (! $this->canViewOrder($order, $request)) {
            abort(403, 'Sorry, you are not authorized to view this order. Please provide the phone number or email used when placing this order via the Track Order page.');
        }
    }
}
