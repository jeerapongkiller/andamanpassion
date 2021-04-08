<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["prodval"]) && !empty($_POST["status"])){
        #--- Add & Edit Confirm ---#
        if($_POST["status"] == 1 || $_POST["status"] == 3 && !empty($_POST["confirm_name"])){
            $status_confirm = 1;
            $confirm_name = $_POST["confirm_name"];
            $description_field = "สถานะการยืนยัน : ยืนยันแล้ว </br>";
            $description_field .= "ยืนยันโดย : ".$confirm_name;
            $history_type = '5';
        #--- Cancel Confirm ---#
        }elseif($_POST["status"] == 2){
            $status_confirm = 2;
            $confirm_name = '';
            $description_field = "สถานะการยืนยัน : ยังไม่ยืนยัน";
            $history_type = '6';
        }

        $query = "UPDATE booking_products SET status_confirm = ?, status_confirm_by = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'isi', $status_confirm, $confirm_name, $_POST["prodval"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

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

        # --- booking confirm ---#
        $query_confirm = "SELECT booking,status_confirm, status_confirm_by FROM booking_products WHERE id > 0 
                          AND booking = ? AND trash_deleted = 0 AND status_confirm = 2 AND status_confirm_by = '' ";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_confirm);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["bookval"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrowconfirm = mysqli_num_rows($result);
        if ($numrowconfirm == 0) {
            $num_confirm = '1';
        }else{
            $num_confirm = '2';
        }
        $query = "UPDATE booking SET status_confirm = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'ii', $num_confirm ,$_POST["bookval"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        mysqli_close($mysqli_p);
    }
?>