<?php

use PHPMailer\PHPMailer\PHPMailer;

include 'order_mail.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



function send_email_to_user($shipping_fee, $order_id, $address, $full_name, $email, $current_email, $current_password, $mail_table = "", $total_price = 0)
{
	$barcode_text = $order_id + 2019000;

    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML(true);
        $mail->Username = $current_email;
        $mail->Password = $current_password;
        $mail->setFrom($current_email, 'no-reply');
        $mail->Subject = 'Verify Order';
		
		$image_url = "";
		
		if($mail_table != ""){
			$image_url = './images/goldiwhite-logo.png';
			$barcode_path_image = "./images/" . $barcode_text . ".png";
		} else {
			$image_url = '../images/goldiwhite-logo.png';
			$barcode_path_image = "../images/" . $barcode_text . ".png";
		}
			
        $mail->AddEmbeddedImage($image_url, 'goldilogo');
		$mail->AddEmbeddedImage($barcode_path_image, 'barcodeimg');
        $mail->Body = email_body($shipping_fee, $order_id, $address, $full_name, $mail_table, $total_price);
        $mail->addAddress($email);

        $mail->send();
    } catch (Exception $e) { }
}
