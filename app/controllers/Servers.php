<?php
require_once '../app/middleware/auth.php';

class Servers extends Controller {
    private $serverModel;

    public function __construct() {
        $this->serverModel = $this->model('Server');
    }

    // Display all servers
    public function index() {
        $filters = [
            'name' => $_GET['name'] ?? '',
            'ip' => $_GET['ip'] ?? '',
            'owner' => $_GET['owner'] ?? '',
            'type' => $_GET['type'] ?? '',
            'env' => $_GET['env'] ?? '',
            'site' => $_GET['site'] ?? '',
            'asset_class' => $_GET['asset_class'] ?? '',
            'asset_type' => $_GET['asset_type'] ?? ''
        ];

        $servers = $this->serverModel->getServersByFilter($filters);
        $data = [
            'servers' => $servers,
            'filters' => $filters
        ];

        $this->view('servers/index', $data);
    }

    // Show add server form
    public function create() {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])) {
            header('Location: /servers');
            exit;
        }

        $data = [
            'name' => '',
            'ip' => '',
            'os' => '',
            'site' => '',
            'type' => '',
            'env' => '',
            'owner' => '',
            'pic' => '',
            'vendor' => '',
            'cpu' => '',
            'ram' => '',
            'disk' => '',
            'asset_class' => '',
            'asset_type' => '',
            'deploy_date' => '',
            'hypervisor' => '',
            'name_err' => '',
            'ip_err' => '',
            'os_err' => '',
            'site_err' => '',
            'type_err' => '',
            'env_err' => '',
            'owner_err' => '',
            'pic_err' => '',
            'asset_class_err' => '',
            'asset_type_err' => ''
        ];

        $this->view('servers/create', $data);
    }

    // Add new server
    public function store() {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])) {
            header('Location: /servers');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'ip' => trim($_POST['ip']),
                'os' => trim($_POST['os']),
                'site' => trim($_POST['site']),
                'type' => trim($_POST['type']),
                'env' => trim($_POST['env']),
                'owner' => trim($_POST['owner']),
                'pic' => trim($_POST['pic']),
                'vendor' => trim($_POST['vendor']),
                'cpu' => trim($_POST['cpu']),
                'ram' => trim($_POST['ram']),
                'disk' => trim($_POST['disk']),
                'asset_class' => trim($_POST['asset_class']),
                'asset_type' => trim($_POST['asset_type']),
                'deploy_date' => trim($_POST['deploy_date']),
                'hypervisor' => trim($_POST['hypervisor']),
                'name_err' => '',
                'ip_err' => '',
                'os_err' => '',
                'site_err' => '',
                'type_err' => '',
                'env_err' => '',
                'owner_err' => '',
                'pic_err' => '',
                'asset_class_err' => '',
                'asset_type_err' => ''
            ];

            // Validate required fields
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter server name';
            } elseif (strlen($data['name']) < 3 || strlen($data['name']) > 100) {
                $data['name_err'] = 'Name must be between 3 and 100 characters';
            }

            if (empty($data['ip'])) {
                $data['ip_err'] = 'Please enter IP address';
            } elseif (!filter_var($data['ip'], FILTER_VALIDATE_IP)) {
                $data['ip_err'] = 'Please enter valid IP address';
            }

            if (empty($data['os'])) {
                $data['os_err'] = 'Please select OS';
            }

            if (empty($data['site'])) {
                $data['site_err'] = 'Please enter site';
            }

            if (empty($data['type'])) {
                $data['type_err'] = 'Please select type';
            }

            if (empty($data['env'])) {
                $data['env_err'] = 'Please select environment';
            }

            if (empty($data['owner'])) {
                $data['owner_err'] = 'Please enter owner';
            }

            if (empty($data['pic'])) {
                $data['pic_err'] = 'Please enter Person In Charge';
            }

            if (empty($data['asset_class'])) {
                $data['asset_class_err'] = 'Please select asset class';
            }

            if (empty($data['asset_type'])) {
                $data['asset_type_err'] = 'Please select asset type';
            }

            // Make sure no errors
            if (empty($data['name_err']) && empty($data['ip_err']) && empty($data['os_err']) && 
                empty($data['site_err']) && empty($data['type_err']) && empty($data['env_err']) && 
                empty($data['owner_err']) && empty($data['pic_err']) && empty($data['asset_class_err']) && 
                empty($data['asset_type_err'])) {
                
                // Create server
                if ($this->serverModel->createServer($data)) {
                    header('Location: /servers');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('servers/create', $data);
        } else {
            $data = [
                'name' => '',
                'ip' => '',
                'os' => '',
                'site' => '',
                'type' => '',
                'env' => '',
                'owner' => '',
                'pic' => '',
                'vendor' => '',
                'cpu' => '',
                'ram' => '',
                'disk' => '',
                'asset_class' => '',
                'asset_type' => '',
                'deploy_date' => '',
                'hypervisor' => '',
                'name_err' => '',
                'ip_err' => '',
                'os_err' => '',
                'site_err' => '',
                'type_err' => '',
                'env_err' => '',
                'owner_err' => '',
                'pic_err' => '',
                'asset_class_err' => '',
                'asset_type_err' => ''
            ];

            $this->view('servers/create', $data);
        }
    }

    // Show server details
    public function show($id) {
        $server = $this->serverModel->getServerById($id);

        if (!$server) {
            header('Location: /servers');
            exit;
        }

        $data = [
            'server' => $server
        ];

        $this->view('servers/show', $data);
    }

    // Show edit server form
    public function edit($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])) {
            header('Location: /servers');
            exit;
        }

        $server = $this->serverModel->getServerById($id);

        if (!$server) {
            header('Location: /servers');
            exit;
        }

        $data = [
            'server' => $server,
            'name' => $server['name'],
            'ip' => $server['ip'],
            'os' => $server['os'],
            'site' => $server['site'],
            'type' => $server['type'],
            'env' => $server['env'],
            'owner' => $server['owner'],
            'pic' => $server['pic'],
            'vendor' => $server['vendor'],
            'cpu' => $server['cpu'],
            'ram' => $server['ram'],
            'disk' => $server['disk'],
            'asset_class' => $server['asset_class'],
            'asset_type' => $server['asset_type'],
            'deploy_date' => $server['deploy_date'],
            'hypervisor' => $server['hypervisor'],
            'name_err' => '',
            'ip_err' => '',
            'os_err' => '',
            'site_err' => '',
            'type_err' => '',
            'env_err' => '',
            'owner_err' => '',
            'pic_err' => '',
            'asset_class_err' => '',
            'asset_type_err' => ''
        ];

        $this->view('servers/edit', $data);
    }

    // Update server
    public function update($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])) {
            header('Location: /servers');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'ip' => trim($_POST['ip']),
                'os' => trim($_POST['os']),
                'site' => trim($_POST['site']),
                'type' => trim($_POST['type']),
                'env' => trim($_POST['env']),
                'owner' => trim($_POST['owner']),
                'pic' => trim($_POST['pic']),
                'vendor' => trim($_POST['vendor']),
                'cpu' => trim($_POST['cpu']),
                'ram' => trim($_POST['ram']),
                'disk' => trim($_POST['disk']),
                'asset_class' => trim($_POST['asset_class']),
                'asset_type' => trim($_POST['asset_type']),
                'deploy_date' => trim($_POST['deploy_date']),
                'hypervisor' => trim($_POST['hypervisor']),
                'name_err' => '',
                'ip_err' => '',
                'os_err' => '',
                'site_err' => '',
                'type_err' => '',
                'env_err' => '',
                'owner_err' => '',
                'pic_err' => '',
                'asset_class_err' => '',
                'asset_type_err' => ''
            ];

            // Validate required fields
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter server name';
            } elseif (strlen($data['name']) < 3 || strlen($data['name']) > 100) {
                $data['name_err'] = 'Name must be between 3 and 100 characters';
            }

            if (empty($data['ip'])) {
                $data['ip_err'] = 'Please enter IP address';
            } elseif (!filter_var($data['ip'], FILTER_VALIDATE_IP)) {
                $data['ip_err'] = 'Please enter valid IP address';
            }

            if (empty($data['os'])) {
                $data['os_err'] = 'Please select OS';
            }

            if (empty($data['site'])) {
                $data['site_err'] = 'Please enter site';
            }

            if (empty($data['type'])) {
                $data['type_err'] = 'Please select type';
            }

            if (empty($data['env'])) {
                $data['env_err'] = 'Please select environment';
            }

            if (empty($data['owner'])) {
                $data['owner_err'] = 'Please enter owner';
            }

            if (empty($data['pic'])) {
                $data['pic_err'] = 'Please enter Person In Charge';
            }

            if (empty($data['asset_class'])) {
                $data['asset_class_err'] = 'Please select asset class';
            }

            if (empty($data['asset_type'])) {
                $data['asset_type_err'] = 'Please select asset type';
            }

            // Make sure no errors
            if (empty($data['name_err']) && empty($data['ip_err']) && empty($data['os_err']) && 
                empty($data['site_err']) && empty($data['type_err']) && empty($data['env_err']) && 
                empty($data['owner_err']) && empty($data['pic_err']) && empty($data['asset_class_err']) && 
                empty($data['asset_type_err'])) {
                
                // Update server
                if ($this->serverModel->updateServer($id, $data)) {
                    header('Location: /servers');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('servers/edit', $data);
        } else {
            $server = $this->serverModel->getServerById($id);

            if (!$server) {
                header('Location: /servers');
                exit;
            }

            $data = [
                'server' => $server,
                'name' => $server['name'],
                'ip' => $server['ip'],
                'os' => $server['os'],
                'site' => $server['site'],
                'type' => $server['type'],
                'env' => $server['env'],
                'owner' => $server['owner'],
                'pic' => $server['pic'],
                'vendor' => $server['vendor'],
                'cpu' => $server['cpu'],
                'ram' => $server['ram'],
                'disk' => $server['disk'],
                'asset_class' => $server['asset_class'],
                'asset_type' => $server['asset_type'],
                'deploy_date' => $server['deploy_date'],
                'hypervisor' => $server['hypervisor'],
                'name_err' => '',
                'ip_err' => '',
                'os_err' => '',
                'site_err' => '',
                'type_err' => '',
                'env_err' => '',
                'owner_err' => '',
                'pic_err' => '',
                'asset_class_err' => '',
                'asset_type_err' => ''
            ];

            $this->view('servers/edit', $data);
        }
    }

    // Delete server
    public function delete($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_ENGINEER'])) {
            header('Location: /servers');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->serverModel->deleteServer($id)) {
                header('Location: /servers');
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: /servers');
            exit;
        }
    }
}
?>
