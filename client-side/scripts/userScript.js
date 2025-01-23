document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient";
    const userTableBody = document.querySelector("#userTable tbody");
    const addUserBtn = document.getElementById("addUserBtn");
    const addUserModal = new bootstrap.Modal(document.getElementById("addUserModal"));
    const addUserForm = document.getElementById("addUserForm");

    const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
    const editUserForm = document.getElementById("editUserForm");

    const assignTaskModal = new bootstrap.Modal(document.getElementById("assignTaskModal"));
    const assignTaskForm = document.getElementById("assignTaskForm");

    const showTasksModal = new bootstrap.Modal(document.getElementById("showTasksModal"));
    const tasksTableBody = document.getElementById("tasksTableBody");

    let currentUserId = null; // To store the current user ID being edited
    let tasks = []; // To store the task options

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
                            <button class="btn btn-warning btn-sm editBtn" data-id="${user.user_id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="${user.user_id}">Delete</button>
                            <button class="btn btn-primary btn-sm assignTaskBtn" data-id="${user.user_id}">Assign Task</button>
                            <button class="btn btn-info btn-sm showTasksBtn" data-id="${user.user_id}">Show Tasks</button>
                        </td>
                    `;

                    userTableBody.appendChild(row);
                });

                // Add event listeners for edit, delete, and assign task buttons
                attachEventListeners();
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

    function attachEventListeners() {
        document.querySelectorAll(".editBtn").forEach((button) => {
            button.addEventListener("click", (event) => {
                const userId = event.target.getAttribute("data-id");
                showEditUserModal(userId);
            });
        });

        document.querySelectorAll(".deleteBtn").forEach((button) => {
            button.addEventListener("click", (event) => {
                const userId = event.target.getAttribute("data-id");
                showDeleteUserConfirmation(userId);
            });
        });

        document.querySelectorAll(".assignTaskBtn").forEach((button) => {
            button.addEventListener("click", (event) => {
                const userId = event.target.getAttribute("data-id");
                const userName = event.target.closest("tr").children[0].textContent;
                showAssignTaskModal(userId, userName);
            });
        });

        document.querySelectorAll(".showTasksBtn").forEach((button) => {
            button.addEventListener("click", (event) => {
                const userId = event.target.getAttribute("data-id");
                showAssignedTasks(userId);
            });
        });
    }

    // Show the add user modal
    addUserBtn.addEventListener("click", () => {
        addUserModal.show();
    });

    // Handle form submission for adding user
    addUserForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const userData = {
            name: document.getElementById("userName").value,
            email: document.getElementById("userEmail").value,
            username: document.getElementById("userUsername").value,
            password: document.getElementById("userPassword").value,
            user_type: "patient",
        };

        fetch("http://localhost/Naluri/server-side/routes/userRoutes.php/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(userData),
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
                    title: 'User Added',
                    text: 'The user has been added successfully.',
                });
                addUserModal.hide();
                fetchUserData();
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Adding User',
                    text: 'An error occurred while adding the user. Please try again later.',
                });
                console.error("Error adding user:", error);
            });
    });

    // Show the edit user modal
    function showEditUserModal(userId) {
        currentUserId = userId;
        // Fetch user details based on ID and populate the form
        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("editUserName").value = data.name;
                document.getElementById("editUserEmail").value = data.email;
                document.getElementById("editUserUsername").value = data.username;
                document.getElementById("editUserPassword").value = data.password;
                editUserModal.show();
            })
            .catch(error => {
                console.error("Error fetching user details:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching User Details',
                    text: 'An error occurred while fetching user details. Please try again later.',
                });
            });
    }

    // Handle form submission for editing user
    editUserForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const userData = {
            name: document.getElementById("editUserName").value,
            email: document.getElementById("editUserEmail").value,
            username: document.getElementById("editUserUsername").value,
            password: document.getElementById("editUserPassword").value,
        };

        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/update?user_id=${currentUserId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(userData),
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
                    title: 'User Updated',
                    text: 'The user details have been updated successfully.',
                });
                editUserModal.hide();
                fetchUserData();
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Updating User',
                    text: 'An error occurred while updating the user. Please try again later.',
                });
                console.error("Error updating user:", error);
            });
    });

    // Show delete user confirmation
    function showDeleteUserConfirmation(userId) {
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
                deleteUser(userId);
            }
        });
    }

    // Handle user deletion
    function deleteUser(userId) {
        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/delete?user_id=${userId}`, {
            method: "DELETE",
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
                return response;
            })
            .then((response) => {
                if (response.status === 200 || response.status === 204) {
                    fetchUserData(); // Refresh the user list
                    Swal.fire({
                        icon: 'success',
                        title: 'User Deleted',
                        text: 'The user has been deleted successfully.',
                    });
                } else {
                    return response.text().then((text) => {
                        throw new Error(text);
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Deleting User',
                    text: 'An error occurred while deleting the user. Please try again later.',
                });
                console.error("Error deleting user:", error);
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


    // Show assigned tasks for a user
    function showAssignedTasks(userId) {
        fetch(`http://localhost/Naluri/server-side/routes/userTaskRoutes.php/read_by_user?user_id=${userId}`)
            .then(response => response.json())
            .then(tasks => {
                tasksTableBody.innerHTML = ""; // Clear existing rows
                tasks.forEach(task => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td>${task.title}</td>
                    <td>${task.due_date}</td>
                    <td>${task.status}</td>
                `;
                    tasksTableBody.appendChild(row);
                });
                showTasksModal.show();
            })
            .catch(error => {
                console.error("Error fetching tasks:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Tasks',
                    text: 'An error occurred while fetching tasks. Please try again later.',
                });
            });
    }

    // Initialize by fetching user data and task data
    fetchUserData();
});