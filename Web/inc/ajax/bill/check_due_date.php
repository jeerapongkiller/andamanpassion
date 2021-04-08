<?php
require("../../../inc/connection.php");

if(!empty($_POST["status"])){
    $next_due_date = date('Y-m-d',strtotime($today . "+2 days"));
    $query_bill = "SELECT * FROM bill WHERE id > '0' AND due_date < '".$next_due_date."' AND due_date <= '".$today."' ";
    $procedural_statement_bill = mysqli_prepare($mysqli_p, $query_bill);
    mysqli_stmt_execute($procedural_statement_bill);
    $result_bill = mysqli_stmt_get_result($procedural_statement_bill);
    $num_bill = mysqli_num_rows($result_bill);
    echo $num_bill > '0' ? "<span class=\"heartbit\"></span> <span class=\"point\"></span>" : "" ;
}
?>