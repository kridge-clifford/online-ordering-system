<?php

include "db.php";
require('fpdf/fpdf.php');
include "functions.php";

if (isset($_GET['status'])) {
    $order_status = $_GET['status'];

    if (strtolower($order_status) == 'all'){
        $order_status = '';
    }
    else if (strtolower($order_status) != 'pending' && strtolower($order_status) != 'processing' && strtolower($order_status) != 'waiting' && strtolower($order_status) != 'completed' && strtolower($order_status) != 'refunded' && strtolower($order_status) != 'shipping' && strtolower($order_status) != 'cancelled') {
        echo 'Error Occured! Try again.';
        exit;
    }
} else {
    echo 'Error Occured! Try again.';
    exit;
}

$sql = " AND order_item.status = 'Processing'";
if(strtolower($order_status) == 'all'){
    $sql = "";
} else if (strtolower($order_status) == 'cancelled') {
    $sql = " AND order_item.status = 'Cancelled'";
}
else if (strtolower($order_status) == 'completed') {
    $sql = " AND order_item.status = 'Completed'";
}
//Connect to your database
//Select the Products you want to show in your PDF file
// $query = "SELECT orders.order_id, CONCAT(customer.customer_firstname, ' ', customer.customer_lastname) as customer_name, orders.order_date, SUM(order_item.order_price) as total_price, order_status from orders INNER JOIN customer ON customer.customer_id = orders.customer_id INNER JOIN order_item ON order_item.order_id = orders.order_id WHERE orders.order_status LIKE ? AND orders.order_status != 'Pending' " . $sql . " GROUP BY orders.order_id ORDER BY order_status ASC, order_item.order_id DESC ";

$query = "SELECT products.product_id, concat(products.product_name, CASE WHEN variants.variant_name != 'NONE' THEN concat('(', variants.variant_name, ')') ELSE '' END) as product_name, order_item.created_at, SUM(order_item.order_price) as total_price, order_item.status from products INNER JOIN variants ON products.product_id = variants.product_id INNER JOIN order_item ON order_item.product_id = products.product_id AND variants.variant_id = order_item.variant_id WHERE order_item.status LIKE ? AND variant_status = 'active' GROUP BY variants.variant_id, products.product_id ORDER BY order_item.status, products.product_id ASC, order_item.order_id DESC";
$stmt = $connection->prepare($query);
$param = '%' . $order_status . '%';
$stmt->bind_param('s', $param);
$stmt->execute();
$result = $stmt->get_result();

$number_of_products = $result->num_rows;

//Initialize the 3 columns and the total
$column_order_id = "";
$column_name = "";
$column_total_price = "";
$column_order_date = "";
$column_order_status = "";
$total = 0;


$result_signatory = retrieveSignatory();
while($row = $result_signatory->fetch_assoc()){
    $current_signatory = $row['full_name'];
    $current_position = $row['position'];
    $current_contact_no = $row['contact_no'];
    $current_address = $row['address'];
}
//For each row, add the field to the corresponding column
while ($row = $result->fetch_assoc()) {
    $order_id = $row["product_id"];
    $name = $row["product_name"];
    $real_total_price = $row["total_price"];
    $order_status_show = $row["status"];
    $order_date = date_format(date_create($row['created_at']), 'Y/m/d');
    $total_price = number_format($real_total_price, 2, '.', '.');

    $column_order_id = $column_order_id . $order_id . "\n";
    $column_name = $column_name . $name . "\n";
    $column_total_price = $column_total_price . $total_price . "\n";
    $column_order_date = $column_order_date . $order_date . "\n";
    $column_order_status = $column_order_status . $order_status_show . "\n";

    //Sum all the Prices (TOTAL)
    $total = $total + $real_total_price;
}
$stmt->close();

//Convert the Total Price to a number with (.) for thousands, and (,) for decimals.
$total = number_format($total, 2, '.', '.');

//Create a new PDF file
$pdf = new FPDF();
$pdf->AddPage();



$pdf->Image('../../images/goldilogo.png', 15, 15, 33.78);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(130, 19); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, "Address: ");
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(150, 17); // position of text1, numerical, of course, not x1 and y1
//$pdf->Write(0, $current_address);
$pdf->MultiCell(50, 5, $current_address, 0,  "J" );
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(130, 30); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, "Contact: ");
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(150, 30); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, $current_contact_no);


$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(15, 48); // position of text1, numerical, of course, not x1 and y1
$pdf->Cell(0, 0, ucwords(strtolower($order_status)) . ' Products', '0', 0, 'C');
if ($number_of_products < 1) {

    $pdf->SetXY(80, 60); // position of text1, numerical, of course, not x1 and y1
    $pdf->Write(0, 'No record found.');
    $pdf->Output();
    exit;
}
$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(15, 55); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, 'Total Count:');
$pdf->SetXY(40, 55); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, $number_of_products);
$pdf->SetXY(160, 55); // position of text1, numerical, of course, not x1 and y1
$pdf->Write(0, 'Date: ' . date("Y-m-d"));
//Fields Name position
$Y_Fields_Name_position = 60;
//Table position, under Fields Name
$Y_Table_Position = 66;

//First create each Field Name
//Gray color filling each Field Name box
$pdf->SetFillColor(232, 232, 232);
//Bold Font for Field Name
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(15);
$pdf->Cell(30, 6, 'Product ID', 1, 0, 'L', 1);
$pdf->SetX(45);
$pdf->Cell(100, 6, 'Product Name', 1, 0, 'L', 1);
$pdf->SetX(105);
$pdf->Cell(30, 6, 'Total Price', 1, 0, 'R', 1);
$pdf->SetX(135);
$pdf->Cell(30, 6, 'Date', 1, 0, 'C', 1);
$pdf->SetX(165);
$pdf->Cell(30, 6, 'Status', 1, 0, 'C', 1);
$pdf->Ln();

//Now show the 3 columns
$pdf->SetFont('Arial', '', 12);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(15);
$pdf->MultiCell(30, 6, $column_order_id, 1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(45);
$pdf->MultiCell(90, 6, $column_name, 1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(105);
$pdf->MultiCell(30, 6, $column_total_price, 1, 'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(135);
$pdf->MultiCell(30, 6, $column_order_date, 1, 'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(165);
$pdf->MultiCell(30, 6, $column_order_status, 1, 'C');
// $pdf->SetX(130);
// $pdf->MultiCell(30, 6, '$ ' . $total, 1, 'R');

//Create lines (boxes) for each ROW (Product)
//If you don't use the following code, you don't create the lines separating each row
$i = 0;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_products) {
    $pdf->SetX(15);
    $pdf->MultiCell(180, 6, '', 1);
    $i = $i + 1;
}

//$line_length = (strlen($current_signatory) * 4) - 2;
//$pdf->Line(15, 272, $line_length, 272);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(20, 265); // position of text1, numerical, of course, not x1 and y1
$pdf->Cell(50, 10, $current_signatory, 'T', 0, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(20, 275);
$pdf->Cell(50, 0, $current_position, '0', 0, 'C');

$pdf->Output();
