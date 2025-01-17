document.addEventListener("DOMContentLoaded", () => {
    const userId = document.getElementById("userId").value;
    const apiEndpoint = `http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`;
    console.log("API Endpoint:", apiEndpoint); // Debugging line

    const profileCard = document.getElementById("profile-card");

    // Fetch user profile data
    function fetchUserProfile() {
        fetch(apiEndpoint)
            .then((response) => {
                console.log("API Response Status:", response.status); // Debugging line
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((user) => {
                console.log("User Data:", user); // Debugging line
                profileCard.innerHTML = `
                    <div class="col-md-8 col-xl-6">
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

                // Add event listener for the edit button
                document.getElementById("editUserBtn").addEventListener("click", () => {
                    showEditUserModal(user);
                });
            })
            .catch((error) => {
                console.error("Error fetching user profile:", error);
                profileCard.innerHTML = `
                    <div class="col-12 text-center text-danger">
                        Failed to load profile data.
                    </div>
                `;
                Swal.fire(
                    'Error!',
                    'Failed to load user profile data.',
                    'error'
                );
            });
    }

    // Show the edit user modal with pre-filled data
    function showEditUserModal(user) {
        document.getElementById("editUserName").value = user.name;
        document.getElementById("editUserEmail").value = user.email;
        document.getElementById("editUserUsername").value = user.username;
        document.getElementById("editUserPassword").value = ""; 
    }

    // Handle form submission for editing user
    document.getElementById("editUserForm").addEventListener("submit", (event) => {
        event.preventDefault();

        const userData = {
            name: document.getElementById("editUserName").value,
            email: document.getElementById("editUserEmail").value,
            username: document.getElementById("editUserUsername").value,
            password: document.getElementById("editUserPassword").value,
        };

        fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/update?user_id=${userId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(userData),
        })
            .then((response) => {
                console.log("Update Response Status:", response.status); // Debugging line
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then((data) => {
                console.log("User updated:", data); // Debugging line
                Swal.fire(
                    'Success!',
                    'Profile updated successfully!',
                    'success'
                );
                fetchUserProfile(); // Refresh the profile data
                bootstrap.Modal.getInstance(document.getElementById("editUserModal")).hide();
            })
            .catch((error) => {
                console.error("Error updating user:", error);
                Swal.fire(
                    'Error!',
                    'Failed to update profile.',
                    'error'
                );
            });
    });

    // Initial fetch of user profile data
    fetchUserProfile();
});