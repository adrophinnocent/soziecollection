<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $totalOrders = $user->orders()->count();
        $wishlistCount = $user->wishlists()->count();
        $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total_amount');
        $recentOrders = $user->orders()->with('items')->latest()->take(5)->get();

        return view('account.dashboard', compact(
            'user',
            'totalOrders',
            'wishlistCount',
            'totalSpent',
            'recentOrders'
        ));
    }

    public function orders(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $user->orders()->with('items')->latest()->paginate(10);

        return view('account.orders', compact('user', 'orders'));
    }

    public function showOrder(string $orderNumber): View
    {
        /** @var User $user */
        $user = Auth::user();

        $order = Order::with(['items.product', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->user_id !== $user->id) {
            abort(403, __('This order does not belong to your account.'));
        }

        return view('account.show-order', compact('user', 'order'));
    }

    public function wishlist(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $wishlistItems = $user->wishlists()->with('product')->latest()->get();

        return view('account.wishlist', compact('user', 'wishlistItems'));
    }

    public function addresses(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $addresses = $user->addresses()->latest()->get();

        return view('account.addresses', compact('user', 'addresses'));
    }

    public function createAddress(): View
    {
        $user = Auth::user();

        return view('account.address-form', compact('user'));
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (! empty($validated['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            ...$validated,
            'is_default' => ! empty($validated['is_default']) ? true : ($user->addresses()->count() === 0),
        ]);

        return redirect()->route('account.addresses')
            ->with('success', __('Shipping address saved successfully.'));
    }

    public function editAddress(int $id): View
    {
        /** @var User $user */
        $user = Auth::user();

        $address = $user->addresses()->findOrFail($id);

        return view('account.address-form', compact('user', 'address'));
    }

    public function updateAddress(Request $request, int $id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (! empty($validated['is_default'])) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update([
            ...$validated,
            'is_default' => ! empty($validated['is_default']),
        ]);

        return redirect()->route('account.addresses')
            ->with('success', __('Address updated successfully.'));
    }

    public function destroyAddress(int $id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $address = $user->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault && $user->addresses()->count() > 0) {
            $user->addresses()->first()->update(['is_default' => true]);
        }

        return redirect()->route('account.addresses')
            ->with('success', __('Address removed successfully.'));
    }

    public function setDefaultAddress(int $id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->addresses()->update(['is_default' => false]);

        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return redirect()->route('account.addresses')
            ->with('success', __('Default shipping address updated.'));
    }

    public function profile(): View
    {
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($validated);

        return redirect()->route('account.profile')
            ->with('success', __('Your profile information has been updated.'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.profile')
            ->with('success', __('Your password has been changed successfully.'));
    }
}
