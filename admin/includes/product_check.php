<?php
include "db.php";
include "functions.php";
if(isset($_POST['product_name'])){
    $product_name = $_POST['product_name'];
    
    if (boolProductExist($product_name)) {
        echo json_encode(array('success' => true, 'product' => 'exists'));
        exit;
    }
    
    echo json_encode(array('success' => true, 'product' => 'ok'));
}
