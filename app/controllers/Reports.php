<?php
require_once '../app/middleware/auth.php';

class Reports extends Controller {
    private $reportModel;
    private $serverModel;
    private $checklistModel;

    public function __construct() {
        $this->reportModel = $this->model('Report');
        $this->serverModel = $this->model('Server');
        $this->checklistModel = $this->model('Checklist');
    }

    // Display reports dashboard
    public function index() {
        // Check if user has appropriate role (admin only for now)
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /dashboard');
            exit;
        }

        $templates = $this->reportModel->getAllTemplates();
        $schedules = $this->reportModel->getAllSchedules();

        $data = [
            'templates' => $templates,
            'schedules' => $schedules,
            'frequencies' => $this->reportModel->getFrequencies()
        ];

        $this->view('reports/index', $data);
    }

    // Show create template form
    public function createTemplate() {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        $data = [
            'name' => '',
            'description' => '',
            'type' => '',
            'name_err' => '',
            'type_err' => '',
            'filters' => [
                'env' => '',
                'type' => '',
                'site' => ''
            ],
            'columns' => [
                'name' => true,
                'ip' => true,
                'os' => true,
                'site' => true,
                'type' => true,
                'env' => true,
                'owner' => true
            ]
        ];

        $this->view('reports/create_template', $data);
    }

    // Store new template
    public function storeTemplate() {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'type' => trim($_POST['type']),
                'name_err' => '',
                'type_err' => '',
                'filters' => [
                    'env' => $_POST['filter_env'] ?? '',
                    'type' => $_POST['filter_type'] ?? '',
                    'site' => $_POST['filter_site'] ?? ''
                ],
                'columns' => [
                    'name' => isset($_POST['column_name']),
                    'ip' => isset($_POST['column_ip']),
                    'os' => isset($_POST['column_os']),
                    'site' => isset($_POST['column_site']),
                    'type' => isset($_POST['column_type']),
                    'env' => isset($_POST['column_env']),
                    'owner' => isset($_POST['column_owner'])
                ]
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name';
            }

            // Validate type
            if (empty($data['type'])) {
                $data['type_err'] = 'Please select a type';
            }

            // Make sure no errors
            if (empty($data['name_err']) && empty($data['type_err'])) {
                $templateData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'type' => $data['type'],
                    'created_by' => $_SESSION['username'],
                    'filters' => json_encode($data['filters']),
                    'columns' => json_encode(array_keys(array_filter($data['columns'])))
                ];

                // Create template
                if ($this->reportModel->createTemplate($templateData)) {
                    header('Location: /reports?success=Template created successfully');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('reports/create_template', $data);
        } else {
            header('Location: /reports');
            exit;
        }
    }

    // Show create schedule form
    public function createSchedule() {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        $templates = $this->reportModel->getAllTemplates();

        $data = [
            'templates' => $templates,
            'template_id' => '',
            'frequency' => '',
            'day_of_week' => '',
            'day_of_month' => '',
            'time' => '',
            'recipients' => '',
            'is_active' => 1,
            'template_err' => '',
            'frequency_err' => '',
            'time_err' => '',
            'recipients_err' => '',
            'frequencies' => $this->reportModel->getFrequencies(),
            'days_of_week' => $this->reportModel->getDaysOfWeek(),
            'days_of_month' => $this->reportModel->getDaysOfMonth()
        ];

        $this->view('reports/create_schedule', $data);
    }

    // Store new schedule
    public function storeSchedule() {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'templates' => $this->reportModel->getAllTemplates(),
                'template_id' => trim($_POST['template_id']),
                'frequency' => trim($_POST['frequency']),
                'day_of_week' => trim($_POST['day_of_week']),
                'day_of_month' => trim($_POST['day_of_month']),
                'time' => trim($_POST['time']),
                'recipients' => trim($_POST['recipients']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'template_err' => '',
                'frequency_err' => '',
                'time_err' => '',
                'recipients_err' => '',
                'frequencies' => $this->reportModel->getFrequencies(),
                'days_of_week' => $this->reportModel->getDaysOfWeek(),
                'days_of_month' => $this->reportModel->getDaysOfMonth()
            ];

            // Validate template
            if (empty($data['template_id'])) {
                $data['template_err'] = 'Please select a template';
            }

            // Validate frequency
            if (empty($data['frequency'])) {
                $data['frequency_err'] = 'Please select a frequency';
            }

            // Validate time
            if (empty($data['time'])) {
                $data['time_err'] = 'Please enter a time';
            } elseif (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $data['time'])) {
                $data['time_err'] = 'Please enter a valid time in HH:MM format';
            }

            // Validate recipients
            if (empty($data['recipients'])) {
                $data['recipients_err'] = 'Please enter recipient emails';
            } else {
                $emails = explode(',', $data['recipients']);
                foreach ($emails as $email) {
                    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                        $data['recipients_err'] = 'Please enter valid email addresses separated by commas';
                        break;
                    }
                }
            }

            // Make sure no errors
            if (empty($data['template_err']) && empty($data['frequency_err']) && 
                empty($data['time_err']) && empty($data['recipients_err'])) {
                
                $scheduleData = [
                    'template_id' => $data['template_id'],
                    'frequency' => $data['frequency'],
                    'day_of_week' => $data['day_of_week'] ?: null,
                    'day_of_month' => $data['day_of_month'] ?: null,
                    'time' => $data['time'],
                    'recipients' => json_encode(explode(',', $data['recipients'])),
                    'is_active' => $data['is_active']
                ];

                // Create schedule
                if ($this->reportModel->createSchedule($scheduleData)) {
                    header('Location: /reports?success=Schedule created successfully');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('reports/create_schedule', $data);
        } else {
            header('Location: /reports');
            exit;
        }
    }

    // Activate schedule
    public function activateSchedule($id) {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($this->reportModel->activateSchedule($id)) {
            header('Location: /reports?success=Schedule activated successfully');
            exit;
        } else {
            header('Location: /reports?error=Failed to activate schedule');
            exit;
        }
    }

    // Deactivate schedule
    public function deactivateSchedule($id) {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($this->reportModel->deactivateSchedule($id)) {
            header('Location: /reports?success=Schedule deactivated successfully');
            exit;
        } else {
            header('Location: /reports?error=Failed to deactivate schedule');
            exit;
        }
    }

    // Delete template
    public function deleteTemplate($id) {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->reportModel->deleteTemplate($id)) {
                header('Location: /reports?success=Template deleted successfully');
                exit;
            } else {
                header('Location: /reports?error=Failed to delete template');
                exit;
            }
        } else {
            header('Location: /reports');
            exit;
        }
    }

    // Delete schedule
    public function deleteSchedule($id) {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->reportModel->deleteSchedule($id)) {
                header('Location: /reports?success=Schedule deleted successfully');
                exit;
            } else {
                header('Location: /reports?error=Failed to delete schedule');
                exit;
            }
        } else {
            header('Location: /reports');
            exit;
        }
    }

    // Generate report (for testing)
    public function generate($templateId) {
        // Check if user has appropriate role
        if ($_SESSION['role'] !== 'ADMIN') {
            header('Location: /reports');
            exit;
        }

        $template = $this->reportModel->getTemplateById($templateId);
        
        if (!$template) {
            header('Location: /reports?error=Template not found');
            exit;
        }

        // For now, just redirect back with a message
        // In a real application, you would generate the actual report here
        header('Location: /reports?success=Report generation functionality would be implemented here');
        exit;
    }
}
?>
