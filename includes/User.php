<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function fetchUsers($query) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllUsers() {
        return $this->fetchUsers("SELECT id, name, email, role FROM users");
    }

    public function getTeamMembers() {
        return $this->fetchUsers("SELECT id, name FROM users WHERE role = 'team_member'");
    }
}
?>
