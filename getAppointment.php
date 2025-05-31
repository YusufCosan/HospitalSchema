<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Get New Appointment</title>
</head>
<body>
    <a href="home.php">Home</a> |
    <a href="javascript:history.back()">Back</a>
    <h1>Book a New Appointment</h1>
    <?php
    $patientId = isset($_GET['patientId']) ? intval($_GET['patientId']) : 0;
    $selectedDept = isset($_GET['departmentId']) ? intval($_GET['departmentId']) : 0;
    $selectedDoc = isset($_GET['doctorId']) ? intval($_GET['doctorId']) : 0;
    $step = 1;
    if ($selectedDept) $step = 2;
    if ($selectedDoc) $step = 3;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $doctorId = intval($_POST['doctorId']);
        $patientId = intval($_POST['patientId']);
        $date = $conn->real_escape_string($_POST['date']);
        $status = 'Ongoing';
        $sql = "INSERT INTO visit (doctorId, patientId, date, status) VALUES ($doctorId, $patientId, '$date', '$status')";
        if ($conn->query($sql)) {
            echo '<p>Appointment booked successfully!</p>';
        } else {
            echo '<p>Error booking appointment: ' . $conn->error . '</p>';
        }
        $step = 0;
    }
    if ($step === 1) {
        echo '<form method="get">';
        if ($patientId) echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
        echo 'Select Department: <select name="departmentId" onchange="this.form.submit()">';
        echo '<option value="">--Select--</option>';
        $deptRes = $conn->query("SELECT departmentId, name FROM department");
        while ($dept = $deptRes->fetch_assoc()) {
            $sel = ($selectedDept == $dept['departmentId']) ? 'selected' : '';
            echo '<option value="' . $dept['departmentId'] . '" ' . $sel . '>' . htmlspecialchars($dept['name']) . '</option>';
        }
        echo '</select></form>';
    } elseif ($step === 2) {
        echo '<form method="get">';
        if ($patientId) echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
        echo '<input type="hidden" name="departmentId" value="' . $selectedDept . '">';
        echo 'Select Doctor: <select name="doctorId" onchange="this.form.submit()">';
        echo '<option value="">--Select--</option>';
        $docRes = $conn->query("SELECT d.userId, u.fullName FROM doctor d JOIN user u ON d.userId = u.userId WHERE d.workingIn = $selectedDept");
        while ($doc = $docRes->fetch_assoc()) {
            $sel = ($selectedDoc == $doc['userId']) ? 'selected' : '';
            echo '<option value="' . $doc['userId'] . '" ' . $sel . '>' . htmlspecialchars($doc['fullName']) . '</option>';
        }
        echo '</select></form>';
    } elseif ($step === 3) {
        echo '<form method="post">';
        echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
        echo '<input type="hidden" name="doctorId" value="' . $selectedDoc . '">';
        echo 'Select Date: <input type="date" name="date" required> ';
        echo '<input type="submit" value="Book Appointment">';
        echo '</form>';
    }
    ?>
</body>
</html> 