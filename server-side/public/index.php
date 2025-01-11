<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include the database and routes
include_once '../config/connectdb.php';

// Handle unsupported HTTP methods and errors
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Check if the requested method is supported
    if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
        throw new Exception("Method Not Allowed", 405);
    }
} catch (Exception $e) {
    // Return a proper response for unsupported methods
    http_response_code($e->getCode());
    echo json_encode(['message' => $e->getMessage()]);
    exit;
}
?>
