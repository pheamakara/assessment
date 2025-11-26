<?php
require_once '../app/middleware/auth.php';

class Dashboard extends Controller {
    private $serverModel;
    private $checklistModel;

    public function __construct() {
        $this->serverModel = $this->model('Server');
        $this->checklistModel = $this->model('Checklist');
    }

    // Display dashboard
    public function index() {
        // Get server statistics
        $serverStats = $this->serverModel->getServerStats();
        
        // Get checklist statistics
        $checklistStats = $this->checklistModel->getChecklistStats();
        
        // Get OS distribution
        $osDistribution = $this->serverModel->getOsDistribution();
        
        // Get hypervisor distribution
        $hypervisorDistribution = $this->serverModel->getHypervisorDistribution();
        
        // Get recent activities
        $recentActivities = $this->checklistModel->getRecentActivities();
        
        $data = [
            'serverStats' => $serverStats,
            'checklistStats' => $checklistStats,
            'osDistribution' => $osDistribution,
            'hypervisorDistribution' => $hypervisorDistribution,
            'recentActivities' => $recentActivities
        ];

        $this->view('dashboard/index', $data);
    }
}
?>
