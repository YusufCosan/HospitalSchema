<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Departments - Hospital Management System</title>
    <link rel="stylesheet" href="styles.css">
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
            <h1>Hospital Departments</h1>
            <p>Browse through our specialized departments and their management teams.</p>
            <table>
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>Specialty</th>
                        <th>Medical Manager</th>
                        <th>Admin Manager</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT d.departmentId, d.name, d.specialty, u1.fullName AS medManager, u2.fullName AS adManager
                            FROM department d
                            JOIN doctor doc ON d.medManagerId = doc.userId
                            JOIN user u1 ON doc.userId = u1.userId
                            JOIN staff s ON d.adManagerId = s.userId
                            JOIN user u2 ON s.userId = u2.userId";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='departmentDetails.php?departmentId=" . $row['departmentId'] . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['specialty']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['medManager']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adManager']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>