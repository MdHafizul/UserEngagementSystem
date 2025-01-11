document.addEventListener("DOMContentLoaded", () => {
    const apiEndpoint = `http://localhost/Naluri/server-side/routes/userRoutes.php/read_single?user_id=${userId}`;
    const profileCard = document.getElementById("profile-card");

    console.log("Script loaded. User ID:", userId); // Debugging

    // Fetch user profile data
    function fetchUserProfile() {
        console.log("Attempting to fetch user profile from:", apiEndpoint); // Debugging

        fetch(apiEndpoint)
            .then((response) => {
                console.log("Received response:", response); // Debugging response object

                if (!response.ok) {
                    console.error("Network response was not ok. Status:", response.status);
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((user) => {
                console.log("Fetched user data:", user); // Debugging user data

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

                // Pre-fill the edit form with user data
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserUsername').value = user.username;
            })
            .catch((error) => {
                console.error("Error fetching user profile:", error);
            });
    }

    // Initial fetch of user profile data
    fetchUserProfile();

    // Handle Edit User Form Submission
    document.getElementById('editUserForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const name = sanitizeInput(document.getElementById('editUserName').value.trim());
        const email = sanitizeInput(document.getElementById('editUserEmail').value.trim());
        const username = sanitizeInput(document.getElementById('editUserUsername').value.trim());
        const password = sanitizeInput(document.getElementById('editUserPassword').value.trim());

        try {
            const response = await fetch(`http://localhost/Naluri/server-side/routes/userRoutes.php/update?user_id=${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    name,
                    email,
                    username,
                    password
                })
            });

            const result = await response.json();

            if (response.ok) {
                alert("Profile updated successfully!");
                fetchUserProfile(); // Refresh the profile data
                const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                editUserModal.hide(); // Hide the modal
            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error("Error updating profile:", error);
            alert("An error occurred during profile update. Please try again later.");
        }
    });

    function sanitizeInput(input) {
        const element = document.createElement('div');
        element.innerText = input;
        return element.innerHTML;
    }
});