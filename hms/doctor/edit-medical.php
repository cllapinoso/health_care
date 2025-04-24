<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

$submissionSuccess = false; // Flag to indicate successful submission

// Fetch the record to edit
$labId = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM medical_checkup_lab_form WHERE id='$labId'");
$row = mysqli_fetch_array($query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $labName = $con->real_escape_string($_POST['labName']);
    $address = $con->real_escape_string($_POST['address']);
    $phone = $con->real_escape_string($_POST['phone']);
    $date = $con->real_escape_string($_POST['date']);
    $patientId = $con->real_escape_string($_POST['patientId']);
    $fullName = $con->real_escape_string($_POST['fullName']);
    $dob = $con->real_escape_string($_POST['dob']);
    $age = (int)$_POST['age']; // Cast to int
    $gender = $con->real_escape_string($_POST['gender']);
    $contactNumber = $con->real_escape_string($_POST['contactNumber']);
    $email = $con->real_escape_string($_POST['email']);
    $patientAddress = $con->real_escape_string($_POST['patientAddress']);
    $referredBy = isset($_POST['referredBy']) && $_POST['referredBy'] !== '' ? "'" . $con->real_escape_string($_POST['referredBy']) . "'" : "NULL"; // Nullable
    $familyPhysician = isset($_POST['familyPhysician']) && $_POST['familyPhysician'] !== '' ? "'" . $con->real_escape_string($_POST['familyPhysician']) . "'" : "NULL"; // Nullable
    $emergencyContact = isset($_POST['emergencyContact']) && $_POST['emergencyContact'] !== '' ? "'" . $con->real_escape_string($_POST['emergencyContact']) . "'" : "NULL"; // Nullable
    $relationship = isset($_POST['relationship']) && $_POST['relationship'] !== '' ? "'" . $con->real_escape_string($_POST['relationship']) . "'" : "NULL"; // Nullable
    $emergencyPhone = isset($_POST['emergencyPhone']) && $_POST['emergencyPhone'] !== '' ? "'" . $con->real_escape_string($_POST['emergencyPhone']) . "'" : "NULL"; // Nullable

    // Medical History
    $chronicConditions = isset($_POST['chronicConditions']) ? $_POST['chronicConditions'] : [];
    if (in_array('Others', $chronicConditions) && !empty($_POST['chronicConditionsOthers'])) {
        $othersInput = $con->real_escape_string($_POST['chronicConditionsOthers']);
        $chronicConditions = "'" . implode(", ", $chronicConditions) . ", $othersInput'" ;
    } else {
        $chronicConditions = "'" . implode(", ", $chronicConditions) . "'";
    }

    $previousSurgeries = isset($_POST['previousSurgeries']) && $_POST['previousSurgeries'] !== '' ? "'" . $con->real_escape_string($_POST['previousSurgeries']) . "'" : "NULL"; // Nullable
    $allergies = isset($_POST['allergies']) && $_POST['allergies'] !== '' ? "'" . $con->real_escape_string($_POST['allergies']) . "'" : "NULL"; // Nullable
    $currentMedications = isset($_POST['currentMedications']) && $_POST['currentMedications'] !== '' ? "'" . $con->real_escape_string($_POST['currentMedications']) . "'" : "NULL"; // Nullable
    $familyMedicalHistory = isset($_POST['familyMedicalHistory']) && $_POST['familyMedicalHistory'] !== '' ? "'" . $con->real_escape_string($_POST['familyMedicalHistory']) . "'" : "NULL"; // Nullable

    // Vital Signs
    $bloodPressure = isset($_POST['bloodPressure']) && $_POST['bloodPressure'] !== '' ? "'" . $con->real_escape_string($_POST['bloodPressure']) . "'" : "NULL"; // Nullable
    $heartRate = isset($_POST['heartRate']) && $_POST['heartRate'] !== '' ? "'" . $con->real_escape_string($_POST['heartRate']) . "'" : "NULL"; // Nullable
    $respiratoryRate = isset($_POST['respiratoryRate']) && $_POST['respiratoryRate'] !== '' ? "'" . $con->real_escape_string($_POST['respiratoryRate']) . "'" : "NULL"; // Nullable
    $temperature = isset($_POST['temperature']) && $_POST['temperature'] !== '' ? "'" . $con->real_escape_string($_POST['temperature']) . "'" : "NULL"; // Nullable
    $height = isset($_POST['height']) && $_POST['height'] !== '' ? "'" . $con->real_escape_string($_POST['height']) . "'" : "NULL"; // Nullable
    $weight = isset($_POST['weight']) && $_POST['weight'] !== '' ? "'" . $con->real_escape_string($_POST['weight']) . "'" : "NULL"; // Nullable
    $bmi = isset($_POST['bmi']) && $_POST['bmi'] !== '' ? "'" . $con->real_escape_string($_POST['bmi']) . "'" : "NULL"; // Nullable

    // Lab Tests Requested
    $bloodTests = isset($_POST['bloodTests']) ? $_POST['bloodTests'] : [];
    if (in_array('Others', $bloodTests) && !empty($_POST['othersBlood'])) {
        $othersBloodInput = $con->real_escape_string($_POST['othersBlood']);
        $bloodTests = "'" . implode(", ", $bloodTests) . ", $othersBloodInput'" ;
    } else {
        $bloodTests = "'" . implode(", ", $bloodTests) . "'";
    }

    $urineTests = isset($_POST['urineTests']) ? $_POST['urineTests'] : [];
    if (in_array('Others', $urineTests) && !empty($_POST['othersUrine'])) {
        $othersUrineInput = $con->real_escape_string($_POST['othersUrine']);
        $urineTests = "'" . implode(", ", $urineTests) . ", $othersUrineInput'" ;
    } else {
        $urineTests = "'" . implode(", ", $urineTests) . "'";
    }

    $imagingTests = isset($_POST['imagingTests']) ? $_POST['imagingTests'] : [];
    if (in_array('Others', $imagingTests) && !empty($_POST['othersImaging'])) {
        $othersImagingInput = $con->real_escape_string($_POST['othersImaging']);
        $imagingTests = "'" . implode(", ", $imagingTests) . ", $othersImagingInput'" ;
    } else {
        $imagingTests = "'" . implode(", ", $imagingTests) . "'";
    }

    $diseaseScreening = isset($_POST['diseaseScreening']) ? $_POST['diseaseScreening'] : [];
    if (in_array('Others', $diseaseScreening) && !empty($_POST['othersDisease'])) {
        $othersDiseaseInput = $con->real_escape_string($_POST['othersDisease']);
        $diseaseScreening = "'" . implode(", ", $diseaseScreening) . ", $othersDiseaseInput'" ;
    } else {
        $diseaseScreening = "'" . implode(", ", $diseaseScreening) . "'";
    }

    $otherTests = isset($_POST['otherTests']) && $_POST['otherTests'] !== '' ? "'" . $con->real_escape_string($_POST['otherTests']) . "'" : "NULL"; // Nullable

    // Prepare the SQL statement
    $sql = "UPDATE medical_checkup_lab_form SET 
        labName='$labName', 
        address='$address', 
        phone='$phone', 
        date='$date', 
        patientId='$patientId', 
        fullName='$fullName', 
        dob='$dob', 
        age=$age, 
        gender='$gender', 
        contactNumber='$contactNumber', 
        email='$email', 
        patientAddress='$patientAddress', 
        referredBy=$referredBy, 
        familyPhysician=$familyPhysician, 
        emergencyContact=$emergencyContact, 
        relationship=$relationship, 
        emergencyPhone=$emergencyPhone, 
        chronicConditions=$chronicConditions, 
        previousSurgeries=$previousSurgeries, 
        allergies=$allergies, 
        currentMedications=$currentMedications, 
        familyMedicalHistory=$familyMedicalHistory, 
        bloodPressure=$bloodPressure, 
        heartRate=$heartRate, 
        respiratoryRate=$respiratoryRate, 
        temperature=$temperature, 
        height=$height, 
        weight=$weight, 
        bmi=$bmi, 
        bloodTests=$bloodTests, 
        urineTests=$urineTests, 
        imagingTests=$imagingTests, 
        diseaseScreening=$diseaseScreening, 
        otherTests=$otherTests 
        WHERE id='$labId'";

    // Execute the SQL statement
    if ($con->query($sql) === TRUE) {
        $submissionSuccess = true; // Set success flag
        echo "<script>
                alert('Entry updated successfully!');
                window.location.href = 'manage-medical-checkup.php';
            </script>";
    } else {
        echo "<script>alert('Error: " . $con->error . "');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Check-Up Laboratory Form</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .container h1 {
            text-align: center;
        }

        .success-message {
            display: none;
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
        }

        .others-input {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleOthersInput(selectElement, othersInputId) {
            const othersInput = document.getElementById(othersInputId);
            othersInput.style.display = selectElement.value === 'Others' ? 'block' : 'none';
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                document.getElementById('successMessage').style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit Medical Check-Up Laboratory Form</h1>

        <form id="medicalForm" action="" method="POST">
            <h2>Laboratory Information</h2>
            <label for="labName">Laboratory Name:</label>
            <input type="text" id="labName" name="labName" value="<?php echo $row['labName']; ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $row['address']; ?>" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $row['phone']; ?>" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $row['date']; ?>" required>

            <label for="patientId">Patient ID:</label>
            <input type="text" id="patientId" name="patientId" value="<?php echo $row['patientId']; ?>" required>

            <h2>Patient Information</h2>
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo $row['fullName']; ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo $row['dob']; ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $row['age']; ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Female" <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Male" <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Other" <?php echo ($row['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" value="<?php echo $row['contactNumber']; ?>" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>

            <label for="patientAddress">Address:</label>
            <input type="text" id="patientAddress" name="patientAddress" value="<?php echo $row['patientAddress']; ?>" required>

            <label for="referredBy">Referred By:</label>
            <input type="text" id="referredBy" name="referredBy" value="<?php echo $row['referredBy']; ?>">

            <label for="familyPhysician">Family Physician:</label>
            <input type="text" id="familyPhysician" name="familyPhysician" value="<?php echo $row['familyPhysician']; ?>">

            <label for="emergencyContact">Emergency Contact:</label>
            <input type="text" id="emergencyContact" name="emergencyContact" value="<?php echo $row['emergencyContact']; ?>">

            <label for="relationship">Relationship:</label>
            <input type="text" id="relationship" name="relationship" value="<?php echo $row['relationship']; ?>">

            <label for="emergencyPhone">Emergency Phone:</label>
            <input type="tel" id="emergencyPhone" name="emergencyPhone" value="<?php echo $row['emergencyPhone']; ?>">

            <h2>Medical History</h2>
            <label>Chronic Conditions (Check all that apply):</label>
            <?php 
            $chronicConditions = explode(", ", $row['chronicConditions']);
            ?>
            <input type="checkbox" name="chronicConditions[]" value="Hypertension" <?php echo in_array('Hypertension', $chronicConditions) ? 'checked' : ''; ?>> Hypertension<br>
            <input type="checkbox" name="chronicConditions[]" value="Diabetes" <?php echo in_array('Diabetes', $chronicConditions) ? 'checked' : ''; ?>> Diabetes<br>
            <input type="checkbox" name="chronicConditions[]" value="Heart Disease" <?php echo in_array('Heart Disease', $chronicConditions) ? 'checked' : ''; ?>> Heart Disease<br>
            <input type="checkbox" name="chronicConditions[]" value="Asthma" <?php echo in_array('Asthma', $chronicConditions) ? 'checked' : ''; ?>> Asthma<br>
            <input type="checkbox" name="chronicConditions[]" value="Kidney Disease" <?php echo in_array('Kidney Disease', $chronicConditions) ? 'checked' : ''; ?>> Kidney Disease<br>
            <input type="checkbox" name="chronicConditions[]" value="Others" <?php echo in_array('Others', $chronicConditions) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="chronicConditionsOthers" value="<?php echo in_array('Others', $chronicConditions) ? trim(str_replace('Others,', '', $row['chronicConditions'])) : ''; ?>"><br>

            <label for="previousSurgeries">Previous Surgeries:</label>
            <input type="text" id="previousSurgeries" name="previousSurgeries" value="<?php echo $row['previousSurgeries']; ?>">

            <label for="allergies">Allergies (medications, foods, etc.):</label>
            <input type="text" id="allergies" name="allergies" value="<?php echo $row['allergies']; ?>">

            <label for="currentMedications">Current Medications:</label>
            <input type="text" id="currentMedications" name="currentMedications" value="<?php echo $row['currentMedications']; ?>">

            <label for="familyMedicalHistory">Family Medical History:</label>
            <textarea id="familyMedicalHistory" name="familyMedicalHistory"><?php echo $row['familyMedicalHistory']; ?></textarea>

            <h2>Vital Signs</h2>
            <label for="bloodPressure">Blood Pressure:</label>
            <input type="text" id="bloodPressure" name="bloodPressure" value="<?php echo $row['bloodPressure']; ?>" required>

            <label for="heartRate">Heart Rate:</label>
            <input type="text" id="heartRate" name="heartRate" value="<?php echo $row['heartRate']; ?>" required>

            <label for="respiratoryRate">Respiratory Rate:</label>
            <input type="text" id="respiratoryRate" name="respiratoryRate" value="<?php echo $row['respiratoryRate']; ?>" required>

            <label for="temperature">Temperature:</label>
            <input type="text" id="temperature" name="temperature" value="<?php echo $row['temperature']; ?>" required>

            <label for="height">Height:</label>
            <input type="text" id="height" name="height" value="<?php echo $row['height']; ?>" required>

            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight" value="<?php echo $row['weight']; ?>" required>

            <label for="bmi">BMI:</label>
            <input type="text" id="bmi" name="bmi" value="<?php echo $row['bmi']; ?>">

            <h2>Medical Check-Up Lab Tests Requested</h2>
            <label for="bloodTests">Blood Work:</label>
            <select id="bloodTests" name="bloodTests[]" onchange="toggleOthersInput(this, 'othersBloodInput')" required>
                <option value="">Select</option>
                <option value="CBC" <?php echo (strpos($row['bloodTests'], 'CBC') !== false) ? 'selected' : ''; ?>>Complete Blood Count (CBC)</option>
                <option value="Blood Chemistry" <?php echo (strpos($row['bloodTests'], 'Blood Chemistry') !== false) ? 'selected' : ''; ?>>Blood Chemistry</option>
                <option value="Lipid Profile" <?php echo (strpos($row['bloodTests'], 'Lipid Profile') !== false) ? 'selected' : ''; ?>>Lipid Profile</option>
                <option value="Liver Function Test" <?php echo (strpos($row['bloodTests'], 'Liver Function Test') !== false) ? 'selected' : ''; ?>>Liver Function Test</option>
                <option value="Kidney Function Test" <?php echo (strpos($row['bloodTests'], 'Kidney Function Test') !== false) ? 'selected' : ''; ?>>Kidney Function Test</option>
                <option value="Thyroid Panel" <?php echo (strpos($row['bloodTests'], 'Thyroid Panel') !== false) ? 'selected' : ''; ?>>Thyroid Panel</option>
                <option value="Vitamin D" <?php echo (strpos($row['bloodTests'], 'Vitamin D') !== false) ? 'selected' : ''; ?>>Vitamin D</option>
                <option value="HIV Screening" <?php echo (strpos($row['bloodTests'], 'HIV Screening') !== false) ? 'selected' : ''; ?>>HIV Screening</option>
                <option value="Hepatitis B & C" <?php echo (strpos($row['bloodTests'], 'Hepatitis B & C') !== false) ? 'selected' : ''; ?>>Hepatitis B & C</option>
                <option value="Syphilis Test" <?php echo (strpos($row['bloodTests'], 'Syphilis Test') !== false) ? 'selected' : ''; ?>>Syphilis Test</option>
                <option value="Others" <?php echo (strpos($row['bloodTests'], 'Others') !== false) ? 'selected' : ''; ?>>Others</option>
            </select>
            <input type="text" id="othersBloodInput" name="othersBlood" class="others-input" placeholder="Please specify" value="<?php echo (strpos($row['bloodTests'], 'Others') !== false) ? trim(str_replace('Others,', '', $row['bloodTests'])) : ''; ?>" onfocus="this.value=''" style="display:none;">

            <label for="urineTests">Urine Tests:</label>
            <select id="urineTests" name="urineTests[]" onchange="toggleOthersInput(this, 'othersUrineInput')" required>
                <option value="">Select</option>
                <option value="Urinalysis" <?php echo (strpos($row['urineTests'], 'Urinalysis') !== false) ? 'selected' : ''; ?>>Urinalysis</option>
                <option value="Stool Examination" <?php echo (strpos($row['urineTests'], 'Stool Examination') !== false) ? 'selected' : ''; ?>>Stool Examination</option>
                <option value="Urine Culture" <?php echo (strpos($row['urineTests'], 'Urine Culture') !== false) ? 'selected' : ''; ?>>Urine Culture</option>
                <option value="Others" <?php echo (strpos($row['urineTests'], 'Others') !== false) ? 'selected' : ''; ?>>Others</option>
            </select>
            <input type="text" id="othersUrineInput" name="othersUrine" class="others-input" placeholder="Please specify" value="<?php echo (strpos($row['urineTests'], 'Others') !== false) ? trim(str_replace('Others,', '', $row['urineTests'])) : ''; ?>" onfocus="this.value=''" style="display:none;">

            <label for="imagingTests">Imaging Tests:</label>
            <select id="imagingTests" name="imagingTests[]" onchange="toggleOthersInput(this, 'othersImagingInput')" required>
                <option value="">Select</option>
                <option value="Chest X-Ray" <?php echo (strpos($row['imagingTests'], 'Chest X-Ray') !== false) ? 'selected' : ''; ?>>Chest X-Ray</option>
                <option value="Ultrasound" <?php echo (strpos($row['imagingTests'], 'Ultrasound') !== false) ? 'selected' : ''; ?>>Ultrasound</option>
                <option value="Mammogram" <?php echo (strpos($row['imagingTests'], 'Mammogram') !== false) ? 'selected' : ''; ?>>Mammogram</option>
                <option value="Bone Density Scan" <?php echo (strpos($row['imagingTests'], 'Bone Density Scan') !== false) ? 'selected' : ''; ?>>Bone Density Scan</option>
                <option value="ECG" <?php echo (strpos($row['imagingTests'], 'ECG') !== false) ? 'selected' : ''; ?>>ECG</option>
                <option value="Others" <?php echo (strpos($row['imagingTests'], 'Others') !== false) ? 'selected' : ''; ?>>Others</option>
            </select>
            <input type="text" id="othersImagingInput" name="othersImaging" class="others-input" placeholder="Please specify" value="<?php echo (strpos($row['imagingTests'], 'Others') !== false) ? trim(str_replace('Others,', '', $row['imagingTests'])) : ''; ?>" onfocus="this.value=''" style="display:none;">

            <label for="diseaseScreening">Infectious Disease Screening:</label>
            <select id="diseaseScreening" name="diseaseScreening[]" onchange="toggleOthersInput(this, 'othersDiseaseInput')" required>
                <option value="">Select</option>
                <option value="Tuberculosis" <?php echo (strpos($row['diseaseScreening'], 'Tuberculosis') !== false) ? 'selected' : ''; ?>>Tuberculosis</option>
                <option value="Malaria" <?php echo (strpos($row['diseaseScreening'], 'Malaria') !== false) ? 'selected' : ''; ?>>Malaria</option>
                <option value="Dengue" <?php echo (strpos($row['diseaseScreening'], 'Dengue') !== false) ? 'selected' : ''; ?>>Dengue</option>
                <option value="Others" <?php echo (strpos($row['diseaseScreening'], 'Others') !== false) ? 'selected' : ''; ?>>Others</option>
            </select>
            <input type="text" id="othersDiseaseInput" name="othersDisease" class="others-input" placeholder="Please specify" value="<?php echo (strpos($row['diseaseScreening'], 'Others') !== false) ? trim(str_replace('Others,', '', $row['diseaseScreening'])) : ''; ?>" onfocus="this.value=''" style="display:none;">

            <label for="otherTests">Other Tests/Notes:</label>
            <textarea id="otherTests" name="otherTests"><?php echo $row['otherTests']; ?></textarea>

            <button type="submit">Update</button>
            <a href="manage-medical-checkup.php" class="btn btn-secondary" style="margin-left: 10px;">Back</a>
            </form>
            </div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

