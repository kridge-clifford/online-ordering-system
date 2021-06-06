<?php 

$result = order_verified(); 
$name = "";
while($rows = $result->fetch_assoc()){
    $name = $rows['customer_name'];
}

?>

<section class="hero hero-page gray-bg padding-small">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-9 order-2 order-lg-1">
                <h1>Order verified</h1>
            </div>
            <div class="col-lg-3 text-right order-1 order-lg-2">
                <ul class="breadcrumb justify-content-lg-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Order Verified</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="checkout">
    <div class="container">
        <div class="confirmation-icon"><i class="fa fa-check"></i></div>
        <h2><?= ucwords($name) ?>, your order has been verified. Thank you!</h2>
        <p class="mb-5">We will contact you as soon as possible.</p>
    </div>
</section>