<?php
include_once '../models/taskAnalysisModel.php';
include_once '../config/connectdb.php';

class TaskAnalysisController
{
    // @desc Create a new task analysis
    // @route POST /routes/taskAnalysisRoutes.php/create
    public function create($data)
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $taskAnalysis->user_task_id = $data['user_task_id'] ?? null;
        $taskAnalysis->user_id = $data['user_id'] ?? null;
        $taskAnalysis->is_task_done = $data['is_task_done'] ?? false;
        $taskAnalysis->time_taken_in_hours = $data['time_taken_in_hours'] ?? 0;
        $taskAnalysis->article_watched = $data['article_watched'] ?? false;
        $taskAnalysis->video_watched = $data['video_watched'] ?? false;
        $taskAnalysis->books_read = $data['books_read'] ?? false;

        if ($taskAnalysis->createAnalysis()) {
            echo json_encode(["success" => true, "message" => "Task analysis created successfully"], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be created"], JSON_PRETTY_PRINT);
        }
    }

    // @desc Get all task analysis data
    // @route GET /routes/taskAnalysisRoutes.php/read
    public function read()
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $result = $taskAnalysis->read();

        if ($result) {
            echo json_encode($result, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["message" => "No task analysis found"]);
        }
    }

    // @desc Get total tasks completed
    // @route GET /routes/taskAnalysisRoutes.php/total-tasks-completed
    public function getTotalTasksCompleted()
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $total = $taskAnalysis->getTotalTasksCompleted();

        echo json_encode(["total_completed_tasks" => $total], JSON_PRETTY_PRINT);
    }

    // @desc Get total tasks completed by user ID
    // @route GET /routes/taskAnalysisRoutes.php/total-tasks-completed/{user_id}
    public function getTotalTasksCompletedByUser($user_id)
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $total = $taskAnalysis->getTotalTasksCompletedByUser($user_id);

        echo json_encode(["total_completed_tasks" => $total], JSON_PRETTY_PRINT);
    }
}
?>
