<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["prodval"]) && !empty($_POST["bookval"])){
        $trash_deleted = 0;
        $status_email = 2;
        $catesecond = $_POST["catesecond"];
        $booking = $_POST["bookval"];
        $prodval = $_POST["prodval"];
        $id_type = $_POST["id_type"];

        # ---- Check same ---- #
        // $querysame = "SELECT * FROM booking_products WHERE booking = ? AND products_category_second = ? AND trash_deleted = ?";
        // $procedural_statement = mysqli_prepare($mysqli_p, $querysame);
        // mysqli_stmt_bind_param($procedural_statement, 'iii', $booking, $catesecond, $trash_deleted);
        // mysqli_stmt_execute($procedural_statement);
        // $resultsame = mysqli_stmt_get_result($procedural_statement);
        // $numrowsame = mysqli_num_rows($resultsame);
        // if ($numrowsame > 0) {
        //     echo $numrowsame;
        //     exit();
        // }

        $query = "UPDATE booking_products SET trash_deleted = ? , status_email = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'iii', $trash_deleted, $status_email, $prodval);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        # --- booking_history
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
            $description_field = "สถานะ : คืนข้อมูล";

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
            array_push($params, $booking);

            $query_history .= " history_type = ?,";
            $bind_types .= "i";
            array_push($params, '4');

            $query_history .= " booking_products = ?,";
            $bind_types .= "i";
            array_push($params, $prodval);

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
        mysqli_close($mysqli_p);
    }
?>