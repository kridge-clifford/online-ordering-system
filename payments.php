<?php

include "includes/db.php";
include "includes/phpqrcode/qrlib.php";
require_once('includes/php-barcode-master/barcode.php');

session_start();

$enableSandbox = false;

$paypalConfig = [
	'email' => '', //prod
	'return_url' => '',
	'cancel_url' => '',
	'notify_url' => ''
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Product being purchased.
$itemName = 'Test Item, Test Item1';
$itemAmount = 25.00;

// Include Functions 
include "includes/functions.php";

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

	global $connection;

	// Grab the post data so that we can set up the query string for PayPal.
	// Ideally we'd use a whitelist here to check nothing is being injected into
	// our post data.
	$data = [];
	foreach ($_POST as $key => $value) {
		$data[$key] = stripslashes($value);
	}

	// Set the PayPal account.
	$data['cmd'] = '_cart';
	$data['upload'] = '1';
	$data['business'] = $paypalConfig['email'];

	// Set the PayPal return addresses.
	$data['return'] = stripslashes($paypalConfig['return_url']);
	$data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
	$data['notify_url'] = stripslashes($paypalConfig['notify_url']);

	// Set the details about the product being purchased, including the amount
	// and currency so that these aren't overridden by the form data.


	$counter = 1;
	foreach ($_SESSION['cart'] as $key => $value) {
		$product_id = $key;

		$query = "SELECT product_id, product_name, product_image, product_price FROM products WHERE product_id = ? ";
		$stmt = $connection->prepare($query);
		$stmt->bind_param("i", $product_id);
		$stmt->execute();

		$result = $stmt->get_result();

		while ($rows = $result->fetch_assoc()) {
			$product_name = $rows['product_name'];
			$product_id = $rows['product_id'];
			$product_price = $rows['product_price'];
		}

		$order_quantity = $value['quantity'];

		$data['item_name_' . $counter] = $product_name;
		$data['quantity_' . $counter] = $order_quantity;
		$data['amount_' . $counter] = $product_price;
		$data['item_number_' . $counter] = $product_id;


		$counter = $counter + 1;
	}


	$data['currency_code'] = 'PHP';
	$data['custom'] = '';

	$queryString = http_build_query($data);

	header('location:' . $paypalUrl . '?' . $queryString);
	exit();
} else {

	try {
		include "./includes/send_mail.php";

		if (verifyTransaction($_POST)) {
			global $connection;


			$personal_info = explode("|", $_POST['custom']);
			$first_name = $personal_info[0];
			$last_name = $personal_info[1];
			$email = $personal_info[2];
			$phone_number = $personal_info[3];
			$address = $personal_info[4];

			$shippping = "SHIPPING";
			$payment = "PAYPAL";
			$order_date = date("Y-m-d H:i:s");
			$status = "Pending";
			$shipping_fee = 200;

			$query_customer = "
			INSERT INTO customer (customer_regdate, customer_address, customer_firstname, 
									customer_lastname, customer_email, customer_phone)
						VALUES 
								(?, ?, ?, ?, ?, ?)";

			$stmt = $connection->prepare($query_customer);
			$stmt->bind_param("ssssss", $order_date, $address, $first_name, $last_name, $email, $phone_number);
			$stmt->execute();


			$customer_id = $stmt->insert_id;
			$stmt->close();

			$query_order = "
			INSERT INTO orders (order_date, order_status, customer_id) 
						VALUES
								(?, ?, ?)";

			$stmt = $connection->prepare($query_order);
			$stmt->bind_param("ssi", $order_date, $status, $customer_id);
			$stmt->execute();

			$order_id = $stmt->insert_id;
			$stmt->close();

			$total_price = 0.0;

			$barcode_text = $order_id + 2019000;
			$barcode_text = strval($barcode_text);
			//barcode("./images/" .  $barcode_text . ".png", $barcode_text, "30", "Horizontal", "Code128", "true", "1" );

			//qrcode
			$tempDir = './images/';

			$codeContents = "https://goldiloops-vapeshop.online/index.php?option=track_order&ref=" . $barcode_text;

			$fileName = $barcode_text . '.png';

			$pngAbsoluteFilePath = $tempDir . $fileName;

			QRcode::png($codeContents, $pngAbsoluteFilePath);

			$mail_table = "";
			for ($i = 0; $i < $_POST['num_cart_items']; $i++) {
				$index = $i + 1;
				$productkey = $_POST['item_number' . $index];
				$product_variant = explode("_", $productkey);

				$product_id = $product_variant[0];
				$variant_id = $product_variant[1];

				if ($variant_id == 0){
           
					$query = "SELECT variant_id, variant_name FROM variants WHERE product_id = ? AND variant_status = 'active' ; ";
					$stmt = $connection->prepare($query);
					$stmt->bind_param("i", $product_id);
					$stmt->execute();
					$result = $stmt->get_result();
		
					while($variant_rows = $result->fetch_assoc()){
						$variant_id = $variant_rows['variant_id'];
					}
				}

				$order_quantity = $_POST['quantity' . $index];
				$product_price = $_POST['mc_gross_' . $index];
				$product_name = $_POST['item_name' . $index];

				$product_total_price = $product_price * $order_quantity;

				$total_price += $product_price;

				$query_order_item = "
				INSERT INTO order_item (order_id, product_id, variant_id, order_quantity, order_price)
							VALUES
										(?, ?, ?, ?, ?)";

				$stmt = $connection->prepare($query_order_item);
				$stmt->bind_param("iiiid", $order_id, $product_id, $variant_id, $order_quantity, $product_price);
				$stmt->execute();

				$mail_table .= '<tr>
					<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; color:black;">' . $product_name . '</td>
					<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">' . $product_price . '</td>
					<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:center; color:black;">' . $order_quantity . '</td>
					<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">' . $product_total_price . '</td>
				</tr>';
			}

			$total_price += $shipping_fee;
			$query_payment = "
			INSERT INTO payment (order_id, payment_amount, payment_type, delivery_method)
						VALUES
								(?, ?, ?, ?)";

			$stmt = $connection->prepare($query_payment);
			$stmt->bind_param("idss", $order_id, $total_price, $payment, $shippping);
			$stmt->execute();
			$stmt->close();

			try {

				$query = "SELECT email, password FROM email WHERE status = 'active' ";
				$stmt = $connection->prepare($query);
				$stmt->execute();

				$result = $stmt->get_result();

				while ($rows = $result->fetch_assoc()) {
					$current_email = $rows['email'];
					$current_password = $rows['password'];
				}
			} catch (Exception $ex) {
				echo $ex;
				exit;
			}

			$full_name = $first_name . " " .  $last_name;
			send_email_to_user($shipping_fee, $order_id, $address, $full_name, $email, $current_email, $current_password, $mail_table, $total_price);
			$_SESSION['cart'] = [];
			echo json_encode(array("success" => true));
		}
	} catch (Exception $ex) {
		$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
		fwrite($myfile, $ex->getMessage());
		fclose($myfile);
	}
	// Handle the PayPal response.

	// Create a connection to the database.

}
