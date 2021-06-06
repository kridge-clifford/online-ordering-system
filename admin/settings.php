<?php include "includes/header.php"; ?>
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include "includes/navigation.php"; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include "includes/topbar.php"; ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php
                updateEmail();
                updateSignatoryName();

                $current_email_pass = getCurrentEmail();
                while ($rows = $current_email_pass->fetch_assoc()) {
                    $current_email = $rows['email'];
                    $current_password = $rows['password'];
                }

                $result_signatory = retrieveSignatory();

                while($row = $result_signatory->fetch_assoc()){
                    $current_signatory = $row['full_name'];
                    $current_position = $row['position'];
                    $current_contact_no = $row['contact_no'];
                    $current_address = $row['address'];
                }

                ?>
                <h1 class="h3 mb-4 text-gray-800">Email</h1>
                <p class="mb-4">Becareful on updating email. Your email must be SMTP enabled.</p>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Admin Email</h6>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" action="" method="post">
                                    <div class="form-group">
                                        <label for="brand_title">Email Address</label>
                                        <input class="form-control" type="email" name="email" pattern="\S+.*" value="<?= $current_email ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid email
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="brand_title">Password</label>
                                        <input class="form-control" type="password" name="password" pattern="\S+.*" value="<?= $current_password ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a email password
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" name="update" value="Update Email">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Signatory</h6>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" action="" method="post">
                                    <div class="form-group">
                                        <label for="signatory_name">Name</label>
                                        <input class="form-control" type="text" name="signatory_name" maxlength="50" pattern="\S+.*" value="<?= $current_signatory ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid name
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="signatory_position">Position</label>
                                        <input class="form-control" type="text" name="signatory_position" maxlength="50" pattern="\S+.*" value="<?= $current_position ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid position
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="signatory_contact">Contact</label>
                                        <input class="form-control" type="text" name="signatory_contact" maxlength="15" pattern="\S+.*" value="<?= $current_contact_no ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid Contact
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="signatory_address">Address</label>
                                        <textarea class="form-control" name="signatory_address" id="" cols="30" rows="2" maxlength="255" required><?= $current_address ?></textarea>
                                        <div class="invalid-feedback">
                                            Please enter a valid Address
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" name="update_signatory" value="Update Name">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

      
        <?php include "includes/footer.php"; ?>