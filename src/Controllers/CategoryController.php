<?php
require_once __DIR__ . '/../Config/Database.php';

class CategoryController
{
	private \PDO $conn;
	
	public function __construct()
	{
		$db = new \Database();
		$this->conn = $db->getConnection();
		header('Content-Type: application/json');
	}
	
	public function list(): void
	{
		$stmt = $this->conn->query(
			"SELECT id, name, parent_id FROM categories ORDER BY parent_id ASC, name ASC"
		);
		$cats = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		echo json_encode(['categories' => $cats]);
	}
	
	public function create(): void
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if (empty($data['name'])) {
			http_response_code(400);
			echo json_encode(['error'=>'Category name is required']);
			return;
		}
		$stmt = $this->conn->prepare(
			"INSERT INTO categories (name, parent_id) VALUES (:name, :parent_id)"
		);
		$stmt->execute([
			':name'      => $data['name'],
			':parent_id' => $data['parent_id'] ?? null
		]);
		echo json_encode(['success'=>true, 'id'=>$this->conn->lastInsertId()]);
	}
	
	public function update(int $id): void
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if (empty($data['name'])) {
			http_response_code(400);
			echo json_encode(['error'=>'Category name is required']);
			return;
		}
		$stmt = $this->conn->prepare(
			"UPDATE categories
         SET name = :name, parent_id = :parent_id
         WHERE id = :id"
		);
		$stmt->execute([
			':name'      => $data['name'],
			':parent_id' => $data['parent_id'] ?? null,
			':id'        => $id
		]);
		echo json_encode(['success'=>true]);
	}
	
	public function delete(int $id): void
	{
		$this->conn
			->prepare("UPDATE categories SET parent_id = NULL WHERE parent_id = :id")
			->execute([':id'=>$id]);
		
		$this->conn
			->prepare("DELETE FROM categories WHERE id = :id")
			->execute([':id'=>$id]);
		
		echo json_encode(['success'=>true]);
	}
}
