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

                <h1 class="h3 mb-4 text-gray-800">Categories</h1>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        if (isset($_GET['edit'])) {
                            include "includes/update_categories.php";
                        }
                        ?>
                        <div class="card shadow mb-4">
                            <?php addCategory(); ?>
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add Category</h6>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" action="" method="post" novalidate>
                                    <div class="form-group">
                                        <label for="category_title">Category Title</label>
                                        <input class="form-control" type="text" name="category_title" pattern="\S+.*" required>
                                        <div class="invalid-feedback">
                                            Please enter a category
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">All Categories</h6>
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

                                            $result = selectAllCategories();
                                            while ($row = $result->fetch_assoc()) {
                                                $category_id = $row['category_id'];
                                                $category_title = $row['category_title'];

                                                ?>
                                                <tr>
                                                    <td><?= $category_id ?></td>
                                                    <td><?= $category_title ?></td>
                                                    <td class="p-3" style="width: 30%">
                                                        <a class="btn btn-success mb-2" data-toggle="tooltip" title="Edit" href='categories.php?edit=<?= $category_id ?>'><i class="fa fa-edit fa-md"></i></a>
                                                        <a class="btn btn-danger mb-2" data-toggle="tooltip" title="Delete" href='categories.php?delete=<?= $category_id ?>'><i class="fa fa-trash fa-md"></i></a>
                                                    </td>
                                                </tr>

                                            <?php } ?>
                                            <?php deleteCategory(); ?>
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

        <?php addValidation(); ?>
        <?php include "includes/footer.php"; ?>