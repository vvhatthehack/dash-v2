<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class SendWhatsappVerificationCode
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $user = $event->user;
        if ($user->whatsapp_verified_at === null) {
            // Convert phone number format
            $whatsappNumber = $this->convertToInternationalFormat($user->whatsapp);

            // Generate a random verification code
            $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store the verification code in the database
            $user->update(['whatsapp_verification_code' => $verificationCode]);

            // Replace with the actual endpoint and payload format required by wagw.sss.id
            $response = Http::withHeaders([
                "Accept" => "*/*",
                "Content-Type" => "application/json",
            ])->post(env('WHATSAPP_GATEWAY_API_URL'), [
                        'apikey' => env('WHATSAPP_GATEWAY_API_KEY'),
                        'mtype' => 'text',
                        'text' => "*Account Verification Code*\n*$verificationCode* is your whatsapp verification code\n_S-Network_",
                        'receiver' => $whatsappNumber,
                    ]);

            if ($response->failed()) {
                // Handle the failed request, e.g., log an error
                \Log::error('Failed to send WhatsApp verification code', [
                    'user_id' => $user->id,
                    'receiver' => $user->whatsapp,
                    'response' => $response->body()
                ]);
            }
        }
    }
    private function convertToInternationalFormat($number)
    {
        // Check if the number starts with '0'
        if (substr($number, 0, 1) === '0') {
            // Remove '0' and prepend '62'
            return '62' . substr($number, 1);
        }

        // Number is already in international format
        return $number;
    }
}
