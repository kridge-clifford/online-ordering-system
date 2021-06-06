<!-- Hero Section-->
<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>About us</h1>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">About us</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- about us-->
<section class="padding-small">
    <div class="container">
        <div class="row about-item">
            <div class="col-lg-8 col-sm-9">
                <h2>Our mission</h2>
                <p class="text-muted">To encourage cigarette smokers to stop smoking and start vaping to prevent cigarette smoking related diseases and provide affordable products for vaping.</p>
            </div>
            <div class="col-lg-4 col-sm-3 d-none d-sm-flex align-items-center">
                <div class="about-icon ml-lg-0"><i class="fa icon-heart"></i></div>
            </div>
        </div>
	<div class="row about-item">
          <div class="col-lg-4 col-sm-3 d-none d-sm-flex align-items-center">
            <div class="about-icon mr-lg-0"><i class="fa fa-user-o">                      </i></div>
          </div>
          <div class="col-lg-8 col-sm-9">
            <h2>History</h2>
            <p class="text-muted">We started to operate in July 2016. The name of our shop is from idea name of goldilocks. Open Hours: 11:00 AM - 12:AM Monday - Sunday.</p>
          </div>
        </div>
    </div>
</section>
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