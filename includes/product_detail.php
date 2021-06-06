<?php
include "db.php";
include "functions.php";


if (isset($_POST['product_id'])) {

    $product_id = str_replace("quick-view-", "", $_POST['product_id']);
    $selected_product = selectedProductById($product_id);

    while ($rows = $selected_product->fetch_assoc()) {
        $product_name = $rows['product_name'];
        $product_description = $rows['product_description'];
        $product_image = $rows['product_image'];
        $product_price = number_format($rows['product_price'], 2, '.', ',');
    }

    echo ' 
            <div class="image col-lg-5"><img src="images/' . $product_image . '" alt="..." class="img-fluid d-block"></div>
            <div class="details col-lg-7">
                <h2>' . $product_name . '</h2>
                <ul class="price list-inline">
                    <li class="list-inline-item current">₱' . $product_price . '</li>
                </ul>
                <p>' . ($product_description == "" ? "No description available." : $product_description) . '</p>
                <div class="d-flex align-items-center">
                    <div class="quantity d-flex align-items-center">
                        <div class="dec-btn">-</div>
                        <input type="text" value="1" class="quantity-no">
                        <div class="inc-btn">+</div>
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <a href="#" class="btn btn-template delete-item"> <i class="fa fa-shopping-cart"></i>Add to Cart</a>
                </div>
            </div>
        ';
}
