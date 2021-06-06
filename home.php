<div id="carouselVapeImages" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselVapeImages" data-slide-to="0" class="active"></li>
        
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active item-carousel">
            <img class="d-block w-100" src="images/vp1.png" alt="First slide">
        </div>
        
    </div>
</div>

<script>
    $(document).ready(function() {
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
                    $(".item-image-" + product_id).parents('.item-' + product_id).fadeOut().css("cssText", "display: none !important;");
                    $(".price-total").text("₱" + obj.total_price);
                }
            });
        });
    });
</script>