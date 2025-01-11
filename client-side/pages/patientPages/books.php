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
    <title>Books</title>

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
            <li><a href="./profile.php">Profile</a></li>
            <li><a href="./books.php" class="active">Books</a></li>
        </ul>
        <div class="logout-section">
            <a href="?action=logout" class="btn btn-outline-light">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="main-header">
            <h1>Books</h1>
            <p class="subtext">Explore a collection of books to aid your journey.</p>
        </header>

        <section class="books">
            <div class="container-fluid py-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card my-4">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                    <h6 class="text-white text-capitalize ps-3">Books</h6>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-2">
                                <div class="row">
                                    <!-- Book 1 -->
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card">
                                            <img src="../../assets/img/books/book1.jpg" class="card-img-top" alt="Book 1">
                                            <div class="card-body">
                                                <h5 class="card-title">Understanding Mental Illness</h5>
                                                <p class="card-text">Brief description of the book.</p>
                                                <a href="book1.php" class="btn btn-primary">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Book 2 -->
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card">
                                            <img src="../../assets/img/books/book2.jpg" class="card-img-top" alt="Book 2">
                                            <div class="card-body">
                                                <h5 class="card-title">Book Title 2</h5>
                                                <p class="card-text">Brief description of the book.</p>
                                                <a href="book2.php" class="btn btn-primary">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Book 3 -->
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="card">
                                            <img src="../../assets/img/books/book3.jpg" class="card-img-top" alt="Book 3">
                                            <div class="card-body">
                                                <h5 class="card-title">Book Title 3</h5>
                                                <p class="card-text">Brief description of the book.</p>
                                                <a href="book3.php" class="btn btn-primary">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add more books as needed -->
                                </div>
                            </div>
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