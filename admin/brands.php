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

                <h1 class="h3 mb-4 text-gray-800">Brands</h1>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        if (isset($_GET['edit'])) {
                            include "includes/update_brands.php";
                        }
                        ?>
                        <div class="card shadow mb-4">
                            <?php addbrand(); ?>
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add Brand</h6>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" action="" method="post" novalidate>
                                    <div class="form-group">
                                        <label for="brand_title">Brand Title</label>
                                        <input class="form-control" type="text" name="brand_title" pattern="\S+.*" required>
                                        <div class="invalid-feedback">
                                            Please enter a brand
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" name="submit" value="Add brand">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">All Brands</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Id</th>
                                                <th>Title</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php

                                            $result = selectAllBrands();
                                            while ($row = $result->fetch_assoc()) {
                                                $brand_id = $row['brand_id'];
                                                $brand_title = $row['brand_title'];

                                                ?>
                                                <tr>
                                                    <td><?= $brand_id ?></td>
                                                    <td><?= $brand_title ?></td>
                                                    <td class="p-3" style="width: 30%">
                                                        <a class="btn btn-success mb-2" data-toggle="tooltip" title="Edit" href='brands.php?edit=<?= $brand_id ?>'><i class="fa fa-edit fa-md"></i></a>
                                                        <a class="btn btn-danger mb-2" data-toggle="tooltip" title="Delete" href='brands.php?delete=<?= $brand_id ?>'><i class="fa fa-trash fa-md"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php deletebrand(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <script>
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
        <?php include "includes/footer.php"; ?>