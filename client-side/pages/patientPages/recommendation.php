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
  <title>Naluri - Recommendations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="../../assets/css/patientstyles.css">
</head>
<body class="bg-light">

  <!-- Sidebar -->
  <div class="sidebar">
    <h2 class="brand-logo">Naluri</h2>
    <ul class="nav flex-column">
      <li><a href="#" class="active">Recommendations</a></li>
      <li><a href="./task.php">Tasks</a></li>
      <li><a href="#">Resources</a></li>
      <li><a href="#">Profile</a></li>
    </ul>
    <div class="logout-section">
      <button class="btn btn-outline-light">Logout</button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <header class="main-header">
      <h1>Good Morning, Hafizul!</h1>
      <p class="subtext">Here are your personalized recommendations for today.</p>
    </header>

    <section class="recommendations">
      <div class="row">
        <!-- Recommendation Card 1 -->
        <div class="col-md-4">
          <div class="recommendation-card">
            <h3>Mindfulness Exercise</h3>
            <p>Spend 10 minutes practicing mindful breathing to start your day calmly.</p>
            <button class="btn btn-gradient">Start Now</button>
          </div>
        </div>
        <!-- Recommendation Card 2 -->
        <div class="col-md-4">
          <div class="recommendation-card">
            <h3>Read an Article</h3>
            <p>"How to Build Resilience" - A guide to strengthen mental fortitude.</p>
            <button class="btn btn-gradient">Read Article</button>
          </div>
        </div>
        <!-- Recommendation Card 3 -->
        <div class="col-md-4">
          <div class="recommendation-card">
            <h3>Take a Break</h3>
            <p>Walk outside for 15 minutes to refresh your mind and body.</p>
            <button class="btn btn-gradient">Relax Now</button>
          </div>
        </div>
      </div>
    </section>

    <section class="progress-section">
      <h2>Your Daily Progress</h2>
      <div class="progress-circle"></div>
      <p>60% of your goals completed today!</p>
    </section>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
