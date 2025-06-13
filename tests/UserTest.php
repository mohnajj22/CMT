<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../includes/User.php';

class UserTest extends TestCase {
    public function testGetTeamMembers() {
        $mock = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        // Simulate the returned rows
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Ahmed'],
            ['id' => 2, 'name' => 'Sara']
        ]);

        $stmt->method('execute')->willReturn(true);
        $mock->method('prepare')->willReturn($stmt);

        $user = new User($mock);
        $result = $user->getTeamMembers();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Ahmed', $result[0]['name']);
    }
}
