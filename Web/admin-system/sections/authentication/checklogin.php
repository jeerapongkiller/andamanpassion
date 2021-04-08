<?php
    if(!empty($_POST['em_username']) && !empty($_POST['em_password']) && !empty($_POST['em_code']) && ($_POST['em_code'] == $confirm_code)){
        // Procedural mysqli
        $query = "SELECT * FROM employee WHERE em_username = ? AND em_password = ? AND status = '1'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);

        // Check error query
        if($procedural_statement == false) {
            die("<pre>".mysqli_error($mysqli_p).PHP_EOL.$query."</pre>");
        }

        mysqli_stmt_bind_param($procedural_statement, 'ss', $em_username, $em_password);

        $em_username = $_POST["em_username"];
        $em_password = $_POST["em_password"];

        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        if($numrow > 0){
            $_SESSION["admin"]["id"] = $row["id"];
            $_SESSION["admin"]["permission"] = $row["permission"];
            $_SESSION["admin"]["name"] = $row["name"];
            $_SESSION["admin"]["em_email"] = $row["em_email"];
            $_SESSION["admin"]["timestamp"] = time();

            mysqli_close($mysqli_p);

            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/list'\" >";
        }else{
            mysqli_close($mysqli_p);

            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?message=error-login'\" >"; # go to login page with error message
        }
    }else{
        mysqli_close($mysqli_p);
        
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?message=error-login'\" >"; # go to login page with error message
    }
?>