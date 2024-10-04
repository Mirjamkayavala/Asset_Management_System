<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        // Hard-coded default credentials for the admin user
        $defaultUsername = 'admin';
        $defaultPassword = 'password123';

        if ($this->username === $defaultUsername && $this->password === $defaultPassword) {
            // If the provided credentials match the hardcoded ones,
            $adminUser = \App\Models\User::firstOrCreate([
                'username' => $defaultUsername,
            ], [
                'name' => 'Default Admin',
                'email' => 'admin@example.com', 
                'password' => bcrypt($defaultPassword), 
            ]);
    
            
            $adminUser->assignRole('1'); 
    
            Auth::login($adminUser, $this->boolean('remember'));
    
            // Clear the rate limiter after successful login
            RateLimiter::clear($this->throttleKey());
    
            return;
        }

        $credentials = [
            'samaccountname' => $this->username,
            'password' => $this->password,
            'fallback' => [
            'username' => $this->username,
            'password' => $this->password,
            ]
        ];

        
        try {
            // Attempt to authenticate the user
            if (!Auth::attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::hit($this->throttleKey()); // Add rate limit
    
                // Throw validation exception if authentication fails
                throw ValidationException::withMessages([
                    'username' => trans('auth.failed'),
                ]);
            }
    
            // Clear the rate limiter on successful login
            RateLimiter::clear($this->throttleKey());
        } catch (\Throwable $e) {
            // Log detailed error message
            Log::error('Authentication failed: ', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
    
            //Display the validation error or throw a generic error
            throw ValidationException::withMessages([
                'error' => 'Invalid credential, Please try again.',
            ]);
        }

        // RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}
