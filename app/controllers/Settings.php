<?php
require_once '../app/middleware/auth.php';

class Settings extends Controller {
    private $settingModel;

    public function __construct() {
        $this->settingModel = $this->model('Setting');
    }

    // Display settings
    public function index() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $settings = $this->settingModel->getSettings();

        $data = [
            'settings' => $settings,
            'smtp_host' => $settings['smtp_host'] ?? '',
            'smtp_port' => $settings['smtp_port'] ?? '',
            'smtp_user' => $settings['smtp_user'] ?? '',
            'smtp_pass' => $settings['smtp_pass'] ?? '',
            'smtp_from' => $settings['smtp_from'] ?? '',
            'company_name' => $settings['company_name'] ?? '',
            'company_logo' => $settings['company_logo'] ?? '',
            'smtp_host_err' => '',
            'smtp_port_err' => '',
            'company_name_err' => ''
        ];

        $this->view('settings/index', $data);
    }

    // Update settings
    public function update() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $settings = $this->settingModel->getSettings();

            $data = [
                'settings' => $settings,
                'smtp_host' => trim($_POST['smtp_host']),
                'smtp_port' => trim($_POST['smtp_port']),
                'smtp_user' => trim($_POST['smtp_user']),
                'smtp_pass' => trim($_POST['smtp_pass']),
                'smtp_from' => trim($_POST['smtp_from']),
                'company_name' => trim($_POST['company_name']),
                'company_logo' => $settings['company_logo'] ?? '',
                'smtp_host_err' => '',
                'smtp_port_err' => '',
                'company_name_err' => ''
            ];

            // Validate SMTP port if provided
            if (!empty($data['smtp_port']) && !is_numeric($data['smtp_port'])) {
                $data['smtp_port_err'] = 'SMTP port must be a number';
            }

            // Validate company name
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Please enter company name';
            }

            // Make sure no errors
            if (empty($data['smtp_host_err']) && empty($data['smtp_port_err']) && empty($data['company_name_err'])) {
                $settingData = [
                    'smtp_host' => $data['smtp_host'],
                    'smtp_port' => $data['smtp_port'],
                    'smtp_user' => $data['smtp_user'],
                    'smtp_pass' => $data['smtp_pass'],
                    'smtp_from' => $data['smtp_from'],
                    'company_logo' => $data['company_logo'],
                    'company_name' => $data['company_name']
                ];

                // Update settings
                if ($this->settingModel->updateSettings($settingData)) {
                    header('Location: /settings?success=Settings updated successfully');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('settings/index', $data);
        } else {
            header('Location: /settings');
            exit;
        }
    }

    // Upload logo
    public function uploadLogo() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if file was uploaded
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['logo']['tmp_name'];
                $fileName = $_FILES['logo']['name'];
                $fileSize = $_FILES['logo']['size'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // Validate file extension
                $allowedfileExtensions = array('jpg', 'jpeg', 'png');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // Validate file size (max 5MB)
                    if ($fileSize <= 5 * 1024 * 1024) {
                        // Create uploads directory if it doesn't exist
                        $uploadDir = '../public/uploads/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        // Generate unique file name
                        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                        $dest_path = $uploadDir . $newFileName;

                        // Move uploaded file
                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            // Update logo in database
                            if ($this->settingModel->updateLogo('/uploads/' . $newFileName)) {
                                header('Location: /settings?success=Logo uploaded successfully');
                                exit;
                            } else {
                                die('Something went wrong');
                            }
                        } else {
                            header('Location: /settings?error=Error uploading file');
                            exit;
                        }
                    } else {
                        header('Location: /settings?error=File size exceeds 5MB limit');
                        exit;
                    }
                } else {
                    header('Location: /settings?error=Invalid file type. Only JPG, JPEG, and PNG files are allowed');
                    exit;
                }
            } else {
                header('Location: /settings?error=No file uploaded');
                exit;
            }
        } else {
            header('Location: /settings');
            exit;
        }
    }

    // Test email
    public function testEmail() {
        // Check if user has admin role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        // For now, just redirect back with a message
        // In a real application, you would send a test email here
        header('Location: /settings?success=Test email functionality would be implemented here');
        exit;
    }
}
?>
