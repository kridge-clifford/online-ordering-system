<?php
$orders_count = ordersCount();
$orders_html = "";
$orders_total_count = $orders_count->num_rows;

if ($orders_total_count > 0) {
    $orders_html = $orders_total_count;
}

?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <div class="input-group w-25" id="datetime">
    </div>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="./orders.php">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter"><?= $orders_html ?></span>
                <?php

                ?>
            </a>
        </li>
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username']; ?></span>
                <img class="img-profile rounded-circle" src="../images/user.jpg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>

    <!-- User Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title font-weight-bold" id="changePasswordModalLabel">Change Password</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="changePasswordContent">
                    <form class="change-password">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                        <div class="form-group password">
                            <input type="password" class="form-control" placeholder="Current Password" name="password" id="password" pattern="\S+.*">
                            <label class="invalid-feedback">
                                Please enter current password.
                            </label>
                        </div>
                        <div class="form-group new_password">
                            <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password" pattern="\S+.*">
                            <label class="invalid-feedback">
                                Please enter new password.
                            </label>
                        </div>
                        <div class="form-group confirm_password">
                            <input type="password" class="form-control" placeholder="Re-type Password" name="confirm_password" id="confirm_password" pattern="\S+.*">
                            <label class="invalid-feedback">
                                Please re-type password.
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="change_password" class="btn btn-primary btn-block">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function() {


        setInterval(() => {
            var dt = new Date();
            $("#datetime").html(dt.toLocaleString());
        }, 1000);
    });

    function validate() {
        var password = $("#password").val();
        var new_password = $("#new_password").val();
        var confirm_password = $("#confirm_password").val();
        var patt = /(?=.*[A-Z])/g;
        console.log(patt.test(password.trim()));
        $(".change-password .invalid-feedback").hide();
        if (password.trim() == "") {
            $(".password .invalid-feedback").text("Please enter current password.");
            $(".password .invalid-feedback").show();
            return false;
        } else if (new_password.trim() == "") {
            $(".new_password .invalid-feedback").text("Please enter new password.");
            $(".new_password .invalid-feedback").show();
            return false;
        } else if (confirm_password.trim() == "") {
            $(".confirm_password .invalid-feedback").text("Please re-type password.");
            $(".confirm_password .invalid-feedback").show();
            return false;
        } else if (!patt.test(new_password.trim())) {
            $(".new_password .invalid-feedback").text("Should contain at least one upper case.");
            $(".new_password .invalid-feedback").show();
            return false;
        } else if (new_password.trim().length < 8 || new_password.trim().length > 12) {
            $(".new_password .invalid-feedback").text("Should contain at least 8-12 characters.");
            $(".new_password .invalid-feedback").show();
            return false;
        } else if (confirm_password.trim() != new_password.trim()) {
            $(".confirm_password .invalid-feedback").text("Passwords do not match.");
            $(".confirm_password .invalid-feedback").show();
            return false;
        }

        return true;
    }

    function clear_fields() {
        $("#confirm_password").val("");
        $("#password").val("");
        $("#new_password").val("");
    }

    $(function() {

        $('.change-password').on("submit", function(e) {
            e.preventDefault();

            if (!validate()) {
                return;
            }

            $.ajax({
                url: 'includes/change_password.php',
                method: "post",
                data: $('.change-password').serialize(),
                success: function(data) {
                    JSON.stringify(data);
                    console.log();
                    if (data.success) {
                        clear_fields();
                        $("#changePasswordModal").modal('toggle');
                        // alert("Password successfuly changed.")

                        $("#successModal .success-modal-content").html("Password Changed");
                        $("#successModal").modal('toggle');

                    } else {
                        $(".password .invalid-feedback").text(data.message);
                        $(".password .invalid-feedback").show();
                    }
                },
            });
        });
    });
</script>