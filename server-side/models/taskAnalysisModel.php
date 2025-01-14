<?php
class TaskAnalysis
{
    private $conn;
    private $table = 'taskanalysis';

    public $analysis_id;
    public $user_task_id;
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
                (user_task_id, user_id, is_task_done, time_taken_in_hours, article_watched, video_watched, books_read) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            'iisiiii',
            $this->user_task_id,
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
}
?>
