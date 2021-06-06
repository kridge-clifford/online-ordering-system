<h1 class="h3 mb-4 text-gray-800">All Products</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Products</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Thumbnail</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Thumbnail</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php

                    $result = selectAllProducts();
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row['product_id'];
                        $category_title = $row['category_title'];
                        $brand_title = $row['brand_title'];
                        $product_name = $row['product_name'];
                        $product_description = $row['product_description'];
                        $product_quantity = $row['product_quantity'];
                        $product_price = $row['product_price'];
                        $product_image = $row['product_image'];
                        ?>
                        <tr>
                            <td><?= $product_id ?></td>
                            <td><?= $product_name ?></td>
                            <td><?= $category_title ?></td>
                            <td><?= $brand_title ?></td>
                            <td><?= $product_description ?></td>
                            <td><?= $product_quantity ?></td>
                            <td><?= $product_price ?></td>
                            <td><img width='100' src='../images/<?= $product_image ?>'></td>
                            <td class="p-3" style="text-align:center;">
                                <a class="btn btn-success mb-2" data-toggle="tooltip" title="Edit" href='products.php?source=edit_product&product_id=<?= $product_id ?>'>
                                    <i class="fa fa-edit fa-md"></i>
                                </a>
                                <a class="btn btn-danger mb-2" data-toggle="tooltip" title="Delete" href='products.php?delete=<?= $product_id ?>'>
                                    <i class="fa fa-trash fa-md"></i>
                                </a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php deleteProduct(); ?>