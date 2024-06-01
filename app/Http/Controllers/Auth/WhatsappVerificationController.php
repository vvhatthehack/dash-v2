<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsappVerificationController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail() && $request->user()->hasVerifiedWhatsapp()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        if (!$request->user()->hasVerifiedEmail()) {
            addJavascriptFile('assets/js/custom/authentication/verify-email/general.js');
            return redirect()->intended('/verify-email');
        }

        if (!$request->user()->hasVerifiedWhatsapp()) {
            addJavascriptFile('assets/js/custom/authentication/verify-whatsapp/general.js');
            $user = Auth::user();
            return view('pages/auth.verify-whatsapp', compact('user'));
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code_1' => 'required|numeric',
            'code_2' => 'required|numeric',
            'code_3' => 'required|numeric',
            'code_4' => 'required|numeric',
            'code_5' => 'required|numeric',
            'code_6' => 'required|numeric',
        ]);

        $expectedCode = Auth::user()->whatsapp_verification_code;
        $submittedCode = implode('', $request->only(['code_1', 'code_2', 'code_3', 'code_4', 'code_5', 'code_6']));

        $expectedCode = trim($expectedCode);
        $submittedCode = trim($submittedCode);

        if ($submittedCode !== $expectedCode) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code. Please try again.'], 200);
        }

        $user = Auth::user();
        $user->whatsapp_verified_at = now();
        $user->save();

        return response()->json(['success' => true, 'redirect_url' => route('dashboard')], 200);
    }

    public function resend_code_change_number($user)
    {
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['whatsapp_verification_code' => $verificationCode]);

        $whatsappNumber = $this->convertToInternationalFormat($user->whatsapp);

        $response = Http::withHeaders([
            "Accept" => "*/*",
            "Content-Type" => "application/json",
        ])->post(env('WHATSAPP_GATEWAY_API_URL'), [
            'apikey' => env('WHATSAPP_GATEWAY_API_KEY'),
            'mtype' => 'text',
            'text' => "*Account Verification Code*\n*$verificationCode* is your WhatsApp verification code\n_S-Network_",
            'receiver' => $whatsappNumber,
        ]);

        if ($response->failed()) {
            Log::error('Failed to send WhatsApp verification code', [
                'user_id' => $user->id,
                'receiver' => $user->whatsapp,
                'response' => $response->body()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to resend verification code.'], 200);
        }

        return response()->json(['success' => true, 'message' => 'Verification code has been resent.'], 200);
    }
    public function resend_code_verification(Request $request)
    {
        $user = Auth::user();
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['whatsapp_verification_code' => $verificationCode]);

        $whatsappNumber = $this->convertToInternationalFormat($user->whatsapp);

        $response = Http::withHeaders([
            "Accept" => "*/*",
            "Content-Type" => "application/json",
        ])->post(env('WHATSAPP_GATEWAY_API_URL'), [
            'apikey' => env('WHATSAPP_GATEWAY_API_KEY'),
            'mtype' => 'text',
            'text' => "*Account Verification Code*\n*$verificationCode* is your WhatsApp verification code\n_S-Network_",
            'receiver' => $whatsappNumber,
        ]);

        if ($response->failed()) {
            Log::error('Failed to send WhatsApp verification code', [
                'user_id' => $user->id,
                'receiver' => $user->whatsapp,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to resend verification code.'], 200);
        }

        return response()->json(['success' => true, 'message' => 'Verification code has been resent.'], 200);
    }


    private function convertToInternationalFormat($number)
    {
        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }

        return $number;
    }
}
