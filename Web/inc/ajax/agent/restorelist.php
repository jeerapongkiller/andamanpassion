<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["ag_id"])){
        // $query = "DELETE FROM supplier WHERE id = ?";
        $query = "UPDATE agent SET trash_deleted = ?, status = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'iii', $trash_deleted, $status, $_POST["ag_id"]);
        $trash_deleted = 0;
        $status = 1;
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        mysqli_close($mysqli_p);
    }
?>