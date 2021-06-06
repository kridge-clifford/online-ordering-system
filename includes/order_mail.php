<?php
// session_start();
function email_body($shipping_fee, $order_id, $address, $full_name, $mail_table = "", $total_price = 0)
{
    $shipping_fee_html = "";

    if($shipping_fee > 0){
        $shipping_fee_html = '
        <tr>
                                <td style="padding: 10px; border: 1px solid black; border-collapse: collapse; color:black;" colspan="3">Shipping fee</td>
                                <td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">200php</td>
                            </tr>';
    }
    $order_temp_id = $order_id + 2019000;
    $output = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
<table align="center" width="700px" border="0" cellpadding="0" cellspacing="0" >
    <tr>
        <td>
        <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr>
                <td>
                    <div style="padding:10px 10px; background:black;">
                        <img src="cid:goldilogo" height="50px">
                    </div>
                <td>
            </tr>
			 <tr>
                <td>
                    <div style="padding:10px; background:#f7f7f7; color: black; " >
                        <span style="color: black;">Ordered At: ' . date('Y-m-d H:i:s') . '</span><br>
                        
                    </div>
                <td>
            </tr>
            <tr>
                <td>
                    <div style="padding:10px; background:#f7f7f7; color: black; " >
                        <span style="color: black;">Please confirm your order below:</span><br>
                        
                    </div>
                <td>
            </tr>
            <tr>
                <td>
                <div style="background:#f7f7f7; color: black; text-align: center;" >
                    <strong>Reference no.: ' . $order_temp_id . '</strong>
                </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="padding:10px; background:#f7f7f7; color: black; text-align: center;padding-bottom: 10px;" >
                        <img src="cid:barcodeimg">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="border: 1px solid black; border-collapse: collapse; width: 100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="padding: 10px; border: 1px solid black; border-collapse: collapse; background-color: #7f878c; color:black;">Items</th>
                        <th style="padding: 10px; border: 1px solid black; border-collapse: collapse; background-color: #7f878c; color:black;">Price</th>
                        <th style="padding: 10px; border: 1px solid black; border-collapse: collapse; background-color: #7f878c; color:black;">Quantity</th>
                        <th style="padding: 10px; border: 1px solid black; border-collapse: collapse; background-color: #7f878c; color:black;">Total</th>
                    </tr>';

    // $total_price = 0.0;

    if ($mail_table == "") {
        foreach ($_SESSION['cart'] as $key => $value) {
            $total_price += $value['product_price'];


            $output .= '<tr>
										<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; color:black;">' . $value['product_name'] . '</td>
										<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">' . $value['product_price_fix'] . '</td>
										<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:center; color:black;">' . $value['quantity'] . '</td>
										<td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">' . $value['product_price'] . '</td>
									</tr>';
        }
    } else {
        $output .= $mail_table;
    }

    $total_price += $shipping_fee;

    $output .= $shipping_fee_html . '
                            <tr>
                                <td style="padding: 10px; border: 1px solid black; border-collapse: collapse; color:black;" colspan="3"></td>
                                <td style="padding: 10px; border: 1px solid black; border-collapse: collapse; text-align:right; color:black;">' . $total_price . 'php</td>
                            </tr>


                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="background:#f7f7f7; padding:10px; color:black;">
                        <strong>Delivery Details </strong>
                        <pre><strong>Name: </strong>' . $full_name . '</pre>
                        <pre><strong>Address: </strong>' . $address . '</pre>
                        <pre>*You may contact us through telephone numbers: 88-483-270</pre>
                        <pre>*Estimated delivery 2-7days</pre>
                    </div>
                    <div style="padding:30px; color:black;">
                        Click <a style="color:black;" href="https://' . $_SERVER['SERVER_NAME'] . '/index.php?option=verified&id=' . $order_id . '" target="_blank">here</a> to confirm your order<br>
                           or<br>
                        <a style="color:black;" href="https://' . $_SERVER['SERVER_NAME'] . '/index.php?option=verified&id=' . $order_id . '" target="_blank">https://'  . $_SERVER['SERVER_NAME'] . '/ordering_online_system/index.php?option=verified&id=' . $order_id . '</a>
                  
					</div>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>

        
         
        
           
</body>

</html>';

    return $output;
}
