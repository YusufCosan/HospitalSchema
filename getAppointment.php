<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment - Hospital Management System</title>
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
        <div class="card">
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
                $status = 'Scheduled';
                $sql = "INSERT INTO visit (doctorId, patientId, date, status) VALUES ($doctorId, $patientId, '$date', '$status')";
                if ($conn->query($sql)) {
                    echo '<div class="alert alert-success">Appointment booked successfully!</div>';
                } else {
                    echo '<div class="alert alert-error">Error booking appointment: ' . $conn->error . '</div>';
                }
                $step = 0;
            }
            echo '<div class="appointment-form">';
            echo '<div class="progress-steps">';
            echo '<div class="step' . ($step >= 1 ? ' active' : '') . '">1. Select Department</div>';
            echo '<div class="step' . ($step >= 2 ? ' active' : '') . '">2. Choose Doctor</div>';
            echo '<div class="step' . ($step >= 3 ? ' active' : '') . '">3. Pick Date</div>';
            echo '</div>';
            if ($step === 1) {
                echo '<form method="get" class="form-group">';
                if ($patientId) echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
                echo '<label for="departmentId">Select Department</label>';
                echo '<select name="departmentId" id="departmentId" class="form-control" onchange="this.form.submit()">';
                echo '<option value="">--Select Department--</option>';
                $deptRes = $conn->query("SELECT departmentId, name FROM department");
                while ($dept = $deptRes->fetch_assoc()) {
                    $sel = ($selectedDept == $dept['departmentId']) ? 'selected' : '';
                    echo '<option value="' . $dept['departmentId'] . '" ' . $sel . '>' . htmlspecialchars($dept['name']) . '</option>';
                }
                echo '</select></form>';
            } elseif ($step === 2) {
                echo '<form method="get" class="form-group">';
                if ($patientId) echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
                echo '<input type="hidden" name="departmentId" value="' . $selectedDept . '">';
                echo '<label for="doctorId">Select Doctor</label>';
                echo '<select name="doctorId" id="doctorId" class="form-control" onchange="this.form.submit()">';
                echo '<option value="">--Select Doctor--</option>';
                $docRes = $conn->query("SELECT d.userId, u.fullName FROM doctor d JOIN user u ON d.userId = u.userId WHERE d.workingIn = $selectedDept");
                while ($doc = $docRes->fetch_assoc()) {
                    $sel = ($selectedDoc == $doc['userId']) ? 'selected' : '';
                    echo '<option value="' . $doc['userId'] . '" ' . $sel . '>' . htmlspecialchars($doc['fullName']) . '</option>';
                }
                echo '</select></form>';
            } elseif ($step === 3) {
                echo '<form method="post" class="form-group">';
                echo '<input type="hidden" name="patientId" value="' . $patientId . '">';
                echo '<input type="hidden" name="doctorId" value="' . $selectedDoc . '">';
                echo '<label for="date">Select Appointment Date</label>';
                echo '<input type="date" id="date" name="date" class="form-control" required min="' . date('Y-m-d') . '">';
                echo '<button type="submit" class="btn btn-primary" style="margin-top: 20px;">Book Appointment</button>';
                echo '</form>';
            }
            echo '</div>';
            ?>
        </div>
    </div>
</body>
</html>