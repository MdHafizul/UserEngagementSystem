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
        $taskAnalysis->task_id = $data['task_id']; // Changed from user_task_id to task_id
        $taskAnalysis->user_id = $data['user_id'];
        $taskAnalysis->is_task_done = $data['is_task_done'];
        $taskAnalysis->time_taken_in_hours = $data['time_taken_in_hours'];
        $taskAnalysis->article_watched = $data['article_watched'];
        $taskAnalysis->video_watched = $data['video_watched'];
        $taskAnalysis->books_read = $data['books_read'];

        if ($taskAnalysis->createAnalysis()) {
            echo json_encode(["success" => true, "message" => "Task analysis created successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be created"]);
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
            echo json_encode(["message" => "No task analysis data found"]);
        }
    }

    // @desc Get total tasks completed
    // @route GET /routes/taskAnalysisRoutes.php/total-tasks-completed
    public function getTotalTasksCompleted()
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $totalCompleted = $taskAnalysis->getTotalTasksCompleted();
        echo json_encode(["total_completed" => $totalCompleted]);
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

    // @desc Delete a task analysis
    // @route DELETE /routes/taskAnalysisRoutes.php/delete
    public function delete($analysis_id)
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $taskAnalysis->analysis_id = $analysis_id;

        if ($taskAnalysis->delete()) {
            echo json_encode(["success" => true, "message" => "Task analysis deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Task analysis could not be deleted"]);
        }
    }

    // @desc Get media counts for all users
    // @route GET /routes/taskAnalysisRoutes.php/media-counts
    public function getMediaCounts()
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $counts = $taskAnalysis->getMediaCounts();
        echo json_encode($counts, JSON_PRETTY_PRINT);
    }

    // @desc Get media counts by user ID
    // @route GET /routes/taskAnalysisRoutes.php/media-counts/{user_id}
    public function getMediaCountsByUser($user_id)
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $counts = $taskAnalysis->getMediaCountsByUser($user_id);
        echo json_encode($counts, JSON_PRETTY_PRINT);
    }

    // @desc Get tasks data
    // @route GET /routes/taskAnalysisRoutes.php/tasks-done
    public function getTasksData()
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $tasks = $taskAnalysis->getTasksData();
        echo json_encode($tasks, JSON_PRETTY_PRINT);
    }

    // @desc Get tasks done and time taken by user ID
    // @route GET /routes/taskAnalysisRoutes.php/tasks-done-by-user
    public function getTasksDataByUser($user_id)
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $tasks = $taskAnalysis->getTasksDataByUser($user_id);
        echo json_encode($tasks, JSON_PRETTY_PRINT);
    }

    // @desc Get time taken to complete tasks for all users
    // @route GET /routes/taskAnalysisRoutes.php/time-taken
    public function getTimeTakenToCompleteTasks()
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $timeData = $taskAnalysis->getTimeTakenToCompleteTasks();
        echo json_encode($timeData, JSON_PRETTY_PRINT);
    }

    // @desc Get time taken to complete tasks by user ID
    // @route GET /routes/taskAnalysisRoutes.php/time-taken-by-user
    public function getTimeTakenToCompleteTasksByUser($user_id)
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $timeData = $taskAnalysis->getTimeTakenToCompleteTasksByUser($user_id);
        echo json_encode($timeData, JSON_PRETTY_PRINT);
    }

    // @desc Create or update a task analysis
    // @route POST /routes/taskAnalysisRoutes.php/create

    public function createOrUpdate($data)
    {
        global $conn;

        $taskAnalysis = new TaskAnalysis($conn);
        $taskAnalysis->task_id = $data['task_id'];
        $taskAnalysis->user_id = $data['user_id'];
        $taskAnalysis->is_task_done = $data['is_task_done'];
        $taskAnalysis->time_taken_in_hours = $data['time_taken_in_hours'];
        $taskAnalysis->article_watched = $data['article_watched'];
        $taskAnalysis->video_watched = $data['video_watched'];
        $taskAnalysis->books_read = $data['books_read'];

        if ($taskAnalysis->exists()) {
            if ($taskAnalysis->updateAnalysis()) {
                echo json_encode(["success" => true, "message" => "Task analysis updated successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Task analysis could not be updated"]);
            }
        } else {
            if ($taskAnalysis->createAnalysis()) {
                echo json_encode(["success" => true, "message" => "Task analysis created successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Task analysis could not be created"]);
            }
        }
    }

    public function checkTaskAnalysisExists($task_id, $user_id)
    {
        global $conn;
        $taskAnalysis = new TaskAnalysis($conn);
        $taskAnalysis->task_id = $task_id;
        $taskAnalysis->user_id = $user_id;
        return $taskAnalysis->exists();
    }
}
?>