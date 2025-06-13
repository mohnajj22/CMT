<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../includes/Task.php';

class TaskTest extends TestCase {

    public function testAssignTask() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $task = new Task($mock);
        $result = $task->assignTask("Final Report", "2025-12-31", 2, 5);
        $this->assertTrue($result);
    }

    public function testSubmitWork() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $task = new Task($mock);
        $result = $task->submitWork(1, 2, 'uploads/report.pdf');
        $this->assertTrue($result);
    }

    public function testGetTasksByProject() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Task A'],
            ['id' => 2, 'title' => 'Task B']
        ]);
        $mock->method('prepare')->willReturn($stmt);

        $task = new Task($mock);
        $result = $task->getTasksByProject(5);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetAllTeamMembers() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Ayoub'],
            ['id' => 2, 'name' => 'Khalifa']
        ]);
        $mock->method('prepare')->willReturn($stmt);

        $task = new Task($mock);
        $members = $task->getAllTeamMembers();

        $this->assertIsArray($members);
        $this->assertEquals('Ayoub', $members[0]['name']);
    }

    public function testCreateTask() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $task = new Task($mock);
        $result = $task->create("Doc Review", "Final check", "2025-12-31", 3, "files/doc.pdf");

        $this->assertTrue($result);
    }
}
