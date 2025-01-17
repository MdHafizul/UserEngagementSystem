document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskAnalysisRoutes.php";
    const userApiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php";
    const userSelect = document.getElementById("userSelect");
    const showDataBtn = document.getElementById("showDataBtn");

    // Fetch user list and populate the select dropdown
    function fetchUserList() {
        console.log("Fetching user list from:", `${userApiEndpoint}/read_by_type?user_type=patient`);
        fetch(`${userApiEndpoint}/read_by_type?user_type=patient`)
            .then(response => {
                console.log("Received response for user list:", response);
                return response.json();
            })
            .then(data => {
                console.log("User list data:", data); // Debugging statement
                data.forEach(user => {
                    const option = document.createElement("option");
                    option.value = user.user_id;
                    option.textContent = `${user.name}`;
                    userSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching user list:", error));
    }

    // Fetch and display data based on selected user
    function fetchData(userId) {
        const url = userId === "all" ? `${apiEndpoint}/media-counts` : `${apiEndpoint}/media-counts-by-user/${userId}`;
        console.log("Fetching data from:", url);
        fetch(url)
            .then(response => {
                console.log("Received response for data fetch:", response);
                return response.json();
            })
            .then(data => {
                console.log("Fetched data:", data); // Debugging statement
                if (!data || Object.keys(data).length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Data Available',
                        text: 'No data available for the selected user.',
                    });
                    return;
                }

                // Populate cards with data
                document.getElementById("articles-watched").textContent = data.total_articles_watched || 0;
                document.getElementById("videos-watched").textContent = data.total_videos_watched || 0;
                document.getElementById("books-read").textContent = data.total_books_read || 0;

                // Populate charts with data
                populateCharts(userId);

                Swal.fire({
                    icon: 'success',
                    title: 'Data Fetched Successfully',
                    text: 'Data has been successfully fetched and displayed.',
                });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: 'An error occurred while fetching data. Please try again later.',
                });
            });
    }

    // Populate charts with data
    function populateCharts(userId) {
        const taskUrl = userId === "all" ? `${apiEndpoint}/tasks-done` : `${apiEndpoint}/tasks-done-by-user?user_id=${userId}`;
        const timeUrl = userId === "all" ? `${apiEndpoint}/time-taken` : `${apiEndpoint}/time-taken-by-user?user_id=${userId}`;

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
                    console.warn("No tasks available for the user or system.");
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Task Data Available',
                        text: 'No task data available for the selected user.',
                    });
                    return;
                }

                const notCompletedTasks = totalTasks - completedTasks;

                // Calculate percentages
                const completedPercentage = ((completedTasks / totalTasks) * 100).toFixed(2);
                const notCompletedPercentage = ((notCompletedTasks / totalTasks) * 100).toFixed(2);

                // PIE CHART setup for Task Completion
                const pieChartOptions = {
                    series: [parseFloat(completedPercentage), parseFloat(notCompletedPercentage)], // Convert percentages to numbers
                    chart: {
                        type: 'pie',
                        height: 350,
                    },
                    labels: ['Completed', 'Not Completed'], // Labels for the chart
                    tooltip: {
                        y: {
                            formatter: (val) => `${val}%`, // Show percentage in tooltip
                        },
                    },
                };

                const pieChart = new ApexCharts(document.querySelector('#bar-chart'), pieChartOptions);
                pieChart.render();
            })
            .catch(error => console.error("Error fetching task data:", error));

        // Fetch time data
        fetch(timeUrl)
            .then(response => response.json())
            .then(timeData => {
                const taskIds = timeData.map(item => item.task_id);
                const totalTimeTaken = timeData.map(item => item.total_time_taken);

                // AREA CHART setup for Total Time Taken
                const areaChartOptions = {
                    series: [{
                        name: 'Total Time (hours)',
                        data: totalTimeTaken, // Total time taken in hours
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                    },
                    xaxis: {
                        categories: taskIds,
                    },
                };

                const areaChart = new ApexCharts(document.querySelector('#area-chart'), areaChartOptions);
                areaChart.render();
            })
            .catch(error => console.error("Error fetching time data:", error));
    }

    // Function to clear existing chart data
    function clearCharts() {
        // Assuming you are using ApexCharts
        if (window.ApexCharts) {
            const charts = document.querySelectorAll('.apexcharts-canvas');
            charts.forEach(chart => chart.remove());
        }
    }

    // Event listener for the Show Data button
    showDataBtn.addEventListener("click", () => {
        const selectedUserId = userSelect.value;
        fetchData(selectedUserId);
    });

    // Initial fetch of user list
    fetchUserList();

    // Fetch and display data for all users on initial page load
    fetchData("all");

    // Simulate a click on the Show Data button when the page loads
    showDataBtn.click();
});
