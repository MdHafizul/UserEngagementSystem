<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: /Naluri/client-side/index.php');
    exit();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
    header('Location: /Naluri/client-side/index.php');
    exit();
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naluri - Tasks</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="../../assets/css/patientstyles.css">
</head>
<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar">
    <h2 class="brand-logo">Naluri</h2>
    <ul class="nav flex-column">
      <li><a href="./recommendation.php">Recommendations</a></li>
      <li><a href="./task.php" class="active">Tasks</a></li>
      <li><a href="./resource.php">Resources</a></li>
      <li><a href="./profile.php">Profile</a></li>
    </ul>
    <div class="logout-section">
      <a href="?action=logout" class="btn btn-outline-light">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <header class="main-header">
      <h1>Your Tasks</h1>
      <p class="subtext">Manage and track your tasks here.</p>
    </header>

    <section class="tasks">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Task List</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="taskTable">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Due Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data will be populated here by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Hidden input to store user ID -->
    <input type="hidden" id="userId" value="<?php echo $user_id; ?>">
  </div>

  <!-- Submit Task Modal -->
  <div class="modal fade" id="submitTaskModal" tabindex="-1" aria-labelledby="submitTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-radius-lg">
        <div class="modal-header bg-gradient-dark text-white">
          <h5 class="modal-title fw-bold" id="submitTaskModalLabel">Submit Task</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="submitTaskForm">
            <!-- Task Done -->
            <div class="mb-4">
              <label for="taskDone" class="form-label fw-semibold">Task Done</label>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="taskDone">
                <label class="form-check-label ms-1" for="taskDone">Yes</label>
              </div>
            </div>
            <!-- Time Taken -->
            <div class="mb-4">
              <label for="timeTaken" class="form-label fw-semibold">Time Taken in Hours</label>
              <input type="text" class="form-control border-radius-lg" id="timeTaken" placeholder="Enter time taken">
            </div>
            <!-- Video Watched -->
            <div class="mb-4">
              <label for="videoWatched" class="form-label fw-semibold">Video Watched</label>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="videoWatched">
                <label class="form-check-label ms-1" for="videoWatched">Yes</label>
              </div>
            </div>
            <!-- Article Read -->
            <div class="mb-4">
              <label for="articleRead" class="form-label fw-semibold">Article Read</label>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="articleRead">
                <label class="form-check-label ms-1" for="articleRead">Yes</label>
              </div>
            </div>
            <!-- Book Read -->
            <div class="mb-4">
              <label for="bookRead" class="form-label fw-semibold">Book Read</label>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="bookRead">
                <label class="form-check-label ms-1" for="bookRead">Yes</label>
              </div>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn bg-gradient-dark text-white w-100 border-radius-lg">Submit Task</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const userId = document.getElementById("userId").value;
      console.log("User ID:", userId);
      const apiEndpoint = `http://localhost/Naluri/server-side/routes/userTaskRoutes.php/read_by_user?user_id=${userId}`;
      const taskTableBody = document.querySelector("#taskTable tbody");

      // Fetch task data for the logged-in user
      function fetchTaskData() {
        fetch(apiEndpoint)
          .then((response) => {
            if (!response.ok) {
              throw new Error("Network response was not ok");
            }
            return response.json();
          })
          .then((data) => {
            console.log("Fetched data:", data); // Log the fetched data

            // Check if data is an array
            if (!Array.isArray(data)) {
              throw new Error("Data is not an array");
            }

            // Clear existing rows
            taskTableBody.innerHTML = "";

            // Populate table rows
            data.forEach((task) => {
              const row = document.createElement("tr");

              row.innerHTML = `
                <td>${task.title}</td>
                <td>${task.description}</td>
                <td class="text-center">${task.due_date}</td>
                <td class="text-center">${task.status}</td>
                <td class="text-center">
                  <button class="btn btn-primary btn-sm submitTaskBtn" data-id="${task.user_task_id}">Submit Task</button>
                </td>
              `;

              taskTableBody.appendChild(row);
            });

            // Add event listeners for submit task buttons
            document.querySelectorAll(".submitTaskBtn").forEach((button) => {
              button.addEventListener("click", (event) => {
                const taskId = event.target.getAttribute("data-id");
                console.log("Task ID:", taskId); // Debugging statement
                showSubmitTaskModal(taskId);
              });
            });
          })
          .catch((error) => {
            console.error("Error fetching task data:", error);
            taskTableBody.innerHTML = `
              <tr>
                <td colspan="5" class="text-center text-danger">Failed to load task data.</td>
              </tr>
            `;
          });
      }

      // Show the submit task modal
      function showSubmitTaskModal(taskId) {
        // Store the current task ID
        document.getElementById("submitTaskForm").setAttribute("data-task-id", taskId);
        const submitTaskModal = new bootstrap.Modal(document.getElementById("submitTaskModal"));
        submitTaskModal.show();
      }

      // Submit task form event listener
      document.getElementById("submitTaskForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const taskId = document.getElementById("submitTaskForm").getAttribute("data-task-id");

        // API call to delete the task
        fetch(`http://localhost/Naluri/server-side/routes/userTaskRoutes.php/delete?user_task_id=${taskId}`, {
          method: "DELETE",
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert("Task submitted and successfully!");
              fetchTaskData(); // Refresh the task list
              document.querySelector("#submitTaskModal .btn-close").click(); // Close modal
            } else {
              alert("Failed to delete the task.");
            }
          })
          .catch((error) => {
            console.error("Error deleting task:", error);
            alert("Failed to delete the task. Please try again.");
          });
      });

      // Initial fetch of task data
      fetchTaskData();
    });
  </script> -->
  <script src="../../scripts/userScript/taskScript.js"></script>
  <!-- Include Bootstrap JS for modal functionality -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>