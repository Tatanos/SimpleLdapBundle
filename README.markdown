SimpleLdapBundle
================

This is a Bundle for Symfony for an Ldap Authentification

In this bundle, you will be able to authentificate user against an LDAP annuary. By default every single user in the LDAP annuary will have a default ROLE (role that you can choose). It is also possible to give specific user a specific role.

The database is simple, you have a database where a id corresponding to a field(the name of that field is defined int the parameter.yml) in the ldap tree (exemple: employeenumber - 00000), and you will give a specific role for the user with the field employee number 00000
You also have a boolean field for the user to be unvalidated, user who are unvalidated, will have a default role defined in the parameter.yml.

Requirement
-----------

To use this Bundle you will need to have php_ldap installed on you server

Installation
------------

You need to add a package to your dependency list :

    "yunai39/simple-ldap-bundle": "dev-master"
    
When do a composer update:
    
    composer update "yunai39/simple-ldap-bundle"

You need to enable the bundle into your kernel

    //app/AppKernel.php
    new Yunai39\Bundle\SimpleLdapBundle\SimpleLdapBundle(),
    

Configuration
-------------

The configuration is a bit long:

First you need to register the bundle in your AppKernel
    
    
    $bundles = array(
    ...
    new Yunai39\Bundle\SimpleLdapBundle\SimpleLdapBundle(),
    ...
    };



You need to configure your domain specific information, put those information into app/config/config.yml

        simple_ldap:
            settings:
                server: ip.to.server.ldap
                port: 389 or 636
                account_suffix : employeeNumber 
                base_dn :
                    base1: OU=people,DC=company
                    base2: OU=contractors,OU=people,DC=company

            # The attribut you want your user Class to have
            settings_user:
                FullName: cn
            # The redirection after login based on the ROLE
            user_redirects: 
              ROLE_USER: user_home
              ROLE_ADMIN: admin_home
            # Name of the user class
            user_class: Acme\DemoBundle\Security\User\CustomLdapUser
            #if the user is not registered in that database or is not registered as valid in the database he will have the default role
            default_role: ROLE_USER

You will also need to create an UserClass which inherit from the UserLdap defined in the bundle (Use that only if you want specific attribut from the ldap such as email or fullname)

        <?php
        
        namespace Acme\DemoBundle\Security\User;
        
        use Symfony\Component\Security\Core\User\UserInterface;
        use Yunai39\Bundle\SimpleLdapBundle\Security\User\UserLdap;
        class CustomLdapUser extends  UserLdap
        {
        /* displayname*/
            private $fullname;
        
            public function getFullName(){
                return $this->fullname;
            }
        
            public function setFullName($fullname){
                $this->fullname = $fullname; 
            }
        }


If you do not wish to have a user with email or any other field coming from LDAP you do not need to recreate an new user class, you can just use the userldap in the bundle

Define the user class this way in the config.yml

        user_class: Yunai39\Bundle\SimpleLdapBundle\Security\User\UserLdap



The security parameters (Just what's needed for the Bundle, the rest is up to you)

    security:
        encoders:
            Acme\DemoBundle\Security\User\CustomLdapUser : plaintext #Active directory does not support encrypted password yet
    providers:
        my_active_directory_provider:
              id: security_ldap_provider

You will also need to add the following configuration key in your firewall to reference the providers

    ldap: true
    
Example

        firewalls:
            ldap:
                pattern:  ^/
                provider: my_active_directory_provider
                anonymous: ~
                form_login:
                    login_path: login
                    check_path: login_check
                logout:
                    path:   /logout
                    target: login
                ldap: true
                
Add the road for the gestion (Make sure they are under a firewall)

        user_role:
            resource: "@SimpleLdapBundle/Resources/config/routing.yml"
            prefix:   /admin

If you do not have the role for login, logout and login check


    login:
        pattern:  /login
        defaults: { _controller: "SimpleLdapBundle:Security:login" }
    
    login_check:
        pattern:  /login_check
    
    
    logout:
        pattern:  /logout

And finally do not forget to update your database.

Version
----------------------
    
2.*
    - A database to handle user role
