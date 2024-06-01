<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\Auth\WhatsappVerificationController;
use Illuminate\Support\Facades\Hash;
use App\Models\SessionHistory;
use App\Http\Controllers\SessionController;
use Stevebauman\Location\Facades\Location;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        addJavascriptFile('assets/js/custom/account/my-profile/general.js');
        addJavascriptFile('assets/js/custom/account/my-profile/update-email.js');
        addJavascriptFile('assets/js/custom/account/my-profile/update-phone.js');
        addJavascriptFile('assets/js/custom/account/my-profile/update-password.js');

        $user = Auth::user(); // Fetch the authenticated user
        $last_login = $user->last_login_at;
        $created_at = $user->created_at;

        if ($last_login) {
            $last_login = Carbon::parse($last_login)->format('d M Y H:i');
        } else {
            $last_login = 'N/A'; // Handle the case where last_login_at is null
        }
        if ($created_at) {
            $created_at = Carbon::parse($created_at)->format('d M Y H:i');
        } else {
            $created_at = 'N/A'; // Handle the case where created_at is null
        }

        // Fetch the user's address
        $address = $user->default_address;

        // Fetch the user's session
        $sessionController = new SessionController();
        $sessions = $sessionController->index()->map(function ($session) {
            $session->country = $this->isLocalIP($session->ip_address) ? 'Local IP' : $session->country;
            return $session;
        });

        // Get GeoIP information using Cloudflare driver
        $ip = $request->ip();
        if ($this->isLocalIP($ip)) {
            $country = 'Local IP';
            $city = 'Local IP';
        } else {
            $geoip = Location::get($ip);

            if ($geoip) {
                $country = $geoip->countryName ?? 'Unknown';
                $city = $geoip->cityName ?? 'Unknown';
            } else {
                $country = 'Unknown';
                $city = 'Unknown';
            }

            // Debugging: Log the geoip information
            \Log::info('GeoIP Information:', ['geoip' => $geoip]);
        }

        return view('pages.profile.index', compact('user', 'last_login', 'address', 'created_at', 'sessions', 'country', 'city'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

         // Update address details if necessary
         $address = $user->default_address;
         $address->address_line_1 = $request->input('address1');
         $address->address_line_2 = $request->input('address2');
         $address->city = $request->input('city');
         $address->state = $request->input('state');
         $address->postal_code = $request->input('postcode');
         $address->country = $request->input('country');
         $address->save();

        // Handle avatar removal
        if ($request->has('avatar_remove') && $request->avatar_remove == 1) {
            if ($user->profile_photo_path && file_exists(public_path($user->profile_photo_path))) {
                unlink(public_path($user->profile_photo_path));
            }
            $user->profile_photo_path = null;
        }

        // Handle new avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile_photo_path && file_exists(public_path($user->profile_photo_path))) {
                unlink(public_path($user->profile_photo_path));
            }

            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $avatarName);
            $user->profile_photo_path = 'avatars/' . $avatarName;
        }

        // Update other fields
        $user->name = $request->name;
        $user->save();

        return redirect()->back()->with('status', 'Profile updated successfully!');
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->email === $request->profile_email) {
            return response()->json(['error' => 'The new email address is the same as the current one.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'profile_email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user->email = $request->profile_email;
        $user->email_verified_at = null;
        $user->save();

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'Email updated successfully!']);
    }

    public function updatePhone(Request $request)
    {
        $user = Auth::user();

        if ($user->phone === $request->profile_phone) {
            return response()->json(['error' => 'The new phone number is the same as the current one.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'profile_phone' => 'required|numeric|unique:users,whatsapp,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user->whatsapp = $request->profile_phone;
        $user->whatsapp_verified_at = null;
        $user->save();

        // Call the resendCode method in the WhatsappVerificationController
        app(WhatsappVerificationController::class)->resend_code_change_number($user);

        return response()->json(['status' => 'Phone updated successfully!']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'The current password is incorrect.'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['status' => 'Password updated successfully!']);
    }

    private function isLocalIP($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1']) || substr($ip, 0, 8) === '192.168.' || substr($ip, 0, 7) === '10.0.0';
    }
}
