<?php
class User extends Model {
    private $table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $query = "INSERT INTO " . $this->table . " (id, username, email, password, role) 
                  VALUES (UUID(), :username, :email, :password, :role)";
        
        $stmt = $this->db->prepare($query);
        
        // Sanitize and bind data
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':role', $data['role']);
        
        return $stmt->execute();
    }

    public function updateUser($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, email = :email, role = :role, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Sanitize and bind data
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role', $data['role']);
        
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $password) {
        $query = "UPDATE " . $this->table . " 
                  SET password = :password, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password', $password);
        
        return $stmt->execute();
    }

    public function updateLastLogin($id) {
        $query = "UPDATE " . $this->table . " 
                  SET last_login = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>
