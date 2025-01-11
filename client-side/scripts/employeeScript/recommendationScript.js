document.addEventListener("DOMContentLoaded", () => {
    const userSelect = document.getElementById("userSelect");

    // Fetch users with user_type = patient
    fetch("http://localhost/Naluri/server-side/routes/userRoutes.php/read_by_type?user_type=patient")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            // Check if the response contains users
            if (data && Array.isArray(data)) {
                // Clear existing options
                userSelect.innerHTML = '<option value="" disabled selected>Select a user</option>';

                // Populate options
                data.forEach((user) => {
                    const option = document.createElement("option");
                    option.value = user.user_id;
                    option.textContent = user.name;
                    userSelect.appendChild(option);
                });
            } else {
                console.error("No users found or invalid response format");
            }
        })
        .catch((error) => {
            console.error("Error fetching user data:", error);
        });
});