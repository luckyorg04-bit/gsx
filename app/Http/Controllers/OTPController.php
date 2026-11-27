<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTPRequest;
use App\Models\Authentication;
use App\Traits\AuthenticationProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OTPController extends Controller
{
    use AuthenticationProcess;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        if (Auth::user()->mobile_verified) {
            return redirect('/admin/dashboard');
        }

        return view('admin.otp.create');
    }

    public function store(OTPRequest $request)
    {
        $authentication = Authentication::where([
            ['user_id', Auth::id()],
            ['status', 0],
            ['type', 'register'],
        ])->latest('id')->first();

        if (! $authentication) {
            return back()->with('failmessage', 'No active OTP was found. Request a new code.');
        }

        try {
            DB::transaction(function () use ($authentication) {
                $authentication->status = 1;
                $authentication->save();

                $user = Auth::user();
                $user->mobile_verification_code = $authentication->token;
                $user->mobile_verified = 1;
                $user->mobile_verified_at = now();
                $user->save();
            });

            return redirect('/admin/dashboard')->with(
                'successmessage',
                trans('messages.mobile_verify_success_msg')
            );
        } catch (Throwable $e) {
            Log::error('GSX OTP verification failed', [
                'user_id' => Auth::id(),
                'exception' => $e->getMessage(),
            ]);

            return back()->with('failmessage', 'OTP verification could not be completed. Try again.');
        }
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->mobile_verified) {
            return redirect('/admin/dashboard');
        }

        $sent = $this->createAuthentication($user, $request, 'register');

        if (! $sent) {
            return back()->with('failmessage', 'OTP delivery failed. Check the SMS gateway configuration and retry.');
        }

        return back()->with('successmessage', 'A new OTP has been sent to your registered mobile number.');
    }
}
