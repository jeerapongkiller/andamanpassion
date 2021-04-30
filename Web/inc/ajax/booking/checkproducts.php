<?php
require("../../../inc/connection.php");

if (!empty($_POST["booking_products"]) && !empty($_POST["booking"])) {
    $booking_products = $_POST["booking_products"];
    $booking = $_POST["booking"];
    $price_paid = 0;
    $query = "SELECT BP.*,
            PCS.id as pcsID, PCS.name as pcsName 
            FROM booking_products BP
            LEFT JOIN products_category_second PCS
            ON BP.products_category_second = PCS.id
            WHERE BP.id > '0' ";
    $query .= $booking_products == 'all' ? " AND BP.booking = '$booking'" : " AND BP.id = '$booking_products'";
    $result = mysqli_query($mysqli_p, $query);
    if ($booking_products == 'all') {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $price_paid = $price_paid + $row['price_latest'];
        }
    } else {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $price_paid = $row['price_latest'];
    }
    echo number_format($price_paid);
}
