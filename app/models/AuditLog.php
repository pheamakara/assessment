<?php
class AuditLog extends Model {
    private $table = 'audit_logs';

    public function __construct() {
        parent::__construct();
    }

    public function getAllLogs($limit = 50) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY timestamp DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogsByFilter($filters = [], $limit = 50) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];

        if (!empty($filters['username'])) {
            $query .= " AND username LIKE :username";
            $params[':username'] = '%' . $filters['username'] . '%';
        }

        if (!empty($filters['action'])) {
            $query .= " AND action = :action";
            $params[':action'] = $filters['action'];
        }

        if (!empty($filters['entity'])) {
            $query .= " AND entity = :entity";
            $params[':entity'] = $filters['entity'];
        }

        if (!empty($filters['date_from'])) {
            $query .= " AND timestamp >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $query .= " AND timestamp <= :date_to";
            $params[':date_to'] = $filters['date_to'] . ' 23:59:59';
        }

        $query .= " ORDER BY timestamp DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogsByEntityId($entityId) {
        $query = "SELECT * FROM " . $this->table . " WHERE entity_id = :entity_id ORDER BY timestamp DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':entity_id', $entityId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLog($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id, username, action, entity, entity_id, details) 
                  VALUES 
                  (UUID(), :username, :action, :entity, :entity_id, :details)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':action', $data['action']);
        $stmt->bindParam(':entity', $data['entity']);
        $stmt->bindParam(':entity_id', $data['entity_id']);
        $stmt->bindParam(':details', $data['details']);
        
        return $stmt->execute();
    }

    public function getActions() {
        return [
            'LOGIN', 'LOGOUT', 'CREATE_USER', 'UPDATE_USER', 'DELETE_USER', 'SET_PASSWORD',
            'CREATE_SERVER', 'UPDATE_SERVER', 'DELETE_SERVER', 'IMPORT_SERVERS', 'EXPORT_SERVERS',
            'CREATE_CHECKLIST', 'UPDATE_CHECKLIST_STATUS', 'APPROVE_CHECKLIST', 'REJECT_CHECKLIST',
            'EXPORT_CHECKLIST_PDF', 'UPDATE_SETTINGS', 'UPLOAD_LOGO', 'GENERATE_REPORT', 'SEND_REPORT'
        ];
    }

    public function getEntities() {
        return [
            'SERVER' => 'Server',
            'CHECKLIST' => 'Checklist',
            'USER' => 'User',
            'SETTING' => 'Setting'
        ];
    }
}
?>
