<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];
// Requête SQL pour récupérer les offres de l'utilisateur
$sql = "SELECT * FROM offer WHERE id_offer = ?";
$id_offer = $_GET['offerId'];
$stmt = $connection->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id_offer);
    $stmt->execute();
    $result = $stmt->get_result();
    $offer = $result->fetch_assoc();
}

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
// ... (votre code existant)

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


// Close the skills query result
$skillsResult->close();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    // Retrieve other job-related details from the form
    $jobTitle = $_POST['jobTitle'];
    $jobDesc = $_POST['jobDesc'];
    $salary = $_POST['salary'];
    $dateDuJour = new DateTime();
    $createdAt = $dateDuJour->format("Y-m-d H:i:s");
    $dateExp = $_POST['dateExp'];
    $expirationDateTime = new DateTime($dateExp . ' ' . $dateDuJour->format('H:i:s'));
    $expirationDate = $expirationDateTime->format("Y-m-d H:i:s");
    $status =  ($expirationDate - $createdAt < 0) ? 'Expired' : 'Open';



    $updateOfferSQL = "UPDATE offer SET title = ?, description = ?, salary = ?, date_end = ?, status=? WHERE id_offer = ?";
    $stmtUpdateOffer = $connection->prepare($updateOfferSQL);

    if ($stmtUpdateOffer) {
        $stmtUpdateOffer->bind_param("ssissi", $jobTitle, $jobDesc, $salary, $expirationDate, $status, $id_offer);
        $stmtUpdateOffer->execute();
        $stmtUpdateOffer->close();

        // Supprimer d'abord les compétences existantes associées à l'offre
        $deleteSkillsSQL = "DELETE FROM offer_skills WHERE id_offer = ?";
        $stmtDeleteSkills = $connection->prepare($deleteSkillsSQL);

        if ($stmtDeleteSkills) {
            $stmtDeleteSkills->bind_param("i", $id_offer);
            $stmtDeleteSkills->execute();
            $stmtDeleteSkills->close();

            // Insérer ensuite les compétences sélectionnées
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                $insertSkillsSQL = "INSERT INTO offer_skills (id_offer, id_skill) VALUES (?, ?)";
                $stmtInsertSkills = $connection->prepare($insertSkillsSQL);

                foreach ($_POST['tags'] as $selectedSkillId) {
                    $stmtInsertSkills->bind_param("ii", $id_offer, $selectedSkillId);
                    $stmtInsertSkills->execute();
                }

                $stmtInsertSkills->close();
            }

            header("location:offersList.php");
        } else {
            echo "Error deleting existing skills: " . $connection->error;
        }
    } else {
        echo "Error updating offer details: " . $connection->error;
    }
}
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
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h1 class="h3 mb-0 text-gray-800">Offer Details</h1>
                    </div>




                    <div class="container">
                        <div class="row">
                            <div class="p-5 col-md-12">
                                <form class="user" method="post" action="">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="jobTitle">Job Title</label>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" required value="<?php echo $offer['title']  ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="jobDesc">Job Description</label>
                                            <textarea type="text" class="form-control" id="jobDesc" name="jobDesc" rows="5" required><?php echo $offer['description']  ?></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="tags">Skills</label>
                                            <select class="form-control" id="tags" name="tags[]" multiple>

                                                <?php
                                                // Loop through skills array to populate the <select> options
                                                foreach ($skillsArray as $skillId => $skillName) {
                                                    $selected = in_array($skillId, $selectedSkills) ? 'selected' : '';
                                                    echo "<option value=\"$skillId\" $selected>$skillName</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-end">
                                        <div class="form-group col">
                                            <label for="salary">Salary</label>
                                            <input type="number" class="form-control" id="salary" name="salary" required value="<?php echo $offer['salary']  ?>">
                                        </div>
                                        <div class="form-group col">
                                            <label for="dateExp">Expiration Date</label>
                                            <?php
                                            // Convertir la date de la base de données au format "YYYY-MM-DD"
                                            $formattedDate = date('Y-m-d', strtotime($offer['date_end']));
                                            ?>
                                            <input type="date" class="form-control" id="dateExp" name="dateExp" required min="<?= date('Y-m-d') ?>" value="<?php echo $formattedDate ?>">
                                        </div>
                                    </div>
                                    <button type="submit" name="add_offer" class="btn btn-primary btn-user btn-block">
                                        Add Offer
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
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>

            </script>

</body>

</html>