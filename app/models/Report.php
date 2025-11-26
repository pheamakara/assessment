<?php
class Report extends Model {
    private $templateTable = 'report_templates';
    private $scheduleTable = 'report_schedules';

    public function __construct() {
        parent::__construct();
    }

    // Template methods
    public function getAllTemplates() {
        $query = "SELECT * FROM " . $this->templateTable . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTemplateById($id) {
        $query = "SELECT * FROM " . $this->templateTable . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTemplate($data) {
        $query = "INSERT INTO " . $this->templateTable . " 
                  (id, name, description, type, created_by, filters, columns) 
                  VALUES 
                  (UUID(), :name, :description, :type, :created_by, :filters, :columns)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':created_by', $data['created_by']);
        $stmt->bindParam(':filters', $data['filters']);
        $stmt->bindParam(':columns', $data['columns']);
        
        return $stmt->execute();
    }

    public function updateTemplate($id, $data) {
        $query = "UPDATE " . $this->templateTable . " 
                  SET name = :name, description = :description, type = :type, filters = :filters, columns = :columns, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':filters', $data['filters']);
        $stmt->bindParam(':columns', $data['columns']);
        
        return $stmt->execute();
    }

    public function deleteTemplate($id) {
        $query = "DELETE FROM " . $this->templateTable . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Schedule methods
    public function getAllSchedules() {
        $query = "SELECT s.*, t.name as template_name FROM " . $this->scheduleTable . " s 
                  JOIN " . $this->templateTable . " t ON s.template_id = t.id 
                  ORDER BY s.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleById($id) {
        $query = "SELECT * FROM " . $this->scheduleTable . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSchedulesByTemplateId($templateId) {
        $query = "SELECT * FROM " . $this->scheduleTable . " WHERE template_id = :template_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':template_id', $templateId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createSchedule($data) {
        $query = "INSERT INTO " . $this->scheduleTable . " 
                  (id, template_id, frequency, day_of_week, day_of_month, time, recipients, is_active) 
                  VALUES 
                  (UUID(), :template_id, :frequency, :day_of_week, :day_of_month, :time, :recipients, :is_active)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':template_id', $data['template_id']);
        $stmt->bindParam(':frequency', $data['frequency']);
        $stmt->bindParam(':day_of_week', $data['day_of_week']);
        $stmt->bindParam(':day_of_month', $data['day_of_month']);
        $stmt->bindParam(':time', $data['time']);
        $stmt->bindParam(':recipients', $data['recipients']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function updateSchedule($id, $data) {
        $query = "UPDATE " . $this->scheduleTable . " 
                  SET template_id = :template_id, frequency = :frequency, day_of_week = :day_of_week, 
                      day_of_month = :day_of_month, time = :time, recipients = :recipients, 
                      is_active = :is_active, updated_at = CURRENT_TIMESTAMP 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':template_id', $data['template_id']);
        $stmt->bindParam(':frequency', $data['frequency']);
        $stmt->bindParam(':day_of_week', $data['day_of_week']);
        $stmt->bindParam(':day_of_month', $data['day_of_month']);
        $stmt->bindParam(':time', $data['time']);
        $stmt->bindParam(':recipients', $data['recipients']);
        $stmt->bindParam(':is_active', $data['is_active'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function deleteSchedule($id) {
        $query = "DELETE FROM " . $this->scheduleTable . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function activateSchedule($id) {
        $query = "UPDATE " . $this->scheduleTable . " SET is_active = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deactivateSchedule($id) {
        $query = "UPDATE " . $this->scheduleTable . " SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getFrequencies() {
        return [
            'DAILY' => 'Daily',
            'WEEKLY' => 'Weekly',
            'MONTHLY' => 'Monthly'
        ];
    }

    public function getDaysOfWeek() {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];
    }

    public function getDaysOfMonth() {
        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $days[$i] = $i;
        }
        return $days;
    }
}
?>
