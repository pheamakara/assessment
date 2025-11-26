<?php
require_once '../app/middleware/auth.php';

class Profile extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    // Display profile
    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if (!$user) {
            header('Location: /auth/logout');
            exit;
        }

        $data = [
            'user' => $user,
            'current_password_err' => '',
            'new_password_err' => '',
            'confirm_password_err' => ''
        ];

        $this->view('profile/index', $data);
    }

    // Change password
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $user = $this->userModel->getUserById($_SESSION['user_id']);

            if (!$user) {
                header('Location: /auth/logout');
                exit;
            }

            $data = [
                'user' => $user,
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate current password
            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Please enter current password';
            } elseif (!password_verify($data['current_password'], $user['password'])) {
                $data['current_password_err'] = 'Current password is incorrect';
            }

            // Validate new password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Please enter new password';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm new password';
            } else {
                if ($data['new_password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure no errors
            if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                // Hash new password
                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

                // Update password
                if ($this->userModel->updatePassword($_SESSION['user_id'], $hashedPassword)) {
                    // Redirect with success message
                    header('Location: /profile?success=Password updated successfully');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('profile/index', $data);
        } else {
            header('Location: /profile');
            exit;
        }
    }
}
?>
