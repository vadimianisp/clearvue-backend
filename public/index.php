<?php
	header("Access-Control-Allow-Origin: http://localhost:5173"); // eventually change to *
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
	header("Access-Control-Allow-Credentials: true");
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		http_response_code(204);
		exit();
	}
	
require_once __DIR__ . '/../src/Routes/web.php';
