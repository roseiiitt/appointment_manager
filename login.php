<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Manager - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div id="auth">
            <h2>Login</h2>
            <form id="loginForm" method="POST">
                <input type="text" id="username" name="username" placeholder="Username">
                <input type="password" id="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const errorMessages = {
        username: 'Username is required',
        password: 'Password must be at least 8 characters long'
    };

    function showError(message) {
        let errorElement = document.querySelector('.error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error';
            form.insertBefore(errorElement, form.firstChild);
        }
        errorElement.textContent = message;
    }

    function clearErrors() {
        const errorElement = document.querySelector('.error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    form.addEventListener('submit', (event) => {
        clearErrors();

        let valid = true;

        if (username.value.trim() === '') {
            showError(errorMessages.username);
            valid = false;
        }

        if (password.value.length < 8) {
            showError(errorMessages.password);
            valid = false;
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});

    </script>
</body>
</html>

<?php

include 'db.php'; 
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Bind results
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // Check if a row was found and verify password
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username']=$username;
        header("Location: doctor.html");
        exit; 
    }
    else {
            $error_message = "Invalid username or password";
    }
    $stmt->close(); // Close statement
    $conn->close(); // Close connection
}
?>
