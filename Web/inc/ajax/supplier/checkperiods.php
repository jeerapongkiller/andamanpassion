<?php
require("../../../inc/connection.php");

if (!empty($_POST["second_id"]) && $_POST["second_id"] > 0 && !empty($_POST["third_periods_from"]) && !empty($_POST["third_periods_to"])) {
    $bind_types = "";
    $params = array();
    $query_cate = "SELECT * 
                        FROM products_category_third 
                        WHERE id > '0' 
                            AND products_category_second = ? 
            ";
    $query_cate .= " AND (periods_from <= '" . $_POST["third_periods_to"] . "') AND (periods_to >= '" . $_POST["third_periods_from"] . "')";
    $query_cate .= $_POST["third_id"] > 0 ? " AND id != '" . $_POST["third_id"] . "'" : '';
    $query_cate .= " ORDER BY products_category_second ASC, periods_from ASC";
    $procedural_statement = mysqli_prepare($mysqli_p, $query_cate);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["second_id"]);
    mysqli_stmt_execute($procedural_statement);
    $result_cate = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result_cate);
    echo $numrow > 0 ? "false" : "true";
} else {
    echo "false";
}
