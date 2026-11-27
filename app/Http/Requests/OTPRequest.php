<?php

namespace App\Http\Requests;

use App\Models\Authentication;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OTPRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'otp' => [
                'required',
                'digits:6',
                function ($attribute, $value, $fail) {
                    $authentication = Authentication::where([
                        ['user_id', Auth::id()],
                        ['status', 0],
                        ['type', 'register'],
                    ])->latest('id')->first();

                    if (! $authentication) {
                        $fail('No active OTP was found. Request a new code.');
                        return;
                    }

                    if (! hash_equals((string) $authentication->token, (string) $value)) {
                        $fail('Invalid OTP.');
                        return;
                    }

                    if (! $authentication->expires_on || Carbon::parse($authentication->expires_on)->isPast()) {
                        $fail('OTP expired. Request a new code.');
                        return;
                    }

                    if ($authentication->ip_address !== $this->ip()) {
                        $fail('OTP verification must come from the same session network.');
                    }
                },
            ],
        ];
    }
}
