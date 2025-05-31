<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
</head>
<body>
    <h1>Departments</h1>
    <a href="home.php">Home</a>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Specialty</th>
            <th>Medical Manager</th>
            <th>Admin Manager</th>
        </tr>
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
    </table>
</body>
</html> 