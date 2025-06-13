<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../includes/Project.php';

class ProjectTest extends TestCase {

    public function testCreate() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $project = new Project($mock);
        $result = $project->create("AI Project", "Build AI tools", "2025-12-31", 1);
        $this->assertTrue($result);
    }

    public function testGetProjectsByProfessor() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'AI Project'],
            ['id' => 2, 'title' => 'Web App']
        ]);
        $mock->method('prepare')->willReturn($stmt);

        $project = new Project($mock);
        $result = $project->getProjectsByProfessor(1);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetAllProjects() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'AI Project'],
            ['id' => 2, 'title' => 'Web App']
        ]);
        $mock->method('prepare')->willReturn($stmt);

        $project = new Project($mock);
        $projects = $project->getAllProjects();
        $this->assertIsArray($projects);
        $this->assertEquals('AI Project', $projects[0]['title']);
    }

    public function testGetProjectById() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn(['id' => 1, 'title' => 'AI Project']);
        $mock->method('prepare')->willReturn($stmt);

        $project = new Project($mock);
        $result = $project->getProjectById(1);
        $this->assertEquals('AI Project', $result['title']);
    }

    public function testCreateWithCode() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $project = new Project($mock);
        $result = $project->createWithCode("Secure App", "Encrypted system", "2025-12-31", 2, "ABC123");
        $this->assertTrue($result);
    }
}
