document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskRoutes.php/read"; 
    const taskTableBody = document.querySelector("#taskTable tbody");
    const addTaskBtn = document.getElementById("addTaskBtn");
    const addTaskModal = new bootstrap.Modal(document.getElementById("addTaskModal"));
    const addTaskForm = document.getElementById("addTaskForm");

    const editTaskModal = new bootstrap.Modal(document.getElementById("editTaskModal"));
    const editTaskForm = document.getElementById("editTaskForm");

    let currentTaskId = null; // To store the current task ID being edited

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
                        <td class="text-center">${task.status}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm editBtn" data-id="${task.task_id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="${task.task_id}">Delete</button>
                        </td>
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

    // Show the add task modal
    addTaskBtn.addEventListener("click", () => {
        addTaskModal.show();
    });

    // Handle form submission for adding task
    addTaskForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const taskData = {
            title: document.getElementById("taskTitle").value,
            description: document.getElementById("taskDesc").value,
            due_date: document.getElementById("taskDueDate").value,
            status: document.getElementById("taskStatus").value,
        };

        fetch("http://localhost/Naluri/server-side/routes/taskRoutes.php/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(taskData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                console.log("Task added:", data);
                addTaskModal.hide();
                fetchTaskData(); // Refresh the task list
            })
            .catch((error) => {
                console.error("Error adding task:", error);
            });
    });

    // Show the edit task modal and populate it with task data
    function showEditTaskModal(taskId) {

        currentTaskId = taskId;
        fetch(`http://localhost/Naluri/server-side/routes/taskRoutes.php/read_single?task_id=${taskId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((task) => {
                document.getElementById("editTaskTitle").value = task.title;
                document.getElementById("editTaskDesc").value = task.description;
                document.getElementById("editTaskDueDate").value = task.due_date;
                document.getElementById("editTaskStatus").value = task.status;

                editTaskModal.show();
            })
            .catch((error) => {
                console.error("Error fetching task data:", error);
            });
    }

    // Handle form submission for editing task
    editTaskForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const taskData = {
            title: document.getElementById("editTaskTitle").value,
            description: document.getElementById("editTaskDesc").value,
            due_date: document.getElementById("editTaskDueDate").value,
            status: document.getElementById("editTaskStatus").value,
        };

        fetch(`http://localhost/Naluri/server-side/routes/taskRoutes.php/update?task_id=${currentTaskId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(taskData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                console.log("Task updated:", data);
                editTaskModal.hide();
                fetchTaskData(); // Refresh the task list
            })
            .catch((error) => {
                console.error("Error updating task:", error);
            });
    });

    // Handle task deletion
    function deleteTask(taskId) {
        if (confirm("Are you sure you want to delete this task?")) {
            fetch(`http://localhost/Naluri/server-side/routes/taskRoutes.php/delete?task_id=${taskId}`, {
                method: "DELETE",
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Task deleted:", data);
                    fetchTaskData(); // Refresh the task list
                })
                .catch((error) => {
                    console.error("Error deleting task:", error);
                });
        }
    }

    // Initial fetch of task data
    fetchTaskData();
});