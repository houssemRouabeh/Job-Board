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
    $status = 'Open';



    // Insert the offer details into the 'offers' table
    $insertOfferSQL = "INSERT INTO offer (title, description, salary, date_creation, date_end, status, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($insertOfferSQL);

    if ($stmt) {
        $stmt->bind_param("ssisssi", $jobTitle, $jobDesc, $salary, $createdAt, $expirationDate, $status, $id);
        $stmt->execute();
        $offerId = $stmt->insert_id; // Get the ID of the newly inserted offer
        $stmt->close();

        // Insert selected skills into the 'offer_skills' table
        if (isset($_POST['tags']) && is_array($_POST['tags'])) {
            $insertSkillsSQL = "INSERT INTO offer_skills (id_offer, id_skill) VALUES (?, ?)";
            $stmt = $connection->prepare($insertSkillsSQL);

            foreach ($_POST['tags'] as $selectedSkillId) {
                $stmt->bind_param("ii", $offerId, $selectedSkillId);
                $stmt->execute();
            }
            $stmt->close();
            header("location:index.php");
        }
    } else {
        echo "Error inserting offer details: " . $connection->error;
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
                        <h1 class="h3 mb-0 text-gray-800">Job Offers</h1>
                    </div>




                    <div class="container">
                        <div class="row">
                            <div class="p-5 col-md-12">
                                <form class="user" method="post" action="">
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="jobTitle">Job Title</label>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="jobDesc">Job Description</label>
                                            <textarea type="text" class="form-control" id="jobDesc" name="jobDesc" rows="5" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="tags">Skills</label>
                                            <select class="form-control" id="tags" name="tags[]" multiple>
                                                <?php
                                                // Loop through skills array to populate the <select> options
                                                foreach ($skillsArray as $skillId => $skillName) {
                                                    echo "<option value=\"$skillId\">$skillName</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-end">
                                        <div class="form-group col">
                                            <label for="salary">Salary</label>
                                            <input type="number" class="form-control" id="salary" name="salary" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="dateExp">Expiration Date</label>
                                            <input type="date" class="form-control" id="dateExp" name="dateExp" required min="<?php echo date('Y-m-d'); ?>">
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