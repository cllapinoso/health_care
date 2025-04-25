<?php
session_start();
error_reporting(0);
include('include/config.php');

if(strlen($_SESSION['id'])==0) {
    header('location:logout.php');
} else {
    $submissionSuccess = false; // Flag to indicate successful submission

    // Fetch the record to edit
    $labId = $_GET['id'];
    $query = mysqli_query($con, "SELECT * FROM hiv_lab_form WHERE id='$labId'");
    $row = mysqli_fetch_array($query);

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
        $testedBefore = $_POST['testedBefore'];
        $testedWhen = $_POST['testedWhen'];
        $riskFactors = implode(", ", $_POST['riskFactors']);
        $symptoms = implode(", ", $_POST['symptoms']);
        $testingOptions = implode(", ", $_POST['testingOptions']);
        $notes = $_POST['notes'];

        // Update the record
        $sql = "UPDATE hiv_lab_form SET 
                labName='$labName', 
                address='$address', 
                phone='$phone', 
                date='$date', 
                patientId='$patientId', 
                fullName='$fullName', 
                dob='$dob', 
                age='$age', 
                gender='$gender', 
                contactNumber='$contactNumber', 
                email='$email', 
                patientAddress='$patientAddress', 
                referredBy='$referredBy', 
                testedBefore='$testedBefore', 
                testedWhen='$testedWhen', 
                riskFactors='$riskFactors', 
                symptoms='$symptoms', 
                testingOptions='$testingOptions', 
                consent='$consent', 
                notes='$notes' 
                WHERE id='$labId'";

        if (mysqli_query($con, $sql)) {
            $submissionSuccess = true; // Set success flag
            header("Location: manage-hiv.php?success=1");
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    }

    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit HIV Laboratory Test Form</title>
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
        <h1>Edit HIV Laboratory Test Form</h1>

        <div id="successMessage" class="success-message">
            Your changes were saved successfully!
        </div>

        <form id="hivForm" action="" method="POST">
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
            <input type="text" id="fullName" name="fullName" placeholder="Lastname, Firstname Middlename" value="<?php echo $row['fullName']; ?>" required>

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

            <h2>Risk Factors and History</h2>
            <label>Have you been tested for HIV before?</label>
            <select name="testedBefore" required>
                <option value="Yes" <?php echo ($row['testedBefore'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                <option value="No" <?php echo ($row['testedBefore'] == 'No') ? 'selected' : ''; ?>>No</option>
            </select>
            <label for="testedWhen">If yes, when?</label>
            <input type="date" id="testedWhen" name="testedWhen" value="<?php echo $row['testedWhen']; ?>">

            <label>Do you have any known risk factors for HIV? (Check all that apply)</label>
            <?php
            $riskFactors = explode(", ", $row['riskFactors']);
            ?>
            <input type="checkbox" name="riskFactors[]" value="Unprotected sex" <?php echo in_array('Unprotected sex', $riskFactors) ? 'checked' : ''; ?>> Unprotected sex<br>
            <input type="checkbox" name="riskFactors[]" value="Multiple sexual partners" <?php echo in_array('Multiple sexual partners', $riskFactors) ? 'checked' : ''; ?>> Multiple sexual partners<br>
            <input type="checkbox" name="riskFactors[]" value="Intravenous drug use" <?php echo in_array('Intravenous drug use', $riskFactors) ? 'checked' : ''; ?>> Intravenous drug use<br>
            <input type="checkbox" name="riskFactors[]" value="History of STIs" <?php echo in_array('History of STIs', $riskFactors) ? 'checked' : ''; ?>> History of sexually transmitted infections (STIs)<br>
            <input type="checkbox" name="riskFactors[]" value="Blood transfusions" <?php echo in_array('Blood transfusions', $riskFactors) ? 'checked' : ''; ?>> Blood transfusions (before 1992)<br>
            <input type="checkbox" name="riskFactors[]" value="Partner with HIV" <?php echo in_array('Partner with HIV', $riskFactors) ? 'checked' : ''; ?>> Partner with HIV<br>
            <input type="checkbox" name="riskFactors[]" value="Other" <?php echo in_array('Other', $riskFactors) ? 'checked' : ''; ?>> Other: <input type="text" name="riskFactorsOther" value="<?php echo in_array('Other', $riskFactors) ? '' : ''; ?>"><br>

            <label>Are you currently experiencing any symptoms?</label>
            <?php
            $symptoms = explode(", ", $row['symptoms']);
            ?>
            <input type="checkbox" name="symptoms[]" value="Weight loss" <?php echo in_array('Weight loss', $symptoms) ? 'checked' : ''; ?>> Weight loss<br>
            <input type="checkbox" name="symptoms[]" value="Fever" <?php echo in_array('Fever', $symptoms) ? 'checked' : ''; ?>> Fever<br>
            <input type="checkbox" name="symptoms[]" value="Fatigue" <?php echo in_array('Fatigue', $symptoms) ? 'checked' : ''; ?>> Fatigue<br>
            <input type="checkbox" name="symptoms[]" value="Night sweats" <?php echo in_array('Night sweats', $symptoms) ? 'checked' : ''; ?>> Night sweats<br>
            <input type="checkbox" name="symptoms[]" value="Other" <?php echo in_array('Other', $symptoms) ? 'checked' : ''; ?>> Others: <input type="text" name="symptomsOther" value="<?php echo in_array('Other', $symptoms) ? '' : ''; ?>"><br>

            <h2>HIV Testing Options</h2>
            <?php
            $testingOptions = explode(", ", $row['testingOptions']);
            ?>
            <label>Standard HIV Tests:</label>
            <input type="checkbox" name="testingOptions[]" value="HIV Antibody Test (ELISA)" <?php echo in_array('HIV Antibody Test (ELISA)', $testingOptions) ? 'checked' : ''; ?>> HIV Antibody Test (ELISA)<br>
            <input type="checkbox" name="testingOptions[]" value="HIV-1/2 Antigen/Antibody Combination Test" <?php echo in_array('HIV-1/2 Antigen/Antibody Combination Test', $testingOptions) ? 'checked' : ''; ?>> HIV-1/2 Antigen/Antibody Combination Test (4th generation)<br>
            <input type="checkbox" name="testingOptions[]" value="Western Blot" <?php echo in_array('Western Blot', $testingOptions) ? 'checked' : ''; ?>> Western Blot (confirmatory)<br>

            <label>Rapid HIV Testing:</label>
            <input type="checkbox" name="testingOptions[]" value="Rapid HIV Test" <?php echo in_array('Rapid HIV Test', $testingOptions) ? 'checked' : ''; ?>> Rapid HIV Test (results in 20 minutes)<br>
            <input type="checkbox" name="testingOptions[]" value="Follow-up confirmatory test" <?php echo in_array('Follow-up confirmatory test', $testingOptions) ? 'checked' : ''; ?>> Follow-up confirmatory test if positive<br>

            <label>HIV RNA Test:</label>
            <input type="checkbox" name="testingOptions[]" value="HIV Viral Load" <?php echo in_array('HIV Viral Load', $testingOptions) ? 'checked' : ''; ?>> HIV Viral Load (Quantitative RNA PCR)<br>
            <input type="checkbox" name="testingOptions[]" value="Early detection" <?php echo in_array('Early detection', $testingOptions) ? 'checked' : ''; ?>> Early detection (before antibodies develop)<br>

            <label>Other Related Tests:</label>
            <input type="checkbox" name="testingOptions[]" value="CD4 Count" <?php echo in_array('CD4 Count', $testingOptions) ? 'checked' : ''; ?>> CD4 Count<br>
            <input type="checkbox" name="testingOptions[]" value="HIV Resistance Testing" <?php echo in_array('HIV Resistance Testing', $testingOptions) ? 'checked' : ''; ?>> HIV Resistance Testing<br>
            <input type="checkbox" name="testingOptions[]" value="Other" <?php echo in_array('Other', $testingOptions) ? 'checked' : ''; ?>> Other: <input type="text" name="testingOptionsOther" value="<?php echo in_array('Other', $testingOptions) ? '' : ''; ?>"><br>

            <label for="notes">Additional Notes:</label>
            <textarea id="notes" name="notes"><?php echo $row['notes']; ?></textarea>

            <button type="submit">Update Entry</button>
            <a href="manage-hiv.php" class="btn btn-secondary" style="margin-left: 10px;">Back</a>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
