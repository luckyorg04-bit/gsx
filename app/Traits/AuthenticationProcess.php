<?php
/**
 * Manages OTP-based authentication creation and validation for users.
 */

namespace App\Traits;

use App\Models\Authentication;
use App\Traits\MSG91;
use App\Models\User;
use Carbon\Carbon;
use Log;

trait AuthenticationProcess
{
    use MSG91;

    /**
     * Create an OTP authentication record for a user and send the OTP.
     *
     * @param \App\Models\User $user Target user
     * @param \Illuminate\Http\Request|string $request Request object or empty string when unavailable
     * @param string $type Authentication type (e.g., register, login)
     * @return bool
     */
    public function createAuthentication($user, $request, $type): bool
    {
        try {
            if (! $user->mobile_no) {
                return false;
            }

            $otp = random_int(100000, 999999);
            $authentication = new Authentication();
            $authentication->user_id = $user->id;
            $authentication->type = $type;
            $authentication->token = $otp;
            $authentication->status = 0;
            $authentication->expires_on = Carbon::now()->addMinutes(5);
            $authentication->ip_address = $request instanceof \Illuminate\Http\Request
                ? $request->ip()
                : request()->ip();
            $authentication->save();

            $smsResponse = $this->getOTP($otp, $user->mobile_no);
            $decodedResponse = is_string($smsResponse) ? json_decode($smsResponse, true) : null;

            if (! is_string($smsResponse)
                || trim($smsResponse) === ''
                || str_starts_with($smsResponse, 'cURL Error')
                || preg_match('/^(error|invalid|unauthorized|failed)/i', trim($smsResponse)) === 1
                || (is_array($decodedResponse) && strtolower($decodedResponse['type'] ?? '') === 'error')) {
                $authentication->delete();
                Log::warning('GSX registration OTP delivery failed', ['user_id' => $user->id]);
                return false;
            }

            \Session::put('successmessage', trans('messages.otp_success_msg'));

            return true;
        } catch (\Throwable $e) {
            Log::error('GSX OTP creation failed', [
                'user_id' => $user->id ?? null,
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check whether a registration OTP for the user has been verified.
     *
     * @param int $user_id User identifier
     * @return int Status flag from authentication record (0 or 1)
     */
    public  function checkAuthentication($user_id)
    {
        $authentication = Authentication::where([['user_id',$user_id],['type','register']])->orderBy('id','DESC')->get();
        if(count($authentication)>0)
        {
            return $authentication[0]->status;  
        }
        else
        {
            return 0;
        }
    }
}
