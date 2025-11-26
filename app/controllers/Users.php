<?php
require_once '../app/middleware/auth.php';

class Users extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    // Display all users
    public function index() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $users = $this->userModel->getAllUsers();
        $data = [
            'users' => $users
        ];

        $this->view('users/index', $data);
    }

    // Show add user form
    public function create() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'username' => '',
            'email' => '',
            'role' => '',
            'username_err' => '',
            'email_err' => '',
            'role_err' => ''
        ];

        $this->view('users/create', $data);
    }

    // Add new user
    public function store() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'password' => password_hash('password123', PASSWORD_DEFAULT), // Default password
                'username_err' => '',
                'email_err' => '',
                'role_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter valid email';
            }

            // Validate role
            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            // Check if username or email already exists
            if (empty($data['username_err']) && empty($data['email_err'])) {
                if ($this->userModel->getUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username already taken';
                }
            }

            // Make sure no errors
            if (empty($data['username_err']) && empty($data['email_err']) && empty($data['role_err'])) {
                // Create user
                if ($this->userModel->createUser($data)) {
                    header('Location: /users');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('users/create', $data);
        } else {
            $data = [
                'username' => '',
                'email' => '',
                'role' => '',
                'username_err' => '',
                'email_err' => '',
                'role_err' => ''
            ];

            $this->view('users/create', $data);
        }
    }

    // Show edit user form
    public function edit($id) {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            header('Location: /users');
            exit;
        }

        $data = [
            'user' => $user,
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'username_err' => '',
            'email_err' => '',
            'role_err' => ''
        ];

        $this->view('users/edit', $data);
    }

    // Update user
    public function update($id) {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'role' => trim($_POST['role']),
                'username_err' => '',
                'email_err' => '',
                'role_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter valid email';
            }

            // Validate role
            if (empty($data['role'])) {
                $data['role_err'] = 'Please select role';
            }

            // Make sure no errors
            if (empty($data['username_err']) && empty($data['email_err']) && empty($data['role_err'])) {
                // Update user
                if ($this->userModel->updateUser($id, $data)) {
                    header('Location: /users');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('users/edit', $data);
        } else {
            $user = $this->userModel->getUserById($id);

            if (!$user) {
                header('Location: /users');
                exit;
            }

            $data = [
                'user' => $user,
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'username_err' => '',
                'email_err' => '',
                'role_err' => ''
            ];

            $this->view('users/edit', $data);
        }
    }

    // Delete user
    public function delete($id) {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->deleteUser($id)) {
                header('Location: /users');
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: /users');
            exit;
        }
    }

    // Show set password form
    public function setPassword($id) {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $user = $this->userModel->getUserById($id);

        if (!$user) {
            header('Location: /users');
            exit;
        }

        $data = [
            'user' => $user,
            'password' => '',
            'confirm_password' => '',
            'password_err' => '',
            'confirm_password_err' => ''
        ];

        $this->view('users/set_password', $data);
    }

    // Update user password
    public function updatePassword($id) {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'user' => $this->userModel->getUserById($id),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure no errors
            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash password
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                // Update password
                if ($this->userModel->updatePassword($id, $hashedPassword)) {
                    header('Location: /users');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('users/set_password', $data);
        } else {
            $user = $this->userModel->getUserById($id);

            if (!$user) {
                header('Location: /users');
                exit;
            }

            $data = [
                'user' => $user,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            $this->view('users/set_password', $data);
        }
    }
}
?>
