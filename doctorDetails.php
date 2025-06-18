<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Details - Hospital Management System</title>
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
        <div class="navigation">
            <a href="javascript:history.back()">← Back</a>
        </div>
        <?php
        if (!isset($_GET['doctorId'])) {
            echo "<div class='alert alert-error'><p>No doctor selected.</p></div>";
            exit;
        }
        $doctorId = intval($_GET['doctorId']);
        $docSql = "SELECT u.fullName, d.specialty, dep.name as departmentName FROM user u JOIN doctor d ON u.userId = d.userId JOIN department dep ON d.workingIn = dep.departmentId WHERE u.userId = $doctorId";
        $docRes = $conn->query($docSql);
        if ($docRes && $docRes->num_rows > 0) {
            $doc = $docRes->fetch_assoc();
            echo "<div class='card'>";
            echo "<h1>Dr. " . htmlspecialchars($doc['fullName']) . "</h1>";
            echo "<div class='doctor-info'>";
            echo "<p><strong>Specialty:</strong> " . htmlspecialchars($doc['specialty'] ?? 'General Medicine') . "</p>";
            echo "<p><strong>Department:</strong> " . htmlspecialchars($doc['departmentName']) . "</p>";
            echo "</div></div>";
        }
        ?>
    </div>
</body>
</html>