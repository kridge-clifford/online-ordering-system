<?php

use PHPMailer\PHPMailer\Exception;

use function PHPSTORM_META\type;

function confirmQuery($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED ." . mysqli_error($connection));
    }
}

function colorOrderStatus($order_status)
{
    if ($order_status == "Pending" || $order_status == "Waiting") {
        $text_color = "badge badge-warning";
    } elseif ($order_status == "Completed") {
        $text_color = "badge badge-success";
    } elseif ($order_status == "Shipping") {
        $text_color = "badge badge-secondary";
    } elseif ($order_status == "Processing") {
        $text_color = "badge badge-info";
    } elseif ($order_status == "Refunded" || $order_status == "Cancelled") {
        $text_color = "badge badge-danger";
    }
    return $text_color;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function addValidation()
{
    echo "<script>
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>";
}

function addProduct()
{
    global $connection;

    $product_name = $_POST['product_name'];

    if (boolProductExist($product_name)) {
        echo '<script>alert("Product already exists!"); location.href = "./products.php?source=add_product" </script>';
        exit;
    }

    $product_category_id = $_POST['product_category'];
    $product_brand_id = $_POST['product_brand'];
    $product_quantity = $_POST['product_quantity'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];

    if (isset($_POST['product_variants'])) {
        $variant_name = $_POST['variant_name'] != "" ? $_POST['variant_name'] : "NONE";
    }else{
        $variant_name = "NONE";
    }

    $post_image = $_FILES['image']['name'];
    $product_image_temp = $_FILES['image']['tmp_name'];
    $extension = explode("/", $_FILES["image"]["type"]);

    $image_name = generateRandomString(20) . "." . $extension[1];

    move_uploaded_file($product_image_temp, "../images/$image_name");

    $product_images = [];
    for ($i = 0; $i < count($_FILES["uploadFile"]["name"]); $i++) {
        $uploadfile = $_FILES["uploadFile"]["tmp_name"][$i];
        $extension_images = explode("/", $_FILES["uploadFile"]["type"][$i]);
        $images_name = generateRandomString(20) . "." . $extension_images[1];
        $product_images[] = $images_name;
        move_uploaded_file($uploadfile, "../images/$images_name");
    }


    try {
        $query = "
                INSERT INTO products (category_id, brand_id, product_name, product_description, product_quantity, product_price, product_image, product_variant) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?);
        ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iissidss", $product_category_id, $product_brand_id, $product_name, $product_description, $product_quantity, $product_price, $image_name, $variant_name);
        $stmt->execute();
        $product_id = $stmt->insert_id;
        $stmt->close();


        foreach ($product_images as $value) {

            $query_p = "INSERT INTO products_images (product_id, products_images_name) VALUES (?, ?) ";

            $stmt2 = $connection->prepare($query_p);
            $stmt2->bind_param("is", $product_id, $value);
            $stmt2->execute();
            $stmt2->close();
        }

        $variant_option = explode(",", $_POST['variant_option_values']);

        if (isset($_POST['product_variants'])) {
            if (count($variant_option) < 1) {
                $query_p = "INSERT INTO variants (product_id, variant_name) VALUES (?, ?) ";
                $value = "NONE";
                $stmt2 = $connection->prepare($query_p);
                $stmt2->bind_param("is", $product_id, $value);
                $stmt2->execute();
                $stmt2->close();
            } else {
                foreach ($variant_option as $item) {
                    $query_p = "INSERT INTO variants (product_id, variant_name) VALUES (?, ?) ";

                    $stmt2 = $connection->prepare($query_p);
                    $stmt2->bind_param("is", $product_id, $item);
                    $stmt2->execute();
                    $stmt2->close();
                }
            }
        }

        echo "<script>
                alert('Saved!');
                window.location.href = 'products.php';
            </script>";
    } catch (Exception $ex) {
        echo "<script>alert('$ex')</script>";
        die("QUERY FAILED ." . mysqli_error($ex));
    }
}
function selectAllProducts()
{
    global $connection;
    $query = "SELECT product_id, category_title, brand_title, product_name, product_description, product_quantity, product_price, product_image FROM products 
                INNER JOIN categories ON categories.category_id = products.category_id 
                INNER JOIN brand ON brand.brand_id = products.brand_id ; ";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function selectAllProductVariants($product_id)
{
    global $connection;
    $query = "SELECT variant_name FROM variants WHERE product_id = ? AND variant_status = 'active' ; ";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function deleteProduct()
{
    global $connection;

    if (isset($_GET['delete'])) {
        $the_post_id = $_GET['delete'];
        $query = "DELETE FROM products WHERE product_id = ? ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $the_post_id);
        $stmt->execute();
        $stmt->close();
        header("Location: products.php");
    }
}

function selectAllCategories()
{
    global $connection;
    $query = "SELECT category_id, category_title FROM categories ; ";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function addCategory()
{
    global $connection;
    if (isset($_POST['submit'])) {
        $category_title = $_POST['category_title'];

        if (boolCategoryExist($category_title)) {
            echo '<script>alert("Category already exists!"); location.href = "./categories.php" </script>';
            exit;
        }

        if ($category_title == "" || empty($category_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO categories (category_title) ";
            $query .= "VALUES (?) ";

            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $category_title);
            $stmt->execute();
            if (!$stmt) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
    }
}

function deleteCategory()
{
    global $connection;

    if (isset($_GET['delete'])) {
        $category_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE category_id = ? ; ";
        $stmt = mysqli_prepare($connection, $query);
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        header("Location: categories.php");
    }
}

function selectAllBrands()
{
    global $connection;
    $query = "SELECT brand_id, brand_title FROM brand ; ";
    $stmt = mysqli_prepare($connection, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function addBrand()
{
    global $connection;
    if (isset($_POST['submit'])) {
        $brand_title = $_POST['brand_title'];

        if (boolBrandExist($brand_title)) {
            echo '<script>alert("Brand already exists!"); location.href = "./brands.php" </script>';
            exit;
        }

        if ($brand_title == "" || empty($brand_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO brand (brand_title) VALUES (?) ";

            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $brand_title);
            $stmt->execute();

            if (!$stmt) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
    }
}

function deleteBrand()
{
    global $connection;

    if (isset($_GET['delete'])) {
        $brand_id = $_GET['delete'];
        $query = "DELETE FROM brand WHERE brand_id = ? ; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        header("Location: brands.php");
    }
}

function selectProductById()
{
    global $connection;

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    }

    try {
        $query = "SELECT product_id, category_title, brand_title, product_name, product_description, 
                        product_quantity, product_price, product_image, product_variant FROM products
                        INNER JOIN categories ON categories.category_id = products.category_id
                        INNER JOIN brand ON brand.brand_id = products.brand_id WHERE product_id = ? ;";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $ex) {
        die("Query failed " . $ex);
    }
}

function updateProduct()
{
    global $connection;

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];

    if (boolProductExist($product_name, $product_id)) {
        echo '<script>alert("Product already exists!"); location.href = "./products.php?source=add_product" </script>';
        exit;
    }

    $product_category_id = $_POST['product_category'];
    $product_brand_id = $_POST['product_brand'];
    $product_quantity = $_POST['product_quantity'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $variant_name = $_POST['variant_name'] != "" ? $_POST['variant_name'] : "NONE";

    $post_image = $_FILES['image']['name'];


    if (empty($post_image)) {
        $query = "SELECT product_image FROM products WHERE product_id = ? ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $image_name = $row['product_image'];
        }
    } else {
        $product_image_temp = $_FILES['image']['tmp_name'];
        $extension = explode("/", $_FILES["image"]["type"]);


        $image_name = generateRandomString(20) . "." . $extension[1];

        move_uploaded_file($product_image_temp, "../images/$image_name");
    }

    $product_images_name = $_FILES["uploadFile"]["name"];

    $product_images = [];
    // var_dump($product_images_name);
    // echo count($product_images_name);
    // exit;
    if ($product_images_name[0] == "") {
        $query = "SELECT products_images_name FROM products_images WHERE product_id = ? AND products_images_status = 'active' ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($rows = $result->fetch_assoc()) {
            $product_images[] = $rows['products_images_name'];
        }
    } else {
        for ($i = 0; $i < count($_FILES["uploadFile"]["name"]); $i++) {
            $uploadfile = $_FILES["uploadFile"]["tmp_name"][$i];
            $extension_images = explode("/", $_FILES["uploadFile"]["type"][$i]);
            $images_name = generateRandomString(20) . "." . $extension_images[1];
            $product_images[] = $images_name;
            move_uploaded_file($uploadfile, "../images/$images_name");
        }
    }



    $query_p = "UPDATE products_images SET products_images_status = 'inactive' WHERE product_id = ? ";
    $stmt2 = $connection->prepare($query_p);
    $stmt2->bind_param("i", $product_id);
    $stmt2->execute();
    $stmt2->close();

    foreach ($product_images as $value) {

        $query_p = "INSERT INTO products_images (product_id, products_images_name) VALUES (?, ?) ";

        $stmt2 = $connection->prepare($query_p);
        $stmt2->bind_param("is", $product_id, $value);
        $stmt2->execute();
        $stmt2->close();
    }

    $variant_option = explode(",", $_POST['variant_option_values']);

    $query_p = "UPDATE variants SET variant_status = 'inactive' WHERE product_id = ? ";
    $stmt2 = $connection->prepare($query_p);
    $stmt2->bind_param("i", $product_id);
    $stmt2->execute();
    $stmt2->close();

    if (isset($_POST['product_variants'])) {
        if (count($variant_option) == 1 && $variant_option[0] == "") {
            $query_p = "INSERT INTO variants (product_id, variant_name) VALUES (?, ?) ";
            $value = "NONE";
            $stmt2 = $connection->prepare($query_p);
            $stmt2->bind_param("is", $product_id, $value);
            $stmt2->execute();
            $stmt2->close();
        } else {
            foreach ($variant_option as $item) {
                $query_p = "INSERT INTO variants (product_id, variant_name) VALUES (?, ?) ";

                $stmt2 = $connection->prepare($query_p);
                $stmt2->bind_param("is", $product_id, $item);
                $stmt2->execute();
                $stmt2->close();
            }
        }
    } else {
        $variant_name = "NONE";

        $query_p = "INSERT INTO variants (product_id, variant_name) VALUES (?, ?) ";
        $value = "NONE";
        $stmt2 = $connection->prepare($query_p);
        $stmt2->bind_param("is", $product_id, $value);
        $stmt2->execute();
        $stmt2->close();
    }


    try {
        $query = "UPDATE products SET  
        product_name        = ?,  
        category_id         = ?,  
        brand_id            = ?,  
        product_description = ?,  
        product_quantity    = ?, 
        product_price       = ?,  
        product_image       = ?,
        product_variant        = ?
        WHERE product_id = ? ";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("siisidssi", $product_name, $product_category_id, $product_brand_id, $product_description, $product_quantity, $product_price, $image_name, $variant_name, $product_id);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $ex) {
        echo "QUERY FAILED ." . $ex;
    }
    echo "<script>
                alert('Updated!');
                window.location.href = 'products.php';
            </script>";
}

function selectAllOrders($order_id = 0)
{
    global $connection;

    $query = "SELECT orders.order_id, CONCAT(customer.customer_firstname, ' ', customer.customer_lastname) as customer_name, orders.order_date, SUM(order_item.order_price) as total_price, payment.payment_type, payment.delivery_method, orders.order_status, COUNT(order_item.order_id) as items_count, customer.customer_address, customer.customer_email, customer.customer_phone   
            FROM orders 
            INNER JOIN customer 
                ON orders.customer_id = customer.customer_id 
            INNER JOIN payment 
                ON payment.order_id = orders.order_id 
            INNER JOIN order_item 
                ON orders.order_id = order_item.order_id ";

    if ($order_id != 0) {
        $query .= " WHERE orders.order_id = ? GROUP BY order_id; ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $order_id);
    } else {
        $query .= " WHERE orders.order_status != 'Pending' GROUP BY order_id ORDER BY orders.order_date DESC ";
        $stmt = $connection->prepare($query);
    }

    $stmt->execute();

    $result = $stmt->get_result();


    return $result;
}

function selectOrderItemsById($order_id_post)
{
    global $connection;
    $query = "SELECT products.product_name, variants.variant_name, order_item.order_quantity, order_item.order_price as total, products.product_id, order_item.status FROM order_item INNER JOIN orders ON orders.order_id = order_item.order_id INNER JOIN products ON products.product_id = order_item.product_id INNER JOIN variants ON variants.variant_id = order_item.variant_id WHERE orders.order_id = ?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id_post);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function updateOrderStatus()
{
    global $connection;

    if (isset($_GET['update'])) {
        $order_status = $_GET['update'];
        $order_id = $_GET['order_id'];

        if ($order_status == "completed") {
            $order_item = selectOrderItemsById($order_id);
            while ($rows = $order_item->fetch_assoc()) {
                $query = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?";

                $stmt = $connection->prepare($query);
                $stmt->bind_param("ii", $rows['order_quantity'], $rows['product_id']);
                $stmt->execute();
            }

            $query = "UPDATE order_item SET status = 'Completed' WHERE order_id = ?";

            $stmt = $connection->prepare($query);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
        } else if ($order_status == "refunded") {
            $order_item = selectOrderItemsById($order_id);
            while ($rows = $order_item->fetch_assoc()) {
                $query = "UPDATE products SET product_quantity = product_quantity + ? WHERE product_id = ?";

                $stmt = $connection->prepare($query);
                $stmt->bind_param("ii", $rows['order_quantity'], $rows['product_id']);
                $stmt->execute();
            }
        } else if ($order_status == "cancelled") {
            $query = "UPDATE order_item SET status = 'Cancelled' WHERE order_id = ?";

            $stmt = $connection->prepare($query);
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
        }

        $phone_number = retrieveCustomerPhone($order_id);
        $len = strlen($phone_number);
        $phone_number = "63" . substr($phone_number, 1, $len);
        sendSMS($phone_number, $order_status, $order_id);

        $query = "UPDATE orders SET order_status= ? WHERE order_id = ? ";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("si", $order_status, $order_id);
        $stmt->execute();

        header("Location: orders.php");
    }
}

function updateOrderItemStatus()
{
    global $connection;

    if (isset($_GET['update_item'])) {
        $order_status = $_GET['update_item'];
        $order_id = $_GET['order_id'];
        $product_id = $_GET['p_id'];

        if ($order_status == "completed") {
            $order_item = selectOrderItemsById($order_id);
            while ($rows = $order_item->fetch_assoc()) {
                if ($product_id == $rows['product_id']) {
                    $query = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?";

                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("ii", $rows['order_quantity'], $rows['product_id']);
                    $stmt->execute();
                }
            }
        } else if ($order_status == "refunded") {
            $order_item = selectOrderItemsById($order_id);
            while ($rows = $order_item->fetch_assoc()) {
                if ($product_id == $rows['product_id']) {
                    $query = "UPDATE products SET product_quantity = product_quantity + ? WHERE product_id = ?";

                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("ii", $rows['order_quantity'], $rows['product_id']);
                    $stmt->execute();
                }
            }
        }

        $query = "UPDATE order_item SET status= ? WHERE order_id = ? AND product_id = ? ";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("sii", $order_status, $order_id, $product_id);
        $stmt->execute();

        header("Location: orders.php");
    }
}

function retrieveCustomerPhone($order_id)
{
    global $connection;

    $query = "SELECT customer_phone FROM customer INNER JOIN orders ON orders.customer_id = customer.customer_id WHERE orders.order_id = ? ;";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $phone_number = 0;
    while ($rows = $result->fetch_assoc()) {
        $phone_number = $rows['customer_phone'];
    }

    return $phone_number;
}

function retrieveProductImages()
{
    global $connection;

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
    }

    $query = "SELECT products_images_name FROM products_images WHERE product_id = ? AND products_images_status = 'active' ";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function orderStatusCount($order_date_from = "", $order_date_to = "")
{
    global $connection;

    $sqlWhere = "";

    if ($order_date_from != "0" && $order_date_to != "0" && $order_date_from != "" && $order_date_to != "") {
        $sqlWhere = "WHERE CAST(orders.order_date AS DATE) BETWEEN CAST('" . $order_date_from.  "' AS DATE) AND CAST('" . $order_date_to.  "' AS DATE)";
    }

    $query = "SELECT COUNT(orders.order_id) as count, orders.order_status FROM orders " . $sqlWhere . " GROUP BY orders.order_status";
    // echo $query;
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function orderStatusCountRefund($order_date_from = "", $order_date_to = "")
{
    global $connection;

    $sqlWhere = "";
    //echo $order_date_from;
    //echo $order_date_to;

    if ($order_date_from != "0" && $order_date_to != "0" && $order_date_from != "" && $order_date_to != "") {
        $sqlWhere = "WHERE CAST(order_item.created_at AS DATE) BETWEEN CAST('" . $order_date_from.  "' AS DATE) AND CAST('" . $order_date_to.  "' AS DATE)";
    }

    $query = "SELECT COUNT(order_item.order_item_id) as count, order_item.status FROM order_item " . $sqlWhere . " GROUP BY order_item.status ";
    //echo $query;
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function orderTodaySales()
{
    global $connection;

    $query = "SELECT SUM(order_item.order_price) as total_price FROM order_item WHERE order_item.status = 'Completed' AND CAST(created_at as Date) = CAST(NOW() as Date) GROUP BY order_item.status ";

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function getCurrentEmail()
{
    global $connection;

    $query = "SELECT email, password FROM email WHERE status = 'active' ";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function ordersCount()
{
    global $connection;

    $query = "SELECT order_id FROM orders WHERE order_status = 'Waiting' OR order_status = 'Processing' ";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function updateEmail()
{
    global $connection;

    if (isset($_POST['update'])) {
        $new_email = $_POST['email'];
        $new_password = $_POST['password'];

        $query = "UPDATE email SET email= ?, password = ? WHERE status = 'active' ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ss", $new_email, $new_password);
        $stmt->execute();
    }
}

function sendSMS($mobile_number, $order_status, $order_id)
{
    $text_message = "";
    $order_id = $order_id + 2019000;
    if ($order_status == "pending") {
        $text_message = "GOLDILOOPSVAPESHOP - Your order $order_id has been received and waiting for verification. You can track your order at https://goldiloops-vapeshop.online/index.php?option=track_order";
    } else if ($order_status == "processing") {
        $text_message = "GOLDILOOPSVAPESHOP - Your order $order_id has been approved! Your product(s) is being processed. You can track your order at https://goldiloops-vapeshop.online/index.php?option=track_order";
    } else if ($order_status == "shipping") {
        $text_message = "GOLDILOOPSVAPESHOP - Your product(s) from order $order_id has been shipped. You can track your order at https://goldiloops-vapeshop.online/index.php?option=track_order";
    } else if ($order_status == "completed") {
        $text_message = "GOLDILOOPSVAPESHOP - Thank you! Your product(s) from order $order_id has been delivered. You can track your order at https://goldiloops-vapeshop.online/index.php?option=track_order";
    } else {
        return;
    }

    $api_key = urlencode("");
    $mobile_number = urlencode($mobile_number);
    $text_message = urlencode($text_message);

    file_get_contents("https://platform.clickatell.com/messages/http/send?apiKey=$api_key&to=$mobile_number&content=$text_message");
}

function boolProductExist($product_name, $product_id = 0)
{
    global $connection;

    $sql = "";
    if ($product_id > 0) {
        $sql = " AND product_id != " . $product_id;
    }

    $query = "SELECT product_name FROM products WHERE product_name = ? " . $sql;
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $product_name);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($rows = $result->fetch_assoc()) {
        return true;
    }

    return false;
}

function boolCategoryExist($category_title, $category_id = 0)
{
    global $connection;

    $sql = "";
    if ($category_id > 0) {
        $sql = " AND category_id != " . $category_id;
    }

    $query = "SELECT category_title FROM categories WHERE category_title = ? " . $sql;
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $category_title);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($rows = $result->fetch_assoc()) {
        return true;
    }

    return false;
}

function boolBrandExist($brand_title, $brand_id = 0)
{
    global $connection;

    $sql = "";
    if ($brand_id > 0) {
        $sql = " AND brand_id != " . $brand_id;
    }
    $query = "SELECT brand_title FROM brand WHERE brand_title = ? " . $sql;
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $brand_title);
    $stmt->execute();

    $result = $stmt->get_result();
    while ($rows = $result->fetch_assoc()) {
        return true;
    }

    return false;
}

function retrieveSignatory()
{
    global $connection;

    $query = "SELECT full_name, position, contact_no, address FROM signatory ";
    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result;
}

function updateSignatoryName()
{
    global $connection;

    if (isset($_POST['update_signatory'])) {
        $signatory_name = $_POST['signatory_name'];
        $signatory_position = $_POST['signatory_position'];
        $signatory_contact = $_POST['signatory_contact'];
        $signatory_address = $_POST['signatory_address'];

        $query = "UPDATE signatory SET full_name = ?, position = ?, contact_no = ?, address = ? ";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("ssss", $signatory_name, $signatory_position, $signatory_contact, $signatory_address);
        $stmt->execute();
    }
}

function logOut()
{
    session_destroy();
}
