<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

$submissionSuccess = false; // Flag to indicate successful submission

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
    }

    $urineTests = isset($_POST['urineTests']) ? implode(", ", $_POST['urineTests']) : '';
    if (in_array('Others', $_POST['urineTests'])) {
        $urineTests .= ', ' . $con->real_escape_string($_POST['othersUrine']);
    }

    $diseaseScreening = isset($_POST['diseaseScreening']) ? implode(", ", $_POST['diseaseScreening']) : '';
    if (in_array('Others', $_POST['diseaseScreening'])) {
        $diseaseScreening .= ', ' . $con->real_escape_string($_POST['othersDisease']);
    }

    $fertilityAssessment = isset($_POST['fertilityAssessment']) ? implode(", ", $_POST['fertilityAssessment']) : '';
    if (in_array('Others', $_POST['fertilityAssessment'])) {
        $fertilityAssessment .= ', ' . $con->real_escape_string($_POST['othersFertility']);
    }

    $contraceptiveTests = isset($_POST['contraceptiveTests']) ? implode(", ", $_POST['contraceptiveTests']) : '';
    if (in_array('Others', $_POST['contraceptiveTests'])) {
        $contraceptiveTests .= ', ' . $con->real_escape_string($_POST['othersContraceptive']);
    }

    $geneticScreening = isset($_POST['geneticScreening']) ? implode(", ", $_POST['geneticScreening']) : '';
    if (in_array('Others', $_POST['geneticScreening'])) {
        $geneticScreening .= ', ' . $con->real_escape_string($_POST['othersGenetic']);
    }

    $otherTests = $con->real_escape_string($_POST['otherTests']);
    $physicianNotes = $con->real_escape_string($_POST['physicianNotes']);

    // Prepare the SQL statement
    $sql = "INSERT INTO family_planning_lab_form 
        (labName, address, phone, date, patientId, fullName, dob, age, gender, contactNumber, email, patientAddress, partnerName, partnerContact, referredBy, consultationDate, numberOfChildren, currentlyPregnant, previousPregnancies, historyOfReproductiveIssues, currentContraceptiveMethod, lastMenstrualPeriod, menstrualCycle, bloodTests, urineTests, diseaseScreening, fertilityAssessment, contraceptiveTests, geneticScreening, otherTests, physicianNotes) 
        VALUES ('$labName', '$address', '$phone', '$date', '$patientId', '$fullName', '$dob', $age, '$gender', '$contactNumber', '$email', '$patientAddress', '$partnerName', '$partnerContact', $referredBy, $consultationDate, $numberOfChildren, $currentlyPregnant, '$previousPregnancies', '$historyOfReproductiveIssues', '$currentContraceptiveMethod', $lastMenstrualPeriod, '$menstrualCycle', '$bloodTests', '$urineTests', '$diseaseScreening', '$fertilityAssessment', '$contraceptiveTests', '$geneticScreening', '$otherTests', '$physicianNotes')";

    // Execute the SQL statement
    if ($con->query($sql) === TRUE) {
        $submissionSuccess = true; // Set success flag
        echo "<script>alert('Entry saved successfully!');</script>";
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
    <title>Add Family Planning Laboratory Form</title>
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
        <h1>Add Family Planning Laboratory Form</h1>

        <form id="medicalForm" action="" method="POST">
            <h2>Laboratory Information</h2>
            <label for="labName">Laboratory Name:</label>
            <input type="text" id="labName" name="labName" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="patientId">Patient ID:</label>
            <input type="text" id="patientId" name="patientId" required>

            <h2>Patient Information</h2>
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
                <option value="Other">Other</option>
            </select>

            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="patientAddress">Address:</label>
            <input type="text" id="patientAddress" name="patientAddress" required>

            <label for="partnerName">Partner’s Name:</label>
            <input type="text" id="partnerName" name="partnerName">

            <label for="partnerContact">Partner’s Contact Information:</label>
            <input type="tel" id="partnerContact" name="partnerContact">

            <label for="referredBy">Referred By:</label>
            <input type="text" id="referredBy" name="referredBy">

            <label for="consultationDate">Consultation Date:</label>
            <input type="date" id="consultationDate" name="consultationDate">

            <h2>Health History</h2>
            <label for="numberOfChildren">Number of Children:</label>
            <input type="number" id="numberOfChildren" name="numberOfChildren" required>

            <label>Currently Pregnant?</label>
            <select id="currentlyPregnant" name="currentlyPregnant">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <label for="previousPregnancies">Previous Pregnancies/Miscarriages:</label>
            <input type="text" id="previousPregnancies" name="previousPregnancies">

            <label for="historyOfReproductiveIssues">History of Reproductive Issues:</label>
            <input type="text" id="historyOfReproductiveIssues" name="historyOfReproductiveIssues">

            <label for="currentContraceptiveMethod">Current Contraceptive Method:</label>
            <input type="text" id="currentContraceptiveMethod" name="currentContraceptiveMethod">

            <label for="lastMenstrualPeriod">Last Menstrual Period (LMP):</label>
            <input type="date" id="lastMenstrualPeriod" name="lastMenstrualPeriod">

            <label for="menstrualCycle">Menstrual Cycle:</label>
            <select id="menstrualCycle" name="menstrualCycle">
                <option value="Regular">Regular</option>
                <option value="Irregular">Irregular</option>
            </select>

            <h2>Family Planning Lab Tests Requested</h2>
            <label>Blood Work:</label>
            <input type="checkbox" name="bloodTests[]" value="CBC"> Complete Blood Count (CBC)<br>
            <input type="checkbox" name="bloodTests[]" value="Blood Type & Rh Factor"> Blood Type & Rh Factor<br>
            <input type="checkbox" name="bloodTests[]" value="Hormone Testing"> Hormone Testing (FSH, LH, Estradiol, Progesterone, etc.)<br>
            <input type="checkbox" name="bloodTests[]" value="Thyroid Panel"> Thyroid Panel (TSH, Free T3, Free T4)<br>
            <input type="checkbox" name="bloodTests[]" value="Prolactin"> Prolactin<br>
            <input type="checkbox" name="bloodTests[]" value="Blood Glucose"> Blood Glucose<br>
            <input type="checkbox" name="bloodTests[]" value="Hepatitis B & C"> Hepatitis B & C<br>
            <input type="checkbox" name="bloodTests[]" value="HIV Screening"> HIV Screening<br>
            <input type="checkbox" name="bloodTests[]" value="Syphilis Test"> Syphilis Test<br>
            <input type="checkbox" name="bloodTests[]" value="Others"> Others: <input type="text" name="othersBlood"><br>

            <label>Urine Tests:</label>
            <input type="checkbox" name="urineTests[]" value="Urinalysis"> Urinalysis<br>
            <input type="checkbox" name="urineTests[]" value="Pregnancy Test"> Pregnancy Test<br>
            <input type="checkbox" name="urineTests[]" value="Proteinuria"> Proteinuria<br>
            <input type="checkbox" name="urineTests[]" value="Urine Culture"> Urine Culture (if needed)<br>
            <input type="checkbox" name="urineTests[]" value="Others"> Others: <input type="text" name="othersUrine"><br>

            <label>Infectious Disease Screening:</label>
            <input type="checkbox" name="diseaseScreening[]" value="Chlamydia"> Chlamydia<br>
            <input type="checkbox" name="diseaseScreening[]" value="Gonorrhea"> Gonorrhea<br>
            <input type="checkbox" name="diseaseScreening[]" value="HPV Test"> HPV Test<br>
            <input type="checkbox" name="diseaseScreening[]" value="Others"> Others: <input type="text" name="othersDisease"><br>

            <label>Fertility Assessment (if applicable):</label>
            <input type="checkbox" name="fertilityAssessment[]" value="Semen Analysis"> Semen Analysis (for male partners)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Ovulation Test"> Ovulation Test (Luteinizing Hormone)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Anti-Müllerian Hormone"> Anti-Müllerian Hormone (AMH)<br>
            <input type="checkbox" name="fertilityAssessment[]" value="Others"> Others: <input type="text" name="othersFertility"><br>

            <label>Contraceptive-related Tests:</label>
            <input type="checkbox" name="contraceptiveTests[]" value="Pap Smear"> Pap Smear (Cervical Screening)<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Breast Ultrasound"> Breast Ultrasound/Mammogram<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Pelvic Ultrasound"> Pelvic Ultrasound<br>
            <input type="checkbox" name="contraceptiveTests[]" value="Others"> Others: <input type="text" name="othersContraceptive"><br>

            <label>Genetic Screening (Optional):</label>
            <input type="checkbox" name="geneticScreening[]" value="Genetic Carrier Screening"> Genetic Carrier Screening<br>
            <input type="checkbox" name="geneticScreening[]" value="Cystic Fibrosis"> Cystic Fibrosis<br>
            <input type="checkbox" name="geneticScreening[]" value="Others"> Others: <input type="text" name="othersGenetic"><br>

            <label for="otherTests">Other Tests/Notes:</label>
            <textarea id="otherTests" name="otherTests"></textarea>

            <label for="physicianNotes">Physician’s Notes and Recommendations:</label>
            <textarea id="physicianNotes" name="physicianNotes"></textarea>

            <button type="submit">Add</button>
            <a href="manage-family-planning.php" class="btn btn-secondary" style="margin-left: 10px;">Back</a>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

