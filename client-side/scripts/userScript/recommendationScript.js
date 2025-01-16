document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskAnalysisRoutes.php";
    const userId = document.getElementById("userId").value;

    // Fetch and display data based on the logged-in user
    function fetchData(userId) {
        const taskUrl = `${apiEndpoint}/tasks-done-by-user?user_id=${userId}`;
        const timeUrl = `${apiEndpoint}/time-taken-by-user?user_id=${userId}`;
        console.log("User ID:", userId);

        // Clear existing chart data
        clearCharts();

        // Fetch task data
        fetch(taskUrl)
            .then(response => response.json())
            .then(taskData => {
                const completedTasks = taskData.total_tasks_done || 0;
                const totalTasks = taskData.total_tasks || 0;

                // Handle case where totalTasks is zero to avoid division by zero
                if (totalTasks === 0) {
                    console.warn("No tasks available for the user.");
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Task Data Available',
                        text: 'No task data available for you.',
                    });
                    return;
                }

                const notCompletedTasks = totalTasks - completedTasks;

                // HORIZONTAL BAR CHART setup for Task Completion
                const barChartOptions = {
                    series: [{
                        data: [
                            { x: 'Completed', y: completedTasks },
                            { x: 'Not Completed', y: notCompletedTasks }
                        ],
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: { position: 'top' },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: (val) => val.toString(),
                        offsetX: -10,
                        style: { fontSize: '12px', colors: ['#000'] },
                    },
                    xaxis: {
                        categories: ['Completed', 'Not Completed'],
                        title: { text: 'Number of Tasks' },
                    },
                };

                const barChart = new ApexCharts(document.querySelector('#bar-chart'), barChartOptions);
                barChart.render();
            })
            .catch(error => console.error("Error fetching task data:", error));

        // Fetch time data
        fetch(timeUrl)
            .then(response => response.json())
            .then(timeData => {
                const taskIds = timeData.map(item => item.task_id);
                const totalTimeTaken = timeData.map(item => item.total_time_taken);

                // LINE CHART setup for Total Time Taken
                const lineChartOptions = {
                    series: [{
                        name: 'Total Time (hours)',
                        data: totalTimeTaken,
                    }],
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: { show: false },
                    },
                    stroke: {
                        curve: 'smooth',
                    },
                    xaxis: {
                        categories: taskIds,
                        title: { text: 'Task IDs' },
                    },
                    yaxis: {
                        title: { text: 'Total Time Taken (hours)' },
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => `${val} hours`,
                        },
                    },
                };

                const lineChart = new ApexCharts(document.querySelector('#area-chart'), lineChartOptions);
                lineChart.render();
            })
            .catch(error => console.error("Error fetching time data:", error));
    }

    // Function to clear existing chart data
    function clearCharts() {
        if (window.ApexCharts) {
            const charts = document.querySelectorAll('.apexcharts-canvas');
            charts.forEach(chart => chart.remove());
        }
    }

    // Initial fetch of data for the logged-in user
    fetchData(userId);
});
