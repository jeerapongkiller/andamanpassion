<?php
    $ag_id = !empty($_POST["ag_id"]) ? $_POST["ag_id"] : '';
    $ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
    $supplier = !empty($_POST["supplier"]) ? $_POST["supplier"] : '';
    $combine_id = "";

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    $return_url = (!empty($ag_id) && !empty($ptype)) ? '&ptype='.$ptype.'&agent='.$ag_id : '';
    $message_alert = "error";

    if(!empty($supplier) && !empty($ag_id) && !empty($ptype))
	{
        if(empty($combine_id))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO combine_agentxsupplier (status, products_type, agent, supplier, trash_deleted, last_edit_time) ";
            $query .= "VALUES (0, 0, 0, 0, 0, now())";
            $result = mysqli_query($mysqli_p, $query);
            $combine_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($combine_id))
	    {
            # ---- Update to database ---- #
            $bind_types = "";
            $params = array();

            $query = "UPDATE combine_agentxsupplier SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, 1);

                $query .= " products_type = ?,";
                $bind_types .= "i";
                array_push($params, $ptype);

                $query .= " agent = ?,";
                $bind_types .= "i";
                array_push($params, $ag_id);

                $query .= " supplier = ?,"; 
                $bind_types .= "i";
                array_push($params, $supplier);

                $query .= " trash_deleted = ?,"; 
                $bind_types .= "i";
                array_push($params, 0);

            $query .= " last_edit_time = now()";
            $query .= " WHERE id = '$combine_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&ptype=".$ptype."&agent=".$ag_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/combine-agentxsupplier".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/combine-agentxsupplier".$return_url."&message=".$message_alert."'\" >";
    }
?>