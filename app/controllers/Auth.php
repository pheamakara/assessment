<?php
class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'username' => trim($_POST['username']),
                'password' => $_POST['password'],
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check if user exists
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Try LDAP authentication first
                $ldapUser = $this->authenticateWithLDAP($data['username'], $data['password']);
                
                if ($ldapUser) {
                    // LDAP authentication successful
                    // Check if user exists in local database
                    $user = $this->userModel->getUserByUsername($data['username']);
                    
                    if (!$user) {
                        // Create user in local database with LDAP info
                        $this->createLDAPUser($ldapUser);
                        $user = $this->userModel->getUserByUsername($data['username']);
                    } else {
                        // Update user info from LDAP
                        $this->updateLDAPUser($user['id'], $ldapUser);
                    }
                    
                    // Create session
                    $this->createUserSession($user);
                    header('Location: /dashboard');
                    exit;
                } else {
                    // Fall back to local authentication
                    $user = $this->userModel->getUserByUsername($data['username']);
                    
                    if ($user) {
                        // Verify password
                        if (password_verify($data['password'], $user['password'])) {
                            // Create session
                            $this->createUserSession($user);
                            header('Location: /dashboard');
                            exit;
                        } else {
                            $data['password_err'] = 'Password incorrect';
                        }
                    } else {
                        $data['username_err'] = 'No user found';
                    }
                }
            }

            $this->view('auth/login', $data);
        } else {
            // If user is already logged in, redirect to dashboard
            if (isset($_SESSION['user_id'])) {
                header('Location: /dashboard');
                exit;
            }
            
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => ''
            ];
            
            $this->view('auth/login', $data);
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy();
        header('Location: /auth/login');
        exit;
    }

    public function createUserSession($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Update last login
        $this->userModel->updateLastLogin($user['id']);
    }

    // Middleware to check if user is logged in
    public function isLoggedIn() {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    // LDAP authentication
    private function authenticateWithLDAP($username, $password) {
        // Check if LDAP extension is available
        if (!extension_loaded('ldap')) {
            return false;
        }

        try {
            // Include LDAP auth class
            require_once '../app/core/LDAPAuth.php';
            
            // Create LDAP auth instance
            $ldapAuth = new LDAPAuth();
            
            // Authenticate user
            $userDN = $ldapAuth->authenticate($username, $password);
            
            if ($userDN) {
                // Get user info
                $userInfo = $ldapAuth->getUserInfo($userDN);
                
                if ($userInfo) {
                    // Assign role based on LDAP groups
                    $role = $ldapAuth->assignRoleFromGroups($userInfo['groups']);
                    $userInfo['role'] = $role;
                    return $userInfo;
                }
            }
            
            return false;
        } catch (Exception $e) {
            // LDAP authentication failed
            return false;
        }
    }

    // Create user from LDAP info
    private function createLDAPUser($ldapUser) {
        $userData = [
            'username' => $ldapUser['username'],
            'email' => $ldapUser['email'] ?? $ldapUser['username'] . '@company.com',
            'password' => password_hash(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 16), PASSWORD_DEFAULT),
            'role' => $ldapUser['role']
        ];
        
        return $this->userModel->createUser($userData);
    }

    // Update user with LDAP info
    private function updateLDAPUser($userId, $ldapUser) {
        $userData = [
            'username' => $ldapUser['username'],
            'email' => $ldapUser['email'] ?? $ldapUser['username'] . '@company.com',
            'role' => $ldapUser['role']
        ];
        
        return $this->userModel->updateUser($userId, $userData);
    }
}
?>
