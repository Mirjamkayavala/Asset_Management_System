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

            // Extract the role information from LDAP
            $ldapRole = $ldapUser->memberOf[0] ?? null; // This depends on how roles are assigned in LDAP

            if (!$user) {
                // If the user does not exist, create a new one with default role
                User::create([
                    'name' => $ldapUser->cn[0] ?? 'Unknown Name',
                    'email' => $email,
                    'username' => $ldapUser->samaccountname[0] ?? 'unknown-username',
                    'password' => bcrypt('default-password'),
                    'role_id' => $this->mapLdapRoleToLocal($ldapRole), // Map the LDAP role to local role
                ]);
            } else {
                // Optionally, update the existing user with new LDAP data
                $user->update([
                    'name' => $ldapUser->cn[0] ?? $user->name,
                    'username' => $ldapUser->samaccountname[0] ?? $user->username,
                    'role_id' => $this->mapLdapRoleToLocal($ldapRole), // Update the role if it has changed
                ]);
            }
        }
    }

    /**
     * Map LDAP roles to local roles.
     */
    private function mapLdapRoleToLocal($ldapRole)
    {
        // Define a mapping array to associate LDAP roles with local role IDs
        $roleMap = [
            'admin' => 1,
            'technician' => 2,
            'costing_department' => 3,
        ];

        // Return the mapped role ID, or default to 5 (viewer or normal_user)
        return $roleMap[$ldapRole] ?? 4;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    // Attempt to authenticate with LDAP
    try {
        $request->authenticate(); 
        // Sync LDAP users to local database
        $this->syncLdapUsers();

        // If LDAP login is successful, assign role and proceed
        $user = Auth::user();
        $this->assignRoleToUser($request, $user);

    } catch (\Exception $e) {
        // LDAP authentication failed, now attempt local authentication
        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $this->assignRoleToUser($request, $user); // Assign role based on local authentication
        } else {
            \Log::error('Login failed due to incorrect credentials.', ['username' => $request->username]);
            return redirect()->back()->withErrors(['login' => 'Login failed. Please check your credentials.']);
        }
    }

    // Regenerate session on successful login
    $request->session()->regenerate();

    return redirect()->intended(route('dashboard', absolute: false));
}
    /**
     * Assign a role to the user based on LDAP or local data.
     */
    private function assignRoleToUser($request, $user)
    {
        // Hardcoded default credentials
        $defaultUsername = 'admin';
        $defaultPassword = 'password123';

        // Check if the user is using the hardcoded credentials
        if ($request->username === $defaultUsername && $request->password === $defaultPassword) {
            $user->role_id = 1; // Admin role for hardcoded credentials
        }

        // No need to manually assign other roles if syncLdapUsers has already updated the role
        $user->save();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log the session timeout error
        if (!Auth::check()) {
            \Log::warning('System timeout: User session expired.', ['user_id' => optional(Auth::user())->id]);
            return redirect('/login')->with('warning', 'System timeout.');
        }
    
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/')->with('warning', 'You have been logged out due to inactivity.');
    }
}
