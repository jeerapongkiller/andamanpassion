<?php
    $em_id = !empty($_POST["em_id"]) ? $_POST["em_id"] : '';
    $em_status = !empty($_POST["em_status"]) ? $_POST["em_status"] : '2';
    $em_username = !empty($_POST["em_username"]) ? $_POST["em_username"] : '';
    $em_password = !empty($_POST["em_password"]) ? $_POST["em_password"] : '';
    $em_permission = !empty($_POST["permission"]) ? $_POST["permission"] : '2';
    $em_name = !empty($_POST["name"]) ? $_POST["name"] : '';
    $em_email = !empty($_POST["em_email"]) ? $_POST["em_email"] : '';
    $em_phone = !empty($_POST["em_phone"]) ? $_POST["em_phone"] : '';
    
    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $em_username_duplicate = !empty($_POST["em_username_duplicate"]) ? $_POST["em_username_duplicate"] : 'false';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    $return_url = !empty($em_id) ? '&id='.$em_id : '';
    $message_alert = "error";

    if(!empty($em_name) && $em_username_duplicate != "false")
	{
        if(empty($em_id))
	    {
            $query = "INSERT INTO employee () VALUES ()";
            $result = mysqli_query($mysqli_p, $query);
            $em_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($em_id))
	    {
            $bind_types = "";
            $params = array();

            $query = "UPDATE employee SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, $em_status);

                $query .= " em_username = ?,"; 
                $bind_types .= "s";
                array_push($params, $em_username);

                $query .= " em_password = ?,"; 
                $bind_types .= "s";
                array_push($params, $em_password);

                $query .= " permission = ?,"; 
                $bind_types .= "i";
                array_push($params, $em_permission);

                $query .= " name = ?,"; 
                $bind_types .= "s";
                array_push($params, $em_name);

                $query .= " em_email = ?,"; 
                $bind_types .= "s";
                array_push($params, $em_email);

                $query .= " em_phone = ?,"; 
                $bind_types .= "s";
                array_push($params, $em_phone);

                $query .= ($page_title == "เพิ่มข้อมูล") ? ' create_date = now(),' : '';

            $query .= " last_edit_time = now()";
            $query .= " WHERE id = '$em_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$em_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=employee/detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=employee/detail".$return_url."&message=".$message_alert."'\" >";
    }
?>