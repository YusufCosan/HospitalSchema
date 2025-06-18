<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Medical History - Hospital Management System</title>
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
            <h1>Patient Medical History</h1>
            <?php
            if (!isset($_GET['patientId'])) {
                echo '<form method="get" class="form-group">';
                echo '<label for="patientId">Enter Patient ID:</label>';
                echo '<div style="display: flex; gap: 10px;">';
                echo '<input type="number" id="patientId" name="patientId" class="form-control" required>'; 
                echo '<button type="submit" class="btn btn-primary">View History</button>';
                echo '</div>';
                echo '</form>';
                exit;
            }
            $patientId = intval($_GET['patientId']);
            $patSql = "SELECT fullName FROM user WHERE userId = $patientId";
            $patRes = $conn->query($patSql);
            if ($patRes && $patRes->num_rows > 0) {
                $pat = $patRes->fetch_assoc();
                echo "<div class='card'>";
                echo "<h2>Patient: " . htmlspecialchars($pat['fullName']) . "</h2>";
                echo "</div>";
            }
            $visSql = "SELECT v.doctorId, v.date, u.fullName AS doctorName FROM visit v JOIN user u ON v.doctorId = u.userId WHERE v.patientId = $patientId ORDER BY v.date DESC";
            $visRes = $conn->query($visSql);
            echo "<div class='card'>";
            echo "<h3>Visit History</h3>";
            if ($visRes && $visRes->num_rows > 0) {
                echo "<ul class='visit-list'>";
                while ($vis = $visRes->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='visit.php?doctorId=" . $vis['doctorId'] . "&patientId=$patientId&date=" . $vis['date'] . "'>";
                    echo "<div class='visit-item'>";
                    echo "<span class='visit-date'>" . date('F j, Y', strtotime($vis['date'])) . "</span>";
                    echo "<span class='visit-doctor'>Dr. " . htmlspecialchars($vis['doctorName']) . "</span>";
                    echo "</div>";
                    echo "</a>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No visit history found.</p>";
            }
            echo "</div>";
            ?>
            <div class="card" style="text-align: center;">
                <a href="getAppointment.php?patientId=<?php echo $patientId; ?>" class="btn btn-primary">Schedule New Appointment</a>
            </div>
        </div>
    </div>
</body>
</html>