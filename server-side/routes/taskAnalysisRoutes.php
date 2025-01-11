<?php

// CORS Headers
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Allow necessary HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers

// Include necessary files
include_once '../models/taskAnalysisModel.php';
include_once '../controllers/taskAnalysisController.php';
include_once '../config/connectdb.php';

// Instantiate TaskAnalysisController
$taskAnalysisController = new TaskAnalysisController();

// Route handling
$request_method = $_SERVER['REQUEST_METHOD']; // Get HTTP method (GET, POST, etc.)
$request_uri = $_SERVER['REQUEST_URI']; // Get the requested URI

// Extract the route parts (simplify the URL structure)
$url_parts = explode('/', parse_url($request_uri, PHP_URL_PATH));

// Check the action from the URL path
$action = end($url_parts);

// Get request body (for POST and PUT requests)
$data = json_decode(file_get_contents("php://input"), true);

// Log the incoming request for debugging
error_log("Request Method: " . $request_method);
error_log("Request URI: " . $request_uri);

switch ($action) {
    case 'create':
        if ($request_method == 'POST') {
            $taskAnalysisController->create($data);
        }
        break;
    case 'read':
        if ($request_method == 'GET') {
            $taskAnalysisController->read();
        }
        break;
    case 'read_single':
        if ($request_method == 'GET' && isset($_GET['id'])) {
            $taskAnalysisController->read_single($_GET['id']);
        }
        break;
    case 'update':
        if ($request_method == 'PUT' && isset($_GET['id'])) {
            $taskAnalysisController->update($_GET['id'], $data);
        }
        break;
    case 'delete':
        if ($request_method == 'DELETE' && isset($_GET['id'])) {
            $taskAnalysisController->delete($_GET['id']);
        }
        break;
    case 'count_all':
        if ($request_method == 'GET') {
            $taskAnalysisController->count_all();
        }
        break;
    case 'count_by_user':
        if ($request_method == 'GET' && isset($_GET['user_id'])) {
            $taskAnalysisController->count_by_user($_GET['user_id']);
        }
        break;
    case 'count_data_by_user':
        if ($request_method == 'GET' && isset($_GET['user_id'])) {
            $taskAnalysisController->count_data_by_user($_GET['user_id']);
        }
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo json_encode(["message" => "Endpoint not found"], JSON_PRETTY_PRINT);
        break;
}
?>