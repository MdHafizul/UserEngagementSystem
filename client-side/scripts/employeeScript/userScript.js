document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient"; // Update with your API URL
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

                // Add event listeners for edit, delete, and assign task buttons
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
                        showAssignTaskModal(userId);
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
            });
    }


    // Show the assign task modal
    function showAssignTaskModal(userId) {
        currentUserId = userId;

        // Fetch patients for the select options
        fetch("http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((patients) => {
                const assignUserSelect = document.getElementById("assignUserSelect");
                assignUserSelect.innerHTML = ""; // Clear existing options

                patients.forEach((patient) => {
                    const option = document.createElement("option");
                    option.value = patient.user_id;
                    option.textContent = patient.name;
                    assignUserSelect.appendChild(option);
                });
            })
            .catch((error) => {
                console.error("Error fetching patients:", error);
            });

        // Fetch tasks for the select options
        fetch("http://localhost/Naluri/server-side/routes/taskRoutes.php/read")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((tasks) => {
                const assignTaskSelect = document.getElementById("assignTaskSelect");
                assignTaskSelect.innerHTML = ""; // Clear existing options

                tasks.forEach((task) => {
                    const option = document.createElement("option");
                    option.value = task.task_id;
                    option.textContent = task.title;
                    assignTaskSelect.appendChild(option);
                });
            })
            .catch((error) => {
                console.error("Error fetching tasks:", error);
            });

        assignTaskModal.show();
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
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                console.log("Task assigned:", data);
                alert("Task assigned successfully!");
                assignTaskModal.hide();
                fetchUserData(); // Refresh the user list
            })
            .catch((error) => {
                console.error("Error assigning task:", error);
            });
    });

    // Initial fetch of user data
    fetchUserData();
});