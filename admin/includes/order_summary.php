<?php include "db.php"; ?>
<?php include "functions.php"; ?>

<?php
if (isset($_POST['order_id_post'])) {
    $order_id_post = $_POST['order_id_post'];
    $output = "
        <div class='table-responsive'>
            <table class='table table-bordered'>
    ";

    $result = selectAllOrders($order_id_post);
    
    while ($row = $result->fetch_assoc()) {

        if ($row['payment_type'] != "PICKUP") {
            $payment_type = ucfirst(strtolower($row['payment_type']));
        } else {
            $payment_type = "Over-the-Counter";
        }
        $order_id_real = $row['order_id'];
        $order_id_temp = $row['order_id'] + 2019000;
        $row_span = $row['items_count'] + 1;
        $output .= "
            <tr>
                <td class='font-weight-bold'><label>Reference No.</label></td>
                <td colspan='4'>" . $order_id_temp . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Customer name</label></td>
                <td colspan='4'>" . $row['customer_name'] . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Email</label></td>
                <td colspan='4'>" . $row['customer_email'] . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Phone</label></td>
                <td colspan='4'>" . $row['customer_phone'] . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Order Date</label></td>
                <td colspan='4'>" . $row['order_date'] . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold' rowspan='" . $row_span . "'><label>Ordered Items</label></td>
                <td class='font-weight-bold'>Item</td>
                <td class='font-weight-bold'>Price</td>
                <td class='font-weight-bold'>Status</td>
                ";

                if($row['order_status'] == "Completed"){
                    $output .= "<td class='font-weight-bold'>Actions</td>";
                }

        $result = selectOrderItemsById($order_id_post);
        $deducted_item = 0;
        while ($rows = $result->fetch_assoc()) {
            $product_name = $rows['product_name'];
            $variant_name = '(' . $rows['variant_name'] . ')';
            $order_quantity = $rows['order_quantity'];
            $total = $rows['total'];
            $order_item_status = $rows['status'];
            $order_item_status_color = colorOrderStatus($order_item_status);
            $product_id = $rows['product_id'];

            $button_html = "";

            if($order_item_status == "Completed" && $row['order_status'] == "Completed"){
                $button_html = "<td><a class='btn btn-danger mb-1 btn-sm' data-toggle='tooltip' title='Refund' href='orders.php?update_item=refunded&order_id=" .  $order_id_real . "&p_id=" . $product_id . "' data-original-title='Refund'> Refund
                                    <i class='fa fa-redo'></i>
                                </a></td>";
            }
            // else if($order_item_status == "Refunded" && $row['order_status'] == "Completed"){
            //     $deducted_item += $total;
            //     $button_html = "<td><a class='btn btn-success mb-1 btn-sm' data-toggle='tooltip' title='Refund' href='orders.php?update_item=completed&order_id=" .  $order_id_real . "&p_id=" . $product_id . "' data-original-title='Completed'> Completed
            //                         <i class='fa fa-check'></i>
            //                     </a></td>";
            // }
            else if($order_item_status == "Cancelled"){
                $deducted_item += $total;
            }

            $output .= "
                    <tr height='100%'>
                        <td>$product_name$variant_name x$order_quantity</td>
                        <td>$total</td>
                        <td><span class='$order_item_status_color'>" . $order_item_status . "</span></td>
                        
                            " . $button_html . "
                        
                    </tr>";
        }

        $text_color = colorOrderStatus($row['order_status']);
        $overall_price = $row['total_price'] - $deducted_item;
        $output .= " 
                    
                </td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Total</label></td>
                <td colspan='4'>" . $overall_price . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Delivery method</label></td>
                <td colspan='4'>" . ucfirst(strtolower($row['delivery_method'])) . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Payment method</label></td>
                <td colspan='4'>" . $payment_type . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Address</label></td>
                <td colspan='4'>" . $row['customer_address'] . "</td>
            </tr>
            <tr>
                <td class='font-weight-bold'><label>Status</label></td>
                <td colspan='4'><h4><span class='$text_color'>" . $row['order_status'] . "</span></h4></td>
            </tr>
        ";
    }
    $output .= "</table></div>";
    echo $output;
}
?>