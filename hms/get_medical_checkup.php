<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if(isset($_POST['id']) && !empty($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Prepare and execute query with proper sanitization to get all fields
    $stmt = mysqli_prepare($con, "SELECT 
        id, service, labName, patientId, fullName, chronicConditions, previousSurgeries, allergies, currentMedications, familyMedicalHistory, 
        bloodPressure, heartRate, respiratoryRate, temperature, height, weight, bmi, bloodTests, urineTests, imagingTests, diseaseScreening, otherTests
        FROM medical_checkup_lab_form WHERE id = ?");
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Return data as JSON
        echo json_encode($row);
    } else {
        // No records found
        echo json_encode(['error' => 'Record not found']);
    }
    
    mysqli_stmt_close($stmt);
} else {
    // Invalid or missing ID
    echo json_encode(['error' => 'Invalid request']);
}

mysqli_close($con);
?>

