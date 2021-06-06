
<?php
if (isset($_GET['action'])) {
    session_destroy();
    header("Location: ./login.php");
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

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-6 col-md-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" method="post">
                                    <div class="form-group username">
                                        <input type="text" class="form-control form-control-user" id="username" aria-describedby="emailHelp" placeholder="Username" name="username">
                                        <label class="invalid-feedback pl-3">
                                            Please re-type password.
                                        </label>
                                    </div>
                                    <div class="form-group password">
                                        <input type="password" class="form-control form-control-user" id="password" placeholder="Password" name="password">
                                        <label class="invalid-feedback pl-3">
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember Me</label>
                                        </div>
                                    </div>
                                    <input class="btn btn-primary btn-user btn-block" type="submit" name="login" value="Login">
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <script>
            function validate() {
                var username = $("#username").val();
                var password = $("#password").val();

                $(".user .invalid-feedback").hide();
                if (username.trim() == "") {
                    $(".username .invalid-feedback").text("Please enter username.");
                    $(".username .invalid-feedback").show();
                    return false;
                } else if (password.trim() == "") {
                    $(".password .invalid-feedback").text("Please enter password.");
                    $(".password .invalid-feedback").show();
                    return false;
                }
                return true;
            }

            $(function() {
                $('.user').on("submit", function(e) {
                    e.preventDefault();

                    if (!validate()) {
                        return;
                    }
                    $.ajax({
                        url: 'includes/check_login.php',
                        method: "post",
                        data: $('.user').serialize(),
                        success: function(data) {
                            var obj = JSON.parse(data); 
                            if (obj.success) {
                                location.href = "./index.php";

                            } else {
                                alert(obj.message);
                               $(function(){
                                    $("#username").val("");
                                    $("#password").val("");
                                });
                            }
                        },
                    });
                });
            });
        </script>

</body>

</html>