<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Role;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;


class RegisteredUserController extends Controller
{

    use ListensForLdapBindFailure;
    protected $username = 'username';


    public function __construct()
    {
        $this->listenForLdapBindFailure();
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function username()
    {
        return 'username';
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        

        $this->validateRequest($request);
       // Create the user
        $user = $this->createUser($request);

        // Assign the role to the user
        $user->assignRole($request->role_id);

        // Check if the user exists in the LDAP directory
        if ($ldapUser = $this->findLdapUser($request->username)) {
            // If LDAP user is found, attempt to log in with LDAP credentials
            return $this->attemptLdapLogin($ldapUser, $request->username, $request->password);
        } else {
            // LDAP user not found, proceed with local registration
            $user = $this->createUser($request);

            // Trigger the 'Registered' event
            event(new Registered($user));

            // Log the user in locally
            Auth::login($user);

            // Redirect the user to the intended page
            return redirect()->intended('dashboard')->with('success', 'Registration successful without LDAP');
        }
    }

    /**
     * Handle the login functionality with LDAP authentication.
     */
    public function login(Request $request): RedirectResponse
    {
        $this->validateLogin($request);

        $user->assignRole($role);

        if ($ldapUser = $this->findLdapUser($request->username)) {
            return $this->attemptLdapLogin($ldapUser, $request->username, $request->password);
        }

        // If LDAP user not found
        return back()->withErrors(['email' => 'User not found in LDAP directory']);
    }

    /**
     * Validate the incoming registration request.
     */
    
    protected function validateRequest(Request $request): void
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => ['required', 'string', 'max:255'],
            // Comment out the email validation if you're not using it
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'contact_number' => ['required', 'string', 'max:15', 'unique:users,contact_number'],
        ]);
    }


    /**
     * Validate the login request.
     */
    protected function validateLogin(Request $request): void
    {
        // dd('here');
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Create a new user instance after registration.
     */
    protected function createUser(Request $request): User
    {
        return User::create([
            'username' => $request->name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'contact_number' => $request->contact_number,
        ]);
    }

    

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'samaccountname' => $this->username,
            'password' => $this->password,
            'fallback' => [
            'usename' => $this->username,
            'password' => $this->password,
            ]
        ];

        if (! Auth::attempt($credentials, $this->filled('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Find a user in Active Directory by email.
     */
    protected function findLdapUser(string $username): ?LdapUser
    {
        // Search for the user in Active Directory
        // $ldapUser = LdapUser::where('username', '=', $request->username)->first();
        return LdapUser::where('username', '=', $username)->first();
    }

    /**
     * Attempt to log the user in using LDAP credentials.
     */
    
    protected function attemptLdapLogin(LdapUser $ldapUser, string $username, string $password): RedirectResponse
    {
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            // If the credentials are correct, log the user in
            return redirect()->intended('dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors(['password' => 'Invalid credentials']);
    }

}
