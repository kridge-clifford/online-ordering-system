
<?php
$ref_id="";
if(isset($_GET['ref'])){
    $ref_id = $_GET['ref']; 
}
?>
<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Track order</h1>
                <p class="lead">Get the status of your order</p>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Track Order</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="padding-small">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 sidebar-none">
                <div class="mb-5">
                    <div class="input-group">
                        <input type="text" class="form-control order_track_id" name="order_id" placeholder="Enter Reference #" value="<?=$ref_id?>">
                        <div class="input-group-append btn-track">
                            <button class="btn btn-outline-primary" type="submit">Track Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block"></div>
       
    </div>
</section>

<script>


    $(document).ready(function() {
        $(".order_track_id").on("keypress keyup change", function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                 
                return false;
            }
        });

        function getOrderInfo(order_id){
            $.ajax({
                url: 'includes/track_order_detail.php',
                method: 'post',
                data: {
                    order_id: order_id,
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    if (obj.success) {
                        $(".block").html(obj.content);
                    } else{
                        alert(obj.error_message);
                        $(".block").html('');
                    }
                },
            });
        }

        $(".btn-track button").on("click", function() {

            var order_id = $(".order_track_id").val();

            getOrderInfo(order_id);
        });

         <?php
        if(isset($_GET['ref'])){
            $ref_id = $_GET['ref'];
        ?>
             getOrderInfo(<?= $ref_id ?>);

        <?php
        }
    ?>
    });
</script>