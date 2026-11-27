<?php
/**
 * Handles user authentication workflow including validation, throttling, and responses.
 */

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Traits\ThrottlesLogins;
use App\Traits\RedirectsUsers;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * Registers custom validators:
     * - checkschool: Validates that the school is active
     * - checkusers: Validates that the user exists
     * - checkactive: Validates that the user is not suspended (inactive status)
     * - checkexit: Validates that the user has not exited (exit status)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $login = trim((string) $request->input('email'));

        Validator::extend('checkschool', function ($attribute, $value, $parameters, $validator) use ($login) {
            $user = $this->findUserForLogin($login);

            if (! $user) {
                return false;
            }

            if ((int) $user->usergroup_id === 1) {
                return true;
            }

            return School::IsActive($user->school_id)->exists();
        }, 'Invalid Credentials. You are not in this school');

        Validator::extend('checkusers', function ($attribute, $value, $parameters, $validator) use ($login) {
            return $this->findUserForLogin($login) !== null;
        }, 'Invalid Credentials');

        Validator::extend('checkactive', function ($attribute, $value, $parameters, $validator) use ($login) {
            $user = $this->findUserForLogin($login);

            return $user !== null
                && $user->status === 'active'
                && $user->userprofile !== null
                && $user->userprofile->status === 'active';
        }, 'You are suspended by site admin');

        Validator::extend('checkexit', function ($attribute, $value, $parameters, $validator) use ($login) {
            $user = $this->findUserForLogin($login);

            return $user !== null
                && $user->status !== 'exit'
                && $user->userprofile !== null
                && $user->userprofile->status !== 'exit';
        }, 'You have exited this school');

        $this->validate($request, [
            $this->username() => 'bail|required|string|checkusers|checkactive|checkexit',
            'password' => 'bail|required|string|checkschool',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $user = $this->findUserForLogin($request->input('email'));

        if ($user !== null) {
            return [
                'id' => $user->getAuthIdentifier(),
                'password' => $request->input('password'),
            ];
        }

        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * Supports flexible login: users can login with either email or registration_number.
     * Validates the input to determine which field to use.
     *
     * @return string The field name ('email' or 'registration_number')
     */
    /**
     * Resolve a login identifier without assuming that it is an email address.
     * Email lookup is case-insensitive; non-email identifiers may be a
     * registration number, mobile number or account name.
     */
    protected function findUserForLogin($login): ?User
    {
        if (! is_string($login) && ! is_numeric($login)) {
            return null;
        }

        $login = trim((string) $login);

        if ($login === '') {
            return null;
        }

        $query = User::query()->with('userprofile');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return $query->whereRaw('LOWER(email) = ?', [strtolower($login)])->first();
        }

        return $query->where(function ($builder) use ($login) {
            $builder->where('registration_number', $login)
                ->orWhere('mobile_no', $login)
                ->orWhere('name', $login);
        })->first();
    }

    public function username()
    {
       $login = request()->input('email');
       $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'registration_number';
       request()->merge([$field => $login]);
       return $field;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
