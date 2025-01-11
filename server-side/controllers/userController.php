<?php
session_start();
include_once '../models/userModels.php';
include_once '../config/connectdb.php';

class UserController
{
    // @desc Create a new user
    // @route POST /routes/userRoutes.php/create
    // @access Public
    public function create($data)
    {
        global $conn;

        $user = new User($conn);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->user_type = $data['user_type'];

        if ($user->create()) {
            http_response_code(201);
            echo json_encode(["message" => "User created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "User could not be created"]);
        }
    }
    
    // @desc Login user
    // @route POST /routes/userRoutes.php/login
    // @access Public
    public function login($data)
    {
        global $conn;

        $user = new User($conn);
        $user->email = $data['email'];

        // Retrieve user by email
        if ($user->read_by_email()) {
            // Verify password
            if (password_verify($data['password'], $user->password)) {
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['user_type'] = $user->user_type;

                // Return a single JSON object
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful",
                    "user_id" => $user->user_id,
                    "user_type" => $user->user_type
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid credentials"
                ]);
            }
        } else {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "User not found"
            ]);
        }
    }

    // @desc Get all users
    // @route GET /routes/userRoutes.php/read
    // @access Public
    public function read()
    {
        global $conn;

        $user = new User($conn);
        $result = $user->read();
        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No users found"]);
        }
    }

    // @desc Get all users by user type
    // @route GET /routes/userRoutes.php/read_by_type
    // @access Public
    public function read_by_type($user_type)
    {
        global $conn;

        $user = new User($conn);
        $user->user_type = $user_type;
        $result = $user->read_by_type();
        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No users found"]);
        }
    }

    // @desc Get a single user by ID
    // @route GET /routes/userRoutes.php/read_single
    // @access Public
    public function read_single($user_id)
    {
        global $conn;

        $user = new User($conn);
        $user->user_id = $user_id;
        $user->read_single();

        if ($user->user_id != null) {
            $user_data = array(
                "user_id" => $user->user_id,
                "name" => $user->name,
                "email" => $user->email,
                "username" => $user->username,
                "user_type" => $user->user_type
            );
            echo json_encode($user_data, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "User not found"]);
        }
    }

    // @desc Update a user
    // @route PUT /routes/userRoutes.php/update
    // @access Admins only
    public function update($user_id, $data)
    {
        global $conn;

        $user = new User($conn);
        $user->user_id = $user_id;

        if ($user->update($data)) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["message" => "User could not be updated"]);
        }
    }

    // @desc Delete a user
    // @route DELETE /routes/userRoutes.php/delete
    // @access Admins only
    public function delete($user_id)
    {
        session_start();
        if ($_SESSION['user_type'] != 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "Access forbidden. Admins only"]);
            return;
        }

        global $conn;

        $user = new User($conn);
        $user->user_id = $user_id;

        if ($user->delete()) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            echo json_encode(["message" => "User could not be deleted"]);
        }
    }
}
?>