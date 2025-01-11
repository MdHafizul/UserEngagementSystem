<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: /Naluri/client-side/index.php');
    exit();
}
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    header('Location: /Naluri/client-side/index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/naluri.png">
    <link rel="icon" type="image/png" href="../../assets/img/naluri.png">
    <title>Dashboard</title>

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0"
                href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
                <img src="../../assets/img/naluri.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">Naluri</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active bg-gradient-dark text-white" href="./dashboard.php">
                        <i class="material-symbols-rounded opacity-5">dashboard</i>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="./task.php">
                        <i class="material-symbols-rounded opacity-5">task</i>
                        <span class="nav-link-text ms-1">Task</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="./user.php">
                        <i class="material-symbols-rounded opacity-5">account_circle</i>
                        <span class="nav-link-text ms-1">User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="./profile.php">
                        <i class="material-symbols-rounded opacity-5">person</i>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <!-- Additional Navigation Links -->
            </ul>
        </div>
        <div class="sidenav-footer position-absolute w-100 bottom-0 ">
            <div class="mx-3">
                <a class="btn bg-gradient-dark w-100" href="?action=logout" type="button">Logout</a>
            </div>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Type here...</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <ul class="navbar-nav d-flex align-items-center justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <select class="form-select" id="userSelect">
                                <option value="all">All Users</option>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <button class="btn btn-outline-primary btn-sm mb-0 me-3" id="showDataBtn">Show Data</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Employee Dashboard</h6>
                            </div>
                        </div>
                        <div class="container-fluid py-2">
                            <div class="row">
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-2 ps-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="text-sm mb-0 text-capitalize">Task Completion</p>
                                                    <h4 class="mb-0" id="task-completion">0</h4>
                                                </div>
                                                <div
                                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                                    <i class="material-symbols-rounded opacity-10">check_circle</i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-2 ps-3">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-2 ps-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="text-sm mb-0 text-capitalize">Total Time Taken</p>
                                                    <h4 class="mb-0" id="total-time-taken">0</h4>
                                                </div>
                                                <div
                                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                                    <i class="material-symbols-rounded opacity-10">schedule</i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-2 ps-3">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-2 ps-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="text-sm mb-0 text-capitalize">Articles Watched</p>
                                                    <h4 class="mb-0" id="articles-watched">0</h4>
                                                </div>
                                                <div
                                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                                    <i class="material-symbols-rounded opacity-10">article</i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-2 ps-3">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-0 ">Video Watched</h6>
                                            <p class="text-sm ">Number of videos watched</p>
                                            <div class="pe-2">
                                                <div class="chart">
                                                    <canvas id="chart-video-watched" class="chart-canvas"
                                                        height="170"></canvas>
                                                </div>
                                            </div>
                                            <hr class="dark horizontal">
                                            <div class="d-flex ">
                                                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                                                <p class="mb-0 text-sm">Updated just now</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mt-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-0 ">Books Read</h6>
                                            <p class="text-sm ">Number of books read</p>
                                            <div class="pe-2">
                                                <div class="chart">
                                                    <canvas id="chart-books-read" class="chart-canvas"
                                                        height="170"></canvas>
                                                </div>
                                            </div>
                                            <hr class="dark horizontal">
                                            <div class="d-flex ">
                                                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                                                <p class="mb-0 text-sm">Updated just now</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../scripts/employeeScript/dashboardScript.js"></script>
    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>