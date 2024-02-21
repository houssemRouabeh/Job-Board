<?php
session_start();
require('../../config/db_connection.php');
$id = $_SESSION['user_id'];

// Requête pour importer les données de l'utilisateur
$sql = "SELECT * FROM employee WHERE id_user = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    }
}

// Requête pour importer les données de l'éducation
$sql_education = "SELECT * FROM education WHERE id_user = ?";
$stmt_education = $connection->prepare($sql_education);

$educations = array(); // Initialiser un tableau pour stocker les données d'éducation

if ($stmt_education) {
    $stmt_education->bind_param('i', $id);
    $stmt_education->execute();
    $result_education = $stmt_education->get_result();

    while ($education = $result_education->fetch_assoc()) {
        $educations[] = $education;
    }
}
// Requête pour importer les données de l'experience
$sql_experience = "SELECT * FROM experience WHERE id_user = ?";
$stmt_experience = $connection->prepare($sql_experience);

$experience = array(); // Initialiser un tableau pour stocker les données d'experience

if ($stmt_experience) {
    $stmt_experience->bind_param('i', $id);
    $stmt_experience->execute();
    $result_experience = $stmt_experience->get_result();

    while ($experience = $result_experience->fetch_assoc()) {
        $experiences[] = $experience;
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

    <title>employee - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style>
    .btn-circle {
        width: 50px;
        height: 50px;
        text-align: center;
        padding: 6px 0;
        font-size: 28px;
        line-height: 1.428571429;
        border-radius: 25px;
    }

    .image-container {
        position: relative;
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: darkcyan;
        /* Couleur du texte */
        font-size: 26px;
        /* Taille du texte */
        font-weight: bolder;
        text-align: center;
        /* Gras */
        opacity: 0;
        /* Caché par défaut */
        transition: opacity 0.3s ease-in-out;
        /* Animation de transition */
    }

    .image-container:hover .overlay-text {
        opacity: 1;
        /* Affiche le texte lorsqu'on survole l'image */
    }
</style>

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Profile</h1>
                    </div>




                    <div class="container">
                        <div class="d-flex justify-content-center mb-4">
                            <label for="customFile2" class="image-container">
                                <img id="selectedAvatar" src="<?php echo $employee['profile_image'] ? $employee['profile_image'] : 'img/undraw_profile.svg'; ?>" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;" alt="example placeholder" />
                                <div class="overlay-text">Click to Change Photo</div>
                            </label>
                        </div>
                        <div class="d-flex justify-content-center">
                            <form action="upload_image.php" method="post" enctype="multipart/form-data">

                                <input type="file" class="form-control" id="customFile2" name="profile_image" style="display: none;" required />

                                <button type="submit" class="btn btn-success">Change Photo</button>
                            </form>
                        </div>



                        <div class="row">
                            <div class="p-5 col-md-12">
                                <form class="user" method="post" action="modifyProfile.php">
                                    <h2 class="mb-4">Personal Details</h2>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="firstName">First Name</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $employee['firstName'] ?>" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $employee['lastName'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $employee['city'] ?>" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="cp">Postal Code</label>
                                            <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $employee['postal_code'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="adress">Adress</label>
                                            <input type="text" class="form-control" id="adress" name="adress" value="<?php echo $employee['adress'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="birthDate">Date of Birth</label>
                                            <input type="date" class="form-control" id="birthDate" name="birthDate" value="<?php echo $employee['birthDate'] ?>" required>
                                        </div>
                                        <div class="form-group col">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $employee['phone'] ?>" required>
                                        </div>
                                    </div>
                                    <hr>
                                    <h2 class="mt-4 mb-4">Academic Background</h2>
                                    <div id="education-container">
                                        <?php
                                        foreach ($educations as $index => $education) {
                                            echo "
                                        <h5 class='mt-4 mb-4'>Education Details</h5>
                                        <div class='row'>
                                            <div class='form-group col'>
                                                <label for='degree_type_$index'>Degree Type</label>
                                                <select class='form-control' name='degree_type_$index' id='degree_type_$index' disabled>
                                                    <option value='Certificate' " . ($education['degree_type'] == 'Certificate' ? 'selected' : '') . ">Certificate</option>
                                                    <option value='Bachelor' " . ($education['degree_type'] == 'Bachelor' ? 'selected' : '') . ">Bachelor</option>
                                                    <option value='Master Degree' " . ($education['degree_type'] == 'Master Degree' ? 'selected' : '') . ">Master Degree</option>
                                                    <option value='PhD' " . ($education['degree_type'] == 'PhD' ? 'selected' : '') . ">PhD Degree</option>
                                                </select>
                                            </div>
                                            <div class='form-group col'>
                                                <label for='field_of_study_$index'>Discipline</label>
                                                <input type='text' class='form-control' id='field_of_study_$index' name='field_of_study_$index' value='{$education['field_of_study']}' required disabled>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='form-group col'>
                                                <label for='institution_$index'>Institution Name</label>
                                                <input type='text' class='form-control' id='institution_$index' name='institution_$index' value='{$education['institution']}' required disabled>
                                            </div>
                                            <div class='form-group col'>
                                                <label for='graduation_year_$index'>Graduation Year</label>
                                                <input type='number' class='form-control' id='graduation_year_$index' name='graduation_year_$index' value='{$education['graduation_year']}' required disabled>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class='form-group col'>
                                                <label for='additional_notes_$index'>Degree Description</label>
                                                <textarea type='text' class='form-control' id='additional_notes_$index' name='additional_notes_$index' rows='3' disabled>{$education['additional_notes']}</textarea>
                                            </div>
                                        </div>
                                        <hr>";
                                        }
                                        ?>

                                    </div>
                                    <div class="row text-center d-flex justify-content-center align-items-center my-3 mb-5">
                                        <button type="button" class="btn btn-primary btn-circle " id="addEducation">+</button> <span class="mx-2">Add Degree</span>
                                    </div>
                                    <hr>
                                    <h2 class="mt-4 mb-4">Professional Experience</h2>
                                    <div id="experience-container">
                                        <?php
                                        foreach ($experiences as $index => $experience) {
                                            echo "
    <h5 class='mt-4 mb-4'>Experience Details</h5>
    <div class='row'>
        <div class='form-group col'>
            <label for='job_title_$index'>Job Title</label>
            <input type='text' class='form-control' id='job_title_$index'' name='job_title_$index'value='{$experience['job_title']}'  required disabled>
        </div>
        <div class='form-group col'>
            <label for='employer_' . $index . ''>Employer</label>
            <input type='text' class='form-control' id='employer_' . $index . '' name='employer_' . $index . '' value='{$experience['company']}' required disabled>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col'>
            <label for='start_date_' . $index . ''>Start Date</label>
            <input type='date' class='form-control' id='start_date_' . $index . '' name='start_date_' . $index . '' value='{$experience['start_date']}' required disabled>
        </div>
        <div class='form-group col'>
            <label for='end_date_' . $index . ''>End Date</label>
            <input type='date' class='form-control' id='end_date_' . $index . '' name='end_date_' . $index . '' value='{$experience['end_date']}' disabled>
        </div>
    </div>
    <div class='row'>
        <div class='form-group col'>
            <label for='job_description_' . $index . ''>Job Description</label>
            <textarea type='text' class='form-control' id='job_description_' . $index . '' name='job_description_' . $index . '' rows='3'disabled >{$experience['achievements']}</textarea>
        </div>
    </div>
    <hr>";
                                        }
                                        ?>

                                    </div>
                                    <div class="row text-center d-flex justify-content-center align-items-center my-3 mb-5">
                                        <button type="button" class="btn btn-primary btn-circle " id="addExperience">+</button> <span class="mx-2">Add Experience</span>
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
            <script>
                $(document).ready(function() {
                    // Counter to keep track of added education and experience sections
                    var educationCounter = 0;
                    var experienceCounter = 0;

                    // Event handler for the "Add Education" button
                    $("#addEducation").click(function() {
                        educationCounter++;

                        // HTML template for the new education section
                        var educationTemplate = `
            <h5 class="mt-4 mb-4">Education Details</h5>
            <div class="row">
                <div class="form-group col">
                    <label for="degree_type_${educationCounter}">Degree Type</label>
                    <select class="form-control" name="degree_type_${educationCounter}" id="degree_type_${educationCounter}">
                        <option value="Certificate">Certificate</option>
                        <option value="Bachelor">Bachelor</option>
                        <option value="Master Degree">Master Degree</option>
                        <option value="PhD">PhD Degree</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="field_of_study_${educationCounter}">Discipline</label>
                    <input type="text" class="form-control" id="field_of_study_${educationCounter}" name="field_of_study_${educationCounter}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="institution_${educationCounter}">Institution Name</label>
                    <input type="text" class="form-control" id="institution_${educationCounter}" name="institution_${educationCounter}" required>
                </div>
                <div class="form-group col">
                    <label for="graduation_year_${educationCounter}">Graduation Year</label>
                    <input type="number" class="form-control" id="graduation_year_${educationCounter}" name="graduation_year_${educationCounter}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="additional_notes_${educationCounter}">Degree Description</label>
                    <textarea type="text" class="form-control" id="additional_notes_${educationCounter}" name="additional_notes_${educationCounter}" rows="3"></textarea>
                </div>
            </div>
            <hr>
        `;

                        // Append the new education section to the container
                        $("#education-container").append(educationTemplate);
                    });

                    // Event handler for the "Add Experience" button
                    $("#addExperience").click(function() {
                        experienceCounter++;

                        // HTML template for the new experience section
                        var experienceTemplate = `
            <h5 class="mt-4 mb-4">Experience Details</h5>
            <div class="row">
                <div class="form-group col">
                    <label for="job_title_${experienceCounter}">Job Title</label>
                    <input type="text" class="form-control" id="job_title_${experienceCounter}" name="job_title_${experienceCounter}" required>
                </div>
                <div class="form-group col">
                    <label for="employer_${experienceCounter}">Employer</label>
                    <input type="text" class="form-control" id="employer_${experienceCounter}" name="employer_${experienceCounter}" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="start_date_${experienceCounter}">Start Date</label>
                    <input type="date" class="form-control" id="start_date_${experienceCounter}" name="start_date_${experienceCounter}" required>
                </div>
                <div class="form-group col">
                    <label for="end_date_${experienceCounter}">End Date</label>
                    <input type="date" class="form-control" id="end_date_${experienceCounter}" name="end_date_${experienceCounter}">
                </div>
            </div>
            <div class="row">
                <div class="form-group col">
                    <label for="job_description_${experienceCounter}">Job Description</label>
                    <textarea type="text" class="form-control" id="job_description_${experienceCounter}" name="job_description_${experienceCounter}" rows="3"></textarea>
                </div>
            </div>
            <hr>
        `;

                        // Append the new experience section to the container
                        $("#experience-container").append(experienceTemplate);
                    });
                });
            </script>

</body>

</html>