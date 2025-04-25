<?php
session_start();
error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

$fullName = $_SESSION['fullName']; // Assuming fullName is stored in session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Family Planning Records</title>
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
    <style>
        /* Fresh modal design */
        .modal-dialog {
            width: 90%;
            max-width: 1000px;
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border: none;
        }
        .modal-header {
            background: linear-gradient(135deg, #4484ce, #1a3c6e);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px 25px;
            border-bottom: none;
        }
        .modal-title {
            font-size: 22px;
            font-weight: 600;
            color: white;
        }
        .modal-body {
            padding: 25px;
            max-height: 75vh;
            overflow-y: auto;
        }
        /* Card-like sections */
        .info-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 25px;
            border-left: 5px solid #4484ce;
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        /* Field styling */
        .field-row {
            display: flex;
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: 600;
            color: #555;
            min-width: 150px;
            padding-right: 15px;
        }
        .field-value {
            flex: 1;
            color: #333;
        }
        .text-area-display {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 10px 15px;
            border: 1px solid #e9ecef;
            min-height: 80px;
            margin-top: 8px;
            font-size: 14px;
            line-height: 1.5;
            color: #495057;
        }
        .empty-field {
            color: #999;
            font-style: italic;
        }
        /* Button styling */
        .btn-dental {
            background-color: #4484ce;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-dental:hover {
            background-color: #3373b7;
            color: white;
        }
        .modal-footer {
            border-top: 1px solid #eee;
            padding: 15px 25px;
            text-align: right;
        }
        .close-btn {
            background-color: #6c757d;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .close-btn:hover {
            background-color: #5a6268;
        }
        /* Loading spinner */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .loading-spinner {
            margin-bottom: 15px;
            color: #4484ce;
        }
    </style>
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
                                <h1 class="mainTitle"><strong>Family Planning Records</strong></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li></li>
                                <li class="active">
                                    <span>View Family Planning Records</span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="over-title margin-bottom-15">&nbsp;</h5>
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">No.</th>
                                            <th>Service</th>
                                            <th>Laboratory Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($con, "SELECT * FROM family_planning_lab_form WHERE fullName='$fullName'");
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($sql)) {
                                        ?>
                                        <tr>
                                            <td class="center"><?php echo $cnt;?>.</td>
                                            <td class="hidden-xs"><?php echo $row['service'];?></td>
                                            <td><?php echo $row['labName'];?></td>
                                            <td>
                                                <button type="button" class="btn btn-dental btn-xs view-details" data-id="<?php echo $row['id']; ?>">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
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
    </div>

    <!-- Modal for displaying details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="detailsModalLabel">Family Planning Form Details</h4>
                </div>
                <div class="modal-body">
                    <div class="loading-container">
                        <i class="fa fa-spinner fa-spin fa-3x loading-spinner"></i>
                        <p>Loading family planning form details...</p>
                    </div>
                    <div id="details-content" class="hidden">
                        <!-- Basic Information -->
                        <div class="info-card">
                            <h4 class="card-title">Basic Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-row">
                                        <div class="field-label">ID:</div>
                                        <div class="field-value" id="detail-id"></div>
                                    </div>
                                    <div class="field-row">
                                        <div class="field-label">Service:</div>
                                        <div class="field-value" id="detail-service"></div>
                                    </div>
                                    <div class="field-row">
                                        <div class="field-label">Laboratory Name:</div>
                                        <div class="field-value" id="detail-labName"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-row">
                                        <div class="field-label">Patient ID:</div>
                                        <div class="field-value" id="detail-patientId"></div>
                                    </div>
                                    <div class="field-row">
                                        <div class="field-label">Full Name:</div>
                                        <div class="field-value" id="detail-fullName"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="info-card">
                            <h4 class="card-title">Additional Information</h4>
                            <div class="field-row">
                                <div class="field-label">Partner Name:</div>
                                <div class="field-value" id="detail-partnerName"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Partner Contact:</div>
                                <div class="field-value" id="detail-partnerContact"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Referred By:</div>
                                <div class="field-value" id="detail-referredBy"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Consultation Date:</div>
                                <div class="field-value" id="detail-consultationDate"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Number of Children:</div>
                                <div class="field-value" id="detail-numberOfChildren"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Currently Pregnant:</div>
                                <div class="field-value" id="detail-currentlyPregnant"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Previous Pregnancies:</div>
                                <div class="field-value" id="detail-previousPregnancies"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">History of Reproductive Issues:</div>
                                <div class="field-value" id="detail-historyOfReproductiveIssues"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Current Contraceptive Method:</div>
                                <div class="field-value" id="detail-currentContraceptiveMethod"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Last Menstrual Period:</div>
                                <div class="field-value" id="detail-lastMenstrualPeriod"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Menstrual Cycle:</div>
                                <div class="field-value" id="detail-menstrualCycle"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Blood Tests:</div>
                                <div class="field-value" id="detail-bloodTests"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Urine Tests:</div>
                                <div class="field-value" id="detail-urineTests"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Disease Screening:</div>
                                <div class="field-value" id="detail-diseaseScreening"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Fertility Assessment:</div>
                                <div class="field-value" id="detail-fertilityAssessment"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Contraceptive Tests:</div>
                                <div class="field-value" id="detail-contraceptiveTests"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Genetic Screening:</div>
                                <div class="field-value" id="detail-geneticScreening"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Other Tests:</div>
                                <div class="field-value" id="detail-otherTests"></div>
                            </div>
                            <div class="field-row">
                                <div class="field-label">Physician Notes:</div>
                                <div class="field-value" id="detail-physicianNotes"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>

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
            
            // Function to handle empty data
            function displayValue(value) {
                return value ? value : '<span class="empty-field">Not specified</span>';
            }
            
            // Handle View button click
            $('.view-details').on('click', function() {
                var recordId = $(this).data('id');
                
                // Show modal and loading indicator
                $('#detailsModal').modal('show');
                $('.loading-container').removeClass('hidden');
                $('#details-content').addClass('hidden');
                
                // Fetch record details via AJAX
                $.ajax({
                    url: 'get_family_planning_details.php',
                    type: 'POST',
                    data: {id: recordId},
                    dataType: 'json',
                    success: function(response) {
                        // Hide loading and show content
                        $('.loading-container').addClass('hidden');
                        $('#details-content').removeClass('hidden');
                        
                        // Populate modal with data - Basic Information
                        $('#detail-id').html(displayValue(response.id));
                        $('#detail-service').html(displayValue(response.service));
                        $('#detail-labName').html(displayValue(response.labName));
                        $('#detail-patientId').html(displayValue(response.patientId));
                        $('#detail-fullName').html(displayValue(response.fullName));
                        
                        // Additional Information
                        $('#detail-partnerName').html(displayValue(response.partnerName));
                        $('#detail-partnerContact').html(displayValue(response.partnerContact));
                        $('#detail-referredBy').html(displayValue(response.referredBy));
                        $('#detail-consultationDate').html(displayValue(response.consultationDate));
                        $('#detail-numberOfChildren').html(displayValue(response.numberOfChildren));
                        $('#detail-currentlyPregnant').html(displayValue(response.currentlyPregnant));
                        $('#detail-previousPregnancies').html(displayValue(response.previousPregnancies));
                        $('#detail-historyOfReproductiveIssues').html(displayValue(response.historyOfReproductiveIssues));
                        $('#detail-currentContraceptiveMethod').html(displayValue(response.currentContraceptiveMethod));
                        $('#detail-lastMenstrualPeriod').html(displayValue(response.lastMenstrualPeriod));
                        $('#detail-menstrualCycle').html(displayValue(response.menstrualCycle));
                        $('#detail-bloodTests').html(displayValue(response.bloodTests));
                        $('#detail-urineTests').html(displayValue(response.urineTests));
                        $('#detail-diseaseScreening').html(displayValue(response.diseaseScreening));
                        $('#detail-fertilityAssessment').html(displayValue(response.fertilityAssessment));
                        $('#detail-contraceptiveTests').html(displayValue(response.contraceptiveTests));
                        $('#detail-geneticScreening').html(displayValue(response.geneticScreening));
                        $('#detail-otherTests').html(displayValue(response.otherTests));
                        $('#detail-physicianNotes').html(displayValue(response.physicianNotes));
                    },
                    error: function() {
                        $('.loading-container').addClass('hidden');
                        $('#details-content').removeClass('hidden').html('<div class="alert alert-danger">Error loading details. Please try again.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>

