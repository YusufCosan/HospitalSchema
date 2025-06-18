<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management System - Home</title>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="navbar-brand">Hospital Management System</a>
            <ul class="nav-links">
                <li><a href="departments.php">Departments</a></li>
                <li><a href="patient.php">Patients</a></li>
                <li><a href="getAppointment.php">Appointments</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="card">
            <h1>Welcome to Hospital Management System</h1>
            <p>Please choose an option below to get started.</p>
            <div style="margin-top: 20px;">
                <a href="departments.php" class="btn">View Departments</a>
                <a href="patient.php" class="btn" style="margin-left: 10px;">Manage Patients</a>
                <a href="getAppointment.php" class="btn" style="margin-left: 10px;">Book Appointment</a>
            </div>
        </div>
    </div>
</body>
</html>