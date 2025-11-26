<?php
require_once '../app/middleware/auth.php';

class Checklists extends Controller {
    private $checklistModel;
    private $serverModel;

    public function __construct() {
        $this->checklistModel = $this->model('Checklist');
        $this->serverModel = $this->model('Server');
    }

    // Display all checklists
    public function index() {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'server_name' => $_GET['server_name'] ?? ''
        ];

        $checklists = $this->checklistModel->getChecklistsByFilter($filters);
        $data = [
            'checklists' => $checklists,
            'filters' => $filters
        ];

        $this->view('checklists/index', $data);
    }

    // Show create checklist form
    public function create($serverId = null) {
        $server = null;
        
        if ($serverId) {
            $server = $this->serverModel->getServerById($serverId);
            if (!$server) {
                header('Location: /servers');
                exit;
            }
        }

        // Get all servers for dropdown if no server selected
        $servers = $this->serverModel->getAllServers();
        
        $data = [
            'server' => $server,
            'servers' => $servers,
            'server_id' => $serverId ?? '',
            'server_err' => '',
            'items' => $this->getChecklistItems($server ? $server['type'] : ''),
            'selected_items' => []
        ];

        $this->view('checklists/create', $data);
    }

    // Store new checklist
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $serverId = trim($_POST['server_id']);
            
            // Validate server
            if (empty($serverId)) {
                $servers = $this->serverModel->getAllServers();
                $data = [
                    'server' => null,
                    'servers' => $servers,
                    'server_id' => '',
                    'server_err' => 'Please select a server',
                    'items' => [],
                    'selected_items' => []
                ];
                $this->view('checklists/create', $data);
                return;
            }

            $server = $this->serverModel->getServerById($serverId);
            if (!$server) {
                $servers = $this->serverModel->getAllServers();
                $data = [
                    'server' => null,
                    'servers' => $servers,
                    'server_id' => '',
                    'server_err' => 'Server not found',
                    'items' => [],
                    'selected_items' => []
                ];
                $this->view('checklists/create', $data);
                return;
            }

            // Get selected items
            $selectedItems = $_POST['items'] ?? [];
            $items = $this->getChecklistItems($server['type']);
            
            // Create items array with checked status
            $checklistItems = [];
            foreach ($items as $item) {
                $checklistItems[$item] = in_array($item, $selectedItems);
            }

            $data = [
                'server_id' => $serverId,
                'type' => $server['type'],
                'items' => json_encode($checklistItems),
                'requested_by' => $_SESSION['username']
            ];

            // Create checklist
            if ($this->checklistModel->createChecklist($data)) {
                header('Location: /checklists');
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: /checklists');
            exit;
        }
    }

    // Show checklist details
    public function show($id) {
        $checklist = $this->checklistModel->getChecklistById($id);

        if (!$checklist) {
            header('Location: /checklists');
            exit;
        }

        // Decode items
        $checklist['items'] = json_decode($checklist['items'], true);

        $data = [
            'checklist' => $checklist
        ];

        $this->view('checklists/show', $data);
    }

    // Approve checklist
    public function approve($id) {
        if (!in_array($_SESSION['role'], ['SECURITY', 'CLOUD_MANAGER'])) {
            header('Location: /checklists');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $checklist = $this->checklistModel->getChecklistById($id);
            
            if (!$checklist) {
                header('Location: /checklists');
                exit;
            }

            // Determine next status based on current status and user role
            $nextStatus = '';
            if ($checklist['status'] === 'DRAFT' || $checklist['status'] === 'REJECTED') {
                if ($_SESSION['role'] === 'SECURITY') {
                    $nextStatus = 'PENDING_SECURITY';
                } elseif ($_SESSION['role'] === 'CLOUD_MANAGER') {
                    $nextStatus = 'PENDING_CLOUD';
                }
            } elseif ($checklist['status'] === 'PENDING_SECURITY' && $_SESSION['role'] === 'SECURITY') {
                $nextStatus = 'PENDING_CLOUD';
            } elseif ($checklist['status'] === 'PENDING_CLOUD' && $_SESSION['role'] === 'CLOUD_MANAGER') {
                $nextStatus = 'APPROVED';
            }

            if (!empty($nextStatus)) {
                if ($this->checklistModel->updateChecklistStatus($id, $nextStatus, $_SESSION['username'])) {
                    header('Location: /checklists/show/' . $id);
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                header('Location: /checklists/show/' . $id);
                exit;
            }
        } else {
            header('Location: /checklists');
            exit;
        }
    }

    // Reject checklist
    public function reject($id) {
        if (!in_array($_SESSION['role'], ['SECURITY', 'CLOUD_MANAGER'])) {
            header('Location: /checklists');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $checklist = $this->checklistModel->getChecklistById($id);
            
            if (!$checklist) {
                header('Location: /checklists');
                exit;
            }

            // Validate rejection reason
            $rejectionReason = trim($_POST['rejection_reason']);
            if (empty($rejectionReason)) {
                // Redirect back with error
                header('Location: /checklists/show/' . $id . '?error=Please provide a rejection reason');
                exit;
            }

            if ($this->checklistModel->updateChecklistStatus($id, 'REJECTED', $_SESSION['username'], $rejectionReason)) {
                header('Location: /checklists/show/' . $id);
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: /checklists');
            exit;
        }
    }

    // Get checklist items based on server type
    private function getChecklistItems($serverType) {
        $commonItems = [
            "Update IP to IPAM",
            "Add to monitoring",
            "Update password to secret",
            "Add to central Linux",
            "Add to endpoint Central",
            "Join domain or config NTP Sync",
            "Install CrowdStrike",
            "Add subscription if needed",
            "Security Scan",
            "CIS Compliance",
            "Update Backup layout and backup register"
        ];

        if ($serverType === 'Virtual') {
            $virtualItems = [
                "Select the right guest operating system and version",
                "Select compatible NIC adapter for network performance (VMNET3 is recommend)",
                "Select proper VLAN (portgroup) for virtual machine. Create new if need.",
                "Assign CPU, RAM by follow recommendation according to service and role",
                "Assign proper datastore base on require space, redundancy and DR need",
                "Install VMware tool",
                "Remove unused device such floppy, ISO file"
            ];
            return array_merge($commonItems, $virtualItems);
        } elseif ($serverType === 'Physical') {
            $physicalItems = [
                "Install physical server properly in the rack",
                "Connect physical server to two different power source with correct power cord",
                "Label power patch cord, network & SAN cable and server follow naming convention",
                "Enable lock screen, screen saver, session timeout and CMOS password protection",
                "Disable boot from USB, Network and CD Room after completed installation tasks",
                "Remove unused peripheral or equipment after installation completed",
                "Ensure that physically server, rack and server room is locked properly to protect",
                "Put asset tag and update asset information on GLPI (https://asset.smart.com.kh)",
                "Update Storage Layout diagram",
                "Update rack diagram list",
                "Update physical server diagram",
                "Update description of SAN switch port & network switch port",
                "Monitor server hardware health via iLO, iMana or iDrac Interface"
            ];
            return array_merge($commonItems, $physicalItems);
        }

        return $commonItems;
    }
}
?>
