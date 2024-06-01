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
use GeoIP;

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
        $sessions = $sessionController->index();

        // Get GeoIP information
        //$geoip = geoip()->getLocation($request->ip());
        //$country = $geoip->country;

        return view('pages.profile.index', compact('user', 'last_login', 'address', 'created_at', 'sessions'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        // Update address details if necessary
        $address = $user->default_address;
        $address->address_line_1 = $request->input('address1');
        $address->address_line_2 = $request->input('address2');
        $address->city = $request->input('city');
        $address->state = $request->input('state');
        $address->postal_code = $request->input('postcode');
        $address->country = $request->input('country');
        $address->save();

        return redirect()->back()->with('status', 'Profile updated successfully!');
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->email === $request->profile_email) {
            return response()->json(['error' => 'The new email address is the same as the current one.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'profile_email' => 'required|email|unique:users,email,' . Auth::id(),
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

        //return redirect()->back()->with('status', 'Phone number updated and verification code sent.');
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
}
