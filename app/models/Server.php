<?php
class Server extends Model {
    private $table = 'servers';

    public function __construct() {
        parent::__construct();
    }

    public function getAllServers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServerById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServersByFilter($filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $query .= " AND name LIKE :name";
            $params[':name'] = '%' . $filters['name'] . '%';
        }

        if (!empty($filters['ip'])) {
            $query .= " AND ip LIKE :ip";
            $params[':ip'] = '%' . $filters['ip'] . '%';
        }

        if (!empty($filters['owner'])) {
            $query .= " AND owner LIKE :owner";
            $params[':owner'] = '%' . $filters['owner'] . '%';
        }

        if (!empty($filters['type'])) {
            $query .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['env'])) {
            $query .= " AND env = :env";
            $params[':env'] = $filters['env'];
        }

        if (!empty($filters['site'])) {
            $query .= " AND site = :site";
            $params[':site'] = $filters['site'];
        }

        if (!empty($filters['asset_class'])) {
            $query .= " AND asset_class = :asset_class";
            $params[':asset_class'] = $filters['asset_class'];
        }

        if (!empty($filters['asset_type'])) {
            $query .= " AND asset_type = :asset_type";
            $params[':asset_type'] = $filters['asset_type'];
        }

        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createServer($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id, name, ip, os, site, type, env, owner, pic, vendor, cpu, ram, disk, asset_class, asset_type, deploy_date, hypervisor) 
                  VALUES 
                  (UUID(), :name, :ip, :os, :site, :type, :env, :owner, :pic, :vendor, :cpu, :ram, :disk, :asset_class, :asset_type, :deploy_date, :hypervisor)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':ip', $data['ip']);
        $stmt->bindParam(':os', $data['os']);
        $stmt->bindParam(':site', $data['site']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':env', $data['env']);
        $stmt->bindParam(':owner', $data['owner']);
        $stmt->bindParam(':pic', $data['pic']);
        $stmt->bindParam(':vendor', $data['vendor']);
        $stmt->bindParam(':cpu', $data['cpu']);
        $stmt->bindParam(':ram', $data['ram']);
        $stmt->bindParam(':disk', $data['disk']);
        $stmt->bindParam(':asset_class', $data['asset_class']);
        $stmt->bindParam(':asset_type', $data['asset_type']);
        $stmt->bindParam(':deploy_date', $data['deploy_date']);
        $stmt->bindParam(':hypervisor', $data['hypervisor']);
        
        return $stmt->execute();
    }

    public function updateServer($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, ip = :ip, os = :os, site = :site, type = :type, env = :env, 
                      owner = :owner, pic = :pic, vendor = :vendor, cpu = :cpu, ram = :ram, disk = :disk, 
                      asset_class = :asset_class, asset_type = :asset_type, deploy_date = :deploy_date, 
                      hypervisor = :hypervisor, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':ip', $data['ip']);
        $stmt->bindParam(':os', $data['os']);
        $stmt->bindParam(':site', $data['site']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':env', $data['env']);
        $stmt->bindParam(':owner', $data['owner']);
        $stmt->bindParam(':pic', $data['pic']);
        $stmt->bindParam(':vendor', $data['vendor']);
        $stmt->bindParam(':cpu', $data['cpu']);
        $stmt->bindParam(':ram', $data['ram']);
        $stmt->bindParam(':disk', $data['disk']);
        $stmt->bindParam(':asset_class', $data['asset_class']);
        $stmt->bindParam(':asset_type', $data['asset_type']);
        $stmt->bindParam(':deploy_date', $data['deploy_date']);
        $stmt->bindParam(':hypervisor', $data['hypervisor']);
        
        return $stmt->execute();
    }

    public function deleteServer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get server statistics for dashboard
    public function getServerStats() {
        $query = "SELECT 
                    COUNT(*) as total_servers,
                    SUM(CASE WHEN type = 'Virtual' THEN 1 ELSE 0 END) as virtual_servers,
                    SUM(CASE WHEN type = 'Physical' THEN 1 ELSE 0 END) as physical_servers,
                    SUM(CASE WHEN env = 'PROD' THEN 1 ELSE 0 END) as prod_servers,
                    SUM(CASE WHEN env = 'DEV' THEN 1 ELSE 0 END) as dev_servers,
                    SUM(CASE WHEN env = 'UAT' THEN 1 ELSE 0 END) as uat_servers,
                    SUM(CASE WHEN env = 'DR' THEN 1 ELSE 0 END) as dr_servers
                  FROM " . $this->table;
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get OS distribution for dashboard
    public function getOsDistribution() {
        $query = "SELECT 
                    CASE 
                        WHEN os IN ('Ubuntu', 'Rocky', 'RHEL') THEN 'Linux'
                        WHEN os = 'Windows' THEN 'Windows'
                        ELSE 'Other'
                    END as os_family,
                    COUNT(*) as count
                  FROM " . $this->table . " 
                  GROUP BY os_family";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get hypervisor distribution for dashboard
    public function getHypervisorDistribution() {
        $query = "SELECT 
                    COALESCE(hypervisor, 'N/A') as hypervisor,
                    COUNT(*) as count
                  FROM " . $this->table . " 
                  GROUP BY hypervisor";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
