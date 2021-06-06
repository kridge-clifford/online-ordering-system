<?php
include "db.php";
include "functions.php";
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_POST['type'])) {

    if ($_POST['type'] == "new" || $_POST['type'] == "new_quantity") {
        $product_id = str_replace("number-prod-", "", $_POST['product_id']);

        $query = "SELECT product_id, product_name, product_image, product_price FROM products WHERE product_id = ? ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $product_name = $rows['product_name'];
            $product_id = $rows['product_id'];
            $product_price = $rows['product_price'];
            $product_image = $rows['product_image'];
        }
        $variant_name = "";
        if (isset($_POST['variant'])) {
            $variant_id = $_POST['variant'];
        } else {
            $variant_id = 0;
            $variant_name = "";
        }

        $variant_result = selectAllProductVariantsById($variant_id);

        while ($variant_row = $variant_result->fetch_assoc()) {
            $variant_name = '(' . $variant_row['variant_name'] . ')';
            $variant_id = $variant_row['variant_id'];
        }

        $quantity = 1;

        if (isset($_POST['quantity_prod'])) {
            $quantity = $_POST['quantity_prod'];
        }

        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];

            $items_count = 0;
            foreach ($_SESSION['cart'] as $existing_key => $existing_value) {
                $items_count = $existing_value['quantity'] + $items_count;
            }

            $items_count = $items_count + $quantity;
            if ($items_count > 99 && $_POST['type'] != "new_quantity") {
                echo json_encode(array('success' => false, 'content' => 'You can order only below 100 items', 'item_count' => $quantity));
                exit;
            }
        }

        $product_key = $product_id . "_" . $variant_id;
        $cart_item = ['product_name' => ucwords($product_name) . ucwords($variant_name), 'quantity' => $quantity, 'product_price' => $product_price * $quantity, 'product_image' => $product_image, 'product_price_fix' => $product_price];

        if (array_key_exists($product_key, $_SESSION['cart'])) {
            $new_quantity = 0;
            $items_count = 0;
            if ($_POST['type'] == "new_quantity") {
                $new_quantity = $_POST['quantity'];

                $items_count = 0;
                foreach ($_SESSION['cart'] as $existing_key => $existing_value) {
                    if ($product_key != $existing_key) {
                        $items_count = $existing_value['quantity'] + $items_count;
                    }
                }

                $items_count = $items_count + $new_quantity;
                if ($items_count > 99) {
                    echo json_encode(array('success' => false, 'content' => 'You can order only below 100 items', 'item_count' => $items_count));
                    exit;
                }
            } else {
                $to_add = 1;
                foreach ($_SESSION['cart'] as $existing_key => $existing_value) {

                    if (isset($_POST['quantity'])) {
                        $to_add = $_POST['quantity'];
                    }

                    if ($product_key == $existing_key) {
                        $new_quantity = $existing_value['quantity'] + $to_add;
                        $items_count += $new_quantity;
                    } else {
                        $items_count = $existing_value['quantity'] + $items_count;
                    }



                    if ($items_count > 99) {
                        echo json_encode(array('success' => false, 'content' => 'You can order only below 100 items', 'item_count' => $items_count));
                        exit;
                    }
                }
            }


            $cart_item = ['product_name' => ucwords($product_name) . ucwords($variant_name), 'quantity' => $new_quantity, 'product_price' => $product_price * $new_quantity, 'product_image' => $product_image, 'product_price_fix' => $product_price];

            $_SESSION['cart'][$product_key] = $cart_item;
        } else {
            $new_product[$product_key] = $cart_item;
            $_SESSION['cart'] += $new_product;
        }

        showCartItems();
    } else if ($_POST['type'] == "show") {
        showCartItems();
    } else if ($_POST['type'] == "delete") {
        $product_id = str_replace("cart-item-", "", $_POST['product_id']);
        unset($_SESSION['cart'][$product_id]);
        showCartItems();
    }
}

function showCartItems()
{
    $shipping_fee = 0.0;
    $real_total = 0.0;
    if (isset($_POST['shipping_fee'])) {
        $shipping_fee = $_POST['shipping_fee'];
    }
    $output = '';
    $total_price = 0.0;

    $cart_count = count($_SESSION['cart']);

    if ($cart_count < 1) {
        $output = "<h4 class='text-center'>0 item</h4>";
    } else {
        foreach ($_SESSION['cart'] as $key => $value) {
            $total_price += $value['product_price'];
            $output .= ' 
                <div class="dropdown-item cart-product">
                <div class="d-flex align-items-center">
                        <div class="img"><img src="images/' . $value['product_image'] . '" alt="..." class="img-fluid"></div>
                        <div class="details d-flex justify-content-between">
                            <div class="text"> <a href="./payments.php"><strong>' . $value['product_name'] . '</strong></a><small>Quantity: ' . $value['quantity'] . ' </small><span class="price">₱' . $value['product_price'] . ' </span></div><a href="#" class="delete delete-item" id="cart-item-' . $key . '"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </div>
                </div>
            ';
        }

        $real_total = $total_price;
        $total_price = number_format($total_price, 2, '.', ',');

        $output .= '
                        <div class="dropdown-item total-price d-flex justify-content-between"><span>Total</span><strong class="text-primary">₱' . $total_price . '</strong></div>
                        <div class="dropdown-item CTA d-flex"><a href="index.php?option=cart" class="btn btn-template wide">View Cart</a><a href="index.php?option=checkout1" class="btn btn-template wide">Checkout</a></div>
						<!--<div class="row">
                            <div class="col"><hr></div>
                            <div class="col-auto">OR</div>
                            <div class="col"><hr></div>
                        </div>
                        <div class="dropdown-item CTA d-flex text-center" id="paypal-button-container" style="justify-content: center;">
                            <table border="0" cellpadding="10" cellspacing="0" align="center">
                                <tr>
                                    <td align="center">
                                        <a class="paypalbtn" href="./payments.php">
                                            <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Check out with PayPal" />
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
						<div class="dropdown-item CTA d-flex text-center">
							<a href="./payments.php" class="btn btn-template wide">Paypal</a>
						</div>-->
                    ';
    }

    echo json_encode(array('success' => true, 'type' => 'new', 'content' => $output, 'count' => $cart_count, 'total_price' => $total_price, 'real_total' => $real_total, 'shipping_fee' => $shipping_fee));
    exit;
}
