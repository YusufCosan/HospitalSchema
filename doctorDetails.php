<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Details</title>
</head>
<body>
    <a href="home.php">Home</a> |
    <a href="javascript:history.back()">Back</a>
    <h1>Doctor Details</h1>
    <?php
    if (!isset($_GET['doctorId'])) {
        echo "<p>No doctor selected.</p>";
        exit;
    }
    $doctorId = intval($_GET['doctorId']);
    // Get doctor info
    $docSql = "SELECT u.fullName FROM user u WHERE u.userId = $doctorId";
    $docRes = $conn->query($docSql);
    if ($docRes && $docRes->num_rows > 0) {
        $doc = $docRes->fetch_assoc();
        echo "<h2>" . htmlspecialchars($doc['fullName']) . "</h2>";
    }
    // Date filter
    $dateFilter = isset($_GET['date']) ? $_GET['date'] : '';
    echo '<form method="get">';
    echo '<input type="hidden" name="doctorId" value="' . $doctorId . '">';
    echo 'Filter by date: <input type="date" name="date" value="' . htmlspecialchars($dateFilter) . '">';
    echo '<input type="submit" value="Filter">';
    echo '</form>';
    // List appointments
    $apptSql = "SELECT v.date, v.patientId, u.fullName FROM visit v JOIN user u ON v.patientId = u.userId WHERE v.doctorId = $doctorId";
    if ($dateFilter) {
        $apptSql .= " AND v.date = '" . $conn->real_escape_string($dateFilter) . "'";
    }
    $apptSql .= " ORDER BY v.date DESC";
    $apptRes = $conn->query($apptSql);
    echo "<h3>Appointments</h3><ul>";
    while ($appt = $apptRes->fetch_assoc()) {
        echo "<li><a href='visit.php?doctorId=$doctorId&patientId=" . $appt['patientId'] . "&date=" . $appt['date'] . "'>" . $appt['date'] . " - " . htmlspecialchars($appt['fullName']) . "</a></li>";
    }
    echo "</ul>";
    ?>
</body>
</html> 