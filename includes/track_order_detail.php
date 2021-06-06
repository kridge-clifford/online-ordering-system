<?php
include "db.php";
include "functions.php";

if (isset($_POST['order_id'])) {
    $order_id =  $_POST['order_id'] - 2019000;
    $result = getOrderId($order_id);

    $result_count = $result->num_rows;

    if($result_count < 1){
        echo json_encode(array('success' => false, 'error_message' => 'Reference number doesn\'t exist.'));
        exit;
    }

    while ($rows = $result->fetch_assoc()) {
        $order_date = date_format(date_create($rows['order_date']), 'Y/m/d');
        $total_price = number_format($rows['total_price'], 2, '.', ',');
        $order_status = $rows['order_status'];
    }

    $percent = "";
    $percent_color = "";

    $order_status_text = ' 
    <div class="p-2 bd-highlight">Processing</div>
    <div class="p-2 bd-highlight">Shipping</div>
    <div class="p-2 bd-highlight">Delivered</div>';

    if ($order_status == "Processing") {
        $percent = "5%";
    } else if ($order_status == "Shipping") {
        $percent = "50%";
    } else if ($order_status == "Completed") {
        $percent = "100%";
        $percent_color = "bg-success";
    } else if ($order_status == "Refunded" || $order_status == "Cancelled") {
        $percent = "100%";
        $percent_color = 'bg-danger';
        $order_status_text = '<div class="p-2 bd-highlight">' . $order_status . '</div>';
    } else if($order_status == "Pending"){
        echo json_encode(array('success'=> true, 'content'=> '<div class="text-center">This order hasn\'t yet verified. Please check your email and verify your order.</div>'));
        exit;
    }

    $product_item = '';

    $product_result = selectOrderItemsById($order_id);

    while ($rows = $product_result->fetch_assoc()) {
        $product_image = $rows['product_image'];
        $product_name = $rows['product_name'];
        $order_quantity = $rows['order_quantity'];
        $product_price = $rows['total'];

        $product_item .= '<div class="item">
                            <div class="row d-flex align-items-center">
                                <div class="col-6">
                                    <div class="d-flex align-items-center"><img src="./images/' . $product_image . '" alt="..." class="img-fluid">
                                        <div class="title"><a href="detail.html">
                                                <h6>' . $product_name . '</h6>
                                            </a></div>
                                    </div>
                                </div>
                                <div class="col-2">' . $order_quantity . '</div>
                                <div class="col-2 text-right"><span>₱' . number_format($product_price, 2, '.', ',') . '</span></div>
                            </div>
                        </div>';
    }

    $output = '
    <div class="block">
        <div class="block-header">
            <h5 style="display:contents;">Order detail </h5>
            <h5 style="float:right;">Est.2-7 days</h5>
        </div>
        <div class="block-body font-weight-bold">
            <div class="d-flex justify-content-between bd-highlight">
                <div class="p-2 bd-highlight">Reference no.: ' . $_POST['order_id'] . '</div>
                <div class="p-2 bd-highlight">Order date: ' . $order_date . '</div>
                <div class="p-2 bd-highlight">Total: ₱' . $total_price . '</div>
            </div>
            <div class="track-contents mb-5">
                <div class="row mb-2">
                    <div class="col-lg-12 sidebar-none">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated ' . $percent_color . '" style="width:' . $percent . '"></div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between bd-highlight">
                   ' . $order_status_text . '
                </div>
            </div>
            <div class="basket basket-customer-order">
                <div class="basket-holder">
                    <div class="basket-header">
                        <div class="row">
                            <div class="col-6">Product</div>
                            <div class="col-2">Quantity</div>
                            <div class="col-2 text-right">Total</div>
                        </div>
                    </div>
                    <div class="basket-body">
                        ' . $product_item . '
                    </div>
                    <div class="basket-footer">
                        <div class="item">
                            <div class="row">
                                <div class="col-12"><strong>You may contact us through telephone numbers: 88-483-270</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

';

    echo json_encode(array('success' => true, 'content' => $output));
}
