<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

$submissionSuccess = false; // Flag to indicate successful submission
$labId = $_GET['id'] ?? null;

if (!$labId) {
    header('Location: manage-family-planning.php'); // Redirect if no ID is provided
    exit;
}

// Fetch existing data for the lab ID
$query = "SELECT * FROM family_planning_lab_form WHERE id = '$labId'";
$result = $con->query($query);
$data = $result->fetch_assoc();

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
    $partnerName = $con->real_escape_string($_POST['partnerName']);
    $partnerContact = $con->real_escape_string($_POST['partnerContact']);
    $referredBy = isset($_POST['referredBy']) && $_POST['referredBy'] !== '' ? "'" . $con->real_escape_string($_POST['referredBy']) . "'" : "NULL"; // Nullable
    $consultationDate = isset($_POST['consultationDate']) ? "'" . $con->real_escape_string($_POST['consultationDate']) . "'" : "NULL";
    $numberOfChildren = (int)$_POST['numberOfChildren'];
    $currentlyPregnant = isset($_POST['currentlyPregnant']) ? "'" . $con->real_escape_string($_POST['currentlyPregnant']) . "'" : "NULL";
    $previousPregnancies = $con->real_escape_string($_POST['previousPregnancies']);
    $historyOfReproductiveIssues = $con->real_escape_string($_POST['historyOfReproductiveIssues']);
    $currentContraceptiveMethod = $con->real_escape_string($_POST['currentContraceptiveMethod']);
    $lastMenstrualPeriod = isset($_POST['lastMenstrualPeriod']) ? "'" . $con->real_escape_string($_POST['lastMenstrualPeriod']) . "'" : "NULL";
    $menstrualCycle = $con->real_escape_string($_POST['menstrualCycle']);

    // Lab Tests Requested
    $bloodTests = isset($_POST['bloodTests']) ? implode(", ", $_POST['bloodTests']) : '';
    if (in_array('Others', $_POST['bloodTests'])) {
        $bloodTests .= ', ' . $con->real_escape_string($_POST['othersBlood']);
    } else {
        // Clear the othersBlood input if 'Others' is not checked
        $_POST['othersBlood'] = '';
    }

    $urineTests = isset($_POST['urineTests']) ? implode(", ", $_POST['urineTests']) : '';
    if (in_array('Others', $_POST['urineTests'])) {
        $urineTests .= ', ' . $con->real_escape_string($_POST['othersUrine']);
    } else {
        // Clear the othersUrine input if 'Others' is not checked
        $_POST['othersUrine'] = '';
    }

    $diseaseScreening = isset($_POST['diseaseScreening']) ? implode(", ", $_POST['diseaseScreening']) : '';
    if (in_array('Others', $_POST['diseaseScreening'])) {
        $diseaseScreening .= ', ' . $con->real_escape_string($_POST['othersDisease']);
    } else {
        // Clear the othersDisease input if 'Others' is not checked
        $_POST['othersDisease'] = '';
    }

    $fertilityAssessment = isset($_POST['fertilityAssessment']) ? implode(", ", $_POST['fertilityAssessment']) : '';
    if (in_array('Others', $_POST['fertilityAssessment'])) {
        $fertilityAssessment .= ', ' . $con->real_escape_string($_POST['othersFertility']);
    } else {
        // Clear the othersFertility input if 'Others' is not checked
        $_POST['othersFertility'] = '';
    }

    $contraceptiveTests = isset($_POST['contraceptiveTests']) ? implode(", ", $_POST['contraceptiveTests']) : '';
    if (in_array('Others', $_POST['contraceptiveTests'])) {
        $contraceptiveTests .= ', ' . $con->real_escape_string($_POST['othersContraceptive']);
    } else {
        // Clear the othersContraceptive input if 'Others' is not checked
        $_POST['othersContraceptive'] = '';
    }

    $geneticScreening = isset($_POST['geneticScreening']) ? implode(", ", $_POST['geneticScreening']) : '';
    if (in_array('Others', $_POST['geneticScreening'])) {
        $geneticScreening .= ', ' . $con->real_escape_string($_POST['othersGenetic']);
    } else {
        // Clear the othersGenetic input if 'Others' is not checked
        $_POST['othersGenetic'] = '';
    }

    $otherTests = $con->real_escape_string($_POST['otherTests']);
    $physicianNotes = $con->real_escape_string($_POST['physicianNotes']);

    // Prepare the SQL statement for update
    $sql = "UPDATE family_planning_lab_form SET 
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
        partnerName='$partnerName', 
        partnerContact='$partnerContact', 
        referredBy=$referredBy, 
        consultationDate=$consultationDate, 
        numberOfChildren=$numberOfChildren, 
        currentlyPregnant=$currentlyPregnant, 
        previousPregnancies='$previousPregnancies', 
        historyOfReproductiveIssues='$historyOfReproductiveIssues', 
        currentContraceptiveMethod='$currentContraceptiveMethod', 
        lastMenstrualPeriod=$lastMenstrualPeriod, 
        menstrualCycle='$menstrualCycle', 
        bloodTests='$bloodTests', 
        urineTests='$urineTests', 
        diseaseScreening='$diseaseScreening', 
        fertilityAssessment='$fertilityAssessment', 
        contraceptiveTests='$contraceptiveTests', 
        geneticScreening='$geneticScreening', 
        otherTests='$otherTests', 
        physicianNotes='$physicianNotes' 
        WHERE id='$labId'";

    // Execute the SQL statement
    if ($con->query($sql) === TRUE) {
        $submissionSuccess = true; // Set success flag
        echo "<script>
                alert('Entry updated successfully!');
                window.location.href = 'manage-family-planning.php';
            </script>";
        echo "<script>document.getElementById('medicalForm').reset();</script>"; // Clear the form fields
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
    <title>Edit Family Planning Laboratory Form</title>
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
        <h1>Edit Family Planning Laboratory Form</h1>

        <form id="medicalForm" action="" method="POST">
            <h2>Laboratory Information</h2>
            <label for="labName">Laboratory Name:</label>
            <input type="text" id="labName" name="labName" value="<?php echo htmlentities($data['labName']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlentities($data['address']); ?>" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlentities($data['phone']); ?>" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlentities($data['date']); ?>" required>

            <label for="patientId">Patient ID:</label>
            <input type="text" id="patientId" name="patientId" value="<?php echo htmlentities($data['patientId']); ?>" required>

            <h2>Patient Information</h2>
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlentities($data['fullName']); ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlentities($data['dob']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlentities($data['age']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Female" <?php echo ($data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Male" <?php echo ($data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Other" <?php echo ($data['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" value="<?php echo htmlentities($data['contactNumber']); ?>" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlentities($data['email']); ?>" required>

            <label for="patientAddress">Address:</label>
            <input type="text" id="patientAddress" name="patientAddress" value="<?php echo htmlentities($data['patientAddress']); ?>" required>

            <label for="partnerName">Partner’s Name:</label>
            <input type="text" id="partnerName" name="partnerName" value="<?php echo htmlentities($data['partnerName']); ?>">

            <label for="partnerContact">Partner’s Contact Information:</label>
            <input type="tel" id="partnerContact" name="partnerContact" value="<?php echo htmlentities($data['partnerContact']); ?>">

            <label for="referredBy">Referred By:</label>
            <input type="text" id="referredBy" name="referredBy" value="<?php echo htmlentities($data['referredBy']); ?>">

            <label for="consultationDate">Consultation Date:</label>
            <input type="date" id="consultationDate" name="consultationDate" value="<?php echo htmlentities($data['consultationDate']); ?>">

            <h2>Health History</h2>
            <label for="numberOfChildren">Number of Children:</label>
            <input type="number" id="numberOfChildren" name="numberOfChildren" value="<?php echo htmlentities($data['numberOfChildren']); ?>" required>

            <label>Currently Pregnant?</label>
            <select id="currentlyPregnant" name="currentlyPregnant">
                <option value="No" <?php echo ($data['currentlyPregnant'] == 'No') ? 'selected' : ''; ?>>No</option>
                <option value="Yes" <?php echo ($data['currentlyPregnant'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            </select>

            <label for="previousPregnancies">Previous Pregnancies/Miscarriages:</label>
            <input type="text" id="previousPregnancies" name="previousPregnancies" value="<?php echo htmlentities($data['previousPregnancies']); ?>">

            <label for="historyOfReproductiveIssues">History of Reproductive Issues:</label>
            <input type="text" id="historyOfReproductiveIssues" name="historyOfReproductiveIssues" value="<?php echo htmlentities($data['historyOfReproductiveIssues']); ?>">

            <label for="currentContraceptiveMethod">Current Contraceptive Method:</label>
            <input type="text" id="currentContraceptiveMethod" name="currentContraceptiveMethod" value="<?php echo htmlentities($data['currentContraceptiveMethod']); ?>">

            <label for="lastMenstrualPeriod">Last Menstrual Period (LMP):</label>
            <input type="date" id="lastMenstrualPeriod" name="lastMenstrualPeriod" value="<?php echo htmlentities($data['lastMenstrualPeriod']); ?>">

            <label for="menstrualCycle">Menstrual Cycle:</label>
            <select id="menstrualCycle" name="menstrualCycle">
                <option value="Regular" <?php echo ($data['menstrualCycle'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                <option value="Irregular" <?php echo ($data['menstrualCycle'] == 'Irregular') ? 'selected' : ''; ?>>Irregular</option>
            </select>

            <h2>Family Planning Lab Tests Requested</h2>
            <label>Blood Work:</label>
            <input type="checkbox" name="bloodTests[]" value="CBC" <?php echo (strpos($data['bloodTests'], 'CBC') !== false) ? 'checked' : ''; ?>> Complete Blood Count (CBC)<br>
            <input type="checkbox" name="bloodTests[]" value="Blood Type & Rh Factor" <?php echo (strpos($data['bloodTests'], 'Blood Type & Rh Factor') !== false) ? 'checked' : ''; ?>> Blood Type & Rh Factor<br>
            <input type="checkbox" name="bloodTests[]" value="Hormone Testing" <?php echo (strpos($data['bloodTests'], 'Hormone Testing') !== false) ? 'checked' : ''; ?>> Hormone Testing (FSH, LH, Estradiol, Progesterone, etc.)<br>
            <input type="checkbox" name="bloodTests[]" value="Thyroid Panel" <?php echo (strpos($data['bloodTests'], 'Thyroid Panel') !== false) ? 'checked' : ''; ?>> Thyroid Panel (TSH, Free T3, Free T4)<br>
            <input type="checkbox" name="bloodTests[]" value="Prolactin" <?php echo (strpos($data['bloodTests'], 'Prolactin') !== false) ? 'checked' : ''; ?>> Prolactin<br>            
            <input type="checkbox" name="bloodTests[]" value="Blood Glucose" <?php echo (strpos($data['bloodTests'], 'Blood Glucose') !== false) ? 'checked' : ''; ?>> Blood Glucose<br>
            <input type="checkbox" name="bloodTests[]" value="Hepatitis B & C" <?php echo (strpos($data['bloodTests'], 'Hepatitis B & C') !== false) ? 'checked' : ''; ?>> Hepatitis B & C<br>
            <input type="checkbox" name="bloodTests[]" value="HIV Screening" <?php echo (strpos($data['bloodTests'], 'HIV Screening') !== false) ? 'checked' : ''; ?>> HIV Screening<br>
            <input type="checkbox" name="bloodTests[]" value="Syphilis Test" <?php echo (strpos($data['bloodTests'], 'Syphilis Test') !== false) ? 'checked' : ''; ?>> Syphilis Test<br>
            <input type="checkbox" name="bloodTests[]" value="Others" <?php echo (strpos($data['bloodTests'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersBlood" value="<?php echo (strpos($data['bloodTests'], 'Others') !== false) ? htmlentities(explode(', ', $data['bloodTests'])[count(explode(', ', $data['bloodTests'])) - 1] ?? '') : ''; ?>"><br>

            <label>Urine Tests:</label>
            <input type="checkbox" name="urineTests[]" value="Urinalysis" <?php echo (strpos($data['urineTests'], 'Urinalysis') !== false) ? 'checked' : ''; ?>> Urinalysis<br>
            <input type="checkbox" name="urineTests[]" value="Pregnancy Test" <?php echo (strpos($data['urineTests'], 'Pregnancy Test') !== false) ? 'checked' : ''; ?>> Pregnancy Test<br>
            <input type="checkbox" name="urineTests[]" value="Proteinuria" <?php echo (strpos($data['urineTests'], 'Proteinuria') !== false) ? 'checked' : ''; ?>> Proteinuria<br>
            <input type="checkbox" name="urineTests[]" value="Urine Culture" <?php echo (strpos($data['urineTests'], 'Urine Culture') !== false) ? 'checked' : ''; ?>> Urine Culture (if needed)<br>
            <input type="checkbox" name="urineTests[]" value="Others" <?php echo (strpos($data['urineTests'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersUrine" value="<?php echo (strpos($data['urineTests'], 'Others') !== false) ? htmlentities(explode(', ', $data['urineTests'])[count(explode(', ', $data['urineTests'])) - 1] ?? '') : ''; ?>"><br>

            <label>Infectious Disease Screening:</label>
            <input type="checkbox" name="diseaseScreening[]" value="Chlamydia" <?php echo (strpos($data['diseaseScreening'], 'Chlamydia') !== false) ? 'checked' : ''; ?>> Chlamydia<br>
            <input type="checkbox" name="diseaseScreening[]" value="Gonorrhea" <?php echo (strpos($data['diseaseScreening'], 'Gonorrhea') !== false) ? 'checked' : ''; ?>> Gonorrhea<br>
            <input type="checkbox" name="diseaseScreening[]" value="HPV Test" <?php echo (strpos($data['diseaseScreening'], 'HPV Test') !== false) ? 'checked' : ''; ?>> HPV Test<br>
            <input type="checkbox" name="diseaseScreening[]" value="Others" <?php echo (strpos($data['diseaseScreening'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersDisease" value="<?php echo (strpos($data['diseaseScreening'], 'Others') !== false) ? htmlentities(explode(', ', $data['diseaseScreening'])[count(explode(', ', $data['diseaseScreening'])) - 1] ?? '') : ''; ?>"><br>

            <label>Fertility Assessment (if applicable):</label>
            <input type="checkbox" name="fertilityAssessment[]" value="Semen Analysis" <?php echo (strpos($data['fertilityAssessment'], 'Semen Analysis') !== false) ? 'checked' : ''; ?>> Semen Analysis (for male partners)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Ovulation Test" <?php echo (strpos($data['fertilityAssessment'], 'Ovulation Test') !== false) ? 'checked' : ''; ?>> Ovulation Test (Luteinizing Hormone)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Anti-Müllerian Hormone" <?php echo (strpos($data['fertilityAssessment'], 'Anti-Müllerian Hormone') !== false) ? 'checked' : ''; ?>> Anti-Müllerian Hormone (AMH)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Others" <?php echo (strpos($data['fertilityAssessment'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersFertility" value="<?php echo (strpos($data['fertilityAssessment'], 'Others') !== false) ? htmlentities(explode(', ', $data['fertilityAssessment'])[count(explode(', ', $data['fertilityAssessment'])) - 1] ?? '') : ''; ?>"><br>

            <label>Contraceptive-related Tests:</label>
            <input type="checkbox" name="contraceptiveTests[]" value="Pap Smear" <?php echo (strpos($data['contraceptiveTests'], 'Pap Smear') !== false) ? 'checked' : ''; ?>> Pap Smear (Cervical Screening)<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Breast Ultrasound" <?php echo (strpos($data['contraceptiveTests'], 'Breast Ultrasound') !== false) ? 'checked' : ''; ?>> Breast Ultrasound/Mammogram<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Pelvic Ultrasound" <?php echo (strpos($data['contraceptiveTests'], 'Pelvic Ultrasound') !== false) ? 'checked' : ''; ?>> Pelvic Ultrasound<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Others" <?php echo (strpos($data['contraceptiveTests'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersContraceptive" value="<?php echo (strpos($data['contraceptiveTests'], 'Others') !== false) ? htmlentities(explode(', ', $data['contraceptiveTests'])[count(explode(', ', $data['contraceptiveTests'])) - 1] ?? '') : ''; ?>"><br>

            <label>Genetic Screening (Optional):</label>
            <input type="checkbox" name="geneticScreening[]" value="Genetic Carrier Screening" <?php echo (strpos($data['geneticScreening'], 'Genetic Carrier Screening') !== false) ? 'checked' : ''; ?>> Genetic Carrier Screening<br>
            <input type="checkbox" name="geneticScreening[]" value="Cystic Fibrosis" <?php echo (strpos($data['geneticScreening'], 'Cystic Fibrosis') !== false) ? 'checked' : ''; ?>> Cystic Fibrosis<br>
            <input type="checkbox" name="geneticScreening[]" value="Others" <?php echo (strpos($data['geneticScreening'], 'Others') !== false) ? 'checked' : ''; ?>> Others: 
            <input type="text" name="othersGenetic" value="<?php echo (strpos($data['geneticScreening'], 'Others') !== false) ? htmlentities(explode(', ', $data['geneticScreening'])[count(explode(', ', $data['geneticScreening'])) - 1] ?? '') : ''; ?>"><br>

            <label for="otherTests">Other Tests/Notes:</label>
            <textarea id="otherTests" name="otherTests"><?php echo htmlentities($data['otherTests']); ?></textarea>

            <label for="physicianNotes">Physician’s Notes and Recommendations:</label>
            <textarea id="physicianNotes" name="physicianNotes"><?php echo htmlentities($data['physicianNotes']); ?></textarea>

            <button type="submit">Update</button>
            <a href="manage-family-planning.php" class="btn btn-secondary" style="margin-left: 10px;">Back</a>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

