<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["prodval"]) && !empty($_POST["vans_name"])){

        $query = "UPDATE booking_products SET vans = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'si', $_POST["vans_name"], $_POST["prodval"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        $description_field = "หมายเลขรถ/ป้ายทะเบียนรถ : ".$_POST["vans_name"];
        $history_type = "2";

        # --- booking_history ---#
        if (!empty($_POST["prodval"]) && !empty($_POST["bookval"])) {
            function get_client_ip()
            {
                $ipaddress = '';
                if (getenv('HTTP_CLIENT_IP'))
                    $ipaddress = getenv('HTTP_CLIENT_IP');
                else if (getenv('HTTP_X_FORWARDED_FOR'))
                    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                else if (getenv('HTTP_X_FORWARDED'))
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                else if (getenv('HTTP_FORWARDED_FOR'))
                    $ipaddress = getenv('HTTP_FORWARDED_FOR');
                else if (getenv('HTTP_FORWARDED'))
                    $ipaddress = getenv('HTTP_FORWARDED');
                else if (getenv('REMOTE_ADDR'))
                    $ipaddress = getenv('REMOTE_ADDR');
                else
                    $ipaddress = 'UNKNOWN';
                return $ipaddress;
            }

            $ip = get_client_ip();
            
            # ---- Insert to booking_history ---- #
            $query_history = "INSERT INTO booking_history (booking, history_type, booking_products, description_field, employee, ip_address, create_date)";
            $query_history .= "VALUES (0, 0, 0, '', 0, '', now())";
            $result = mysqli_query($mysqli_p, $query_history);
            $history_id = mysqli_insert_id($mysqli_p);

            $bind_types = "";
            $params = array();

            $query_history = "UPDATE booking_history SET";

            $query_history .= " booking = ?,";
            $bind_types .= "i";
            array_push($params, $_POST["bookval"]);

            $query_history .= " history_type = ?,";
            $bind_types .= "i";
            array_push($params, $history_type);

            $query_history .= " booking_products = ?,";
            $bind_types .= "i";
            array_push($params, $_POST["prodval"]);

            $query_history .= " description_field = ?,";
            $bind_types .= "s";
            array_push($params, $description_field);

            $query_history .= " employee = ?,";
            $bind_types .= "i";
            array_push($params, $_SESSION["admin"]["id"]);

            $query_history .= " ip_address = ?,";
            $bind_types .= "s";
            array_push($params, $ip);

            $query_history .= " create_date = now()";
            $query_history .= " WHERE id = '$history_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_history);
            if ($bind_types != "") {
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
        }
        echo $_POST["vans_name"];
    }
    mysqli_close($mysqli_p);
?>