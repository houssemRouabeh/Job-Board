<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];
// Requête SQL pour récupérer les offres de l'utilisateur
$sql = "SELECT * FROM offer WHERE id_offer = ?";
$id_offer = $_GET['offer_id'];
$stmt = $connection->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id_offer);
    $stmt->execute();
    $result = $stmt->get_result();
    $offer = $result->fetch_assoc();
}
$offer_title = $offer['title'];

$dateCreationString = $offer['date_creation'];
$dateObject = new DateTime($dateCreationString);

// Récupérer le jour en nombre
$dayCreationNumber = $dateObject->format('d');

// Récupérer le mois en lettre
$monthCreationName = $dateObject->format('F');

// Récupérer l'année en nombre
$yearCreationNumber = $dateObject->format('Y');
$dateCreationFormat = $dayCreationNumber . ' ' . $monthCreationName . ', ' . $yearCreationNumber; // Formatage de la date de création

$dateExpirationString = $offer['date_end'];
$dateObject = new DateTime($dateExpirationString);

// Récupérer le jour en nombre
$dayExpirationNumber = $dateObject->format('d');

// Récupérer le mois en lettre
$monthExpirationName = $dateObject->format('F');

// Récupérer l'année en nombre
$yearExpirationNumber = $dateObject->format('Y');
$dateExpirationFormat = $dayExpirationNumber . ' ' . $monthExpirationName . ', ' . $yearExpirationNumber; // Formatage de la date d'expiration

$skillsQuery = "SELECT id_skill, name FROM skills";
$skillsResult = $connection->query($skillsQuery);

if ($skillsResult) {
    while ($row = $skillsResult->fetch_assoc()) {
        $skillsArray[$row['id_skill']] = $row['name'];
    }
} else {
    // Handle the error if the skills query fails
    echo "Error fetching skills: " . $connection->error;
}


$selectedSkills = array();

// Requête SQL pour récupérer les compétences associées à l'offre spécifique
$skillsQuery = "SELECT id_skill FROM offer_skills WHERE id_offer = ?";
$stmtSkills = $connection->prepare($skillsQuery);

if ($stmtSkills) {
    $stmtSkills->bind_param('i', $id_offer);
    $stmtSkills->execute();
    $resultSkills = $stmtSkills->get_result();

    while ($rowSkills = $resultSkills->fetch_assoc()) {
        $selectedSkills[] = $rowSkills['id_skill'];
    }

    $stmtSkills->close();
} else {
    // Gérer l'erreur si la requête échoue
    echo "Error fetching selected skills: " . $connection->error;
}
//requette pour importer les données du createur d'emploi
$sql = "SELECT * FROM employer WHERE id_user = ?";
$stmt = $connection->prepare($sql);
$id_employer = $offer['id_user'];
if ($stmt) {
    $stmt->bind_param('i', $id_employer);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employer = $result->fetch_assoc();
        $offer_logo = $employer['logo'];
    }
}

//requette pour verifier si l'utilisateur a postuler  ou non a cette offre
$sql = "SELECT * FROM job_application WHERE id_user = ? AND id_offer = ?";
$stmt = $connection->prepare($sql);
$id_employer = $offer['id_user'];
$havePosytuled = false;
if ($stmt) {
    $stmt->bind_param('ii', $id, $id_offer);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $havePosytuled = true;
    }
}

// Close the skills query result
$skillsResult->close();

$connection->close();

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <h1 class="h3 mb-0 text-gray-800">Offer Details</h1>
                    </div>

                    <!-- Page content-->
                    <div class="container">
                        <div class="row mt-4">
                            <!-- Blog entries-->
                            <div class="col-lg-8">
                                <!-- Featured blog post-->
                                <div class="card mb-4">
                                    <a href="#!"><img class="card-img-top" src="<?php echo '../employer/' . $offer_logo; ?>" alt="company logo" /></a>
                                    <div class="card-body">
                                        <div class="small text-muted">Published : <?php echo $dateCreationFormat; ?></div>
                                        <h2 class="card-title"><?php echo $offer_title; ?></h2>
                                        <p class="card-text">
                                            <?php echo $offer['description']; ?>
                                        </p>
                                        <a class="btn btn-primary" href="companyDetails.php?company_id=<?php echo $offer['id_user']; ?>">About Company →</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Side widgets-->
                            <div class="col-lg-4">
                                <!-- Search widget-->
                                <div class="card mb-4">
                                    <div class="card-header">Application Deadline</div>
                                    <div class="card-body">
                                        <h2 class="text-primary"><?php echo $dateExpirationFormat; ?></h2>
                                    </div>
                                </div>
                                <!-- Categories widget-->
                                <div class="card mb-4">
                                    <div class="card-header">Skills</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <ul class="list-unstyled mb-0">
                                                    <?php
                                                    // Requête SQL pour récupérer les compétences associées à l'offre
                                                    $sqlSkills = "SELECT name FROM skills s, offer_skills os WHERE os.id_skill = s.id_skill AND id_offer = ?";
                                                    $stmtSkills = $connection->prepare($sqlSkills);

                                                    if ($stmtSkills) {
                                                        $stmtSkills->bind_param('i', $id_offer);
                                                        $stmtSkills->execute();
                                                        $resultSkills = $stmtSkills->get_result();

                                                        // Boucle pour parcourir les compétences
                                                        $skillsArray = [];
                                                        while ($skill = $resultSkills->fetch_assoc()) {
                                                            $skillsArray[] = $skill['name'];
                                                        }

                                                        // Affichage des compétences dans une liste
                                                        foreach ($skillsArray as $skillName) {
                                                            echo "<li>{$skillName}</li>";
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Side widget-->
                                <div class="card mb-4">

                                    <?php
                                    if ($havePosytuled) {
                                        echo "<a class='btn btn-danger' href='cancelApplication.php?offer_id=$id_offer&offer_name=$offer_title'>Cancel your application →</a>";
                                    } else {
                                        echo "<a class='btn btn-primary' href='jobApplication.php?offer_id=$id_offer&offer_name=$offer_title'>Send in your application →</a>";
                                    }

                                    ?>


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
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>