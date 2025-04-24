<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    // Fetch total patients per month
    $monthlyPatients = array_fill(0, 12, 0); // Initialize an array for 12 months
    $query = "SELECT MONTH(CreationDate) as month, COUNT(*) as total FROM tblpatient GROUP BY month";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $monthlyPatients[(int)$row['month'] - 1] = (int)$row['total'];
    }

    // Fetch user registrations per month
    $monthlyRegistrations = array_fill(0, 12, 0); // Initialize an array for 12 months
    $queryUsers = "SELECT MONTH(regDate) as month, COUNT(*) as total FROM users GROUP BY month";
    $resultUsers = mysqli_query($con, $queryUsers);

    while ($row = mysqli_fetch_assoc($resultUsers)) {
        $monthlyRegistrations[(int)$row['month'] - 1] = (int)$row['total'];
    }

    // Fetch total patients by service and doctor specialization combined
    $combinedCounts = [];
    $queryServices = "
        SELECT specilization AS label, SUM(total) AS total
        FROM (
            SELECT ds.specilization, COUNT(tp.id) AS total
            FROM doctorspecilization ds
            LEFT JOIN tblpatient tp ON tp.service = ds.specilization
            GROUP BY ds.specilization
            
            UNION ALL
            
            SELECT ds.specilization, COUNT(a.id) AS total
            FROM doctorspecilization ds
            LEFT JOIN appointment a ON a.doctorSpecialization = ds.specilization
            GROUP BY ds.specilization
        ) AS combined
        GROUP BY label
    ";
    $resultServices = mysqli_query($con, $queryServices);

    while ($row = mysqli_fetch_assoc($resultServices)) {
        $combinedCounts[$row['label']] = (int)$row['total'];
    }

    // Prepare data for the pie chart
    $combinedLabels = array_keys($combinedCounts);
    $combinedData = array_values($combinedCounts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reports and Analytics</title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="app">        
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>

            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle"><strong>Reports and Analytics</strong></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li></li>
                                <li class="active">
                                    <span>Reports and Analytics</span>
                                </li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Bar Graph Section for Patients -->
                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white">
                                        <div class="panel-body">
                                            <h3>Total Walk-in Patients per Month</h3>
                                            <canvas id="patientsChart" width="400" height="150"></canvas>
                                            <script>
                                                var ctx = document.getElementById('patientsChart').getContext('2d');
                                                var patientsChart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                                        datasets: [{
                                                            label: 'Total Patients',
                                                            data: <?php echo json_encode($monthlyPatients); ?>,
                                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                            borderColor: 'rgba(75, 192, 192, 1)',
                                                            borderWidth: 1
                                                        }]
                                                    },
                                                    options: {
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bar Graph Section for User Registrations -->
                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white">
                                        <div class="panel-body">
                                            <h3>Total Online Appointments per Month</h3>
                                            <canvas id="registrationsChart" width="400" height="150"></canvas>
                                            <script>
                                                var ctx2 = document.getElementById('registrationsChart').getContext('2d');
                                                var registrationsChart = new Chart(ctx2, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                                        datasets: [{
                                                            label: 'Total Registrations',
                                                            data: <?php echo json_encode($monthlyRegistrations); ?>,
                                                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                                            borderColor: 'rgba(153, 102, 255, 1)',
                                                            borderWidth: 1
                                                        }]
                                                    },
                                                    options: {
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pie Chart Section for Combined Service and Doctor Specialization -->
                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white">
                                        <div class="panel-body">
                                            <h3>Total Patients by Service/Specialization</h3>
                                            <center><canvas id="combinedChart" width="550" height="550"></canvas></center>
                                            <script>
                                                var ctx3 = document.getElementById('combinedChart').getContext('2d');
                                                var combinedChart = new Chart(ctx3, {
                                                    type: 'pie',
                                                    data: {
                                                        labels: <?php echo json_encode($combinedLabels); ?>,
                                                        datasets: [{
                                                            label: 'Total Patients by Service and Doctor Specialization',
                                                            data: <?php echo json_encode($combinedData); ?>,
                                                            backgroundColor: [
                                                                'rgba(255, 99, 132, 0.7)',  // Red
                                                                'rgba(54, 162, 235, 0.7)',  // Blue
                                                                'rgba(255, 206, 86, 0.7)',   // Yellow
                                                                'rgba(75, 192, 192, 0.7)',   // Teal
                                                                'rgba(153, 102, 255, 0.7)',  // Purple
                                                                'rgba(255, 159, 64, 0.7)',   // Orange
                                                                'rgba(255, 99, 71, 0.7)',    // Tomato
                                                                'rgba(60, 179, 113, 0.7)',   // Medium Sea Green
                                                                'rgba(255, 20, 147, 0.7)',   // Deep Pink
                                                                'rgba(30, 144, 255, 0.7)',   // Dodger Blue
                                                                'rgba(255, 140, 0, 0.7)',    // Dark Orange
                                                                'rgba(128, 0, 128, 0.7)'     // Purple
                                                            ],
                                                            borderColor: [
                                                                'rgba(255, 99, 132, 1)',  // Red
                                                                'rgba(54, 162, 235, 1)',  // Blue
                                                                'rgba(255, 206, 86, 1)',   // Yellow
                                                                'rgba(75, 192, 192, 1)',   // Teal
                                                                'rgba(153, 102, 255, 1)',  // Purple
                                                                'rgba(255, 159, 64, 1)',   // Orange
                                                                'rgba(255, 99, 71, 1)',    // Tomato
                                                                'rgba(60, 179, 113, 1)',   // Medium Sea Green
                                                                'rgba(255, 20, 147, 1)',   // Deep Pink
                                                                'rgba(30, 144, 255, 1)',   // Dodger Blue
                                                                'rgba(255, 140, 0, 1)',    // Dark Orange
                                                                'rgba(128, 0, 128, 1)'     // Purple
                                                            ],
                                                            borderWidth: 1
                                                        }]
                                                    },
                                                    options: {
                                                        responsive: false,
                                                        plugins: {
                                                            legend: {
                                                                position: 'top',
                                                            },
                                                            tooltip: {
                                                                callbacks: {
                                                                    label: function(tooltipItem) {
                                                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php include('include/footer.php'); ?>
            <?php include('include/setting.php'); ?>
        </div>
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
