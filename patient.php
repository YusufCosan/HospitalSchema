<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Medical History</title>
</head>
<body>
    <a href="home.php">Home</a>
    <h1>Patient Medical History</h1>
    <?php
    if (!isset($_GET['patientId'])) {
        echo '<form method="get">';
        echo 'Enter Patient ID: <input type="number" name="patientId" required>'; 
        echo '<input type="submit" value="View">';
        echo '</form>';
        exit;
    }
    $patientId = intval($_GET['patientId']);
    $patSql = "SELECT fullName FROM user WHERE userId = $patientId";
    $patRes = $conn->query($patSql);
    if ($patRes && $patRes->num_rows > 0) {
        $pat = $patRes->fetch_assoc();
        echo "<h2>" . htmlspecialchars($pat['fullName']) . "</h2>";
    }
    $visSql = "SELECT v.doctorId, v.date, u.fullName AS doctorName FROM visit v JOIN user u ON v.doctorId = u.userId WHERE v.patientId = $patientId ORDER BY v.date DESC";
    $visRes = $conn->query($visSql);
    echo "<h3>Visits</h3><ul>";
    while ($vis = $visRes->fetch_assoc()) {
        echo "<li><a href='visit.php?doctorId=" . $vis['doctorId'] . "&patientId=$patientId&date=" . $vis['date'] . "'>" . $vis['date'] . " - Dr. " . htmlspecialchars($vis['doctorName']) . "</a></li>";
    }
    echo "</ul>";
    ?>
    <a href="getAppointment.php?patientId=<?php echo $patientId; ?>">Get New Appointment</a>
</body>
</html> 