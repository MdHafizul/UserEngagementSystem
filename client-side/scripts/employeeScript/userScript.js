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
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: 'An error occurred while fetching user data. Please try again later.',
                });
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Patients',
                    text: 'An error occurred while fetching patients. Please try again later.',
                });
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Tasks',
                    text: 'An error occurred while fetching tasks. Please try again later.',
                });
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error Assigning Task',
                    text: 'An error occurred while assigning the task. Please try again later.',
                });
            });
    });

    // Initial fetch of user data
    fetchUserData();
});