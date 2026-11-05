<?php
declare(strict_types=1);
require dirname(__DIR__, 2) . '/config/bootstrap.php';

use App\Core\Database;
use App\Models\Product;

header('Content-Type: application/json; charset=utf-8');

$headers = function_exists('getallheaders') ? getallheaders() : [];
$auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
$expected = 'Bearer ' . ($config['app']['api_token'] ?? '');
if (!hash_equals($expected, $auth)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $model = new Product(Database::connection($config['db']));
    $method = $_SERVER['REQUEST_METHOD'];
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    if ($method === 'GET') {
        if ($id) {
            $item = $model->find($id);
            if (!$item) { http_response_code(404); }
            echo json_encode($item ?: ['error' => 'Product not found']);
        } else {
            echo json_encode($model->all());
        }
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    if (in_array($method, ['POST', 'PUT'], true)) {
        $name = filter_var($input['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $price = filter_var($input['price'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($name === '' || $price === false || $price < 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Valid name and non-negative price required']);
            exit;
        }
        if ($method === 'POST') {
            http_response_code(201);
            echo json_encode(['id' => $model->create($name, (float)$price)]);
        } else {
            if (!$id) { http_response_code(400); echo json_encode(['error' => 'Valid id required']); exit; }
            $model->update($id, $name, (float)$price);
            echo json_encode(['updated' => true]);
        }
        exit;
    }

    if ($method === 'DELETE') {
        if (!$id) { http_response_code(400); echo json_encode(['error' => 'Valid id required']); exit; }
        $model->delete($id);
        echo json_encode(['deleted' => true]);
        exit;
    }

    header('Allow: GET, POST, PUT, DELETE');
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
} catch (Throwable $e) {
    error_log((string)$e);
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}
