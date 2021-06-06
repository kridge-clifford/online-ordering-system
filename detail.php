<?php
if (isset($_GET["id"])) {

    $product = selectedProductById($_GET["id"]);

    while ($rows = $product->fetch_assoc()) {
        $product_name = $rows["product_name"];
        $product_image = $rows["product_image"];
        $product_price = $rows["product_price"];
        $product_description = $rows["product_description"];
        $product_variant = $rows["product_variant"];
    }

    $variant_result = selectAllProductVariants($_GET["id"]);
    $variant_html = "";

    $add_active = "active";
    while ($rows = $variant_result->fetch_assoc()) {
        $variant_html .= '<input type="button" id="' . $rows['variant_id'] . '" value="' . $rows['variant_name']  . '" class="btn btn-outline-dark mr-2 mb-2 variant rounded ' . $add_active . '" style="width: auto;">';
        $add_active = "";
    }
} else {
    echo '<script>location.href = "./index.php";</script>';
}
?>

<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?option=shop">Shop</a></li>
                    <li class="breadcrumb-item active"><?= $product_name ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="product-details">
    <div class="container">
        <div class="row">
            <div class="product-images col-lg-6">
                <div data-slider-id="1" class="owl-carousel items-slider owl-drag">
                    <?php
                    $productImages = selectProductImages($_GET["id"]);
                    while ($rows = $productImages->fetch_assoc()) {
                        echo '<div class="item"><img src="images/' . $rows['products_images_name'] . '" alt="shirt"></div>';
                    }
                    ?>

                </div>
                <div data-slider-id="1" class="owl-thumbs">
                    <?php
                    $productImages = selectProductImages($_GET["id"]);
                    while ($rows = $productImages->fetch_assoc()) {
                        echo '<button class="owl-thumb-item"><img class="product-imgs" src="images/' . $rows['products_images_name'] . '" alt="..."></button>';
                    }

                    ?>
                </div>
            </div>
            <div class="details col-lg-6">
                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row">
                    <h1><?= $product_name ?></h1>
                </div>
                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row">
                    <ul class="price list-inline no-margin">
                        <li class="list-inline-item current">₱<?= number_format($product_price, 2, '.', ',') ?></li>
                    </ul>
                </div>
                <div class="product-detail">
                    <?php
                    if (strtolower($product_variant) != "none") {
                        ?>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mb-2">
                            <ul class="price list-inline no-margin">
                                <li class="list-inline-item current"><?= ucwords($product_variant) ?></li>
                            </ul>
                        </div>
                        <div class="align-items-center">
                            <?= $variant_html ?>

                        </div>
                    <?php }
                    ?>

                    <hr>
                    <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mb-2">
                        <ul class="price list-inline no-margin">
                            <li class="list-inline-item current">Quantity</li>
                        </ul>
                    </div>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                        <div class="quantity d-flex align-items-center">
                            <div class="dec-btn mr-1">-</div>
                            <input type="text" value="1" class="quantity-no" style="width: 75px;">
                            <div class="inc-btn ml-1">+</div>
                        </div>
                    </div>
                    <ul class="CTAs list-inline">
                        <li class="list-inline-item"><a href="#" class="btn btn-template wide add-to-cart" id="number-prod-<?= $_GET['id']; ?>">Add to Cart</a></li>
                        <li class="list-inline-item"><a href="#" class="btn btn-template wide check-out">Checkout</a></li>
                    </ul>
                    <div id="module" class="container">
                        <?php
                        if (strlen($product_description) < 1) {
                            echo '<p>No description available.</p>';
                        } else if (strlen($product_description) < 1000) {
                            echo '<p>' . nl2br(htmlspecialchars($product_description)) . '</p>';
                        } else {
                            echo '<p class="collapse" id="collapseExample" aria-expanded="false">' . nl2br(htmlspecialchars($product_description)) . '></p>';
                            echo  '<a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></a>';
                        }


                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $(".variant").click(function(e) {
            $(".variant").removeClass("active");
            $(this).addClass("active");
        });
        $(".quantity-no").on("keypress keyup change", function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                $("#errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }

            var quantity = $(this).val();
            if (quantity == "") {
                $(this).val("0");
            }

        });

        $(".add-to-cart, .check-out").click(function(e) {
            e.preventDefault();

            var self = this;
          
            var product_id = $(".add-to-cart").attr("id");
            var quantity = $(".quantity-no").val();
            var variant = $('.variant.active').attr("id");

            if (quantity < 1) {
                alert("You selected 0 quantity for this item");
                return;
            }

            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id,
                    variant: variant,
                    quantity: quantity,
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
                        if($(self).text().toLowerCase() == "checkout"){
                            location.href = "./index.php?option=checkout1"
                        }
                    }
                    else {
                        alert(obj.content);
                    }
                }
            });
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
                    show_cart_count(obj.count, obj.total_price);
                    $("#cart-body").addClass("show");
                    $(".dropdown-menu").addClass("show");

                }
            });
        });
    });
</script>