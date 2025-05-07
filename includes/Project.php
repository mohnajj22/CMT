<?php
class Project {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new project
    public function create($title, $description, $deadline, $created_by) {
        $query = "INSERT INTO projects (title, description, deadline, created_by) 
                  VALUES (:title, :description, :deadline, :created_by)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':created_by', $created_by);

        // Execute query
        return $stmt->execute();
    }

    // Get all projects for a specific professor (by professor_id)
    public function getProjectsByProfessor($professor_id) {
        $query = "SELECT * FROM projects WHERE created_by = :professor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':professor_id', $professor_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllProjects() {
        $query = "SELECT * FROM projects";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProjectById($id) {
        try {
            $query = "SELECT * FROM projects WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching project: " . $e->getMessage();
            return false;
        }
    }
    public function createWithCode($title, $description, $deadline, $professor_id, $code) {
        $stmt = $this->conn->prepare("INSERT INTO projects (title, description, deadline, professor_id, enrollment_code)
                                      VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $deadline, $professor_id, $code]);
    }
    
    
}
