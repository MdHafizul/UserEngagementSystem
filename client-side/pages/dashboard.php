<!-- Material Icons -->
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
<!-- CSS Files -->
<link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
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
                    <a class="nav-link active bg-gradient-dark text-white" href="./dashboard.php">
                        <i class="material-symbols-rounded opacity-5">dashboard</i>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="./employee.php">
                        <i class="material-symbols-rounded opacity-5">table_view</i>
                        <span class="nav-link-text ms-1">Employee</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="./task.php">
                        <i class="material-symbols-rounded opacity-5">task</i>
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
                            <button class="btn btn-outline-primary btn-sm mb-0 me-3 ms-2" id="showDataBtn">Show
                                Data</button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Admin Dashboard</h6>
                            </div>
                        </div>
                        <div class="container-fluid py-4">
                            <div class="row">
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-3 ps-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="text-sm mb-0 text-capitalize">Video Watched</p>
                                                    <h4 class="mb-0" id="task-completion">0</h4>
                                                </div>
                                                <div
                                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                                    <i class="material-symbols-rounded opacity-10">check_circle</i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-3 ps-4">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-3 ps-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="text-sm mb-0 text-capitalize">Books Read</p>
                                                    <h4 class="mb-0" id="total-time-taken">0</h4>
                                                </div>
                                                <div
                                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                                    <i class="material-symbols-rounded opacity-10">schedule</i>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                        <div class="card-footer p-3 ps-4">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                    <div class="card">
                                        <div class="card-header p-3 ps-4">
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
                                        <div class="card-footer p-3 ps-4">
                                            <p class="mb-0 text-sm"><span
                                                    class="text-success font-weight-bolder">Updated just now</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="charts mt-4">
                                <div class="charts-card mb-4">
                                    <h2 class="chart-title">Task Completion</h2>
                                    <div id="bar-chart"></div>
                                </div>
                                <div class="charts-card mb-4">
                                    <h2 class="chart-title">Total Time Taken</h2>
                                    <div id="area-chart"></div>
                                </div>
                            </div>
                        </div>
    </main>
    <!-- End Main -->

    </div>

    <!-- Scripts -->
    <!-- ApexCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
    <!-- Custom JS -->
    <script>
        // SIDEBAR TOGGLE

        let sidebarOpen = false;
        const sidebar = document.getElementById('sidebar');

        function openSidebar() {
            if (!sidebarOpen) {
                sidebar.classList.add('sidebar-responsive');
                sidebarOpen = true;
            }
        }

        function closeSidebar() {
            if (sidebarOpen) {
                sidebar.classList.remove('sidebar-responsive');
                sidebarOpen = false;
            }
        }

        // PIE CHART setup for Task Completion
        const pieChartOptions = {
            series: [80, 70, 90, 60, 85], // Task completion percentages
            chart: {
                type: 'pie',
                height: 350,
            },
            labels: ['Task 1', 'Task 2', 'Task 3', 'Task 4', 'Task 5'], // Task labels
        };

        const pieChart = new ApexCharts(document.querySelector('#bar-chart'), pieChartOptions);
        pieChart.render();
        // AREA CHART setup for Total Time Taken
        const areaChartOptions = {
            series: [{
                name: 'Total Time (hours)',
                data: [10, 15, 20, 25, 30, 35, 40], // Total time taken in hours
            }],
            chart: {
                type: 'area',
                height: 350,
            },
            xaxis: {
                categories: ['Task 1', 'Task 2', 'Task 3', 'Task 4', 'Task 5', 'Task 6', 'Task 7'],
            },
        };

        const areaChart = new ApexCharts(document.querySelector('#area-chart'), areaChartOptions);
        areaChart.render();
    </script>
</body>

</html>