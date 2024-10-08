<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $doctor_name = htmlspecialchars($_POST['doctor_name']);
    $appointment_date = $_POST['appointment_date'];

    // Check if the appointment date is not in the past
    if (strtotime($appointment_date) < time()) {
        echo "You cannot schedule an appointment in the past.";
    } else {
        // Check if the doctor is available at the given time
        $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_name = ? AND appointment_date = ?");
        $stmt->bind_param("ss", $doctor_name, $appointment_date);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_name, appointment_date) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $doctor_name, $appointment_date);

            if ($stmt->execute()) {
                header("Location: dashboard.php");
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "The doctor is not available at the selected time.";
        }
    }

    $conn->close();
}

// Fetch doctors and specialties from the database
$doctors = [];
$result = $conn->query("SELECT name, speciality FROM doctor");
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule an Appointment</title>
    <link rel="stylesheet" href="css/schedule.css">
    <script src="js/schedule_appointment.js"></script>
</head>
<body>
    <p>Schedule an Appointment</p>
    <form method="POST" action="schedule_appointment.php">
        <select id="doctor" name="doctor_name" required>
            <option value="">Select Doctor</option>
            <?php foreach ($doctors as $doctor) : ?>
                <option value="<?= htmlspecialchars($doctor['name']) ?>"><?= htmlspecialchars($doctor['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="datetime-local" name="appointment_date" min="<?= date('Y-m-d\TH:i') ?>" required>
        <button type="submit">Schedule Appointment</button>
    </form>

</body>
</html>
<?php
include 'doctor.html';
?>
