<?php
require("../../../inc/connection.php");

if (!empty($_POST["page"])) {
    $query_question = "SELECT * FROM question WHERE page = '" . $_POST["page"] . "' ";
    $procedural_statement_question = mysqli_prepare($mysqli_p, $query_question);
    mysqli_stmt_execute($procedural_statement_question);
    $result_question = mysqli_stmt_get_result($procedural_statement_question);
    $row_question = mysqli_fetch_assoc($result_question);
    echo $row_question['detail'];
}
