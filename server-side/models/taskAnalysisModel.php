<?php
class TaskAnalysis
{
    private $conn;
    private $table = 'taskanalysis';

    public $analysis_id;
    public $task_id; // Changed from user_task_id to task_id
    public $user_id;
    public $is_task_done;
    public $time_taken_in_hours;
    public $article_watched;
    public $video_watched;
    public $books_read;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new task analysis
    public function createAnalysis()
    {
        $sql = "INSERT INTO " . $this->table . " 
                (task_id, user_id, is_task_done, time_taken_in_hours, article_watched, video_watched, books_read) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            'iisiiii',
            $this->task_id, // Changed from user_task_id to task_id
            $this->user_id,
            $this->is_task_done,
            $this->time_taken_in_hours,
            $this->article_watched,
            $this->video_watched,
            $this->books_read
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get all task analysis data
    public function read()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            if ($stmt->num_rows > 0) {
                $analysis = array();
                while ($row = $stmt->fetch_assoc()) {
                    $analysis[] = $row;
                }
                return $analysis;
            }
            return false;
        }
        return false;
    }

    // Fetch total tasks completed
    public function getTotalTasksCompleted()
    {
        $sql = "SELECT COUNT(*) as total_completed FROM " . $this->table . " WHERE is_task_done = 1";
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            $result = $stmt->fetch_assoc();
            return $result['total_completed'];
        }
        return 0;
    }

    // Fetch total tasks completed by user ID
    public function getTotalTasksCompletedByUser($user_id)
    {
        $sql = "SELECT COUNT(*) as total_completed FROM " . $this->table . " WHERE is_task_done = 1 AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $total_completed = 0;
        $stmt->bind_result($total_completed);
        $stmt->fetch();
        return $total_completed;
    }

    // Delete task analysis by task_id
    public function deleteByTaskId() 
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE task_id = ?'; 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->task_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete task analysis by analysis_id
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE analysis_id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->analysis_id);
    
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                error_log("No rows affected. Analysis ID: " . $this->analysis_id);
                return false;
            }
        } else {
            error_log("Error executing query: " . $stmt->error);
            return false;
        }
    }

    // Fetch the number of videos watched, books read, and articles watched
    public function getMediaCounts()
    {
        $sql = "SELECT 
                    SUM(video_watched) as total_videos_watched, 
                    SUM(books_read) as total_books_read, 
                    SUM(article_watched) as total_articles_watched 
                FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            return $stmt->fetch_assoc();
        }
        return false;
    }

    // Fetch the number of videos watched, books read, and articles watched by user ID
    public function getMediaCountsByUser($user_id)
    {
        $sql = "SELECT 
                    SUM(video_watched) as total_videos_watched, 
                    SUM(books_read) as total_books_read, 
                    SUM(article_watched) as total_articles_watched 
                FROM " . $this->table . " 
                WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

    // Fetch the number of tasks done
    public function getTasksData()
    {
        $sql = "SELECT 
                    SUM(is_task_done) as total_tasks_done, 
                    COUNT(*) as total_tasks 
                FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        if ($stmt) {
            return $stmt->fetch_assoc();
        }
        return false;
    }

    // Fetch the number of tasks done by user ID

    public function getTasksDataByUser($user_id)
    {
        $sql = "SELECT 
                    SUM(is_task_done) as total_tasks_done, 
                    COUNT(*) as total_tasks 
                FROM " . $this->table . " 
                WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_assoc();
        }
        return false;
    }

    // Fetch the amount of time taken to complete tasks, grouped by task_id
public function getTimeTakenToCompleteTasks()
{
    $sql = "SELECT 
                task_id, 
                SUM(time_taken_in_hours) as total_time_taken 
            FROM " . $this->table . " 
            GROUP BY task_id";
    $stmt = $this->conn->query($sql);

    if ($stmt) {
        return $stmt->fetch_all(MYSQLI_ASSOC); // Fetch all results as an associative array
    }
    return false;
}

// Fetch the amount of time taken to complete tasks by user ID, grouped by task_id
public function getTimeTakenToCompleteTasksByUser($user_id)
{
    $sql = "SELECT 
                task_id, 
                SUM(time_taken_in_hours) as total_time_taken
            FROM " . $this->table . " 
            WHERE user_id = ? 
            GROUP BY task_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all results as an associative array
    }
    return false;
}


}
?>