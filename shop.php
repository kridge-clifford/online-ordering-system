<?php
$current_page = 1;
if (isset($_GET['page'])) {
    $current_page = $_GET['page'];
}

$selected_category = 0;
if (isset($_GET['category'])) {
    $selected_category = $_GET['category'];
}

$selected_brands = 0;
if (isset($_GET['brand'])) {
    $selected_brands = $_GET['brand'];
}


$limit = 12;
$offset = ($current_page - 1)  * $limit;

$product = selectAllProducts($limit, $offset, $selected_category, $selected_brands);
$product_total_count = $product->num_rows;

$product_count = selectAllProductsRowCount($selected_category, $selected_brands);

$pages = ceil($product_count / $limit);
?>


<!-- Hero Section-->
<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Shop</h1>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<main>
    <div class="container">
        <div class="row">
            <!-- Grid -->
            <div class="products-grid col-xl-9 col-lg-8 sidebar-right">
                <header class="d-flex justify-content-between align-items-start"><span class="visible-items">Showing <strong>1-<?= $product_total_count ?> </strong>of <strong><?= $product_count ?> </strong>results</span>
                </header>
                <div class="row">

                    <?php
                    $product_rows = $product->num_rows;
                    if ($product_rows != 0) {
                        while ($rows = $product->fetch_assoc()) {
                            ?>
                            <div class="item col-xl-4 col-md-6">
                                <div class="product is-gray">
                                    <div class="image d-flex align-items-center justify-content-center img-prod-padding">
                                        <img src="images/<?= $rows['product_image']; ?>" alt="product" class="img-fluid img-prod">
                                        <div class="hover-overlay d-flex align-items-center justify-content-center">
                                            <div class="CTA d-flex align-items-center justify-content-center product-content">
                                                <a href="index.php?option=detail&id=<?= $rows['product_id']; ?>" class="visit-product active">
                                                    <i class="icon-search"></i>View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="title"><small class="text-muted"><?= $rows['category_title'] ?></small><a href="index.php?option=detail">
                                            <h3 class="h6 text-uppercase no-margin-bottom"><?= $rows['product_name'] ?></h3>
                                        </a><span class="price text-muted">₱<?= $rows['product_price'] ?></span></div>
                                </div>
                            </div>

                        <?php }
                        } else {
                            ?>
                        <h1>No result</h1>

                    <?php } ?>


                </div>
                <nav aria-label="page navigation example" class="d-flex justify-content-center">
                    <ul class="pagination pagination-custom">
                        <li class="page-item"><a href="#" aria-label="Previous" class="page-link"><span aria-hidden="true">Prev</span><span class="sr-only">Previous</span></a></li>

                        <?php

                        for ($i = 1; $i <= $pages; $i++) {
                            $active = "";
                            if ($current_page == $i) {
                                $active = "active";
                            }
                            echo '<li class="page-item"><a href="./index.php?option=shop&page=' . $i . '" class="page-link ' . $active . '">' . $i . ' </a></li>';
                        }
                        ?>

                        <li class="page-item"><a href="#" aria-label="Next" class="page-link"><span aria-hidden="true">Next</span><span class="sr-only">Next </span></a></li>
                    </ul>
                </nav>
            </div>
            <div class="sidebar col-xl-3 col-lg-4 sidebar-right">
                <div class="block">
                    <h6 class="text-uppercase">Product Categories</h6>
                    <ul class="list-unstyled">

                        <?php
                        $categories = selectAllCategories($selected_brands);

                        while ($category_result = $categories->fetch_assoc()) {
                            $is_category_active = "";
                            $selected_category_brand = "";
                            if ($selected_brands != 0) {
                                $selected_category_brand = "&brand=" . $selected_brands;
                            }
                            $category_href = "./index.php?option=shop&page=1&category=" . $category_result['category_id'] . $selected_category_brand;
                            if ($selected_category == $category_result['category_id']) {
                                $is_category_active = "class='active'";
                                $category_href = "./index.php?option=shop&";
                            }
                            ?>
                            <li <?= $is_category_active ?>><a href="<?= $category_href ?>" class="d-flex justify-content-between align-items-center"><span><?= $category_result['category_title']; ?></span><small><?= $category_result['product_count']; ?></small></a></li>
                        <?php } ?>

                    </ul>
                </div>
                <div class="block">
                    <h6 class="text-uppercase">Brands </h6>
                    <form action="#">
                        <?php
                        $brands = selectAllBrands($selected_category);

                        while ($brands_result = $brands->fetch_assoc()) {
                            ?>
                            <div class="form-group mb-1">
                                <input id="brand<?= $brands_result['brand_id']; ?>" type="checkbox" name="brand[]" value="<?= $brands_result['brand_id']; ?>" class="checkbox-template checkbox-brands" <?php if ($brands_result['brand_id'] == $selected_brands) echo "checked" ?>>
                                <label for="brand<?= $brands_result['brand_id']; ?>"><?= $brands_result['brand_title']; ?> <small>(<?= $brands_result['product_count']; ?>)</small></label>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Overview Popup    -->
<div id="productDetailModal" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade overview">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="icon-close"></i></span></button>
            <div class="modal-body">
                <div class="row d-flex align-items-center product-detail-body">


                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="modalAllowConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-header btn-secondary" style="border-bottom:0;">Are You Above 18 years old?</h4>
            </div>
            <div class="modal-body text-center" id="changePasswordContent">
                <button type="button" class="btn btn-primary allowedage" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-default notallowed" data-dismiss="modal">No</button>
            </div>
            <div class="modal-footer d-flex justify-content-center">

            </div>
        </div>
    </div>
</div>
<div id="scrollTop"><i class="fa fa-long-arrow-up"></i></div>
<script>
    $(document).ready(function() {
        $(".notallowed").click(function() {
            alert("Under age are not allowed to buy.");
            location.href = "./index.php";
        });

        $(".allowedage").click(function() {
            $.ajax({
                url: 'includes/age_confirmation.php',
                method: 'post',
                data: {
                    confirm: true,
                },
                success: function(data) {}
            });
        });
        <?php
        if (!isset($_SESSION['permission_age'])) {
            ?>
            $(window).on('load', function() {
                $('#modalAllowConfirmation').modal('show');
            });

        <?php } ?>

        $(".quick-view").click(function(e) {
            e.preventDefault();

            var product_id = $(this).attr("id");

            $.ajax({
                url: 'includes/product_detail.php',
                method: "post",
                data: {
                    product_id: product_id
                },
                success: function(response) {
                    $("#productDetailModal .product-detail-body").html(response);
                    $("#productDetailModal").modal("show");
                }
            });
        });

        $('.checkbox-brands').change(function() {
            let searchParams = new URLSearchParams(window.location.search)
            var category = "";
            var page = "&page=1";
            var brands = "";
            brands = "&brand=" + $(this).val();
            if (searchParams.has('brand')) {
                if (searchParams.get('brand') == $(this).val()) {
                    location.href = "./index.php?option=shop";
                    return;
                }
            }

            if (searchParams.has('category')) {
                var category = "&category=" + searchParams.get('category');
            }

            if (searchParams.has('page')) {
                var page = "&page=" + searchParams.get('page');
            }

            location.href = "./index.php?option=shop" + page + category + brands;
        });

        $("#cart-items").on("click", ".delete-item", function(e) {
            e.preventDefault();
            var product_id = $(this).attr("id");

            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id,
                    type: 'delete',
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $("#cart-items").html(obj.content);
                    $(".cart-no").text(obj.count);
                    $("#cart-body").addClass("show");
                    $(".dropdown-menu").addClass("show");
                }
            });
        });

        $(".product-content .add-to-cart").click(function(e) {
            e.preventDefault();
            var product_id = $(this).attr("id");
            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id,
                    type: 'new',
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    if (obj.success) {
                        if (obj.type === "new") {
                            $("#cart-items").html(obj.content);
                            $(".cart-no").text(obj.count);
                        }
                        $("#myToast").toast('dispose');
                        $("#myToast").toast("show");
                    }
                }
            });
        });
    });
</script>