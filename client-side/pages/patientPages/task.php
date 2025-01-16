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
  <!-- Include SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap"
    rel="stylesheet">
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description
                      </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Due
                        Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status
                      </th>
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
      <div class="modal-content border-radius-lg shadow-lg">
        <!-- Modal Header -->
        <div class="modal-header bg-gradient-primary text-white">
          <h5 class="modal-title fw-bold" id="submitTaskModalLabel">Submit Task</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
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
              <label for="timeTaken" class="form-label fw-semibold">Time Taken (in hours)</label>
              <input type="number" class="form-control border-radius-lg shadow-sm" id="timeTaken"
                placeholder="Enter time taken">
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
            <button type="submit" class="btn btn-primary w-100 border-radius-lg shadow-sm">
              Submit Task
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <script src="../../scripts/userScript/taskScript.js"></script>
  <!-- Include Bootstrap JS for modal functionality -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Include SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>