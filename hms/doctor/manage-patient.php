<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
    // Initialize search variable
    $search = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($con, trim($_GET['search'])); // Trim whitespace
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Patients</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
    <div id="app">		
        <?php include('include/sidebar.php');?>
        <div class="app-content">
            <?php include('include/header.php');?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle"><strong>View Patients</strong></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li></li>
                                <li class="active">
                                    <span>Manage Patients</span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15">&nbsp;</h5>
                                
                                <!-- Search Form -->
                                <form method="GET" action="" class="mb-3">
                                    <div class="form-group">
                                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by Patient Name, Contact Number, or Gender" value="<?php echo htmlentities($search); ?>" />
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>

                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">ID</th>
                                            <th>Patient Name</th>
                                            <th>Patient Contact Number</th>
                                            <th>Patient Gender</th>
                                            <th>Creation Date</th>
                                            <th>Updation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$docid = $_SESSION['id'];
$sql = "SELECT PatientLastname, PatientFirstname, PatientMiddlename, PatientContno, PatientGender, CreationDate, UpdationDate, ID FROM tblpatient WHERE Docid='$docid' ORDER BY CreationDate DESC";

if (!empty($search)) { // Check if search is not empty
    $sql .= " AND (PatientLastname LIKE '%$search%' 
                   OR PatientFirstname LIKE '%$search%' 
                   OR PatientMiddlename LIKE '%$search%' 
                   OR PatientContno LIKE '%$search%' 
                   OR PatientGender = '$search')";
}

$result = mysqli_query($con, $sql);
$cnt = 1;
while ($row = mysqli_fetch_array($result)) {
    // Combine names
    $patientFullName = trim($row['PatientLastname'] . ' ' . $row['PatientFirstname'] . ' ' . $row['PatientMiddlename']);
?>
                                        <tr>
                                            <td><?php echo $row['ID'];?>.</td>
                                            <td class="hidden-xs"><?php echo $patientFullName; ?></td>
                                            <td><?php echo $row['PatientContno'];?></td>
                                            <td><?php echo $row['PatientGender'];?></td>
                                            <td><?php echo $row['CreationDate'];?></td>
                                            <td><?php echo $row['UpdationDate'];?></td>
                                            <td>
                                                <a href="edit-patient.php?editid=<?php echo $row['ID'];?>" class="btn btn-primary btn-sm" target="_blank">Edit</a>
                                                <a href="view-patient.php?viewid=<?php echo $row['ID'];?>" class="btn btn-warning btn-sm" target="_blank">View Details</a>
                                            </td>
                                        </tr>
<?php 
    $cnt++;
} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('include/footer.php');?>
        <?php include('include/setting.php');?>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
</body>
</html>
<?php } ?>
