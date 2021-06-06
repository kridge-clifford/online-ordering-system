<h1 class="h3 mb-4 text-gray-800">All Orders</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Reference no.</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Reference no.</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php

                    $result = selectAllOrders();
                    while ($row = $result->fetch_assoc()) {

                        $order_id = $row['order_id'];
                        $customer_name = $row['customer_name'];
                        $order_date = $row['order_date'];
                        $total_price = $row['total_price'];
                        $payment_type = $row['payment_type'];
                        $order_status = $row['order_status'];

                        $text_color = colorOrderStatus($order_status);

                        $order_date = date("M d, Y h:i:s a", strtotime($order_date));
                        ?>
                        <tr>
                            <td style="width: 5%"><?= $order_id + 2019000 ?></td>
                            <td style="width: 20%"><?= $customer_name ?></td>
                            <td style="width: 20%"><?= $order_date ?></td>
                            <td style="width: 10%"><?= $payment_type ?></td>
                            <td style="width: 10%" class="font-weight-bold">
                                <h5>
                                    <span class="<?= $text_color ?>">
                                        <?= $order_status ?>
                                    </span>
                                </h5>
                            </td>
                            <td>
                                <?php
                                    if ($order_status == "Waiting") {

                                        echo
                                            '<a class="btn btn-info mb-1 btn-sm" data-toggle="tooltip" title="Approve" href="orders.php?update=processing&order_id=' . $order_id . '">
                                                <i class="fa fa-check"></i> Approve
                                            </a>';
                                    } else if ($order_status == "Processing") {
                                        echo
                                            '<a class="btn btn-secondary mb-1 btn-sm" data-toggle="tooltip" title="Shipping" href="orders.php?update=shipping&order_id=' . $order_id . '">
                                                <i class="fa fa-truck"></i> Shipping
                                            </a>
                                            <a class="btn btn-danger mb-1 btn-sm" data-toggle="tooltip" title="Cancel" href="orders.php?update=cancelled&order_id=' . $order_id . '">
                                                <i class="fa fa-ban"></i> Cancel
                                            </a>';
                                    } else if ($order_status == "Shipping") {
                                        echo
                                            '<a class="btn btn-success mb-1 btn-sm" data-toggle="tooltip" title="Completed" href="orders.php?update=completed&order_id=' . $order_id . '">
                                                <i class="fa fa-check"></i> Completed
                                            </a>
                                            ';
                                    } 
                                    // else if ($order_status == "Completed") {
                                    //     echo
                                    //         '<a class="btn btn-danger mb-1 btn-sm" data-toggle="tooltip" title="Refund" href="orders.php?update=refunded&order_id=' . $order_id . '">
                                    //             <i class="fa fa-redo"></i> Refund
                                    //         </a>';
                                    // } 
                                    else if ($order_status == "Refunded") {
                                        echo
                                            '<a class="btn btn-success mb-1 btn-sm" data-toggle="tooltip" title="Complete" href="orders.php?update=completed&order_id=' . $order_id . '">
                                                <i class="fa fa-check"></i> Complete
                                            </a>';
                                    }
                                    ?>
                                <span class="view_data" id="<?= $order_id ?>">
                                    <a class="btn btn-primary mb-1 btn-sm" data-toggle="tooltip" title="View" href="#">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php 
        updateOrderStatus(); 
        updateOrderItemStatus();
        ?>