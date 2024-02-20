<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];
//requette pour importer les données de l'utilisateur
$sql = "SELECT * FROM employer WHERE id_user = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employer = $result->fetch_assoc();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // récupération des informations du formulaire
    $city = $_POST['city'];
    $postal = $_POST['cp'];
    $adress = $_POST['adress'];
    $selectedEmployeeCount = $_POST["employeeCount"];
    $phone = $_POST['phone'];

    $sql = "UPDATE employer 
        SET city = ?,             
            adress = ?, 
            postal_code = ?, 
            nbr_employee = ?, 
            phone = ? 
        WHERE id_user = ?";
    $stmt = $connection->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssisii", $city, $adress, $postal, $selectedEmployeeCount, $phone, $id);

    if ($stmt->execute()) {
        header("location:index.php");
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employer - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'employerSidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'employerNav.php' ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Profile</h1>
                    </div>




                    <div class="container">
                        <div class="row">
                            <div class="p-5 col-md-12">
                                <form class="user" method="post" action="">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="companyName">Company Name</label>
                                            <input type="text" class="form-control" id="companyName" name="companyName" value="<?php echo $employer['company_name'] ?>" required disabled>
                                        </div>
                                        <div class="form-group col">
                                            <label for="industry">Industry</label>
                                            <input type="text" class="form-control" id="industry" name="industry" value="<?php echo $employer['industry'] ?>" required disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $employer['city'] ?>" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="cp">Postal Code</label>
                                            <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $employer['postal_code'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="adress">Adress</label>
                                            <input type="text" class="form-control" id="adress" name="adress" value="<?php echo $employer['adress'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="nbrE">Number of Employees</label>
                                            <select class="form-control" aria-label="Default select example" name="employeeCount" id="nbrE">
                                                <?php
                                                $selected1 = "";
                                                $selected2 = "";
                                                $selected3 = "";
                                                $default = "selected";
                                                if ($employer['nbr_employee']) {


                                                    switch ($employer['nbr_employee']) {
                                                        case '1--50':
                                                            $selected1 = "selected";
                                                            $selected2 = false;
                                                            $selected3 = false;
                                                            break;
                                                        case '50--100':
                                                            $selected1 = false;
                                                            $selected2 = "selected";
                                                            $selected3 = false;
                                                            break;
                                                        case '100--200':
                                                            $selected1 = false;
                                                            $selected2 = false;
                                                            $selected3 = "selected";
                                                            break;

                                                        default:
                                                            $selected1 = "";
                                                            $selected2 = "";
                                                            $selected3 = "";
                                                            break;
                                                    }
                                                } ?>
                                                <option value="1--50" <?php echo $selected1  ?>>1--50</option>
                                                <option value="50--100" <?php echo $selected2 ?>>50--100</option>
                                                <option value="100--200" <?php echo $selected3 ?>>100--200</option>
                                            </select>
                                        </div>
                                        <div class="form-group col">
                                            <label for="phone">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $employer['phone'] ?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary btn-user btn-block">
                                        Update profile
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>




                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="../../auth/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; JobLand 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <!-- Page level plugins -->
            <script src="vendor/chart.js/Chart.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/chart-area-demo.js"></script>
            <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>