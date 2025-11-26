<?php
require_once '../app/middleware/auth.php';

class Help extends Controller {
    private $helpDocModel;

    public function __construct() {
        $this->helpDocModel = $this->model('HelpDoc');
    }

    // Display all help docs
    public function index() {
        $docs = $this->helpDocModel->getAllDocs();
        
        // Group docs by category
        $groupedDocs = [];
        foreach ($docs as $doc) {
            $groupedDocs[$doc['category']][] = $doc;
        }
        
        $data = [
            'docs' => $groupedDocs,
            'categories' => $this->helpDocModel->getCategories()
        ];

        $this->view('help/index', $data);
    }

    // Show create doc form (admin only)
    public function create() {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])) {
            header('Location: /help');
            exit;
        }

        $data = [
            'title' => '',
            'content' => '',
            'category' => '',
            'sort_order' => '',
            'title_err' => '',
            'content_err' => '',
            'category_err' => '',
            'sort_order_err' => '',
            'categories' => $this->helpDocModel->getCategories()
        ];

        $this->view('help/create', $data);
    }

    // Store new help doc (admin only)
    public function store() {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])) {
            header('Location: /help');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'category' => trim($_POST['category']),
                'sort_order' => trim($_POST['sort_order']),
                'title_err' => '',
                'content_err' => '',
                'category_err' => '',
                'sort_order_err' => '',
                'categories' => $this->helpDocModel->getCategories()
            ];

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter content';
            }

            // Validate category
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Validate sort order
            if (empty($data['sort_order'])) {
                $data['sort_order_err'] = 'Please enter a sort order';
            } elseif (!is_numeric($data['sort_order'])) {
                $data['sort_order_err'] = 'Sort order must be a number';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['content_err']) && empty($data['category_err']) && empty($data['sort_order_err'])) {
                $docData = [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'category' => $data['category'],
                    'sort_order' => $data['sort_order'],
                    'created_by' => $_SESSION['username']
                ];

                // Create doc
                if ($this->helpDocModel->createDoc($docData)) {
                    header('Location: /help');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('help/create', $data);
        } else {
            header('Location: /help');
            exit;
        }
    }

    // Show edit doc form (admin only)
    public function edit($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])) {
            header('Location: /help');
            exit;
        }

        $doc = $this->helpDocModel->getDocById($id);

        if (!$doc) {
            header('Location: /help');
            exit;
        }

        $data = [
            'doc' => $doc,
            'title' => $doc['title'],
            'content' => $doc['content'],
            'category' => $doc['category'],
            'sort_order' => $doc['sort_order'],
            'title_err' => '',
            'content_err' => '',
            'category_err' => '',
            'sort_order_err' => '',
            'categories' => $this->helpDocModel->getCategories()
        ];

        $this->view('help/edit', $data);
    }

    // Update help doc (admin only)
    public function update($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])) {
            header('Location: /help');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $doc = $this->helpDocModel->getDocById($id);

            if (!$doc) {
                header('Location: /help');
                exit;
            }

            $data = [
                'doc' => $doc,
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'category' => trim($_POST['category']),
                'sort_order' => trim($_POST['sort_order']),
                'title_err' => '',
                'content_err' => '',
                'category_err' => '',
                'sort_order_err' => '',
                'categories' => $this->helpDocModel->getCategories()
            ];

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter content';
            }

            // Validate category
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Validate sort order
            if (empty($data['sort_order'])) {
                $data['sort_order_err'] = 'Please enter a sort order';
            } elseif (!is_numeric($data['sort_order'])) {
                $data['sort_order_err'] = 'Sort order must be a number';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['content_err']) && empty($data['category_err']) && empty($data['sort_order_err'])) {
                $docData = [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'category' => $data['category'],
                    'sort_order' => $data['sort_order']
                ];

                // Update doc
                if ($this->helpDocModel->updateDoc($id, $docData)) {
                    header('Location: /help');
                    exit;
                } else {
                    die('Something went wrong');
                }
            }

            $this->view('help/edit', $data);
        } else {
            header('Location: /help');
            exit;
        }
    }

    // Delete help doc (admin only)
    public function delete($id) {
        // Check if user has appropriate role
        if (!in_array($_SESSION['role'], ['ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER'])) {
            header('Location: /help');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->helpDocModel->deleteDoc($id)) {
                header('Location: /help');
                exit;
            } else {
                die('Something went wrong');
            }
        } else {
            header('Location: /help');
            exit;
        }
    }
}
?>
