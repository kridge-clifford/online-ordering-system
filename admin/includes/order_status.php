<?php

include "db.php";
include "functions.php";

$date_from = "";
$date_to = "";

if (isset($_POST["date_from"])) {
    $date_from = $_POST["date_from"];
    $date_to = $_POST["date_to"];
}

$order_status_count = orderStatusCount($date_from, $date_to);

$pending_count = 0;
$completed_count = 0;
$processing_count = 0;
$refunded_count = 0;

while ($rows = $order_status_count->fetch_assoc()) {
    if ($rows['order_status'] == "Waiting") {
        $pending_count = $rows['count'];
    }
    if ($rows['order_status'] == "Completed") {
        $completed_count = $rows['count'];
    }
    if ($rows['order_status'] == "Processing") {
        $processing_count = $rows['count'];
    }
}

$order_status_count_refund = orderStatusCountRefund($date_from, $date_to);

while ($rows = $order_status_count_refund->fetch_assoc()) {
    if ($rows['status'] == "Refunded") {
        $refunded_count = $rows['count'];
    }
}

echo json_encode(array('success' => true, 'pending_count' => $pending_count, 'completed_count' => $completed_count, 'processing_count' => $processing_count, 'refunded_count' => $refunded_count));
exit;
