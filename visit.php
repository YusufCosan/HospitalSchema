<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Visit Details - Hospital Management System</title>
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
        if (!isset($_GET['doctorId']) || !isset($_GET['patientId']) || !isset($_GET['date'])) {
            echo "<div class='alert alert-error'><p>Invalid visit details provided.</p></div>";
            exit;
        }
        $doctorId = intval($_GET['doctorId']);
        $patientId = intval($_GET['patientId']);
        $date = $conn->real_escape_string($_GET['date']);
        // Get visit info
        $visitSql = "SELECT v.*, u1.fullName AS doctorName, u2.fullName AS patientName, r.diagnosis, r.billing FROM visit v JOIN user u1 ON v.doctorId = u1.userId JOIN user u2 ON v.patientId = u2.userId LEFT JOIN report r ON v.reportId = r.reportId WHERE v.doctorId = $doctorId AND v.patientId = $patientId AND v.date = '$date'";
        $visitRes = $conn->query($visitSql);
        if ($visitRes && $visitRes->num_rows > 0) {
            $visit = $visitRes->fetch_assoc();
            echo "<div class='card'>";
            echo "<h1>Visit Details</h1>";
            echo "<div class='visit-info'>";
            echo "<div class='info-group'><label>Doctor</label><p>" . htmlspecialchars($visit['doctorName']) . "</p></div>";
            echo "<div class='info-group'><label>Patient</label><p>" . htmlspecialchars($visit['patientName']) . "</p></div>";
            echo "<div class='info-group'><label>Date</label><p>" . date('F j, Y', strtotime($visit['date'])) . "</p></div>";
            echo "<div class='info-group'><label>Status</label><span class='badge badge-" . strtolower($visit['status']) . "'>" . htmlspecialchars($visit['status']) . "</span></div>";
            if ($visit['diagnosis']) {
                echo "<div class='info-group'><label>Diagnosis</label><p>" . htmlspecialchars($visit['diagnosis']) . "</p></div>";
                echo "<div class='info-group'><label>Billing</label><p class='billing'>$" . number_format($visit['billing'], 2) . "</p></div>";
            }
            echo "</div></div>";
        }
        // List lab tests
        echo "<div class='card'><h3>Laboratory Tests</h3>";
        $testSql = "SELECT t.name, t.result, l.name AS labName FROM test t JOIN laboratory l ON t.labId = l.LabId WHERE t.doctorId = $doctorId AND t.patientId = $patientId AND t.date = '$date'";
        $testRes = $conn->query($testSql);
        if ($testRes && $testRes->num_rows > 0) {
            echo "<div class='table-container'><table><thead><tr><th>Test Name</th><th>Result</th><th>Laboratory</th></tr></thead><tbody>";
            while ($test = $testRes->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($test['name']) . "</td><td>" . htmlspecialchars($test['result']) . "</td><td>" . htmlspecialchars($test['labName']) . "</td></tr>";
            }
            echo "</tbody></table></div>";
        } else {
            echo "<p>No laboratory tests recorded for this visit.</p>";
        }
        echo "</div>";
        // List prescriptions
        echo "<div class='card'><h3>Prescriptions</h3>";
        $presSql = "SELECT medicationName, dosageInstructions FROM prescription WHERE Test_doctorId = $doctorId AND Test_patientId = $patientId AND Test_date = '$date'";
        $presRes = $conn->query($presSql);
        if ($presRes && $presRes->num_rows > 0) {
            echo "<ul class='prescription-list'>";
            while ($pres = $presRes->fetch_assoc()) {
                echo "<li class='prescription-item'><div class='prescription-name'>" . htmlspecialchars($pres['medicationName']) . "</div><div class='prescription-instructions'>" . htmlspecialchars($pres['dosageInstructions']) . "</div></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No prescriptions issued during this visit.</p>";
        }
        echo "</div>";
        ?>
    </div>
</body>
</html>