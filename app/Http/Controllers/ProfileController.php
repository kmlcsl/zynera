<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Fill other profile data
        $user->fill($request->validated());

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->forceFill(['email_verified_at' => null]);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Remove avatar
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Password yang dimasukkan salah.',
        ]);

        $user = $request->user();

        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', 'Akun berhasil dihapus. Terima kasih telah menggunakan AgriConnect!');
    }

    /**
     * Show user profile (public view)
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Get user statistics
        $stats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'member_since' => $user->created_at->format('F Y'),
            'reviews_count' => $user->reviews()->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'order_updates' => 'boolean',
            'promotion_notifications' => 'boolean',
            'newsletter' => 'boolean',
        ]);

        $user = $request->user();

        // You can store these in a separate notifications table or user preferences
        // For now, we'll use JSON field or add columns to users table
        $preferences = [
            'email_notifications' => $request->boolean('email_notifications'),
            'order_updates' => $request->boolean('order_updates'),
            'promotion_notifications' => $request->boolean('promotion_notifications'),
            'newsletter' => $request->boolean('newsletter'),
        ];

        // Assuming you add a JSON field 'notification_preferences' to users table
        $user->update(['notification_preferences' => $preferences]);

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui!');
    }

    /**
     * Get user's order history for profile
     */
    public function orderHistory(Request $request): View
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with(['orderItems.product', 'payment'])
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Get user's review history
     */
    public function reviewHistory(Request $request): View
    {
        $user = $request->user();

        $reviews = $user->reviews()
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('profile.reviews', compact('reviews'));
    }
}
