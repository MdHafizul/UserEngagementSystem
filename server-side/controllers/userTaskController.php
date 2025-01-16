<?php
include_once "../models/userTaskModel.php";
include_once "../models/taskAnalysisModel.php";
include_once "../config/connectdb.php";

class UserTaskController
{
    // @desc Create a new user task
    // @route POST /routes/userTaskRoutes.php/create
    // @access admin only

    public function create($data)
    {
        session_start();
        if ($this->checkUserType() != 'admin' && $this->checkUserType() != 'employee') {
            http_response_code(403);
            echo json_encode(["message" => "Access forbidden. Admins only"]);
            return;
        }
        global $conn;

        $userTask = new UserTask($conn);
        $userTask->user_id = $data['user_id'];
        $userTask->task_id = $data['task_id'];
        $userTask->status = $data['status'];

        // Check if the task is already assigned to the user
        if ($userTask->isTaskAssigned()) {
            echo json_encode(["message" => "Task is already assigned to the user"]);
            return;
        }

        if ($userTask->create()) {
            echo json_encode(["message" => "User task created successfully"]);
        } else {
            echo json_encode(["message" => "User task could not be created"]);
        }
    }

    // @desc Get all user tasks
    // @route GET /routes/userTaskRoutes.php/read
    // @access public
    public function read()
    {
        global $conn;
        $task = new UserTask($conn);
        $result = $task->read();
        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No user tasks found"]);
        }
    }

    // @desc Get user tasks by user ID
    // @route GET /routes/userTaskRoutes.php/read_by_user
    // @access public
    public function read_by_user($user_id)
    {
        global $conn;
        $task = new UserTask($conn);
        $result = $task->read_by_user($user_id);
        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No user tasks found"]);
        }
    }

    // @desc Get a single user task by ID
    // @route GET /routes/userTaskRoutes.php/read_single
    // @access public
    public function read_single($id)
    {
        global $conn;

        $userTask = new UserTask($conn);
        $userTask->user_task_id = $id;
        $userTask->read_single();

        if ($userTask->user_id == null) {
            echo json_encode(["message" => "User task not found"]);
        } else {
            $userTask_arr = [
                "user_task_id" => $userTask->user_task_id,
                "user_id" => $userTask->user_id,
                "task_id" => $userTask->task_id,
                "assigned_at" => $userTask->assigned_at,
                "status" => $userTask->status,
                "completed_at" => $userTask->completed_at
            ];
            echo json_encode($userTask_arr, JSON_PRETTY_PRINT);
        }
    }

    // @desc Update a user task
    // @route PUT /routes/userTaskRoutes.php/update
    // public


    public function update($user_task_id, $data)
    {
        global $conn;

        $userTask = new UserTask($conn);
        $userTask->user_task_id = $user_task_id;

        if ($userTask->update($data)) {
            echo json_encode(["message" => "User task updated successfully"]);
        } else {
            echo json_encode(["message" => "User task could not be updated"]);
        }
    }

    // @desc Delete a user task
    // @route DELETE /routes/userTaskRoutes.php/delete
    // @access Admin only
    public function delete($id)
    {
        global $conn;
        // Delete the user task
        $userTask = new UserTask($conn);
        $userTask->user_task_id = $id;

        if ($userTask->delete()) {
            echo json_encode(["success" => true, "message" => "User task deleted successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "User task could not be deleted"], JSON_PRETTY_PRINT);
        }
    }

    // Helper method to check the logged-in user's type
    private function checkUserType()
    {
        if (isset($_SESSION['user_type'])) {
            return $_SESSION['user_type'];
        }
        return null;
    }

}
?>