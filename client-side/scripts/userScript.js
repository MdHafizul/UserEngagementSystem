document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient"; // Update with your API URL
    const userTableBody = document.querySelector("#userTable tbody");
    const addUserBtn = document.getElementById("addUserBtn");
    const addUserModal = new bootstrap.Modal(document.getElementById("addUserModal"));
    const addUserForm = document.getElementById("addUserForm");

    const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
    const editUserForm = document.getElementById("editUserForm");

    const assignTaskModal = new bootstrap.Modal(document.getElementById("assignTaskModal"));
    const assignTaskForm = document.getElementById("assignTaskForm");

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
                deleteUser(userId);
            });
        });

        document.querySelectorAll(".assignTaskBtn").forEach((button) => {
            button.addEventListener("click", (event) => {
                const userId = event.target.getAttribute("data-id");
                const userName = event.target.closest("tr").children[0].textContent;
                showAssignTaskModal(userId, userName);
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
                Swal.fire(
                    'Success!',
                    'User added successfully.',
                    'success'
                );
                console.log("User added:", data);
                addUserModal.hide();
                fetchUserData();
            })
            .catch((error) => {
                Swal.fire(
                    'Error!',
                    'There was an error adding the user. Please try again.',
                    'error'
                );
                console.error("Error adding user:", error);
            });
    });


    // Show the edit user modal
    function showEditUserModal(userId) {
        currentUserId = userId;

        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((user) => {
                document.getElementById("editUserName").value = user.name;
                document.getElementById("editUserEmail").value = user.email;
                document.getElementById("editUserUsername").value = user.username;
                document.getElementById("editUserPassword").value = ""; // Leave password empty

                editUserModal.show();
            })
            .catch((error) => {
                console.error("Error fetching user data:", error);
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
                console.log("User updated:", data);
                editUserModal.hide();
                fetchUserData(); // Refresh the user list
            })
            .catch((error) => {
                console.error("Error updating user:", error);
            });
    });

    // Handle user deletion
    function deleteUser(userId) {
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
                fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/delete?user_id=${userId}`, {
                    method: "DELETE",
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
                        console.log("User deleted:", data);
                        Swal.fire(
                            'Deleted!',
                            'User has been deleted.',
                            'success'
                        );
                        fetchUserData(); // Refresh the user list
                    })
                    .catch((error) => {
                        console.error("Error deleting user:", error);
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the user.',
                            'error'
                        );
                    });
            }
        });
    }

    function showAssignTaskModal(userId, userName) {
        // Populate user select
        const assignUserSelect = document.getElementById("assignUserSelect");
        assignUserSelect.innerHTML = `<option value="${userId}">${userName}</option>`;

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
                console.log("Task assigned:", data);
                if (data.message === "Task is already assigned to the user") {
                    Swal.fire(
                        'Warning!',
                        'Task is already assigned to the user.',
                        'warning'
                    );
                } else {
                    assignTaskModal.hide();
                    Swal.fire(
                        'Success!',
                        'Task assigned successfully.',
                        'success'
                    );
                    fetchUserData(); // Refresh the user list
                }
            })
            .catch((error) => {
                console.error("Error assigning task:", error);
                Swal.fire(
                    'Error!',
                    'There was an error assigning the task.',
                    'error'
                );
            });
    });

    // Initialize by fetching user data and task data
    fetchUserData();
});
