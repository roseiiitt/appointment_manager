<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Hello, <?php echo htmlspecialchars($username); ?></h1>

        <h2>Your Appointments</h2>
        <ul>
            <?php
            $stmt = $conn->prepare("SELECT app_id, doctor_name, appointment_date FROM appointments WHERE user_id = ?");
            if ($stmt === false) {
                echo "Error: " . htmlspecialchars($conn->error) . "<br>";
            } else {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($app_id, $doctor_name, $appointment_date);
                    while ($stmt->fetch()) {
                        echo "<li> " . htmlspecialchars($doctor_name) . " - " . htmlspecialchars($appointment_date) . "
                              <form method='POST' action='cancel_appointment.php' style='display:inline;'>
                                  <input type='hidden' name='app_id' value='" . htmlspecialchars($app_id) . "'>
                                  <button type='submit' class='cancel-button'>Cancel</button>
                              </form>
                              </li>";
                    }
                } else {
                    echo "<li>No appointments found.</li>";
                }

                $stmt->close();
            }

            $conn->close();
            ?>
        </ul>
    </div>
    <footer>
    <p><a href="schedule_appointment.php">Schedule an Appointment</a></p>
    <a href="logout.php">Logout</a></footer>
</body>
</html>
