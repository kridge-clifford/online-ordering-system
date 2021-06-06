<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
    </div>
    <?php
    
    if (isset($_GET['edit'])) {
        $category_id = $_GET['edit'];

        $query = "SELECT category_id, category_title FROM categories WHERE category_id = {$category_id}";
        $select_all_categories_id = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_all_categories_id)) {
            $category_id = $row['category_id'];
            $category_title = $row['category_title'];
        }
    }

    ?>
    <div class="card-body">
        <form class="needs-validation" action="" method="post" novalidate>
            <div class="form-group">
                <label for="category_title">Category Title</label>
                <input class="form-control" type="text" name="category_title" value="<?=$category_title?>" required>
                <div class="invalid-feedback">
                    Please enter a category
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="update_category" value="Update Category">
            </div>
        </form>
    </div>
    <?php
        if (isset($_POST['update_category'])) { 
            $category_title = $_POST['category_title'];

            if(boolCategoryExist($category_title, $category_id)) {
                echo '<script>alert("Category already exists!"); location.href = "./categories.php" </script>';
                exit;
            }

            $query = "UPDATE categories SET category_title = '{$category_title}' WHERE category_id = {$category_id}; ";
            $update_query = mysqli_query($connection, $query);
            if (!$update_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
            header("Location: categories.php");
        }

        ?>
</div>