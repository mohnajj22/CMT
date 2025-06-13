<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/User.php';

class UserTest extends TestCase {
    private $pdo;
    private $user;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->user = new User($this->pdo);
    }

    public function testGetAllUsers() {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('fetchAll')
             ->willReturn([
                 ['id' => 1, 'name' => 'Ahmed', 'email' => 'ahmed@example.com', 'role' => 'professor'],
                 ['id' => 2, 'name' => 'Khalifa', 'email' => 'khalifa@example.com', 'role' => 'team_member']
             ]);

        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->with('SELECT id, name, email, role FROM users')
                  ->willReturn($stmt);

        $result = $this->user->getAllUsers();
        $this->assertCount(2, $result);
        $this->assertEquals('Ahmed', $result[0]['name']);
    }

  public function testGetTeamMembers() {
    $stmt = $this->createMock(PDOStatement::class);
    $stmt->expects($this->once())
         ->method('fetchAll')
         ->willReturn([
             ['id' => 2, 'name' => 'Khalifa']
         ]);

    $this->pdo->expects($this->once())
              ->method('prepare')
              ->with("SELECT id, name FROM users WHERE role = 'team_member'")
              ->willReturn($stmt);

    $result = $this->user->getTeamMembers();
    $this->assertCount(1, $result);
    $this->assertEquals('Khalifa', $result[0]['name']);
}

}
?>
