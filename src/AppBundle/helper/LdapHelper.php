<?php
/**
 * User: iibarguren
 * Date: 18/05/16
 * Time: 14:31
 */

namespace AppBundle\helper;


use Symfony\Component\DependencyInjection\ContainerInterface;

class LdapHelper
{
    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getLdapUsers() {
        $ldap_username = $this->container->getParameter('ldap_username');
        $ldap_password = $this->container->getParameter('ldap_password');
        $domain = "@".$this->container->getParameter('ldap_domain');
        $ldap_connection = ldap_connect($this->container->getParameter('ldap_host'));
        $message="";
        if (FALSE === $ldap_connection){
            // Uh-oh, something is wrong...
        }

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
        ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

        if (TRUE === ldap_bind($ldap_connection, $ldap_username.$domain, $ldap_password)){
            $ldap_base_dn = 'DC='.$this->container->getParameter('ldap_dc').',DC='.$this->container->getParameter('ldap_dc2');
            $search_filter = '(&(objectCategory=person)(samaccountname=*))';
            $attributes = array();
            $attributes[] = 'givenname';
            $attributes[] = 'mail';
            $attributes[] = 'samaccountname';
            $attributes[] = 'sn';
            $result = ldap_search($ldap_connection, $ldap_base_dn, $search_filter, $attributes);
            if (FALSE !== $result){
                $entries = ldap_get_entries($ldap_connection, $result);
                for ($x=0; $x<$entries['count']; $x++){
                    if (!empty($entries[$x]['givenname'][0]) &&
                        !empty($entries[$x]['mail'][0]) &&
                        !empty($entries[$x]['samaccountname'][0]) &&
                        !empty($entries[$x]['sn'][0]) &&
                        'Shop' !== $entries[$x]['sn'][0] &&
                        'Account' !== $entries[$x]['sn'][0]){
                        $ad_users[strtoupper(trim($entries[$x]['samaccountname'][0]))] = array(
                            'email' => strtolower(trim($entries[$x]['mail'][0])),
                            'first_name' => trim($entries[$x]['givenname'][0]),
                            'last_name' => trim($entries[$x]['sn'][0]),
                            'userid' => trim($entries[$x]['samaccountname'][0])
                        );
                    }
                }
            }
            ldap_unbind($ldap_connection); // Clean up after ourselves.
        }
        return $ad_users;
    }

    public function getLdapComputers() {
        $ldap_username = $this->container->getParameter('ldap_username');
        $ldap_password = $this->container->getParameter('ldap_password');
        $domain = "@".$this->container->getParameter('ldap_domain');
        $ldap_connection = ldap_connect($this->container->getParameter('ldap_host'));
        $message="";
        if (FALSE === $ldap_connection){
            // Uh-oh, something is wrong...
        }

        ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
        ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

        if (TRUE === ldap_bind($ldap_connection, $ldap_username.$domain, $ldap_password)){
            $ldap_base_dn = 'DC='.$this->container->getParameter('ldap_dc').',DC='.$this->container->getParameter('ldap_dc2');
            $search_filter = '(&(objectCategory=computer)(samaccountname=*))';
            $attributes = array();
            $attributes[] = 'givenname';
            $attributes[] = 'mail';
            $attributes[] = 'samaccountname';
            $attributes[] = 'sn';
            $result = ldap_search($ldap_connection, $ldap_base_dn, $search_filter, $attributes);

            if (FALSE !== $result){
                $entries = ldap_get_entries($ldap_connection, $result);
                for ($x=0; $x<$entries['count']; $x++){
                    if (!empty($entries[$x]['samaccountname'][0])
                        ){
                        $ad_users[$x] = rtrim(strtoupper(trim($entries[$x]['samaccountname'][0])),"$");
                    }
                }
            }
            ldap_unbind($ldap_connection); // Clean up after ourselves.
        }
        return $ad_users;
    }

}