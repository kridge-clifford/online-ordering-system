<?php

use function PHPSTORM_META\type;

function selectAllProducts($limit, $offset, $selected_category = 0, $selected_brands = 0)
{
    global $connection;
    $query = "SELECT product_id, category_title, brand_title, product_name, product_description, product_quantity, product_price, product_image FROM products 
                INNER JOIN categories ON categories.category_id = products.category_id 
                INNER JOIN brand ON brand.brand_id = products.brand_id ";


    if ($selected_category == 0 && empty($selected_brands)) {
        $query .= " WHERE product_quantity != 0 LIMIT ? OFFSET ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
    } else if ($selected_category != 0 && $selected_brands != 0) {

        $query .= " WHERE product_quantity != 0 AND products.category_id = ? AND products.brand_id = ? LIMIT ? OFFSET ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iiii", $selected_category, $selected_brands, $limit, $offset);
    } else if ($selected_category != 0) {

        $query .= " WHERE product_quantity != 0 AND products.category_id = ? LIMIT ? OFFSET ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iii", $selected_category, $limit, $offset);
    } else if ($selected_brands != 0) {

        $query .= " WHERE product_quantity != 0 AND products.brand_id = ? LIMIT ? OFFSET ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iii", $selected_brands, $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllProductVariants($product_id)
{
    global $connection;
    $query = "SELECT variant_id, variant_name FROM variants WHERE product_id = ? AND variant_status = 'active' ; ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllProductVariantsById($variant_id)
{
    global $connection;
    $query = "SELECT variant_id, variant_name FROM variants WHERE variant_id = ? AND variant_status = 'active' ; ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $variant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllProductsRowCount($selected_category = 0, $selected_brands = 0)
{
    global $connection;
    $query = "SELECT product_id, category_title, brand_title, product_name, product_description, product_quantity, product_price, product_image FROM products 
                INNER JOIN categories ON categories.category_id = products.category_id 
                INNER JOIN brand ON brand.brand_id = products.brand_id  ";

    if ($selected_category == 0 && $selected_brands == 0) {
        $stmt = $connection->prepare($query);
    } else if ($selected_category != 0 && $selected_brands != 0) {

        $query .= " WHERE products.category_id = ? AND products.brand_id = ?; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ii", $selected_category, $selected_brands);
    } else if ($selected_category != 0) {

        $query .= " WHERE products.category_id = ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $selected_category);
    } else if ($selected_brands != 0) {
        $query .= " WHERE products.brand_id = ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $selected_brands);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->num_rows;

    return $result;
}

function selectedProductById($selected_product_id)
{
    global $connection;
    $query = "SELECT product_id, category_title, brand_title, product_name, product_description, product_quantity, product_price, product_image, product_variant FROM products 
                INNER JOIN categories ON categories.category_id = products.category_id 
                INNER JOIN brand ON brand.brand_id = products.brand_id WHERE products.product_id = ?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $selected_product_id);

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectProductImages($selected_product_id)
{
    global $connection;
    $query = "SELECT products_images_name FROM products_images WHERE product_id = ? AND products_images_status = 'active' ";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $selected_product_id);

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllCategories($selected_brands = 0)
{
    global $connection;
    $query = "SELECT categories.category_id, categories.category_title, COUNT(products.product_id) as product_count FROM categories LEFT JOIN products ON categories.category_id = products.category_id ";

    if ($selected_brands != 0) {
        $query .= "AND products.brand_id = ? GROUP BY category_id;";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $selected_brands);
    } else {
        $query .= "GROUP BY category_id;";
        $stmt = $connection->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllBrands($selected_category = 0)
{
    global $connection;
    $query = "SELECT brand.brand_id, brand.brand_title, COUNT(products.product_id) as product_count FROM brand LEFT JOIN products ON brand.brand_id = products.brand_id ";

    if ($selected_category != 0) {
        $query .= "AND products.category_id = ? GROUP BY brand_id ;";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $selected_category);
    } else {
        $query .= "GROUP BY brand_id ;";
        $stmt = $connection->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function showCart($isShipping = false)
{
    $isShippingHtml = "";
    if($isShipping){
        $isShippingHtml = '<li class="d-flex justify-content-between"><span>Shipping</span><strong>₱10.00</strong></li>';
    }
    echo "
    <script>
        function show_cart_count(count, total){
            $('.total-items-cart').html('You currently have ' + count + ' items in your shopping cart');
            $('.price-sub-total').text('₱' + total );
            $('.price-total').text('₱' + total );
        }

        $(document).ready(function() {
          
            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'post',
                data: {
                    type: 'show',
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    if (obj.success) {
                        if (obj.type === 'new') {
                            $('#cart-items').html(obj.content);
                            $('.cart-no').text(obj.count);
                            show_cart_count(obj.count, obj.total_price);
                        }
                    }
                }
            }).done(function(data) {});
        });
    </script>
    ";
}

function cartItemsView()
{
    $output = "";

    $cart_count = count($_SESSION['cart']);

    if ($cart_count < 1) {
        $output = "<h3 class='p-5 text-center'>You haven't select any item yet.</h3>";
    } else {
        foreach ($_SESSION['cart'] as $key => $value) {

            $product = selectedProductById($key);

            while ($rows = $product->fetch_assoc()) {
                $product_id = $rows['product_id'];
                $product_name = $rows['product_name'];
                $product_image = $rows['product_image'];
                $product_price = $rows['product_price'];
            }
            $total = $product_price * $value['quantity'];
            $total = number_format($total, 2, '.', ',');
            $product_price = number_format($product_price, 2, '.', ',');
            $output .=
                '
                <div class="item item-' .  $key . '">
                    <div class="row d-flex align-items-center">
                        <div class="col-4">
                            <div class="d-flex align-items-center"><img src="images/'  . $product_image . '" alt="..." class="img-fluid">
                                <div class="title">
                                        <h5>' . $value['product_name'] . '</h5>
                                    </div>
                            </div>
                        </div>
                        <div class="col-2 price-cart-item-' . $key . '"><span>₱' . $product_price . '</span></div>
                        <div class="col-3">
                            <div class="d-flex align-items-center">
                                <div class="quantity d-flex align-items-center">
                                    <div class="dec-btn mr-1">-</div>
                                    <input type="text" value="' . $value['quantity'] . '" class="quantity-no" minlength="1" maxlength="3" id="quantity-' . $key . '" style="width: 75px;">
                                    <div class="inc-btn ml-1">+</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 total-cart-item-' . $key . '"><span>₱' . $total . '</span></div>
                        <div class="col-1 text-center"><i class="delete-item-cart delete-item fa fa-trash" id="cart-item-view-' . $key . '"></i></div>
                    </div>
                </div>
                ';
        }
    }

    echo $output;
}

function order_verified()
{
    global $connection;

    $status = "Waiting";
    if (isset($_GET['id'])) {
        $order_id = $_GET['id'];

        $query = "SELECT order_id FROM orders WHERE order_id = ? AND order_status = 'Pending' ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $count = $result->num_rows;
        if ($count == 0) {
            echo "<script>alert('Order is already verified.'); location.href = './index.php';</script>";
        }
    } else {
        echo "<script>location.href = './index.php'; </script>";
    }

    $query = "SELECT CONCAT(customer.customer_firstname, ' ', customer.customer_lastname) as customer_name FROM orders INNER JOIN customer ON customer.customer_id = orders.customer_id WHERE order_id = ? AND order_status = 'Pending' ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $query = "UPDATE orders SET order_status = ? WHERE order_id = ? ;";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    

    return $result;
}

function getOrderId($order_id)
{
    global $connection;
    $query = "SELECT orders.order_id, orders.order_date, SUM(order_item.order_price) as total_price, orders.order_status   
    FROM orders 
    INNER JOIN customer 
        ON orders.customer_id = customer.customer_id 
    INNER JOIN payment 
        ON payment.order_id = orders.order_id 
    INNER JOIN order_item 
        ON orders.order_id = order_item.order_id WHERE orders.order_id = ? GROUP BY order_id; ";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id);

    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectOrderItemsById($order_id)
{
    global $connection;
    $query = "SELECT products.product_name, order_item.order_quantity, order_item.order_price as total, products.product_id, products.product_image FROM order_item INNER JOIN orders ON orders.order_id = order_item.order_id INNER JOIN products ON products.product_id = order_item.product_id WHERE orders.order_id = ?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function verifyTransaction($data) {
	global $paypalUrl;

	$req = 'cmd=_notify-validate';
	foreach ($data as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
		$req .= "&$key=$value";
	}

	$ch = curl_init($paypalUrl);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	$res = curl_exec($ch);

	if (!$res) {
		$errno = curl_errno($ch);
		$errstr = curl_error($ch);
		curl_close($ch);
		throw new Exception("cURL error: [$errno] $errstr");
	}

	$info = curl_getinfo($ch);

	// Check the http response
	$httpCode = $info['http_code'];
	if ($httpCode != 200) {
		throw new Exception("PayPal responded with http code $httpCode");
	}

	curl_close($ch);

	return $res === 'VERIFIED';
}

/**
 * Check we've not already processed a transaction
 *
 * @param string $txnid Transaction ID
 * @return bool True if the transaction ID has not been seen before, false if already processed
 */
function checkTxnid($txnid) {
	global $connection;

	$txnid = $connection->real_escape_string($txnid);
	$results = $connection->query('SELECT * FROM `payments` WHERE txnid = \'' . $txnid . '\'');

	return ! $results->num_rows;
}

/**
 * Add payment to database
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */
function addPayment($data) {
	global $connection;

	if (is_array($data)) {
		$stmt = $connection->prepare('INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime) VALUES(?, ?, ?, ?, ?)');
		$stmt->bind_param(
			'sdsss',
			$data['txn_id'],
			$data['payment_amount'],
			$data['payment_status'],
			$data['item_number'],
			date('Y-m-d H:i:s')
		);
		$stmt->execute();
		$stmt->close();

		return $connection->insert_id;
	}

	return false;
}

function selectAllProvince()
{
    global $connection;
    $query = "SELECT province_code, province_name, region_code FROM provinces ORDER BY province_name ASC";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllMunicipality($province_code)
{
    global $connection;
    $query = "SELECT municipality_code, municipality_name, province_code FROM municipalities WHERE province_code = ? ORDER BY municipality_name ASC ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $province_code);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectByIdProvince($province_code)
{
    global $connection;
    $query = "SELECT province_code, province_name, region_code FROM provinces WHERE province_code = ? ORDER BY province_name ASC";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $province_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $result_data = "";
    while($row = $result->fetch_assoc()){
        $result_data = $row['province_name'];
    }

    return $result_data;
}

function selectByIdMunicipality($municipality_code)
{
    global $connection;
    $query = "SELECT municipality_code, municipality_name, province_code FROM municipalities WHERE municipality_code = ? ORDER BY municipality_name ASC ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $municipality_code);
    $stmt->execute();
    $result = $stmt->get_result();

    $result_data = "";
    while($row = $result->fetch_assoc()){
        $result_data = $row['municipality_name'];
    }

    return $result_data;
}
