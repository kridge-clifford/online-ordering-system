<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Shopping cart</h1>
                <p class="lead text-muted total-items-cart"></p>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Shopping cart</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Shopping Cart Section-->
<section class="shopping-cart">
    <div class="container">
        <div class="basket">
            <div class="basket-holder">
                <div class="basket-header">
                    <div class="row">
                        <div class="col-4">Product</div>
                        <div class="col-2">Price</div>
                        <div class="col-3">Quantity</div>
                        <div class="col-2">Total</div>
                        <div class="col-1 text-center">Remove</div>
                    </div>
                </div>
                <div class="basket-body" id="cart-item-list">
                    <?php cartItemsView();  ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Order Details Section-->

<?php
if (count($_SESSION['cart']) > 0) {
?>
    <section class="order-details no-padding-top">
        <div class="container">
            <div class="block">
                <div class="block-header">
                    <h6 class="text-uppercase">Order Summary</h6>
                </div>
                <div class="block-body">
                    <p>Shipping and additional costs are calculated based on values you have entered.</p>
                    <ul class="order-menu list-unstyled">
                        <li class="d-flex justify-content-between"><span>Order Subtotal</span><strong class="price-sub-total">$390.00</strong></li>
                        <li class="d-flex justify-content-between"><span>Total</span><strong class="text-primary price-total">$400.00</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12 text-center CTAs">
                <a href="index.php?option=checkout1" class="btn btn-template btn-lg wide checkout-btn">Proceed to checkout<i class="fa fa-long-arrow-right"></i></a>
            </div>
        </div>
    </section>
<?php } ?>

<script>
    $(document).ready(function() {
        $(".checkout-btn").on("click", function(e) {

            if ($(".price-total").text() == "₱0.00") {
                alert("You don't have any item in your cart!");
                e.preventDefault();
                return;
            }
        });

        $("#cart-item-list").on("keypress keyup change", ".quantity-no", function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                $("#errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }

            var product = $(this);
            product_id = product.attr("id");
            product_id = product_id.replace("quantity-", "");
            var quantity = product.val();

            if (quantity == "") {
                quantity = 0;
            }

            inc_dec_btn(quantity, product_id);
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
                    product_id = product_id.replace('cart-item-', '');
                    $(".total-cart-item-" + product_id).parents('.item-' + product_id).fadeOut();

                    if (obj.count < 1) {
                        $(".order-details").hide();
                        $(".shopping-cart .basket-body").html("<h3 class='p-5 text-center'>You haven't select any item yet.</h3>");
                    }
                }
            });
        });

        function inc_dec_btn(quantity, product_key) {
            var product_variant = product_key.split("_");
            var product_id = product_variant[0];
            var variant = product_variant[1];
            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id,
                    quantity: quantity,
                    variant: variant,
                    type: 'new_quantity',
                },
                success: function(data) {

                    var obj = JSON.parse(data);
                    if (obj.success) {
                        $("#cart-items").html(obj.content);
                        $(".cart-no").text(obj.count);
                        show_cart_count(obj.count, obj.total_price);

                        var fix_price = $(".price-cart-item-" + product_id + "_" + variant).text().replace("₱", "").replace(",", "");
                        fix_price = fix_price * quantity;
                        fix_price = Number(Math.round(parseFloat(fix_price + 'e' + 2)) + 'e-' + 2).toFixed(2);
                        $(".total-cart-item-" + product_id + "_" + variant).html("<span>₱" + fix_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "</span>");

                        if ($(".quantity-no").val() == "") {
                            $(".quantity-no").val("0");
                        };
                    } else {
                        alert(obj.content);                        
                        location.reload();
                    }
                }
            });
        }
        $("#cart-item-list").on("click", ".delete-item", function(e) {
            e.preventDefault();
            var item_cart = this;
            var product_key = $(this).attr("id");

            var product_variant = product_key.split("_");
            var product_id = product_variant[0];
            var variant = product_variant[1];

            product_id = product_id.replace("cart-item-view-", "");

            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    product_id: product_id + "_" + variant,
                    type: 'delete',
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    $("#cart-items").html(obj.content);
                    $(".cart-no").text(obj.count);
                    show_cart_count(obj.count, obj.total_price);
                    $(item_cart).parents('.item-' + product_id + "_" + variant).fadeOut();
                    if (obj.count < 1) {
                        $(".order-details").hide();
                        $(".shopping-cart .basket-body").html("<h3 class='p-5 text-center'>You haven't select any item yet.</h3>");
                    }
                }
            });
        });

        $("#cart-item-list").on("click", ".dec-btn", function(e) {
            e.preventDefault();
            var quantity = $(this).val();
            $(this).val(quantity);

            var product = $(this).next();
            product_id = product.attr("id");
            product_id = product_id.replace("quantity-", "");
            var quantity = product.val();

            inc_dec_btn(quantity, product_id);
        });

        $("#cart-item-list").on("click", ".inc-btn", function(e) {
            e.preventDefault();
            var quantity = $(this).val();
            $(this).val(quantity);

            var product = $(this).prev();
            product_id = product.attr("id");
            product_id = product_id.replace("quantity-", "");
            var quantity = product.val();

            inc_dec_btn(quantity, product_id);
        });

    });
</script>