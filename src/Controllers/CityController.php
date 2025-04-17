<?php
	require_once __DIR__ . '/../Config/Database.php';
	
	function listCities() {
		header("Access-Control-Allow-Origin: http://localhost:5173");
		header('Content-Type: application/json');
		$db = new Database();
		$conn = $db->getConnection();
		
		$stmt = $conn->query("SELECT * FROM cities");
		$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		echo json_encode(['cities' => $cities]);
	}
	
	function addCity() {
		header("Access-Control-Allow-Origin: http://localhost:5173");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
		header('Content-Type: application/json');
		
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			http_response_code(204);
			exit();
		}
		
		$db = new Database();
		$conn = $db->getConnection();
		
		$input = json_decode(file_get_contents('php://input'), true);
		
		if (!isset($input['name']) || empty($input['name'])) {
			http_response_code(400);
			echo json_encode(['error' => 'City name is required']);
			return;
		}
		
		try {
			$sql = "INSERT INTO cities (name) VALUES (:name)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':name', $input['name']);
			
			if ($stmt->execute()) {
				http_response_code(201);
				echo json_encode([
					'message' => 'City added successfully',
					'id' => $conn->lastInsertId()
				]);
			} else {
				http_response_code(500);
				echo json_encode(['error' => 'Failed to add city']);
			}
		} catch (Exception $e) {
			error_log("Error adding city: " . $e->getMessage());
			http_response_code(500);
			echo json_encode(['error' => 'An unexpected error occurred while adding the city']);
		}
	}
	
	function updateCity($id) {
		header('Content-Type: application/json');
		$db = new Database();
		$conn = $db->getConnection();
		
		$input = json_decode(file_get_contents('php://input'), true);
		
		if (!isset($input['name']) || empty($input['name'])) {
			http_response_code(400);
			echo json_encode(['error' => 'City name is required']);
			return;
		}
		
		$sql = "UPDATE cities SET name = :name WHERE id = :id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':name', $input['name']);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		
		if ($stmt->execute()) {
			echo json_encode(['message' => 'City updated successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to update city']);
		}
	}
	
	function deleteCity($id) {
		header('Content-Type: application/json');
		$db = new Database();
		$conn = $db->getConnection();
		
		$sql = "DELETE FROM cities WHERE id = :id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		
		if ($stmt->execute()) {
			echo json_encode(['message' => 'City deleted successfully']);
		} else {
			http_response_code(500);
			echo json_encode(['error' => 'Failed to delete city']);
		}
	}
	
	function cacheTest() {
		header('Content-Type: text/plain');
		
		$redis = new Redis();
		$redisHost = getenv('REDIS_HOST') ?: 'redis';
		$redis->connect($redisHost, 6379);
		
		$redis->set("test_key", "Hello from Redis!");
		echo $redis->get("test_key");
	}
