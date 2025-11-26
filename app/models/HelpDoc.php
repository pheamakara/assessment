<?php
class HelpDoc extends Model {
    private $table = 'help_docs';

    public function __construct() {
        parent::__construct();
    }

    public function getAllDocs() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY category, sort_order";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDocById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDocsByCategory($category) {
        $query = "SELECT * FROM " . $this->table . " WHERE category = :category ORDER BY sort_order";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDoc($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id, title, content, category, sort_order, created_by) 
                  VALUES 
                  (UUID(), :title, :content, :category, :sort_order, :created_by)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':sort_order', $data['sort_order']);
        $stmt->bindParam(':created_by', $data['created_by']);
        
        return $stmt->execute();
    }

    public function updateDoc($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, content = :content, category = :category, sort_order = :sort_order, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':content', $data['content']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':sort_order', $data['sort_order']);
        
        return $stmt->execute();
    }

    public function deleteDoc($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getCategories() {
        return [
            'GETTING_STARTED' => 'Getting Started',
            'USER_GUIDE' => 'User Guide',
            'ADMIN_GUIDE' => 'Admin Guide',
            'TROUBLESHOOTING' => 'Troubleshooting'
        ];
    }
}
?>
