<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\WhatsappVerificationController;


class UserController extends Controller
{
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->email_verified_at = null; // Reset email verification status
        $user->save();

        // Trigger email verification process
        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
    public function changePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|unique:users,whatsapp',
        ]);

        $user = Auth::user();
        $user->whatsapp = $request->phone;
        $user->whatsapp_verified_at = null;
        $user->save();

        // Call the resendCode method in the WhatsappVerificationController
        app(WhatsappVerificationController::class)->resend_code_change_number($user);

        return redirect()->back()->with('status', 'Phone number updated and verification code sent.');
    }
}
