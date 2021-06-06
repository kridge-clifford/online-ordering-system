<?php

include "db.php";

if (isset($_POST['year'])) {

    $query = "SELECT 'Jan' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 1 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'FEB' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 2 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'MAR' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 3 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'APR' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 4 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'MAY' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 5 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'JUN' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 6 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'JUL' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 7 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'AUG' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 8 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'SEP' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 9 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'OCT' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 10 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'NOV' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 11 AND YEAR(orders.order_date) = 2019
    
    UNION
    
    SELECT 'DEC' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
    FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
    WHERE month(orders.order_date) = 12 AND YEAR(orders.order_date) = 2019
    ";

    $stmt = $connection->prepare($query);
    $stmt->execute();
 

    echo json_encode(array($stmt->fetchAll()));
}
$query = "SELECT 'Jan' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 1 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'FEB' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 2 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'MAR' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 3 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'APR' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 4 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'MAY' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 5 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'JUN' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 6 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'JUL' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 7 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'AUG' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 8 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'SEP' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 9 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'OCT' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 10 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'NOV' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 11 AND YEAR(orders.order_date) = 2019

UNION

SELECT 'DEC' AS month, COALESCE(SUM(order_item.order_price),0) AS total_sales 
FROM orders INNER JOIN order_item on orders.order_id = order_item.order_id
WHERE month(orders.order_date) = 12 AND YEAR(orders.order_date) = 2019
";

$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all());