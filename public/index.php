<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Core/Response.php';
require_once __DIR__ . '/../src/Model/ProductModel.php';
require_once __DIR__ . '/../src/Controller/ProductController.php';

$db = (new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME))->getConnection();
$controller = new ProductController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $uri);

if ($segments[0] !== 'products'){
    Response::json(['error' => 'Not found'], 404);
    exit;
}

$id = $segments[1] ?? null;

match ($method) {
    'GET' => $id ? $controller->show($id) : $controller->index(),
    'POST' => $controller->store(),
    'PUT' => $id ? $controller->update($id) : Response::json(['error' => 'Missing ID'], 400),
    'DELETE' => $id ? $controller->destroy($id) : Response::json(['error' => 'Missing ID'], 400),
    default => Response::json(['error' => 'Method not allowed'], 405),

};