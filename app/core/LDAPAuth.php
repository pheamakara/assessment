<?php
require_once 'app/config/ldap.php';

class LDAPAuth {
    private $ldapConnection;
    
    public function __construct() {
        $this->connect();
    }
    
    private function connect() {
        $ldapHost = LDAPConfig::LDAP_HOST;
        $ldapPort = LDAPConfig::LDAP_PORT;
        
        // Create LDAP connection
        $this->ldapConnection = ldap_connect($ldapHost, $ldapPort);
        
        if (!$this->ldapConnection) {
            throw new Exception("Could not connect to LDAP server");
        }
        
        // Set LDAP options
        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);
    }
    
    public function authenticate($username, $password) {
        // Find user DN
        $userDN = $this->findUserDN($username);
        
        if (!$userDN) {
            return false;
        }
        
        // Attempt to bind with user credentials
        try {
            $bind = ldap_bind($this->ldapConnection, $userDN, $password);
            return $bind ? $userDN : false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function findUserDN($username) {
        $baseDN = LDAPConfig::LDAP_BASE_DN;
        $filter = "(uid=" . ldap_escape($username, "", LDAP_ESCAPE_FILTER) . ")";
        
        $result = ldap_search($this->ldapConnection, $baseDN, $filter, ['dn']);
        
        if (!$result) {
            return false;
        }
        
        $entries = ldap_get_entries($this->ldapConnection, $result);
        
        if ($entries['count'] == 0) {
            return false;
        }
        
        return $entries[0]['dn'];
    }
    
    public function getUserInfo($userDN) {
        $baseDN = LDAPConfig::LDAP_BASE_DN;
        $attributes = LDAPConfig::LDAP_USER_ATTRIBUTES;
        
        $result = ldap_read($this->ldapConnection, $userDN, "(objectClass=*)", $attributes);
        
        if (!$result) {
            return false;
        }
        
        $entries = ldap_get_entries($this->ldapConnection, $result);
        
        if ($entries['count'] == 0) {
            return false;
        }
        
        $userInfo = [
            'username' => $entries[0]['uid'][0] ?? '',
            'fullname' => $entries[0]['cn'][0] ?? '',
            'email' => $entries[0]['mail'][0] ?? '',
            'groups' => []
        ];
        
        // Extract groups
        if (isset($entries[0]['memberof'])) {
            $groupCount = $entries[0]['memberof']['count'];
            for ($i = 0; $i < $groupCount; $i++) {
                $userInfo['groups'][] = $entries[0]['memberof'][$i];
            }
        }
        
        return $userInfo;
    }
    
    public function assignRoleFromGroups($groups) {
        $groupRoleMapping = LDAPConfig::GROUP_ROLE_MAPPING;
        
        // Check each group the user belongs to
        foreach ($groups as $group) {
            // Check if this group has a role mapping
            if (array_key_exists($group, $groupRoleMapping)) {
                return $groupRoleMapping[$group];
            }
        }
        
        // Default role if no mapping found
        return 'AUDITOR';
    }
    
    public function __destruct() {
        if ($this->ldapConnection) {
            ldap_unbind($this->ldapConnection);
        }
    }
}
?>
