document.addEventListener("DOMContentLoaded", () => {
    const userId = document.getElementById("userId").value;
    console.log("User ID:", userId);
    const apiEndpoint = `http://localhost/Naluri/server-side/routes/userTaskRoutes.php/read_by_user?user_id=${userId}`;
    const taskTableBody = document.querySelector("#taskTable tbody");

    // Fetch task data for the logged-in user
    function fetchTaskData() {
        fetch(apiEndpoint)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log("Fetched data:", data); // Log the fetched data

                // Check if data is an array
                if (!Array.isArray(data)) {
                    throw new Error("Data is not an array");
                }

                // Clear existing rows
                taskTableBody.innerHTML = "";

                // Populate table rows
                data.forEach((task) => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${task.title}</td>
                        <td>${task.description}</td>
                        <td class="text-center">${task.due_date}</td>
                        <td class="text-center">${task.status}</td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm submitTaskBtn" data-id="${task.user_task_id}">Submit Task</button>
                        </td>
                    `;

                    taskTableBody.appendChild(row);
                });

                // Add event listeners for submit task buttons
                document.querySelectorAll(".submitTaskBtn").forEach((button) => {
                    button.addEventListener("click", (event) => {
                        const taskId = event.target.getAttribute("data-id");
                        console.log("Task ID:", taskId); // Debugging statement
                        showSubmitTaskModal(taskId);
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

    // Show the submit task modal
    function showSubmitTaskModal(taskId) {
        // Store the current task ID
        document.getElementById("submitTaskForm").setAttribute("data-task-id", taskId);
        const submitTaskModal = new bootstrap.Modal(document.getElementById("submitTaskModal"));
        submitTaskModal.show();
    }

    // Submit task form event listener
    document.getElementById("submitTaskForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const taskId = document.getElementById("submitTaskForm").getAttribute("data-task-id");

        // API call to delete the task
        fetch(`http://localhost/Naluri/server-side/routes/userTaskRoutes.php/delete?user_task_id=${taskId}`, {
            method: "DELETE",
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert("Task submitted and deleted successfully!");
                    fetchTaskData(); // Refresh the task list
                    document.querySelector("#submitTaskModal .btn-close").click(); // Close modal
                } else {
                    alert("Failed to delete the task.");
                }
            })
            .catch((error) => {
                console.error("Error deleting task:", error);
                alert("Failed to delete the task. Please try again.");
            });
    });

    // Initial fetch of task data
    fetchTaskData();
});
