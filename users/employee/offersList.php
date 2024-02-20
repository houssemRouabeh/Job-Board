<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];

$currentDate = date('Y-m-d');

// Mettez à jour les offres expirées
$sql = "UPDATE offer SET status = 'Expired' WHERE date_end <= ? AND status = 'Open'";
$stmt2 = $connection->prepare($sql);
$stmt2->bind_param('s', $currentDate);
$stmt2->execute();

?>

<!-- Reste du code HTML... -->

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employer - Offers List</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
<style>
    .clickable-row:hover {
        cursor: pointer;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'employerSidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Topbar -->
            <?php include 'employerNav.php' ?>
            <!-- End of Topbar -->
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center mt-3 mb-5">
                        <h1 class="h3 mb-0 text-gray-800">Job Offers List</h1>
                    </div>

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Job Description</th>
                                                <th>Skills</th>
                                                <th>Salary</th>
                                                <th>Published at</th>
                                                <th>Expire at</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Requête SQL pour récupérer les offres de l'utilisateur
                                            $sql = "SELECT * FROM offer WHERE id_user = ?";
                                            $stmt = $connection->prepare($sql);

                                            if ($stmt) {
                                                $stmt->bind_param('i', $id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                // Boucle pour parcourir les offres
                                                while ($offer = $result->fetch_assoc()) {
                                                    $offerId = $offer['id_offer'];

                                                    // Requête SQL pour récupérer les compétences associées à l'offre
                                                    $sqlSkills = "SELECT name FROM skills s, offer_skills os WHERE os.id_skill = s.id_skill AND id_offer = ?";
                                                    $stmtSkills = $connection->prepare($sqlSkills);

                                                    if ($stmtSkills) {
                                                        $stmtSkills->bind_param('i', $offerId);
                                                        $stmtSkills->execute();
                                                        $resultSkills = $stmtSkills->get_result();

                                                        // Boucle pour parcourir les compétences
                                                        $skillsArray = [];
                                                        while ($skill = $resultSkills->fetch_assoc()) {
                                                            $skillsArray[] = $skill['name'];
                                                        }

                                                        echo '<tr class="clickable-row" onclick="window.location=\'offerDetails.php?offerId=' . $offer['id_offer'] . '\'">';
                                                        echo '<td>' . $offer['title'] . '</td>';
                                                        echo '<td>' . $offer['description'] . '</td>';
                                                        echo '<td>' . implode(', ', $skillsArray) . '</td>';
                                                        echo '<td>' . $offer['salary'] . '</td>';
                                                        echo '<td>' . $offer['date_creation'] . '</td>';
                                                        echo '<td>' . $offer['date_end'] . '</td>';
                                                        echo '<td class="' . ($offer['status'] == 'Open' ? 'table-success' : 'table-danger') . '">' . $offer['status'] . '</td>';
                                                        echo '</tr>';




                                                        $stmtSkills->close();
                                                    }
                                                }

                                                $stmt->close();
                                                $connection->close();
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