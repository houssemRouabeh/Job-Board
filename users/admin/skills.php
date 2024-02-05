<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];
// Requête SQL pour vérifier l'utilisateur dans la base de données
$sql = "SELECT * FROM skills ";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
}

if (isset($_POST['addSkill'])) {
    $skillName = $_POST['skillName'];
    $skillCat = $_POST['skillCat'];

    // Function to check if a skill  already exists
    function skillExists($name, $connection)
    {
        $sql = "SELECT COUNT(*) as count FROM skills WHERE name = ?";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $stmt->close();

            return $count > 0;
        } else {
            return false;
        }
    }
    if (skillExists($skillName, $connection)) {
        // User already exists, handle accordingly (e.g., display an error message)
        $_SESSION['alert_message'] =  "Skill already exists.";
        header("location:skills.php");
    } else {
        // Continue with user registration
        $sql = "INSERT INTO skills (name, category) VALUES (?, ?)";
        $stmt = $connection->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $skillName, $skillCat);

            $stmt->execute();
        }
        $stmt->close();
        header("location:skills.php");
    }
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

    <title>Admin - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">JobLand.</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manage
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Accounts</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Users Edit:</h6>
                        <a class="collapse-item" href="admins.php">Admin</a>
                        <a class="collapse-item" href="employer.php">Employer</a>
                        <a class="collapse-item" href="employee.php">Employee</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Jobs</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Offers Edit:</h6>
                        <a class="collapse-item" href="offers.php">Job Offers</a>
                        <a class="collapse-item" href="skills.php">Skills</a>
                    </div>
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </li>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center mt-3">
                        <h1 class="h3 mb-0 text-gray-800">Skills List</h1>
                    </div>
                    <p class="d-inline-flex gap-1 mt-5 ms-4">
                        <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                            Add Skill
                        </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="">
                            <div class="container">
                                <div class="row">
                                    <div class="p-5 col-md-8 offset-md-2">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Add Skills</h1>
                                        </div>
                                        <form class="user" method="post" action="">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="HTML, CSS, Python etc..." name="skillName" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Technical Skills, Creative Skills, Soft Skills, Language etc..." name="skillCat" required>
                                            </div>

                                            <button type="submit" name="addSkill" class="btn btn-primary btn-user btn-block">
                                                Add Skill
                                            </button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Skill Name</th>
                                                <th>Category</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            while ($user = $result->fetch_assoc()) {
                                                // Afficher chaque ligne dans le tableau
                                                echo ' <tr>';
                                                echo '<td>' . $user['name'] . '</td>';
                                                echo '<td>' . $user['category'] . '</td>';
                                                echo '<td><a href="deleteSkill.php?adminId=' . $user['id_skill'] . '" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a> Delete Account</td>
                                            </tr>';
                                            }

                                            ?>

                                        </tbody>
                                    </table>
                                </div>
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
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <!-- Page level plugins -->
            <script src="vendor/chart.js/Chart.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/chart-area-demo.js"></script>
            <script src="js/demo/chart-pie-demo.js"></script>
            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin-2.min.js"></script>

            <!-- Page level plugins -->
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="js/demo/datatables-demo.js"></script>

</body>

</html>