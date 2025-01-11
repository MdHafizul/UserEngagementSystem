<?php
session_start();
require_once './config/connectdb.php';

function sanitizeInput($input)
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, username, password, user_type) VALUES (?, ?, ?, ?, 'patient')");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("ssss", $name, $email, $username, $hashed_password);
    $stmt->execute();
    $stmt->close();
}

if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'employee') {
        header('Location: /Naluri/client-side/pages/employeePages/dashboard.php');
    } else if ($_SESSION['user_type'] === 'admin') {
        header('Location: /Naluri/client-side/pages/dashboard.php');
    } else if ($_SESSION['user_type'] === 'patient') {
        header('Location: /Naluri/client-side/pages/patientPages/patientIndex.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Naluri HomePage</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
    <link rel="stylesheet" href="./style.css">
    <script src="./script.js" defer></script>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form id="signUpForm">
                <h1>Create Account</h1>
                <span>or use your email for registration</span>
                <input type="text" id="name" placeholder="Name" required />
                <input type="email" id="email" placeholder="Email" required />
                <input type="text" id="username" placeholder="Username" required />
                <input type="password" id="password" placeholder="Password" required />
                <input type="password" id="confirmPassword" placeholder="Confirm Password" required />
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form id="signInForm">
                <h1>Sign in</h1>
                <span>or use your account</span>
                <input type="email" id="signinEmail" placeholder="Email" required />
                <input type="password" id="signinPassword" placeholder="Password" required />
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>