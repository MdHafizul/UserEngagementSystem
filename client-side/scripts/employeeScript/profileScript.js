document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = `http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`;
    const profileCard = document.getElementById("profile-card");

    let originalUserData = {};

    // Fetch user profile data
    function fetchUserProfile() {
        fetch(apiEndpoint)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((user) => {
                profileCard.innerHTML = `
                    <div class="col-md-12 col-xl-4">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body text-center">
                                <div class="mt-3 mb-4">
                                    <img id="profileImage" src="${user.profile_image || '../../assets/img/bruce-mars.jpg'}" class="rounded-circle img-fluid" style="width: 100px;" />
                                </div>
                                <h4 id="profileName" class="mb-2">${user.name}</h4>
                                <table class="table table-bordered mt-4">
                                    <tr>
                                        <th>Name</th>
                                        <td id="userName">${user.name}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td id="userEmail">${user.email}</td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td id="userUsername">${user.username}</td>
                                    </tr>
                                </table>
                                <button class="btn btn-primary mt-4" id="editUserBtn" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit Profile</button>
                            </div>
                        </div>
                    </div>
                `;

                // Add event listener for the edit button after it is added to the DOM
                document.getElementById("editUserBtn").addEventListener("click", () => {
                    showEditUserModal(user.user_id);
                });
            })
            .catch((error) => {
                console.error("Error fetching user profile:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Fetching Data',
                    text: 'An error occurred while fetching user profile data. Please try again later.',
                });
            });
    }

    // Show the edit user modal
    function showEditUserModal(userId) {
        // Fetch user details based on ID and populate the form
        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("editUserName").value = data.name;
                document.getElementById("editUserEmail").value = data.email;
                document.getElementById("editUserUsername").value = data.username;
                document.getElementById("editUserPassword").value = data.password;
                const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
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
    document.getElementById("editUserForm").addEventListener("submit", (event) => {
        event.preventDefault();

        const updatedUserData = {};
        const newName = document.getElementById("editUserName").value;
        const newEmail = document.getElementById("editUserEmail").value;
        const newUsername = document.getElementById("editUserUsername").value;
        const newPassword = document.getElementById("editUserPassword").value;

        if (newName !== originalUserData.name) {
            updatedUserData.name = newName;
        }
        if (newEmail !== originalUserData.email) {
            updatedUserData.email = newEmail;
        }
        if (newUsername !== originalUserData.username) {
            updatedUserData.username = newUsername;
        }
        if (newPassword !== originalUserData.password) {
            updatedUserData.password = newPassword;
        }

        if (Object.keys(updatedUserData).length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'No Changes',
                text: 'No changes were made to the profile.',
            });
            return;
        }

        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/update?user_id=${userId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(updatedUserData),
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
                    title: 'Profile Updated',
                    text: 'Your profile details have been updated successfully.',
                });
                const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
                editUserModal.hide();
                fetchUserProfile();
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Updating Profile',
                    text: 'An error occurred while updating your profile. Please try again later.',
                });
                console.error("Error updating profile:", error);
            });
    });

    // Initial fetch of user profile data
    fetchUserProfile();
});