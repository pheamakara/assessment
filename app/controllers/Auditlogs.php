<?php
require_once '../app/middleware/auth.php';

class Auditlogs extends Controller {
    private $auditLogModel;

    public function __construct() {
        $this->auditLogModel = $this->model('AuditLog');
    }

    // Display audit logs
    public function index() {
        // Check if user has appropriate role (admin or auditor)
        if (!in_array($_SESSION['role'], ['ADMIN', 'AUDITOR'])) {
            header('Location: /dashboard');
            exit;
        }

        $filters = [
            'username' => $_GET['username'] ?? '',
            'action' => $_GET['action'] ?? '',
            'entity' => $_GET['entity'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];

        $logs = $this->auditLogModel->getLogsByFilter($filters);

        $data = [
            'logs' => $logs,
            'filters' => $filters,
            'actions' => $this->auditLogModel->getActions(),
            'entities' => $this->auditLogModel->getEntities()
        ];

        $this->view('auditlogs/index', $data);
    }

    // Export logs to CSV
    public function export() {
        // Check if user has appropriate role (admin or auditor)
        if (!in_array($_SESSION['role'], ['ADMIN', 'AUDITOR'])) {
            header('Location: /dashboard');
            exit;
        }

        $filters = [
            'username' => $_GET['username'] ?? '',
            'action' => $_GET['action'] ?? '',
            'entity' => $_GET['entity'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];

        $logs = $this->auditLogModel->getLogsByFilter($filters, 1000); // Limit to 1000 records for export

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV header row
        fputcsv($output, ['Timestamp', 'Username', 'Action', 'Entity', 'Entity ID', 'Details']);

        // Add data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['timestamp'],
                $log['username'],
                $log['action'],
                $log['entity'],
                $log['entity_id'],
                $log['details']
            ]);
        }

        fclose($output);
        exit;
    }
}
?>
