<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Visit Details</title>
</head>
<body>
    <a href="home.php">Home</a> |
    <a href="javascript:history.back()">Back</a>
    <h1>Visit Details</h1>
    <?php
    if (!isset($_GET['doctorId']) || !isset($_GET['patientId']) || !isset($_GET['date'])) {
        echo "<p>Invalid visit.</p>";
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
        echo "<h2>Doctor: " . htmlspecialchars($visit['doctorName']) . "</h2>";
        echo "<h3>Patient: " . htmlspecialchars($visit['patientName']) . "</h3>";
        echo "<p>Date: " . htmlspecialchars($visit['date']) . "</p>";
        echo "<p>Status: " . htmlspecialchars($visit['status']) . "</p>";
        if ($visit['diagnosis']) {
            echo "<p>Diagnosis: " . htmlspecialchars($visit['diagnosis']) . "</p>";
            echo "<p>Billing: $" . htmlspecialchars($visit['billing']) . "</p>";
        }
    }
    // List lab tests
    echo "<h3>Lab Tests</h3><ul>";
    $testSql = "SELECT t.name, t.result, l.name AS labName FROM test t JOIN laboratory l ON t.labId = l.LabId WHERE t.doctorId = $doctorId AND t.patientId = $patientId AND t.date = '$date'";
    $testRes = $conn->query($testSql);
    while ($test = $testRes->fetch_assoc()) {
        echo "<li>Test: " . htmlspecialchars($test['name']) . ", Result: " . htmlspecialchars($test['result']) . ", Lab: " . htmlspecialchars($test['labName']) . "</li>";
    }
    echo "</ul>";
    // List prescriptions
    echo "<h3>Prescriptions</h3><ul>";
    $presSql = "SELECT medicationName, dosageInstructions FROM prescription WHERE Test_doctorId = $doctorId AND Test_patientId = $patientId AND Test_date = '$date'";
    $presRes = $conn->query($presSql);
    while ($pres = $presRes->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($pres['medicationName']) . " - " . htmlspecialchars($pres['dosageInstructions']) . "</li>";
    }
    echo "</ul>";
    ?>
</body>
</html> 