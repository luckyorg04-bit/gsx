<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ValidRecaptcha implements Rule
{
    public function passes($attribute, $value): bool
    {
        $secret = config('services.recaptcha.secret');

        if (! is_string($secret) || trim($secret) === '' || ! is_string($value) || trim($value) === '') {
            return false;
        }

        try {
            $client = new Client([
                'base_uri' => 'https://www.google.com/recaptcha/api/',
                'timeout' => 5,
                'connect_timeout' => 3,
                'verify' => true,
            ]);

            $response = $client->post('siteverify', [
                'form_params' => [
                    'secret' => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ],
            ]);

            $result = json_decode((string) $response->getBody(), true);

            return is_array($result) && ($result['success'] ?? false) === true;
        } catch (\Throwable $e) {
            Log::warning('GSX reCAPTCHA verification request failed', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function message(): string
    {
        return 'reCAPTCHA verification failed. Please try again.';
    }
}
