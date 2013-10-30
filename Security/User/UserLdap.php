<?php

namespace Security\LdapBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class UserLdap implements UserInterface
{

    private $username;
    private $password;
    private $salt;
    private $roles;
	private $email;
    private $fullname;


    public function __construct($username, $password, array $roles)
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = '';
        $this->roles = $roles;
    }

    public Function getEmail(){
        return $this->email;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }
    
    public function getFullName(){
        return $this->fullname;
    }

    public function setFullName($fullname){
        $this->fullname = $fullname; 
    }
    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }



	
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        //return void;
    }


     public function getRoles()
     {
         return $this->roles;
     }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }
}
