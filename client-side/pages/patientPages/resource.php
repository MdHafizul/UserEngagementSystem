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
    <title>Resource Pages</title>

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
            <li><a href="./resource.php" class="active">Resources</a></li>
            <li><a href="./profile.php">Profile</a></li>
        </ul>
        <div class="logout-section">
            <a href="?action=logout" class="btn btn-outline-light">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="main-header">
            <h1>Resources</h1>
            <p class="subtext">Explore various resources to aid your journey.</p>
        </header>

        <section class="resources">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Videos</h5>
                            <p class="card-text">Watch educational videos.</p>
                            <a href="./videos.php" class="btn btn-primary">Go to Videos</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Articles</h5>
                            <p class="card-text">Read informative articles.</p>
                            <a href="./articles.php" class="btn btn-primary">Go to Articles</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Books</h5>
                            <p class="card-text">Explore a collection of books.</p>
                            <a href="./books.php" class="btn btn-primary">Go to Books</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>