<?php

class Task
{
    private $conn;
    private $table = 'tasks';
    public $task_id;
    public $title;
    public $description;
    public $due_date;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new task
    public function createTask()
    {
        $sql = "INSERT INTO " . $this->table . " (title, description, due_date, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param(
            'ssss',
            $this->title,
            $this->description,
            $this->due_date,
            $this->status
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Get all tasks
    public function read()
    {
        $sql = "SELECT task_id, title, description, due_date, status, created_at, updated_at FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            if ($stmt->num_rows > 0) {
                $tasks = array();
                while ($row = $stmt->fetch_assoc()) {
                    $tasks[] = $row;
                }
                return $tasks;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    //Get a single task by ID 
    public function read_single()
    {
        $sql = "SELECT task_id, title, description, due_date, status, created_at FROM " . $this->table . " WHERE task_id =?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }


        $stmt->bind_param('i', $this->task_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->task_id, $this->title, $this->description, $this->due_date, $this->status, $this->created_at);
        $stmt->fetch();
    }

    //update a task 
    public function update($data)
    {

        $fields = [];
        $params = [];
        $types = '';

        if (isset($data['title'])) {
            $fields[] = "title = ?";
            $params[] = $data['title'];
            $types .= 's';
        }


        if (isset($data['description'])) {
            $fields[] = "description = ?";
            $params[] = $data['description'];
            $types .= 's';
        }

        if (isset($data['due_date'])) {
            $fields[] = "due_date = ?";
            $params[] = $data['due_date'];
            $types .= 's';
        }

        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
            $types .= 's';
        }

        // If no fields are set to update, return false
        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE task_id = ?";
        $params[] = $this->task_id;
        $types .= 'i';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //Delete a user 
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table . " WHERE task_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->task_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>