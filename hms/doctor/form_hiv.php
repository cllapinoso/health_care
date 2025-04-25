<?php
session_start();
error_reporting(0);
include('include/config.php');

$submissionSuccess = false; // Flag to indicate successful submission

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $labName = $_POST['labName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $patientId = $_POST['patientId'];
    $fullName = $_POST['fullName'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contactNumber = $_POST['contactNumber'];
    $email = $_POST['email'];
    $patientAddress = $_POST['patientAddress'];
    $referredBy = $_POST['referredBy'];
    $familyPhysician = $_POST['familyPhysician'];
    $testedBefore = $_POST['testedBefore'];
    $testedWhen = $_POST['testedWhen'];
    $riskFactors = implode(", ", $_POST['riskFactors']);
    $symptoms = implode(", ", $_POST['symptoms']);
    $testingOptions = implode(", ", $_POST['testingOptions']);
    $notes = $_POST['notes'];

    // Insert the new record
    $sql = "INSERT INTO hiv_lab_form (labName, address, phone, date, patientId, fullName, dob, age, gender, contactNumber, email, patientAddress, referredBy, familyPhysician, testedBefore, testedWhen, riskFactors, symptoms, testingOptions, notes) 
            VALUES ('$labName', '$address', '$phone', '$date', '$patientId', '$fullName', '$dob', '$age', '$gender', '$contactNumber', '$email', '$patientAddress', '$referredBy', '$familyPhysician', '$testedBefore', '$testedWhen', '$riskFactors', '$symptoms', '$testingOptions', '$notes')";

    if (mysqli_query($con, $sql)) {
        $submissionSuccess = true; // Set success flag
        header("Location: manage-hiv.php?success=1");
        exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add HIV Laboratory Test Form</title>
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
    </style>
    <script>
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
        <h1>Add HIV Laboratory Test Form</h1>

        <div id="successMessage" class="success-message">
            Your entry was saved successfully!
        </div>

        <form id="hivForm" action="" method="POST">
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
            <input type="text" id="fullName" placeholder="Lastname, Firstname Middlename" name="fullName" required>

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

            <label for="referredBy">Referred By:</label>
            <input type="text" id="referredBy" name="referredBy">

            <label for="familyPhysician">Family Physician:</label>
            <input type="text" id="familyPhysician" name="familyPhysician">

            <h2>Risk Factors and History</h2>
            <label>Have you been tested for HIV before?</label>
            <select name="testedBefore" required>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            <label for="testedWhen">If yes, when?</label>
            <input type="date" id="testedWhen" name="testedWhen">

            <label>Do you have any known risk factors for HIV? (Check all that apply)</label>
            <input type="checkbox" name="riskFactors[]" value="Unprotected sex"> Unprotected sex<br>
            <input type="checkbox" name="riskFactors[]" value="Multiple sexual partners"> Multiple sexual partners<br>
            <input type="checkbox" name="riskFactors[]" value="Intravenous drug use"> Intravenous drug use<br>
            <input type="checkbox" name="riskFactors[]" value="History of STIs"> History of sexually transmitted infections (STIs)<br>
            <input type="checkbox" name="riskFactors[]" value="Blood transfusions"> Blood transfusions (before 1992)<br>
            <input type="checkbox" name="riskFactors[]" value="Partner with HIV"> Partner with HIV<br>
            <input type="checkbox" name="riskFactors[]" value="Other"> Other: <input type="text" name="riskFactorsOther"><br>

            <label>Are you currently experiencing any symptoms?</label>
            <input type="checkbox" name="symptoms[]" value="Weight loss"> Weight loss<br>
            <input type="checkbox" name="symptoms[]" value="Fever"> Fever<br>
            <input type="checkbox" name="symptoms[]" value="Fatigue"> Fatigue<br>
            <input type="checkbox" name="symptoms[]" value="Night sweats"> Night sweats<br>
            <input type="checkbox" name="symptoms[]" value="Other"> Others: <input type="text" name="symptomsOther"><br>

            <h2>HIV Testing Options</h2>
            <label>Standard HIV Tests:</label>
            <input type="checkbox" name="testingOptions[]" value="HIV Antibody Test (ELISA)"> HIV Antibody Test (ELISA)<br>
            <input type="checkbox" name="testingOptions[]" value="HIV-1/2 Antigen/Antibody Combination Test"> HIV-1/2 Antigen/Antibody Combination Test (4th generation)<br>
            <input type="checkbox" name="testingOptions[]" value="Western Blot"> Western Blot (confirmatory)<br>

            <label>Rapid HIV Testing:</label>
            <input type="checkbox" name="testingOptions[]" value="Rapid HIV Test"> Rapid HIV Test (results in 20 minutes)<br>
            <input type="checkbox" name="testingOptions[]" value="Follow-up confirmatory test"> Follow-up confirmatory test if positive<br>

            <label>HIV RNA Test:</label>
            <input type="checkbox" name="testingOptions[]" value="HIV Viral Load"> HIV Viral Load (Quantitative RNA PCR)<br>
            <input type="checkbox" name="testingOptions[]" value="Early detection"> Early detection (before antibodies develop)<br>

            <label>Other Related Tests:</label>
            <input type="checkbox" name="testingOptions[]" value="CD4 Count"> CD4 Count<br>
            <input type="checkbox" name="testingOptions[]" value="HIV Resistance Testing"> HIV Resistance Testing<br>
            <input type="checkbox" name="testingOptions[]" value="Other"> Other: <input type="text" name="testingOptionsOther"><br>

            <label for="notes">Additional Notes:</label>
            <textarea id="notes" name="notes"></textarea>

            <button type="submit">Add</button>
            <a href="manage-hiv.php" class="btn btn-secondary" style="margin-left: 10px;">Back</a>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
