<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["cateval"])){
        $query = "UPDATE products_category_third SET trash_deleted = ?, status = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'iii', $trash_deleted, $status, $_POST["cateval"]);
        $trash_deleted = 1;
        $status = 2;
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        $query = "UPDATE products_category_third_combine SET trash_deleted = ? WHERE products_category_third = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'ii', $trash_deleted, $_POST["cateval"]);
        $trash_deleted = 1;
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        mysqli_close($mysqli_p);
    }
?>