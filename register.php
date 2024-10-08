<!DOCTYPE html>
<html>
<head>
  <title>Register as Patient</title>
  <link rel="stylesheet" href="css/register.css">
</head>
<body>
  <h1>Patient Registration</h1>
  <form action="register.php" method="post" id="registration-form">
    <div class="input-group">
      <label for="username" class="label">Username:</label>
      <input type="text" name="username" id="username" placeholder="Enter your username">
      <div class="error-message"></div>
    </div>
    <div class="input-group">
      <label for="password" class="label">Password:</label>
      <input type="password" name="password" id="password" placeholder="Enter your password">
      <div class="error-message"></div>
    </div>
    <div class="input-group">
      <label for="name" class="label">Full Name:</label>
      <input type="text" name="name" id="name" placeholder="Enter your full name">
      <div class="error-message"></div>
    </div>
    <div class="input-group">
      <label for="email" class="label">Email:</label>
      <input type="email" name="email" id="email" placeholder="Enter your email">
      <div class="error-message"></div>
    </div>
    <button type="submit">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>
  <script src="js/register.js"></script>
</body>
</html>


<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];  
    $name = $_POST['name'];   
    $role = $_POST['role'];

    //fetch the id
    $stmt = $conn->prepare("SELECT MAX(id) AS max_id FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'] ?? 0;
    $new_id = $max_id + 1;

    $stmt = $conn->prepare("INSERT INTO users (id, username, password, email, name, role) VALUES (?, ?, ?, ?, ?, 'patient')");
    $stmt->bind_param("issss", $new_id, $username, $password, $email, $name); 

    if ($stmt->execute()) {
        header("Location: index.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
