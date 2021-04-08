<?php
    $dp_id = !empty($_POST["dp_id"]) ? $_POST["dp_id"] : '';
    $dp_status = !empty($_POST["dp_status"]) ? $_POST["dp_status"] : '2';
    $dp_name = !empty($_POST["dp_name"]) ? $_POST["dp_name"] : '';
    $dp_pickup = !empty($_POST["dp_pickup"]) ? $_POST["dp_pickup"] : '1';
    $dp_dropoff = !empty($_POST["dp_dropoff"]) ? $_POST["dp_dropoff"] : '1';

    $return_url = !empty($dp_id) ? '&id='.$dp_id : '';
    $message_alert = "error";

    if(!empty($dp_name))
	{
        if(empty($dp_id))
	    {
            # ---- Insert to database ---- #
            $query_place = "INSERT INTO place (status, name, pickup, dropoff, trash_deleted, last_edit_time)";
            $query_place .= "VALUES (0, '', 0, 0, 0, now())";
            $result = mysqli_query($mysqli_p, $query_place);
            $dp_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($dp_id))
	    {
            # ---- Update to database ---- #
            $bind_types = "";
            $params = array();

            $query = "UPDATE place SET";

            $query .= " status = ?,";
            $bind_types .= "i";
            array_push($params, $dp_status);

            $query .= " name = ?,";
            $bind_types .= "s";
            array_push($params, $dp_name);

            $query .= " pickup = ?,";
            $bind_types .= "i";
            array_push($params, $dp_pickup);

            $query .= " dropoff = ?,";
            $bind_types .= "i";
            array_push($params, $dp_dropoff);

            $query .= " trash_deleted = ?,";
            $bind_types .= "i";
            array_push($params, '2');

            $query .= " last_edit_time = now()";
            $query .= " WHERE id = '$dp_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$dp_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=place/detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=place/detail".$return_url."&message=".$message_alert."'\" >";
    }
?>