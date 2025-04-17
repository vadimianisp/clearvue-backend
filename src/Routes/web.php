<?php

	$requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$requestMethod = $_SERVER['REQUEST_METHOD'];
	$segments      = explode('/', trim($requestUri, '/'));
	
	if ($segments[0] === 'cities') {
		require_once __DIR__ . '/../Controllers/CityController.php';
		$id = $segments[1] ?? null;
		
		if ($requestMethod === 'GET' && count($segments) === 1) {
			listCities();
		} elseif ($requestMethod === 'POST' && count($segments) === 1) {
			addCity();
		} elseif ($requestMethod === 'PUT' && count($segments) === 2) {
			updateCity((int)$id);
		} elseif ($requestMethod === 'DELETE' && count($segments) === 2) {
			deleteCity((int)$id);
		} else {
			http_response_code(404);
			echo json_encode(['error'=>'Route not found']);
		}
	}
	elseif ($segments[0] === 'cache-test') {
		require_once __DIR__ . '/../Controllers/CityController.php';
		cacheTest();
	}
	
	elseif ($segments[0] === 'categories') {
		require_once __DIR__ . '/../Controllers/CategoryController.php';
		$controller = new CategoryController();
		$id = $segments[1] ?? null;
		
		if ($requestMethod === 'GET' && count($segments) === 1) {
			$controller->list();
		}
		elseif ($requestMethod === 'POST' && count($segments) === 1) {
			$controller->create();
		}
		elseif ($requestMethod === 'PUT' && count($segments) === 2) {
			$controller->update((int)$id);
		}
		elseif ($requestMethod === 'DELETE' && count($segments) === 2) {
			$controller->delete((int)$id);
		}
		else {
			http_response_code(404);
			echo json_encode(['error'=>'Route not found']);
		}
	}
	
	else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode(['error'=>'Route not found']);
	}
