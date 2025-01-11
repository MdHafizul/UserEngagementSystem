<?php
include_once '../models/taskAnalysisModel.php';
include_once '../config/connectdb.php';

class TaskAnalysisController
{
    // @desc Create a new task analysis
    // @route POST /routes/taskAnalysisRoutes.php/create
    // @access Admin only
    public function create($data)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $analysis->user_task_id = $data['user_task_id'];
        $analysis->user_id = $data['user_id'];
        $analysis->is_task_done = $data['is_task_done'];
        $analysis->time_taken_in_hours = $data['time_taken_in_hours'];
        $analysis->article_watched = $data['article_watched'];
        $analysis->video_watched = $data['video_watched'];
        $analysis->books_read = $data['books_read'];

        // Log the data being inserted for debugging
        error_log("Creating Task Analysis: " . json_encode($data));

        if ($analysis->createAnalysis()) {
            echo json_encode(["success" => true, "message" => "Task analysis created successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be created"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Get all task analyses
    // @route GET /routes/taskAnalysisRoutes.php/read
    // @access Public
    public function read()
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $result = $analysis->read();

        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No task analyses found"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Get a single task analysis
    // @route GET /routes/taskAnalysisRoutes.php/read_single
    // @access Public
    public function read_single($id)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $analysis->analysis_id = $id;
        $analysis->read_single();

        if ($analysis->analysis_id) {
            echo json_encode([
                "analysis_id" => $analysis->analysis_id,
                "user_task_id" => $analysis->user_task_id,
                "user_id" => $analysis->user_id,
                "is_task_done" => $analysis->is_task_done,
                "time_taken_in_hours" => $analysis->time_taken_in_hours,
                "article_watched" => $analysis->article_watched,
                "video_watched" => $analysis->video_watched,
                "books_read" => $analysis->books_read
            ], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "Task analysis not found"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Update a task analysis
    // @route PUT /routes/taskAnalysisRoutes.php/update
    // @access Admin only
    public function update($id, $data)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $analysis->analysis_id = $id;

        if ($analysis->update($data)) {
            echo json_encode(["success" => true, "message" => "Task analysis updated successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be updated"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Delete a task analysis
    // @route DELETE /routes/taskAnalysisRoutes.php/delete
    // @access Admin only
    public function delete($id)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $analysis->analysis_id = $id;

        if ($analysis->delete()) {
            echo json_encode(["success" => true, "message" => "Task analysis deleted successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be deleted"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Count all task analyses
    // @route GET /routes/taskAnalysisRoutes.php/count_all
    // @access Public
    public function count_all()
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $total = $analysis->countAll();

        echo json_encode($total, JSON_PRETTY_PRINT);
    }

    // @desc Count task analyses by user ID
    // @route GET /routes/taskAnalysisRoutes.php/count_by_user
    // @access Public
    public function count_by_user($user_id)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $total = $analysis->countByUserId($user_id);

        echo json_encode(["total" => $total], JSON_PRETTY_PRINT);
    }

    // @desc Count all types of data by user ID
    // @route GET /routes/taskAnalysisRoutes.php/count_data_by_user
    // @access Public
    public function count_data_by_user($user_id)
    {
        global $conn;

        $analysis = new TaskAnalysis($conn);
        $data = $analysis->countDataByUserId($user_id);

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>