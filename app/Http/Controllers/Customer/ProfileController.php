<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        return view('shop.profile', compact('user', 'addresses'));
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed.');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label'        => ['required', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city'         => ['required', 'string', 'max:100'],
            'state'        => ['required', 'string', 'max:100'],
            'pincode'      => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'label'        => $request->label,
            'address_line' => $request->address_line,
            'city'         => $request->city,
            'state'        => $request->state,
            'pincode'      => $request->pincode,
            'is_default'   => $isFirst || $request->boolean('is_default'),
        ]);

        return back()->with('success', 'Address added.');
    }

    public function setDefault(Address $address)
    {
        $user = Auth::user();
        abort_if($address->user_id !== $user->id, 403);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }

    public function destroyAddress(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        $address->delete();
        return back()->with('success', 'Address removed.');
    }
}