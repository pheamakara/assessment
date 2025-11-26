<?php
// LDAP Configuration
class LDAPConfig {
    // LDAP server settings
    const LDAP_HOST = 'ldap.example.com';
    const LDAP_PORT = 389;
    const LDAP_BASE_DN = 'dc=example,dc=com';
    const LDAP_BIND_DN = 'cn=admin,dc=example,dc=com';
    const LDAP_BIND_PASSWORD = 'admin_password';
    
    // LDAP group to role mappings
    const GROUP_ROLE_MAPPING = [
        'cn=admins,ou=groups,dc=example,dc=com' => 'ADMIN',
        'cn=cloud_managers,ou=groups,dc=example,dc=com' => 'CLOUD_MANAGER',
        'cn=cloud_engineers,ou=groups,dc=example,dc=com' => 'CLOUD_ENGINEER',
        'cn=security,ou=groups,dc=example,dc=com' => 'SECURITY',
        'cn=auditors,ou=groups,dc=example,dc=com' => 'AUDITOR'
    ];
    
    // LDAP user attributes
    const LDAP_USER_ATTRIBUTES = [
        'uid', 'cn', 'mail', 'memberOf'
    ];
}
?>
