<?php
class User
{
    private $conn;
    private $table = 'users';

    public $user_id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $user_type;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new user
    public function create()
    {
        $sql = "INSERT INTO " . $this->table . " (name, email, username, password, user_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind data
        $stmt->bind_param("sssss", $this->name, $this->email, $this->username, $this->password, $this->user_type);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Retrieve user by email
    public function read_by_email()
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->user_id = $row['user_id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->user_type = $row['user_type'];
            return true;
        }

        return false;
    }

    public function read_by_type()
    {
        $query = 'SELECT user_id, name, email, username, user_type FROM ' . $this->table . ' WHERE user_type = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $this->user_type);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        }
    
        return false;
    }
    // Get all users
    public function read()
    {
        $sql = "SELECT user_id, name, email, username, user_type FROM " . $this->table;
        $result = $this->conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $users = array();
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                return $users;  // Return users array to the controller
            } else {
                return []; // No users found
            }
        } else {
            die("Query failed: " . $this->conn->error); // Query failed
        }
    }

    // Get a single user by ID
    public function read_single()
    {
        $sql = "SELECT user_id, name, email, username, user_type FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->user_id, $this->name, $this->email, $this->username, $this->user_type);
        $stmt->fetch();
    }

    // Update a user
    public function update($data)
    {
        // Start building the SQL query
        $fields = [];
        $params = [];
        $types = '';

        // Dynamically add fields to the update query based on provided data
        if (isset($data['name'])) {
            $fields[] = "name = ?";
            $params[] = $data['name'];
            $types .= 's';
        }

        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
            $types .= 's';
        }

        if (isset($data['username'])) {
            $fields[] = "username = ?";
            $params[] = $data['username'];
            $types .= 's';
        }

        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            $types .= 's';
        }

        if (isset($data['user_type'])) {
            $fields[] = "user_type = ?";
            $params[] = $data['user_type'];
            $types .= 's';
        }

        // If no fields are set to update, return false
        if (empty($fields)) {
            return false;
        }

        // Add the WHERE clause for the specific user ID
        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE user_id = ?";
        $params[] = $this->user_id;
        $types .= 'i';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);  // Bind the parameters dynamically

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a user
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>