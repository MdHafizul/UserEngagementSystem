<?php
// CORS Headers
header("Access-Control-Allow-Origin: http://localhost:3001"); // Allow requests from React app
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Allow necessary HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers
header("Access-Control-Allow-Credentials: true"); // Allow cookies or authorization headers

// Handle OPTIONS request (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with HTTP 200 for preflight
    exit();
}
// Include necessary files
include_once '../models/userModels.php';
include_once '../controllers/userController.php';

// Instantiate UserController
$userController = new UserController();

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
    case 'create':  // POST request to create a user
        if ($request_method == 'POST') {
            $userController->create($data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'login':  // POST request for user login
        if ($request_method == 'POST') {
            $userController->login($data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'read':  // GET request for fetching all users
        if ($request_method == 'GET') {
            $userController->read();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;
        case 'read_by_type':  // GET request for fetching users by user type
            $user_type = $_GET['user_type'] ?? null;
            if ($user_type && $request_method == 'GET') {
                $userController->read_by_type($user_type);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "User type is required"]);
            }
            break;

    case 'read_single':  // GET request to get a single user
        $user_id = $_GET['user_id'] ?? null;
        if ($user_id && $request_method == 'GET') {
            $userController->read_single($user_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID is required"]);
        }
        break;

    case 'update':  // PUT request to update a user
        $user_id = $_GET['user_id'] ?? null;
        if ($user_id && $request_method == 'PUT') {
            $userController->update($user_id, $data);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID is required"]);
        }
        break;

    case 'delete':  // DELETE request to delete a user
        $user_id = $_GET['user_id'] ?? null;
        if ($user_id && $request_method == 'DELETE') {
            $userController->delete($user_id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID is required"]);
        }
        break;

    default:  // Invalid request
        http_response_code(404);
        echo json_encode(["message" => "Not Found"]);
        break;
}
?>