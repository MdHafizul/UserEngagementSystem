<?php

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once '../controllers/taskAnalysisController.php';
include_once '../models/taskAnalysisModel.php';

// Instantiate TaskAnalysisController
$taskAnalysisController = new TaskAnalysisController();

// Get the request method and URI
$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Extract the action from the URI
$url_parts = explode('/', parse_url($request_uri, PHP_URL_PATH));
$action = $url_parts[count($url_parts) - 1];

// Get the request body (for POST and PUT requests)
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'create':
        if ($request_method == 'POST') {
            $taskAnalysisController->create($data);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'read':
        if ($request_method == 'GET') {
            $taskAnalysisController->read();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'total-tasks-completed':
        if ($request_method == 'GET') {
            $taskAnalysisController->getTotalTasksCompleted();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'delete':
        if ($request_method == 'DELETE') {
            $analysis_id = $_GET['analysis_id'] ?? null;
            if ($analysis_id) {
                $taskAnalysisController->delete($analysis_id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Analysis ID is required"]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'media-counts':
        if ($request_method == 'GET') {
            $taskAnalysisController->getMediaCounts();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'tasks-done':
        if ($request_method == 'GET') {
            $taskAnalysisController->getTasksData();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'tasks-done-by-user':
        if ($request_method == 'GET') {
            $user_id = $_GET['user_id'] ?? null;
            if ($user_id) {
                $taskAnalysisController->getTasksDataByUser($user_id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "User ID is required"]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'time-taken':
        if ($request_method == 'GET') {
            $taskAnalysisController->getTimeTakenToCompleteTasks();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case 'time-taken-by-user':
        if ($request_method == 'GET') {
            $user_id = $_GET['user_id'] ?? null;
            if ($user_id) {
                $taskAnalysisController->getTimeTakenToCompleteTasksByUser($user_id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "User ID is required"]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    default:
        // Check for the 'total-tasks-completed-by-user/{user_id}' pattern
        if (preg_match('/total-tasks-completed-by-user\/(\d+)$/', $request_uri, $matches)) {
            if ($request_method == 'GET') {
                $user_id = intval($matches[1]);
                $taskAnalysisController->getTotalTasksCompletedByUser($user_id);
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
            }
        }
        // Check for the 'media-counts-by-user/{user_id}' pattern
        else if (preg_match('/media-counts-by-user\/(\d+)$/', $request_uri, $matches)) {
            if ($request_method == 'GET') {
                $user_id = intval($matches[1]);
                $taskAnalysisController->getMediaCountsByUser($user_id);
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Not Found"]);
        }
}
?>