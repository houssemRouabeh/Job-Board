<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/authStyle.css">
    <title>Document</title>
</head>

<body>
    <br>
    <br>
    <div class="cont">
        <div class="form sign-in mt-5">
            <form action="signin.php" method="post">
                <h2>Welcome</h2>
                <label>
                    <span>Email</span>
                    <input type="email" required name="login" />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" required name="password" />
                </label>
                <button type="submit" class="submit" name="signin">Sign In</button>
            </form>
            <?php
            // Check for and display success message
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success text-center">' . $_SESSION['success_message'] . '</div>';
                // Remove the success message from session to avoid displaying it again on page refresh
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['alert_message'])) {
                echo '<div class="alert alert-danger text-center">' . $_SESSION['alert_message'] . '</div>';
                // Remove the success message from session to avoid displaying it again on page refresh
                unset($_SESSION['alert_message']);
            }
            ?>
        </div>
        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up mt-5">

                    <h3>Don't have an account? Please Sign up!<h3>
                </div>
                <div class="img__text m--in mt-5">

                    <h3>If you already has an account, just sign in.<h3>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            <div class="form sign-up">

                <h2>Create your Account</h2>
                <label class="radio">
                    <span>Employee</span>
                    <input id="employee" value="employee" type="radio" name="type" checked onchange="changerFormulaire()" />
                    <span>Employer</span>
                    <input id="employer" value="employer" type="radio" name="type" onchange="changerFormulaire()" />
                </label>
                <form id="employeeForm" action="register.php" method="POST">
                    <label>
                        <span>Email</span>
                        <input type="email" required name="email" />
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" required name="password" />
                    </label>
                    <button type="submit" class="submit" name="employeeSubmit">Sign Up</button>
                </form>
                <form id="employerForm" action="register.php" method="POST">
                    <label>
                        <span>Company name</span>
                        <input type="text" required name="name" />
                    </label>
                    <label>
                        <span>Industry</span>
                        <input type="text" required name="industry" />
                    </label>
                    <label>
                        <span>Login</span>
                        <input type="email" required name="login" />
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" required name="password" />
                    </label>
                    <button type="submit" class="submit" name="employerSubmit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Ajoutez les liens vers les fichiers Bootstrap JS et jQuery ici -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });
        document.getElementById("employerForm").style.display = "none";

        function changerFormulaire() {
            let choix = document.querySelector('input[name="type"]:checked').value;

            // Masquer tous les formulaires
            document.getElementById("employeeForm").style.display = "none";
            document.getElementById("employerForm").style.display = "none";

            // Afficher le formulaire correspondant au choix du client
            document.getElementById(choix + "Form").style.display = "block";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>