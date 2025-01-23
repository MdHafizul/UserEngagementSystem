<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: /Naluri/client-side/index.php');
    exit();
}
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: /Naluri/client-side/index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/naluri.png">
    <link rel="icon" type="image/png" href="../assets/img/naluri.png">
    <title>Employee Pages</title>

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0"
                href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
                <img src="../assets/img/naluri.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">Naluri</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/dashboard.php">
                        <i class="material-symbols-rounded opacity-5">dashboard</i>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-gradient-dark text-white" href="../pages/employee.php">
                        <i class="material-symbols-rounded opacity-5">table_view</i>
                        <span class="nav-link-text ms-1">Employee</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/task.php">
                        <i class="material-symbols-rounded opacity-5">Task</i>
                        <span class="nav-link-text ms-1">Task</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/user.php">
                        <i class="material-symbols-rounded opacity-5">groups</i>
                        <span class="nav-link-text ms-1">Patient</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../pages/profile.php">
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
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Employee</li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    </div>
                    <ul class="navbar-nav d-flex align-items-center justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <button class="btn btn-outline-primary btn-sm mb-0 me-3" id="addEmployeeBtn">Add
                                Employee</button>
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
                                <h6 class="text-white text-capitalize ps-3">Employee Record</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="employeeTable">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Email</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID</th>
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
        </div>
    </main>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEmployeeForm">
                        <div class="mb-3">
                            <label for="employeeName" class="form-label">Name</label>
                            <input type="text" class="form-control border border-primary rounded" id="employeeName"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeEmail" class="form-label">Email</label>
                            <input type="email" class="form-control border border-primary rounde" id="employeeEmail"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeUsername" class="form-label">Username</label>
                            <input type="text" class="form-control border border-primary rounde" id="employeeUsername"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="employeePassword" class="form-label">Password</label>
                            <input type="password" class="form-control border border-primary rounde"
                                id="employeePassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="employeeType" class="form-label">User Type</label>
                            <select class="form-control " id="employeeType" required>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm">
                        <div class="mb-3">
                            <label for="editEmployeeName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editEmployeeName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmployeeEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmployeeEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmployeeUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editEmployeeUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmployeePassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editEmployeePassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmployeeType" class="form-label">User Type</label>
                            <select class="form-control" id="editEmployeeType" required>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Employee Modal -->
    <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEmployeeModalLabel">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteEmployeeForm">
                        <p>Are you sure you want to delete this employee?</p>
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script src="../scripts/employeeScript.js"></script>
    <!-- Include Bootstrap JS for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>