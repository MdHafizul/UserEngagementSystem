<?php

class UserTask
{
    private $conn;
    private $table = 'user_tasks';

    public $user_task_id;
    public $user_id;
    public $task_id;
    public $assigned_at;
    public $status;
    public $completed_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new user-task 
    public function create()
    {
        $sql = "INSERT INTO " . $this->table . " (user_id, task_id, assigned_at, status) VALUES (?, ?, NOW(), ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param('iis', $this->user_id, $this->task_id, $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get all user-task assignments
    public function read()
    {
        $sql = "SELECT user_task_id, user_id, task_id, assigned_at, status, completed_at FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            if ($stmt->num_rows > 0) {
                $userTasks = array();
                while ($row = $stmt->fetch_assoc()) {
                    $userTasks[] = $row;
                }
                return $userTasks;  // Return user-tasks array to the controller
            } else {
                return false; // No user-tasks found
            }
        } else {
            return false; // Query failed
        }
    }
    
    // Get user-task assignments by user ID
    public function read_by_user($user_id)
    {
        $sql = "SELECT ut.user_task_id, ut.user_id, ut.task_id, ut.assigned_at, ut.status, ut.completed_at, t.title, t.description, t.due_date 
                FROM " . $this->table . " ut 
                JOIN tasks t ON ut.task_id = t.task_id 
                WHERE ut.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userTasks = array();
            while ($row = $result->fetch_assoc()) {
                $userTasks[] = $row;
            }
            return $userTasks;  // Return user-tasks array to the controller
        } else {
            return false; // No user-tasks found
        }
    }

    

    // Get a single user-task assignment by ID
    public function read_single()
    {
        $sql = "SELECT user_task_id, user_id, task_id, assigned_at, status, completed_at FROM " . $this->table . " WHERE user_task_id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param('i', $this->user_task_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->user_task_id, $this->user_id, $this->task_id, $this->assigned_at, $this->status, $this->completed_at);
        $stmt->fetch();
    }

    // Update a user-task assignment
    public function update($data)
    {
        $fields = [];
        $params = [];
        $types = '';

        if (isset($data['user_id'])) {
            $fields[] = "user_id = ?";
            $params[] = $data['user_id'];
            $types .= 'i';
        }

        if (isset($data['task_id'])) {
            $fields[] = "task_id = ?";
            $params[] = $data['task_id'];
            $types .= 'i';
        }

        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
            $types .= 's';
        }

        if (isset($data['completed_at'])) {
            $fields[] = "completed_at = ?";
            $params[] = $data['completed_at'];
            $types .= 's';
        }

        // If no fields are set to update, return false
        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE user_task_id = ?";
        $params[] = $this->user_task_id;
        $types .= 'i';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a user task
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table . " WHERE user_task_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->user_task_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>