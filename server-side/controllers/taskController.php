<?php
include_once '../models/taskModel.php';
include_once '../config/connectdb.php';

class TaskController
{
    // @desc Create a new task
    // @route POST /routes/taskRoutes.php/create
    // @access Admin only
    public function create($data)
    {
        session_start();
        if ($this->checkUserType() != 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "Access forbidden. Admins only"]);
            return;
        }
        global $conn;

        $task = new Task($conn);
        $task->title = $data['title'] ?? null;
        $task->description = $data['description'] ?? null;
        $task->due_date = $data['due_date'] ?? null;
        $task->status = $data['status'] ?? null;

        // Log the data being inserted for debugging
        error_log("Creating Task: " . json_encode($data));

        if ($task->createTask()) {
            echo json_encode(["success" => true, "message" => "Task created successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "Task could not be created"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Get all tasks
    // @route GET /routes/taskRoutes.php/read
    // @access Public
    public function read()
    {
        global $conn;
    
        $task = new Task($conn);
        $result = $task->read();
        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No tasks found"]);
        }
    }
    

    // @desc Get a single task by ID
    // @route GET /routes/taskRoutes.php/read_single
    // @access Public
    public function read_single($id)
    {
        global $conn;

        $task = new Task($conn);
        $task->task_id = $id;
        $task->read_single();

        if ($task->task_id != null) {
            $task_Data = array(
                "id" => $task->task_id,
                "title" => $task->title,
                "description" => $task->description,
                "due_date" => $task->due_date,
                "status" => $task->status,
                "created_at" => $task->created_at,
                "updated_at" => $task->updated_at
            );
            echo json_encode($task_Data, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    }

    // @desc Update a task
    // @route PUT /routes/taskRoutes.php/update
    // @access Admins only
    public function update($id, $data)
    {
        session_start();
        if ($this->checkUserType() != 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "Access forbidden. Admins only"]);
            return;
        }

        global $conn;
        $task = new Task($conn);
        $task->task_id = $id;

        $updateData = [];
        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['due_date'])) {
            $updateData['due_date'] = $data['due_date'];
        }
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }
        if ($task->update($updateData)) {
            echo json_encode(["message" => "Task updated successfully"]);
        } else {
            echo json_encode(["message" => "Task could not be updated"]);
        }
    }

    // @desc Delete a task
    // @route DELETE /routes/taskRoutes.php/delete
    // @access Admins only
    public function delete($id)
    {
        session_start();
        if ($this->checkUserType() != 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "Access forbidden. Admins only"]);
            return;
        }

        global $conn;
        $task = new Task($conn);
        $task->task_id = $id;
        if ($task->delete()) {
            echo json_encode(["message" => "Task deleted successfully"]);
        } else {
            echo json_encode(["message" => "Task could not be deleted"]);
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