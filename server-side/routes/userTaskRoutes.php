<?php
// CORS Headers
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Allow necessary HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with HTTP 200 for preflight
    exit();
}

// Include necessary files
include_once '../models/userTaskModel.php';
include_once '../controllers/userTaskController.php';

// Instantiate UserTaskController
$userTaskController = new UserTaskController();

// Route handling
$request_method = $_SERVER['REQUEST_METHOD'];  // Get HTTP method (GET, POST, etc.)
$request_uri = $_SERVER['REQUEST_URI'];        // Get the requested URI

// Extract the route parts (simplify the URL structure)
$url_parts = explode('/', parse_url($request_uri, PHP_URL_PATH));

// Check the action from the URL path
$action = end($url_parts);

// Get request body (for POST and PUT requests)
$data = json_decode(file_get_contents("php://input"), true);

// Route handling based on HTTP method and URL action
switch ($action) {
    case 'create':  // POST request to create a user-task assignment
        if ($request_method == 'POST') {
            $userTaskController->create($data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'read':  // GET request for fetching all user-task assignments
        if ($request_method == 'GET') {
            $userTaskController->read();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'read_single':  // GET request to get a single user-task assignment
        $user_task_id = $_GET['user_task_id'] ?? null;
        if ($user_task_id && $request_method == 'GET') {
            $userTaskController->read_single($user_task_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User task ID is required"]);
        }
        break;

    case 'read_by_user':  // GET request to get user-task assignments by user ID
        $user_id = $_GET['user_id'] ?? null;
        if ($user_id && $request_method == 'GET') {
            $userTaskController->read_by_user($user_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID is required"]);
        }
        break;

    case 'update':  // PUT request to update a user-task assignment
        $user_task_id = $_GET['user_task_id'] ?? null;
        if ($user_task_id && $request_method == 'PUT') {
            $userTaskController->update($user_task_id, $data);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User task ID is required"]);
        }
        break;

    case 'delete':  // DELETE request to delete a user-task assignment
        $user_task_id = $_GET['user_task_id'] ?? null;
        if ($user_task_id && $request_method == 'DELETE') {
            $userTaskController->delete($user_task_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User task ID is required"]);
        }
        break;

    default:  // Invalid request
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
        break;
}
?>