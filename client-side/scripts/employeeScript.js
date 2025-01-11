document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = "http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=employee"; 
    const employeeTableBody = document.querySelector("#employeeTable tbody");
    const addEmployeeBtn = document.getElementById("addEmployeeBtn");
    const addEmployeeModal = new bootstrap.Modal(document.getElementById("addEmployeeModal"));
    const addEmployeeForm = document.getElementById("addEmployeeForm");

    const editEmployeeModal = new bootstrap.Modal(document.getElementById("editEmployeeModal"));
    const editEmployeeForm = document.getElementById("editEmployeeForm");

    const deleteEmployeeModal = new bootstrap.Modal(document.getElementById("deleteEmployeeModal"));
    const deleteEmployeeForm = document.getElementById("deleteEmployeeForm");

    let currentEmployeeId = null; // To store the current employee ID being edited or deleted

    // Fetch employee data
    function fetchEmployeeData() {
        fetch(apiEndpoint)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                // Clear existing rows
                employeeTableBody.innerHTML = "";

                // Populate table rows
                data.forEach((employee) => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${employee.name}</td>
                        <td>${employee.email}</td>
                        <td class="text-center">${employee.user_id}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm editBtn" data-id="${employee.user_id}">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="${employee.user_id}">Delete</button>
                        </td>
                    `;

                    employeeTableBody.appendChild(row);
                });

                // Add event listeners for edit and delete buttons
                document.querySelectorAll('.editBtn').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const employeeId = event.target.getAttribute('data-id');
                        showEditEmployeeModal(employeeId);
                    });
                });

                document.querySelectorAll('.deleteBtn').forEach(button => {
                    button.addEventListener('click', (event) => {
                        const employeeId = event.target.getAttribute('data-id');
                        showDeleteEmployeeModal(employeeId);
                    });
                });
            })
            .catch((error) => {
                console.error("Error fetching employee data:", error);
                employeeTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-danger">Failed to load employee data.</td>
                    </tr>
                `;
            });
    }

    // Show the add employee modal
    addEmployeeBtn.addEventListener("click", () => {
        addEmployeeModal.show();
    });

    // Handle form submission for adding employee
    addEmployeeForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const employeeData = {
            name: document.getElementById("employeeName").value,
            email: document.getElementById("employeeEmail").value,
            username: document.getElementById("employeeUsername").value,
            password: document.getElementById("employeePassword").value,
            user_type: document.getElementById("employeeType").value,
        };

        fetch("http://localhost/Naluri/server-side/routes/userRoutes.php/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(employeeData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                addEmployeeModal.hide();
                fetchEmployeeData(); // Refresh the employee list
            })
            .catch((error) => {
                console.error("Error adding employee:", error);
            });
    });

    // Show the edit employee modal
    function showEditEmployeeModal(employeeId) {
        currentEmployeeId = employeeId;
        // Fetch employee details based on ID and populate the form
        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${employeeId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("editEmployeeName").value = data.name;
                document.getElementById("editEmployeeEmail").value = data.email;
                document.getElementById("editEmployeeUsername").value = data.username;
                document.getElementById("editEmployeePassword").value = data.password;
                document.getElementById("editEmployeeType").value = data.user_type;
                editEmployeeModal.show();
            })
            .catch(error => console.error("Error fetching employee details:", error));
    }

    // Handle form submission for editing employee
    editEmployeeForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const employeeData = {
            name: document.getElementById("editEmployeeName").value,
            email: document.getElementById("editEmployeeEmail").value,
            username: document.getElementById("editEmployeeUsername").value,
            password: document.getElementById("editEmployeePassword").value,
            user_type: document.getElementById("editEmployeeType").value,
        };

        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/update?user_id=${currentEmployeeId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(employeeData),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                editEmployeeModal.hide();
                fetchEmployeeData(); // Refresh the employee list
            })
            .catch((error) => {
                console.error("Error updating employee:", error);
            });
    });

    // Show the delete employee modal
    function showDeleteEmployeeModal(employeeId) {
        currentEmployeeId = employeeId;
        deleteEmployeeModal.show();
    }

    // Handle employee deletion
    deleteEmployeeForm.addEventListener("submit", (event) => {
        event.preventDefault();
    
        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/delete?user_id=${currentEmployeeId}`, {
            method: "DELETE",
        })
            .then((response) => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    return response.text().then(text => { throw new Error(text) });
                }
            })
            .then((data) => {
                deleteEmployeeModal.hide();
                fetchEmployeeData(); // Refresh the employee list
            })
            .catch((error) => {
                console.error("Error deleting employee:", error);
            });
    });

    // Initial fetch of employee data
    fetchEmployeeData();
});
