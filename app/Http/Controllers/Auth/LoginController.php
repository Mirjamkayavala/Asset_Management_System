<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;

class LoginController extends Controller
{
    use AuthenticatesWithLdap, ListensForLdapBindFailure;

    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Validate the username and password input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to bind (authenticate) the user with Active Directory
        try {
            $ldapUser = LdapUser::where('username', '=', $request->username)->firstOrFail();

            // Check if the credentials are valid
            if (Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])) {
                // Credentials matched, log the user in
                return redirect()->intended('dashboard')->with('success', 'Login successful');
            }

        } catch (\LdapRecord\Models\ModelNotFoundException $e) {
            return back()->withErrors(['username' => 'User not found in Active Directory']);
        } catch (\LdapRecord\Auth\BindException $e) {
            return back()->withErrors(['password' => 'Invalid credentials or LDAP server error']);
        }

        // If login failed
        return back()->withErrors(['password' => 'Invalid credentials']);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Specify the username field for authentication.
     */
    public function username()
    {
        return 'username';
    }
}
