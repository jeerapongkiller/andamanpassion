<?php
    require("../../../inc/connection.php");

    if(!empty($_POST["cateval"])){
        $query = "SELECT * FROM products_category_second WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["cateval"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0)
        {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $sc_no = $row["id"];
            $sc_status = 2;
            $sc_catefirst = stripslashes($row["products_category_first"]);
            $sc_name = stripslashes($row["name"]);
            $sc_name_copy = $sc_name." - (Copy)";
            $sc_tax = $row["withholding_tax"];

            if(!empty($row["id"]))
            {
                $second_copy = "";

                if(empty($second_copy))
	            {
                    $query_copy = "INSERT INTO products_category_second (status, products_category_first, name, withholding_tax, trash_deleted, last_edit_time)";
                    $query_copy .= "VALUES (0, 0, '', 0, 0,now())";
                    $result_copy = mysqli_query($mysqli_p, $query_copy);
                    $second_copy = mysqli_insert_id($mysqli_p);
                }

                if(!empty($second_copy))
	            {
                    $bind_types = "";
                    $params = array();

                    $query_copy = "UPDATE products_category_second SET";

                        $query_copy .= " status = ?,";
                        $bind_types .= "i";
                        array_push($params, $sc_status);

                        $query_copy .= " products_category_first = ?,"; 
                        $bind_types .= "i";
                        array_push($params, $sc_catefirst);

                        $query_copy .= " name = ?,"; 
                        $bind_types .= "s";
                        array_push($params, $sc_name_copy);

                        $query_copy .= " withholding_tax = ?,";
                        $bind_types .= "i";
                        array_push($params, $sc_tax);

                    $query_copy .= " last_edit_time = now()";
                    $query_copy .= " WHERE id = '$second_copy'";
                    $procedural_statement_copy = mysqli_prepare($mysqli_p, $query_copy);
                    if($bind_types != "")
                    { 
                        mysqli_stmt_bind_param($procedural_statement_copy, $bind_types, ...$params); 
                    }
                    mysqli_stmt_execute($procedural_statement_copy);
                    $result_copy = mysqli_stmt_get_result($procedural_statement_copy);

                    /* ============================================================== */
                    /* Loop : products_category_third */
                    /* ============================================================== */
                    $query_third = "SELECT * FROM products_category_third WHERE id > '0'";
                    $query_third .= " AND products_category_second = ?";
                    $procedural_statement_third = mysqli_prepare($mysqli_p, $query_third);
                    mysqli_stmt_bind_param($procedural_statement_third, 'i', $sc_no);
                    mysqli_stmt_execute($procedural_statement_third);
                    $result_third = mysqli_stmt_get_result($procedural_statement_third);
                    $numrow_third = mysqli_num_rows($result_third);
                    if($numrow_third > 0)
                    {
                        while($rowthird = mysqli_fetch_array($result_third, MYSQLI_ASSOC))
                        {
                            $third_id = "";

                            if(empty($third_id))
                            {
                                $query_insert = "INSERT INTO products_category_third (status, products_category_first, products_category_second, periods_from, periods_to, rate_1, rate_2, rate_3, rate_4, charter_1, charter_2, group_1, group_2, transfer_1, transfer_2, pax, extra_hour_1, extra_hour_2, hours_no, extrabeds_1, extrabeds_2, sharingbed_1, sharingbed_2, trash_deleted, last_edit_time) ";
                                $query_insert .= "VALUES (0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, now())";
                                $result_insert = mysqli_query($mysqli_p, $query_insert);
                                $third_id = mysqli_insert_id($mysqli_p);
                            }

                            if(!empty($third_id))
                            {
                                $bind_types = "";
                                $params = array();

                                $query_update = "UPDATE products_category_third SET";

                                $query_update .= " status = ?,";
                                $bind_types .= "i";
                                array_push($params, $rowthird["status"]);
                
                                $query_update .= " products_category_first = ?,"; 
                                $bind_types .= "i";
                                array_push($params, $rowthird["products_category_first"]);
                
                                $query_update .= " products_category_second = ?,"; 
                                $bind_types .= "i";
                                array_push($params, $second_copy);
                
                                $query_update .= " periods_from = ?,"; 
                                $bind_types .= "s";
                                array_push($params, $rowthird["periods_from"]);
                
                                $query_update .= " periods_to = ?,"; 
                                $bind_types .= "s";
                                array_push($params, $rowthird["periods_to"]);
                
                                $query_update .= " rate_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["rate_1"]);
                
                                $query_update .= " rate_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["rate_2"]);
                
                                $query_update .= " rate_3 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["rate_3"]);
                
                                $query_update .= " rate_4 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["rate_4"]);
                
                                $query_update .= " charter_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["charter_1"]);
                
                                $query_update .= " charter_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["charter_2"]);
                
                                $query_update .= " group_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["group_1"]);
                
                                $query_update .= " group_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["group_2"]);
                
                                $query_update .= " transfer_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["transfer_1"]);
                
                                $query_update .= " transfer_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["transfer_2"]);
                
                                $query_update .= " pax = ?,"; 
                                $bind_types .= "i";
                                array_push($params, $rowthird["pax"]);
                
                                $query_update .= " hours_no = ?,"; 
                                $bind_types .= "i";
                                array_push($params, $rowthird["hours_no"]);
                
                                $query_update .= " extra_hour_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["extra_hour_1"]);
                
                                $query_update .= " extra_hour_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["extra_hour_2"]);
                
                                $query_update .= " extrabeds_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["extrabeds_1"]);
                
                                $query_update .= " extrabeds_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["extrabeds_2"]);
                
                                $query_update .= " sharingbed_1 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["sharingbed_1"]);
                
                                $query_update .= " sharingbed_2 = ?,"; 
                                $bind_types .= "d";
                                array_push($params, $rowthird["sharingbed_2"]);

                                $query_update .= " last_edit_time = now()";
                                $query_update .= " WHERE id = '$third_id'";
                                $procedural_statement_update = mysqli_prepare($mysqli_p, $query_update);
                                if($bind_types != "")
                                { 
                                    mysqli_stmt_bind_param($procedural_statement_update, $bind_types, ...$params); 
                                }
                                mysqli_stmt_execute($procedural_statement_update);
                                $result_update = mysqli_stmt_get_result($procedural_statement_update);
                            } /* if(!empty($third_id)) */
                        } /* while($rowthird = mysqli_fetch_array($result_third, MYSQLI_ASSOC)) */
                    } /* if($numrow_third > 0) */
                }
                else
                {
                    echo "error";
                } /* if(!empty($second_copy)) */

                echo "success";
            }
            else
            {
                echo "error";
            } /* if(!empty($row["id"])) */
        }
        else
        {
            echo "error";
        } /* if($numrow > 0) */

        mysqli_close($mysqli_p);
    }
?>