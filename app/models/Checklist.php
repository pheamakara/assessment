<?php
class Checklist extends Model {
    private $table = 'checklists';

    public function __construct() {
        parent::__construct();
    }

    public function getAllChecklists() {
        $query = "SELECT c.*, s.name as server_name, s.ip as server_ip 
                  FROM " . $this->table . " c 
                  JOIN servers s ON c.server_id = s.id 
                  ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChecklistById($id) {
        $query = "SELECT c.*, s.name as server_name, s.ip as server_ip, s.type as server_type 
                  FROM " . $this->table . " c 
                  JOIN servers s ON c.server_id = s.id 
                  WHERE c.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChecklistsByServerId($serverId) {
        $query = "SELECT * FROM " . $this->table . " WHERE server_id = :server_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':server_id', $serverId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChecklistsByStatus($status) {
        $query = "SELECT c.*, s.name as server_name, s.ip as server_ip 
                  FROM " . $this->table . " c 
                  JOIN servers s ON c.server_id = s.id 
                  WHERE c.status = :status 
                  ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChecklistsByFilter($filters = []) {
        $query = "SELECT c.*, s.name as server_name, s.ip as server_ip 
                  FROM " . $this->table . " c 
                  JOIN servers s ON c.server_id = s.id 
                  WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $query .= " AND c.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['server_name'])) {
            $query .= " AND s.name LIKE :server_name";
            $params[':server_name'] = '%' . $filters['server_name'] . '%';
        }

        $query .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createChecklist($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id, server_id, type, items, requested_by) 
                  VALUES 
                  (UUID(), :server_id, :type, :items, :requested_by)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':server_id', $data['server_id']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':items', $data['items']);
        $stmt->bindParam(':requested_by', $data['requested_by']);
        
        return $stmt->execute();
    }

    public function updateChecklistStatus($id, $status, $approvedBy = null, $rejectionReason = null) {
        if ($status === 'REJECTED') {
            $query = "UPDATE " . $this->table . " 
                      SET status = :status, rejection_reason = :rejection_reason, approved_by = :approved_by, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':rejection_reason', $rejectionReason);
            $stmt->bindParam(':approved_by', $approvedBy);
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET status = :status, approved_by = :approved_by, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':approved_by', $approvedBy);
        }
        
        return $stmt->execute();
    }

    // Get checklist statistics for dashboard
    public function getChecklistStats() {
        $query = "SELECT 
                    COUNT(*) as total_checklists,
                    SUM(CASE WHEN status = 'DRAFT' THEN 1 ELSE 0 END) as draft_checklists,
                    SUM(CASE WHEN status = 'PENDING_SECURITY' THEN 1 ELSE 0 END) as pending_security_checklists,
                    SUM(CASE WHEN status = 'PENDING_CLOUD' THEN 1 ELSE 0 END) as pending_cloud_checklists,
                    SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) as approved_checklists,
                    SUM(CASE WHEN status = 'REJECTED' THEN 1 ELSE 0 END) as rejected_checklists
                  FROM " . $this->table;
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get recent checklist activities for dashboard
    public function getRecentActivities($limit = 5) {
        $query = "SELECT c.*, s.name as server_name, s.ip as server_ip 
                  FROM " . $this->table . " c 
                  JOIN servers s ON c.server_id = s.id 
                  ORDER BY c.updated_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
