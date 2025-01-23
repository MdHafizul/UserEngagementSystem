document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient";
    const userTableBody = document.querySelector("#userTable tbody");
    const assignTaskModal = new bootstrap.Modal(document.getElementById("assignTaskModal"));
    const assignTaskForm = document.getElementById("assignTaskForm");

    let currentUserId = null; // To store the current user ID being edited

    // Fetch user data
    function fetchUserData() {
        fetch(apiEndpoint)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Clear existing rows
                userTableBody.innerHTML = "";

                // Populate table rows
                data.forEach((user) => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td class="text-center">${user.username}</td>
                        <td class="text-center">${user.user_type}</td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm assignTaskBtn" data-id="${user.user_id}">Assign Task</button>
                        </td>
                    `;

                    userTableBody.appendChild(row);
                });

                // Add event listeners for assign task buttons
                document.querySelectorAll(".assignTaskBtn").forEach((button) => {
                    button.addEventListener("click", (event) => {
                        const userId = event.target.getAttribute("data-id");
                        const userName = event.target.closest("tr").querySelector("td").textContent;
                        showAssignTaskModal(userId, userName);
                    });
                });
            })
            .catch((error) => {
                console.error("Error fetching user data:", error);
                userTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">Failed to load user data.</td>
                    </tr>
                `;
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: 'An error occurred while fetching user data. Please try again later.',
                });
            });
    }

    function showAssignTaskModal(userId, userName) {
        document.getElementById("assignUserId").value = userId;
        const assignUserSelect = document.getElementById("assignUserSelect");
        assignUserSelect.innerHTML = `<option value="${userId}">${userName}</option>`;
        assignUserSelect.disabled = true;

        // Fetch and populate task options
        fetch("http://localhost/Naluri/server-side/routes/taskRoutes.php/read")
            .then((response) => response.json())
            .then((tasks) => {
                const assignTaskSelect = document.getElementById("assignTaskSelect");
                assignTaskSelect.innerHTML = '<option value="">Select a task</option>'; // Default option

                tasks.forEach((task) => {
                    let taskOption = document.createElement("option");
                    taskOption.value = task.task_id; // Use task_id from your data
                    taskOption.textContent = task.title; // Use title from your data
                    assignTaskSelect.appendChild(taskOption);
                });

                assignTaskModal.show();
            })
            .catch((error) => {
                console.error("Error loading tasks:", error);
                Swal.fire(
                    'Error!',
                    'Failed to load tasks. Please try again.',
                    'error'
                );
            });
    }

    // Handle form submission for assigning task
    assignTaskForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const assignData = {
            user_id: document.getElementById("assignUserSelect").value,
            task_id: document.getElementById("assignTaskSelect").value,
            status: "Pending",
        };

        fetch("http://localhost/Naluri/server-side/routes/userTaskRoutes.php/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(assignData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then((data) => {
                // Check if the task is already assigned to the user
                if (data.message === "Task is already assigned to the user") {
                    Swal.fire(
                        'Warning!',
                        'Task is already assigned to the user.',
                        'warning'
                    );
                    throw new Error("Duplicate task assignment"); // Prevent further logic execution
                }

                // If the task is not a duplicate, proceed with creating task analysis
                const taskAnalysisData = {
                    task_id: assignData.task_id,
                    user_id: assignData.user_id,
                    is_task_done: false,
                    time_taken_in_hours: 0,
                    article_watched: false,
                    video_watched: false,
                    books_read: false,
                };

                return fetch("http://localhost/Naluri/server-side/routes/taskAnalysisRoutes.php/create", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(taskAnalysisData),
                });
            })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then((data) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Task Assigned',
                    text: 'The task has been assigned successfully.',
                });
                // Optionally, refresh the task list or perform other actions
            })
            .catch((error) => {
                if (error.message !== "Duplicate task assignment") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Assigning Task',
                        text: 'An error occurred while assigning the task. Please try again later.',
                    });
                    console.error("Error assigning task:", error);
                }
            });
    });

    // Initial fetch of user data
    fetchUserData();
});