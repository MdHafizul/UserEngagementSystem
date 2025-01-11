document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskAnalysisRoutes.php";
    const userApiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php";
    const userSelect = document.getElementById("userSelect");
    const showDataBtn = document.getElementById("showDataBtn");

    // Fetch user list and populate the select dropdown
    function fetchUserList() {
        fetch(`${userApiEndpoint}/read_by_type?user_type=patient`)
            .then(response => response.json())
            .then(data => {
                console.log("User list:", data); // Debugging statement
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
        const url = userId === "all" ? `${apiEndpoint}/count_all` : `${apiEndpoint}/count_data_by_user?user_id=${userId}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log("Fetched data:", data); // Debugging statement
                // Populate cards with data
                document.getElementById("task-completion").textContent = data.total_tasks_done;
                document.getElementById("total-time-taken").textContent = data.total_time_taken;
                document.getElementById("articles-watched").textContent = data.total_articles_watched;

                // Populate charts with data
                populateChart("chart-video-watched", data.total_videos_watched, "Videos Watched");
                populateChart("chart-books-read", data.total_books_read, "Books Read");
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    // Populate chart with data
    function populateChart(chartId, data, label) {
        const ctx = document.getElementById(chartId).getContext("2d");
        console.log("Populating chart:", chartId, data); // Debugging statement
        const chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Data"],
                datasets: [{
                    label: label,
                    data: [data],
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Event listener for show data button
    showDataBtn.addEventListener("click", () => {
        const userId = userSelect.value;
        console.log("Selected user ID:", userId); // Debugging statement
        fetchData(userId);
    });

    // Initial fetch of user list
    fetchUserList();
});