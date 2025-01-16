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
        const userId = document.getElementById("userId").value;
        const isTaskDone = document.getElementById("taskDone").checked;
        const timeTaken = parseFloat(document.getElementById("timeTaken").value) || 0;
        const articleWatched = document.getElementById("articleRead").checked;
        const videoWatched = document.getElementById("videoWatched").checked;
        const booksRead = document.getElementById("bookRead").checked;
    
        const taskAnalysisData = {
            user_task_id: taskId,
            user_id: userId,
            is_task_done: isTaskDone,
            time_taken_in_hours: timeTaken,
            article_watched: articleWatched,
            video_watched: videoWatched,
            books_read: booksRead
        };
    
        console.log("Submitting task analysis data:", taskAnalysisData); // Log the data being sent
    
        // API call to create task analysis
        fetch("http://localhost/Naluri/server-side/routes/taskAnalysisRoutes.php/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(taskAnalysisData),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Server response:", data); // Log the server response
                if (data.success) {
                    Swal.fire(
                        'Success!',
                        'Task submitted successfully!',
                        'success'
                    );
                    fetchTaskData(); // Refresh the task list
                    document.querySelector("#submitTaskModal .btn-close").click(); // Close modal
                } else {
                    Swal.fire(
                        'Error!',
                        'Failed to create task analysis.',
                        'error'
                    );
                }
            })
            .catch((error) => {
                console.error("Error creating task analysis:", error);
                Swal.fire(
                    'Error!',
                    'Failed to create task analysis. Please try again.',
                    'error'
                );
            });
    });
    
    // Initial fetch of task data
    fetchTaskData();
});
