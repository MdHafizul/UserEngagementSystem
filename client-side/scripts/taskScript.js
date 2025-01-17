document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/taskRoutes.php/read"; 
    const taskTableBody = document.querySelector("#taskTable tbody");
    const addTaskBtn = document.getElementById("addTaskBtn");
    const addTaskModal = new bootstrap.Modal(document.getElementById("addTaskModal"));
    const addTaskForm = document.getElementById("addTaskForm");

    const editTaskModal = new bootstrap.Modal(document.getElementById("editTaskModal"));
    const editTaskForm = document.getElementById("editTaskForm");

    let currentTaskId = null; // To store the current task ID being edited or deleted

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
                document.querySelectorAll('.editBtn').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const taskId = event.target.getAttribute('data-id');
                        showEditTaskModal(taskId);
                    });
                });

                document.querySelectorAll('.deleteBtn').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const taskId = event.target.getAttribute('data-id');
                        showDeleteTaskConfirmation(taskId);
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: 'An error occurred while fetching task data. Please try again later.',
                });
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

        // Validate due date
        const dueDate = new Date(taskData.due_date);
        const currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0); // Set to start of the day

        if (dueDate < currentDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Due Date',
                text: 'The due date cannot be in the past.',
            });
            return;
        }

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
                addTaskModal.hide();
                fetchTaskData(); // Refresh the task list
                Swal.fire({
                    icon: 'success',
                    title: 'Task Added',
                    text: 'The task has been added successfully.',
                });
            })
            .catch((error) => {
                console.error("Error adding task:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Adding Task',
                    text: 'An error occurred while adding the task. Please try again later.',
                });
            });
    });

    // Show the edit task modal
    function showEditTaskModal(taskId) {
        currentTaskId = taskId;
        // Fetch task details based on ID and populate the form
        fetch(`http://localhost/Naluri/server-side/routes/taskRoutes.php/read_single?task_id=${taskId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("editTaskTitle").value = data.title;
                document.getElementById("editTaskDesc").value = data.description;
                document.getElementById("editTaskDueDate").value = data.due_date;
                document.getElementById("editTaskStatus").value = data.status;
                editTaskModal.show();
            })
            .catch(error => {
                console.error("Error fetching task details:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Task Details',
                    text: 'An error occurred while fetching task details. Please try again later.',
                });
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

        // Validate due date
        const dueDate = new Date(taskData.due_date);
        const currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0); // Set to start of the day

        if (dueDate < currentDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Due Date',
                text: 'The due date cannot be in the past.',
            });
            return;
        }

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
                editTaskModal.hide();
                fetchTaskData(); // Refresh the task list
                Swal.fire({
                    icon: 'success',
                    title: 'Task Updated',
                    text: 'The task details have been updated successfully.',
                });
            })
            .catch((error) => {
                console.error("Error updating task:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Updating Task',
                    text: 'An error occurred while updating the task. Please try again later.',
                });
            });
    });

    // Show delete task confirmation
    function showDeleteTaskConfirmation(taskId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTask(taskId);
            }
        });
    }

    // Handle task deletion
    function deleteTask(taskId) {
        fetch(`http://localhost/Naluri/server-side/routes/taskRoutes.php/delete?task_id=${taskId}`, {
            method: "DELETE",
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response;
            })
            .then((response) => {
                if (response.status === 200 || response.status === 204) {
                    fetchTaskData(); // Refresh the task list
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Deleted',
                        text: 'The task has been deleted successfully.',
                    });
                } else {
                    return response.text().then(text => { throw new Error(text) });
                }
            })
            .catch((error) => {
                console.error("Error deleting task:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Deleting Task',
                    text: 'An error occurred while deleting the task. Please try again later.',
                });
            });
    }

    // Initial fetch of task data
    fetchTaskData();
});