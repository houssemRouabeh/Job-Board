<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];
//requette pour importer les données de l'utilisateur
$sql = "SELECT * FROM Employee WHERE id_user = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $Employee = $result->fetch_assoc();
    }
    // Vérifier si tous les champs ne sont pas nuls
    $allFieldsNotNull = true;
    foreach ($Employee as $key => $value) {
        if ($value === null) {
            $allFieldsNotNull = false;
            break; // Sortir de la boucle dès qu'un champ null est trouvé
        }
    }
}
$currentDate = date('Y-m-d');

// Mettez à jour les offres expirées
$sql = "UPDATE offer SET status = 'Expired' WHERE date_end <= ? AND status = 'Open'";
$stmt2 = $connection->prepare($sql);
$stmt2->bind_param('s', $currentDate);
$stmt2->execute();


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employee - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
    <link rel="stylesheet" href="css/style.css">


</head>


<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'employeeSidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'employeeNav.php' ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h1 class="h3 mb-0 text-gray-800">Jobs List</h1>
                    </div>

                    <?php
                    if (!$allFieldsNotNull) {
                        echo '<a href="profile.php" class="alert-link"><div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Innactif Account ! </strong> You should complete your profile.
                      </div></a>';
                    }

                    ?>
                    <section class="ftco-section">
                        <div class="container">
                            <div class="d-flex justify-content-around flex-wrap row-gap-3">
                                <?php

                                // Requête SQL pour récupérer les offres de l'utilisateur
                                $sql = "SELECT * FROM offer ";
                                $stmt = $connection->prepare($sql);

                                if ($stmt) {
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Boucle pour parcourir les offres
                                    while ($offer = $result->fetch_assoc()) {
                                        $offerId = $offer['id_offer'];
                                        $dateString = $offer['date_end'];
                                        $dateObject = new DateTime($dateString);

                                        // Récupérer le jour en nombre
                                        $dayNumber = $dateObject->format('d');

                                        // Récupérer le mois en lettre
                                        $monthName = $dateObject->format('F');

                                        // Récupérer l'année en nombre
                                        $yearNumber = $dateObject->format('Y');


                                        echo "
<div class='item col-md-4'> 
    <div class='blog-entry'>
        <a href='#' class='block-20 d-flex align-items-start' style=\"background-image: url('" . ('images/image_3.jpg') . "');\">
            <div class='meta-date text-center p-2'>
                " . ($offer['status'] == 'Open' ?
                                            "<span class='mos'>Expire In</span>
                    <span class='day'>$dayNumber</span>
                    <span class='mos'>$monthName.</span>
                    <span class='yr'>$yearNumber" : "<span class='day'>Expired</span>") . "
            </div>
        </a>
        <div class='text border border-top-0 p-4'>
            <h3 class='heading'><a href='#'>" . $offer["title"] . "</a></h3>
            <p>" . $offer["description"] . ".</p>
            <div class='d-flex align-items-center mt-4'>
                <p class='mb-0'><a href='offerDetails.php?offer_id=" . $offer['id_offer'] . "' class='btn btn-primary' " . ($offer['status'] == 'Expired' ? 'disabled' : '') . ">Read Details <span class='ion-ios-arrow-round-forward'></span></a></p>
            </div>
        </div>
    </div>
</div>";
                                    }


                                    $stmt->close();
                                    $connection->close();
                                }
                                ?>

                            </div>
                        </div>



                    </section>
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

</body>

</html>