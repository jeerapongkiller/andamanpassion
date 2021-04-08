<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["ag_company_invoice"])){
        $bind_types = "";
        $params = array();

        $query = "SELECT * FROM agent WHERE id > '0'";
        if(!empty($_POST["ag_company_invoice"]))
        { 
            $query .= " AND company_invoice = ?"; 
            $bind_types .= "s";
            array_push($params, $_POST["ag_company_invoice"]);
        }
        if(!empty($_POST["ag_id"]))
        { 
            $query .= " AND id != ?"; 
            $bind_types .= "i";
            array_push($params, $_POST["ag_id"]);
        }
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if($bind_types != "")
        { 
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        echo ($numrow > 0) ?  'duplicate' : 'true';
    }else{
        echo "false";
    }
?>