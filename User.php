<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all users (professors and team members)
    public function getAllUsers() {
        $query = "SELECT id, name, email, role FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all team members
    public function getTeamMembers() {
        $query = "SELECT id, name FROM " . $this->table . " WHERE role = 'team_member'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
