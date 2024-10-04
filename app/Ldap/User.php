<?php

namespace App\Ldap;

use LdapRecord\Models\Model;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Auth\BindException;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalPerson',
        'user',
    ];

    /**
     * Get the user's email address attribute.
     */
    public function getEmailAttribute()
    {
        return $this->mail[0] ?? null;  // Avoid undefined index errors
    }

    /**
     * Search for the user in Active Directory by email and password.
     * 
     * @param string $email
     * @param string $password
     * @return bool|LdapUser
     */
    public static function searchAndAuthenticate($username, $password)
    {
        try {
            // Connect to the LDAP server
            $connection = Container::getConnection();

            // Attempt to find the user using both 'mail' and 'userPrincipalName'
            $ldapUser = LdapUser::where('username', '=', $username)->orWhere('userPrincipalName', '=', $username)->first();

            if (!$ldapUser) {
                return false; // User not found in AD
            }

            // Try to authenticate the user
            if ($connection->auth()->attempt($ldapUser->getDn(), $password)) {
                return $ldapUser; // Authentication successful, return the user
            }

            return false; // Authentication failed

        } catch (BindException $e) {
            // Handle failed authentication attempts or connection issues
            return false;
        }
    }
}
