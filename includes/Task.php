<?php
class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function prepareInsertTask() {
        return $this->pdo->prepare("INSERT INTO tasks (title, deadline, assigned_to, project_id) VALUES (?, ?, ?, ?)");
    }

    public function assignTask($title, $deadline, $assigned_to, $project_id) {
        $stmt = $this->prepareInsertTask();
        return $stmt->execute([$title, $deadline, $assigned_to, $project_id]);
    }

    public function getTasksByProject($project_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE project_id = ?");
        $stmt->execute([$project_id]);
        return $stmt->fetchAll();
    }

    public function getAllTeamMembers() {
        $stmt = $this->pdo->prepare("SELECT id, name FROM users WHERE role = 'team_member'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($title, $deadline, $assigned_to, $project_id) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, deadline, assigned_to, project_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $deadline, $assigned_to, $project_id]);
    }

    public function submitWork($task_id, $file_name) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET submission_file = ?, status = 'submitted' WHERE id = ?");
        return $stmt->execute([$file_name, $task_id]);
    }
}
?>
