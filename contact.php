<!-- Hero Section-->
<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Contact</h1>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Contact</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<main class="contact-page">
    <!-- Contact page-->
    <section class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-icon">
                        <div class="icon icon-street-map"></div>
                    </div>
                    <h3>Address</h3>
                    <p>65 E. DELOS SANTOS RD<br>Ampid1, San Mateo, Rizal</p>
                </div>
                <div class="col-md-4">
                    <div class="contact-icon">
                        <div class="icon icon-support"></div>
                    </div>
                    <h3>Contact number</h3>
                    <p>09959843749 or 888483270</p>
                </div>
                <div class="col-md-4">
                    <div class="contact-icon">
                        <div class="icon icon-envelope"></div>
                    </div>
                    <h3>Email support</h3>
                    <p>chongcardona@yahoo.com.</p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.566782154345!2d121.11926261535223!3d14.680509679047894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ba2c4cd80313%3A0x7d4aba191baa3213!2sGoldiloops%20Vapeshop!5e0!3m2!1sen!2sph!4v1569831854877!5m2!1sen!2sph" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </section>
</main>
<script>
    $(document).ready(function() {
        $("#cart-items").on("click", ".delete-item", function(e) {
            e.preventDefault();
            console.log("asdasdsa");
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
    });
</script>