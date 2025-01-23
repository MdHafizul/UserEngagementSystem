document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskRoutes.php/read"; // Update with your API URL
    const taskTableBody = document.querySelector("#taskTable tbody");

    // Fetch task data
    function fetchTaskData() {
        fetch(apiEndpoint)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Clear existing rows
                taskTableBody.innerHTML = "";

                // Populate table rows
                data.forEach((task) => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${task.title}</td>
                        <td>${task.description}</td>
                        <td class="text-center">${task.due_date}</td>
                    `;

                    taskTableBody.appendChild(row);
                });

                // Add event listeners for edit and delete buttons
                document.querySelectorAll(".editBtn").forEach((button) => {
                    button.addEventListener("click", (event) => {
                        const taskId = event.target.getAttribute("data-id");
                        showEditTaskModal(taskId);
                    });
                });

                document.querySelectorAll(".deleteBtn").forEach((button) => {
                    button.addEventListener("click", (event) => {
                        const taskId = event.target.getAttribute("data-id");
                        deleteTask(taskId);
                    });
                });
            })
            .catch((error) => {
                console.error("Error fetching task data:", error);
                taskTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">Failed to load task data.</td>
                    </tr>
                `;
            });
    }
    // Initial fetch of task data
    fetchTaskData();
});