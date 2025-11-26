<?php
class Setting extends Model {
    private $table = 'settings';

    public function __construct() {
        parent::__construct();
    }

    public function getSettings() {
        $query = "SELECT * FROM " . $this->table . " LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSettings($data) {
        // Check if settings exist
        $existing = $this->getSettings();
        
        if ($existing) {
            // Update existing settings
            $query = "UPDATE " . $this->table . " 
                      SET smtp_host = :smtp_host, smtp_port = :smtp_port, smtp_user = :smtp_user, 
                          smtp_pass = :smtp_pass, smtp_from = :smtp_from, company_logo = :company_logo, 
                          company_name = :company_name";
            
            $stmt = $this->db->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(':smtp_host', $data['smtp_host']);
            $stmt->bindParam(':smtp_port', $data['smtp_port']);
            $stmt->bindParam(':smtp_user', $data['smtp_user']);
            $stmt->bindParam(':smtp_pass', $data['smtp_pass']);
            $stmt->bindParam(':smtp_from', $data['smtp_from']);
            $stmt->bindParam(':company_logo', $data['company_logo']);
            $stmt->bindParam(':company_name', $data['company_name']);
            
            return $stmt->execute();
        } else {
            // Insert new settings
            $query = "INSERT INTO " . $this->table . " 
                      (id, smtp_host, smtp_port, smtp_user, smtp_pass, smtp_from, company_logo, company_name) 
                      VALUES 
                      (UUID(), :smtp_host, :smtp_port, :smtp_user, :smtp_pass, :smtp_from, :company_logo, :company_name)";
            
            $stmt = $this->db->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(':smtp_host', $data['smtp_host']);
            $stmt->bindParam(':smtp_port', $data['smtp_port']);
            $stmt->bindParam(':smtp_user', $data['smtp_user']);
            $stmt->bindParam(':smtp_pass', $data['smtp_pass']);
            $stmt->bindParam(':smtp_from', $data['smtp_from']);
            $stmt->bindParam(':company_logo', $data['company_logo']);
            $stmt->bindParam(':company_name', $data['company_name']);
            
            return $stmt->execute();
        }
    }

    public function updateLogo($logoPath) {
        $existing = $this->getSettings();
        
        if ($existing) {
            $query = "UPDATE " . $this->table . " SET company_logo = :company_logo";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_logo', $logoPath);
            return $stmt->execute();
        } else {
            $query = "INSERT INTO " . $this->table . " (id, company_logo) VALUES (UUID(), :company_logo)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_logo', $logoPath);
            return $stmt->execute();
        }
    }

    public function updateCompanyName($companyName) {
        $existing = $this->getSettings();
        
        if ($existing) {
            $query = "UPDATE " . $this->table . " SET company_name = :company_name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_name', $companyName);
            return $stmt->execute();
        } else {
            $query = "INSERT INTO " . $this->table . " (id, company_name) VALUES (UUID(), :company_name)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':company_name', $companyName);
            return $stmt->execute();
        }
    }
}
