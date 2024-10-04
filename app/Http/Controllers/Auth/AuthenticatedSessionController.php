<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Role;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class AuthenticatedSessionController extends Controller
{
    use ListensForLdapBindFailure;

    protected $username = 'username';

    public function __construct()
    {
        $this->listenForLdapBindFailure();
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Fetch users from LDAP and sync with the local database.
     */
    public function syncLdapUsers()
    {
        // Query all users from the LDAP directory
        $ldapUsers = LdapUser::get();

        foreach ($ldapUsers as $ldapUser) {
            // Ensure email exists
            $email = $ldapUser->mail[0] ?? null;

            // Check if the user already exists in the local database
            if (!$email) {
                continue; 
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                // If the user does not exist, create a new one
                User::create([
                    'name' => $ldapUser->cn[0] ?? 'Unknown Name',
                    'email' => $email,
                    'username' => $ldapUser->samaccountname[0] ?? 'unknown-username',
                    'password' => bcrypt('default-password'), 
                    'role_id' => 5, 
                ]);
            } else {
                // Optionally, update the existing user with new LDAP data
                $user->update([
                    'name' => $ldapUser->cn[0] ?? $user->name,
                    'username' => $ldapUser->samaccountname[0] ?? $user->username,
                ]);
            }
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate with LDAP
        try {
            // Try authenticating with LDAP if available
            $request->authenticate(); // This will attempt LDAP authentication
            
            // Sync LDAP users to local database
            $this->syncLdapUsers();

            // If LDAP login is successful, assign role and proceed
            $user = Auth::user();
            $this->assignRoleToUser($request, $user);

        } catch (\Exception $e) {
            // LDAP authentication failed, now attempt local authentication
            $credentials = $request->only('username', 'password');
            
            // Check if local authentication is successful
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $this->assignRoleToUser($request, $user); // Assign role based on local authentication
            } else {
                // Authentication failed completely, redirect back with error
                return redirect()->back()->withErrors(['login' => 'Login failed. Please check your credentials.']);
            }
        }

        // Regenerate session on successful login
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function assignRoleToUser($request, $user)
    {
        // Hardcoded default credentials
        $defaultUsername = 'admin';
        $defaultPassword = 'password123';

        // Check if the user is using the hardcoded credentials
        if ($request->username === $defaultUsername && $request->password === $defaultPassword) {
            // Assign role_id = 1 for the default admin user
            $user->role_id = 1;
        } else {
            // Assign role_id = 5 for all other users
            $user->assignRole(5);
        }

        $user->save();
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
