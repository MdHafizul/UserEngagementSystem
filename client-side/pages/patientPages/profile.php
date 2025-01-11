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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/naluri.png">
    <link rel="icon" type="image/png" href="../../assets/img/naluri.png">
    <title>Profile</title>

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/patientstyles.css">
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="brand-logo">Naluri</h2>
        <ul class="nav flex-column">
            <li><a href="./recommendation.php">Recommendations</a></li>
            <li><a href="./task.php">Tasks</a></li>
            <li><a href="./resource.php">Resources</a></li>
            <li><a href="./profile.php" class="active">Profile</a></li>
        </ul>
        <div class="logout-section">
            <a href="?action=logout" class="btn btn-outline-light">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="main-header">
            <h1>Your Profile</h1>
            <p class="subtext">Manage your profile information here.</p>
        </header>

        <section class="profile">
            <div class="row justify-content-center" id="profile-card">
                <!-- Profile data will be populated here by JavaScript -->
            </div>
        </section>
        <!-- Hidden input to store user ID -->
        <input type="hidden" id="userId" value="<?php echo $user_id; ?>">
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-radius-lg">
                <div class="modal-header bg-gradient-dark text-white">
                    <h5 class="modal-title fw-bold" id="editUserModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <div class="mb-4">
                            <label for="editUserName" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control border-radius-lg" id="editUserName" placeholder="Enter your name">
                        </div>
                        <div class="mb-4">
                            <label for="editUserEmail" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control border-radius-lg" id="editUserEmail" placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label for="editUserUsername" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control border-radius-lg" id="editUserUsername" placeholder="Enter your username">
                        </div>
                        <div class="mb-4">
                            <label for="editUserPassword" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control border-radius-lg" id="editUserPassword" placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn bg-gradient-dark text-white w-100 border-radius-lg">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const userId = document.getElementById("userId").value;
    </script>
    <script src="../../scripts/userScript/profileScript.js"></script>
    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>