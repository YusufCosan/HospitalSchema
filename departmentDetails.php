<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Department Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <nav class="navigation">
            <a href="home.php">Home</a>
            <a href="departments.php">Back to Departments</a>
        </nav>
        <?php
        if (!isset($_GET['departmentId'])) {
            echo "<div class='section'><p>No department selected.</p></div>";
            exit;
        }
        $departmentId = intval($_GET['departmentId']);
        // Get department info
        $deptSql = "SELECT name, specialty FROM department WHERE departmentId = $departmentId";
        $deptRes = $conn->query($deptSql);
        if ($deptRes && $deptRes->num_rows > 0) {
            $dept = $deptRes->fetch_assoc();
            echo "<div class='department-info'>";
            echo "<h1>" . htmlspecialchars($dept['name']) . "</h1>";
            echo "<h2>Specialty: " . htmlspecialchars($dept['specialty']) . "</h2>";
            echo "</div>";
        }
        // List doctors
        echo "<div class='section'>";
        echo "<h3>Department Doctors</h3>";
        $docSql = "SELECT d.userId, u.fullName FROM doctor d JOIN user u ON d.userId = u.userId WHERE d.workingIn = $departmentId";
        $docRes = $conn->query($docSql);
        if ($docRes && $docRes->num_rows > 0) {
            echo "<ul class='doctors-list'>";
            while ($doc = $docRes->fetch_assoc()) {
                echo "<li><a href='doctorDetails.php?doctorId=" . $doc['userId'] . "'>" . htmlspecialchars($doc['fullName']) . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No doctors currently assigned to this department.</p>";
        }
        echo "</div>";
        // List labs
        echo "<div class='section'>";
        echo "<h3>Connected Laboratories</h3>";
        $labSql = "SELECT LabId, name FROM laboratory WHERE connectedDept = $departmentId";
        $labRes = $conn->query($labSql);
        if ($labRes && $labRes->num_rows > 0) {
            echo "<ul class='labs-list'>";
            while ($lab = $labRes->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($lab['name']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No laboratories connected to this department.</p>";
        }
        echo "</div>";
        ?>
    </div>
</body>
</html>