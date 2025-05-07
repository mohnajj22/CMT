<?php
class Task {
    private $conn;
    private $table = 'tasks';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Assign a task to a team member
    public function assignTask($title, $deadline, $assigned_to, $project_id) {
        $sql = "INSERT INTO tasks (title, deadline, assigned_to, project_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$title, $deadline, $assigned_to, $project_id]);
    }
    

    // Get all tasks for a specific project
    public function getTasksByProject($project_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE project_id = :project_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all team members (to assign tasks)
    public function getAllTeamMembers() {
        $query = "SELECT id, name FROM users WHERE role = 'team_member'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($title, $description, $deadline, $project_id, $file = null) {
        $query = "INSERT INTO tasks (title, description, deadline, project_id, file)
                  VALUES (:title, :description, :deadline, :project_id, :file)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':deadline' => $deadline,
            ':project_id' => $project_id,
            ':file' => $file
        ]);
    }
    
}
?>
