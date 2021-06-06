<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit brand</h6>
    </div>
    <?php

    if (isset($_GET['edit'])) {
        $brand_id = $_GET['edit'];

        $query = "SELECT brand_id, brand_title FROM brand WHERE brand_id = {$brand_id}";
        $select_all_brands_id = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_all_brands_id)) {
            $brand_id = $row['brand_id'];
            $brand_title = $row['brand_title'];
        }
    }

    ?>
    <div class="card-body">
        <form class="needs-validation" action="" method="post" novalidate>
            <div class="form-group">
                <label for="brand_title">brand Title</label>
                <input class="form-control" type="text" name="brand_title" value="<?= $brand_title ?>" required>
                <div class="invalid-feedback">
                    Please enter a brand
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="update_brand" value="Update Brand">
            </div>
        </form>
    </div>
    <?php
    if (isset($_POST['update_brand'])) {
        $brand_title = $_POST['brand_title'];

        if(boolBrandExist($brand_title, $brand_id)) {
            echo '<script>alert("Brand already exists!"); location.href = "./brands.php" </script>';
            exit;
        }

        $query = "UPDATE brand SET brand_title = '{$brand_title}' WHERE brand_id = {$brand_id}; ";
        $update_query = mysqli_query($connection, $query);
        if (!$update_query) {
            die("QUERY FAILED" . mysqli_error($connection));
        }
        header("Location: brands.php");
    }

    ?>
</div>