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
        $sql = "INSERT INTO " . $this->table . " (user_task_id, user_id, is_task_done, time_taken_in_hours, article_watched, video_watched, books_read) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param(
            'iiidiii',
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

    // Get all task analyses
    public function read()
    {
        $sql = "SELECT analysis_id, user_task_id, user_id, is_task_done, time_taken_in_hours, article_watched, video_watched, books_read FROM " . $this->table;
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $analyses = array();
            while ($row = $result->fetch_assoc()) {
                $analyses[] = $row;
            }
            return $analyses;
        } else {
            return false;
        }
    }

    // Get a single task analysis by ID
    public function read_single()
    {
        $sql = "SELECT analysis_id, user_task_id, user_id, is_task_done, time_taken_in_hours, article_watched, video_watched, books_read FROM " . $this->table . " WHERE analysis_id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param('i', $this->analysis_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($this->analysis_id, $this->user_task_id, $this->user_id, $this->is_task_done, $this->time_taken_in_hours, $this->article_watched, $this->video_watched, $this->books_read);
        $stmt->fetch();
    }

    // Delete a task analysis by user_task_id
    public function deleteByUserTaskId()
    {
        $sql = "DELETE FROM " . $this->table . " WHERE user_task_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->user_task_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update a task analysis
    public function update($data)
    {
        $fields = [];
        $params = [];
        $types = '';

        if (isset($data['is_task_done'])) {
            $fields[] = "is_task_done = ?";
            $params[] = $data['is_task_done'];
            $types .= 'i';
        }

        if (isset($data['time_taken_in_hours'])) {
            $fields[] = "time_taken_in_hours = ?";
            $params[] = $data['time_taken_in_hours'];
            $types .= 'd';
        }

        if (isset($data['article_watched'])) {
            $fields[] = "article_watched = ?";
            $params[] = $data['article_watched'];
            $types .= 'i';
        }

        if (isset($data['video_watched'])) {
            $fields[] = "video_watched = ?";
            $params[] = $data['video_watched'];
            $types .= 'i';
        }

        if (isset($data['books_read'])) {
            $fields[] = "books_read = ?";
            $params[] = $data['books_read'];
            $types .= 'i';
        }

        // If no fields are set to update, return false
        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE " . $this->table . " SET " . implode(", ", $fields) . " WHERE analysis_id = ?";
        $params[] = $this->analysis_id;
        $types .= 'i';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a task analysis
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table . " WHERE analysis_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->analysis_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Count totals for all task analyses
    public function countAll()
    {
        $sql = "SELECT 
                SUM(is_task_done) as total_tasks_done, 
                SUM(time_taken_in_hours) as total_time_taken, 
                SUM(article_watched) as total_articles_watched, 
                SUM(video_watched) as total_videos_watched, 
                SUM(books_read) as total_books_read 
            FROM " . $this->table;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Initialize variables
        $total_tasks_done = $total_time_taken = $total_articles_watched = $total_videos_watched = $total_books_read = 0;

        // Bind the results
        $stmt->bind_result($total_tasks_done, $total_time_taken, $total_articles_watched, $total_videos_watched, $total_books_read);
        $stmt->fetch();

        // Return the aggregated results as an associative array
        return [
            'total_tasks_done' => $total_tasks_done ?? 0,
            'total_time_taken' => $total_time_taken ?? 0,
            'total_articles_watched' => $total_articles_watched ?? 0,
            'total_videos_watched' => $total_videos_watched ?? 0,
            'total_books_read' => $total_books_read ?? 0
        ];
    }

    // Count task analyses by user ID
    public function countByUserId($user_id)
    {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $total = 0;
        $stmt->bind_result($total);
        $stmt->fetch();
        return $total;
    }

    // Count all types of data by user ID
    public function countDataByUserId($user_id)
    {
        $sql = "SELECT 
                    SUM(is_task_done) as total_tasks_done, 
                    SUM(time_taken_in_hours) as total_time_taken, 
                    SUM(article_watched) as total_articles_watched, 
                    SUM(video_watched) as total_videos_watched, 
                    SUM(books_read) as total_books_read 
                FROM " . $this->table . " WHERE user_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $total_tasks_done = $total_time_taken = $total_articles_watched = $total_videos_watched = $total_books_read = 0;
        $stmt->bind_result($total_tasks_done, $total_time_taken, $total_articles_watched, $total_videos_watched, $total_books_read);
        $stmt->fetch();
        error_log("Total tasks done: " . $total_tasks_done);
        error_log("Total time taken: " . $total_time_taken);
        error_log("Total articles watched: " . $total_articles_watched);
        error_log("Total videos watched: " . $total_videos_watched);
        error_log("Total books read: " . $total_books_read);
        return [
            'total_tasks_done' => $total_tasks_done,
            'total_time_taken' => $total_time_taken,
            'total_articles_watched' => $total_articles_watched,
            'total_videos_watched' => $total_videos_watched,
            'total_books_read' => $total_books_read
        ];
    }
}
?>