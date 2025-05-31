<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Department Details</title>
</head>
<body>
    <a href="home.php">Home</a> |
    <a href="departments.php">Back</a>
    <h1>Department Details</h1>
    <?php
    if (!isset($_GET['departmentId'])) {
        echo "<p>No department selected.</p>";
        exit;
    }
    $departmentId = intval($_GET['departmentId']);
    // Get department info
    $deptSql = "SELECT name, specialty FROM department WHERE departmentId = $departmentId";
    $deptRes = $conn->query($deptSql);
    if ($deptRes && $deptRes->num_rows > 0) {
        $dept = $deptRes->fetch_assoc();
        echo "<h2>" . htmlspecialchars($dept['name']) . " (" . htmlspecialchars($dept['specialty']) . ")</h2>";
    }
    // List doctors
    echo "<h3>Doctors</h3><ul>";
    $docSql = "SELECT d.userId, u.fullName FROM doctor d JOIN user u ON d.userId = u.userId WHERE d.workingIn = $departmentId";
    $docRes = $conn->query($docSql);
    while ($doc = $docRes->fetch_assoc()) {
        echo "<li><a href='doctorDetails.php?doctorId=" . $doc['userId'] . "'>" . htmlspecialchars($doc['fullName']) . "</a></li>";
    }
    echo "</ul>";
    // List labs
    echo "<h3>Connected Laboratories</h3><ul>";
    $labSql = "SELECT LabId, name FROM laboratory WHERE connectedDept = $departmentId";
    $labRes = $conn->query($labSql);
    while ($lab = $labRes->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($lab['name']) . "</li>";
    }
    echo "</ul>";
    ?>
</body>
</html> 