<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id'])==0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $doctorspecilization = $_POST['doctorspecilization'];
        $room = $_POST['room']; // Get room input
        $max_patients = $_POST['max_patients']; // Get maximum patient slots

        // Insert into doctorSpecilization including max_patients
        $sql = mysqli_query($con, "INSERT INTO doctorSpecilization(specilization, room, max_patients) VALUES('$doctorspecilization', '$room', '$max_patients')");

        // Update avail_slots to be equal to max_patients
        $update_slots = mysqli_query($con, "UPDATE doctorSpecilization SET avail_slots = '$max_patients' WHERE specilization = '$doctorspecilization'");

        $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    }
    
    // Code Deletion
    if(isset($_GET['del'])) {
        $sid=$_GET['id'];    
        mysqli_query($con,"delete from doctorSpecilization where id = '$sid'");
        $_SESSION['msg']="data deleted !!";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Specialization and Laboratory</title>
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
                                <h1 class="mainTitle"><strong>Add Doctor Specialization</strong></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li></li>
                                <li class="active">
                                    <span>Add Doctor Specialization</span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row margin-top-30">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-body">
                                                <p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
                                                <?php echo htmlentities($_SESSION['msg']="");?></p>	
                                                <form role="form" name="dcotorspcl" method="post">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Enter Laboratory</label>
                                                        <input type="text" name="doctorspecilization" class="form-control" placeholder="Ex: Dengue Test" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="room">Enter Room Number</label>
                                                        <input type="number" name="room" class="form-control" placeholder="Example: 5" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="max_patients">Maximum Patient Slots</label>
                                                        <input type="number" name="max_patients" class="form-control" placeholder="Enter maximum patient slots" required>
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Enter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="panel panel-white"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15"><span class="text-bold">Doctor Specialization List</span></h5>
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">No.</th>
                                            <th>Laboratory</th>
                                            <th>Room No</th>
                                            <th>Max Patients</th>
                                            <th class="hidden-xs">Creation Date</th>
                                            <th>Updation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$sql=mysqli_query($con,"select * from doctorSpecilization");
$cnt=1;
while($row=mysqli_fetch_array($sql)) {
?>
                                        <tr>
                                            <td class="center"><?php echo $cnt;?>.</td>
                                            <td class="hidden-xs"><?php echo $row['specilization'];?></td>
                                            <td><?php echo $row['room']; ?></td>
                                            <td><?php echo $row['max_patients']; ?></td>
                                            <td><?php echo $row['creationDate'];?></td>
                                            <td><?php echo $row['updationDate'];?></td>
                                            <td>
                                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                    <a href="edit-doctor-specialization.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                    <a href="doctor-specilization.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
                                                </div>
                                            </td>
                                        </tr>
<?php 
$cnt=$cnt+1;
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
