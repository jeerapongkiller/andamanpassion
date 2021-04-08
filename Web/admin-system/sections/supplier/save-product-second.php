<?php
    $sp_id = !empty($_POST["sp_id"]) ? $_POST["sp_id"] : '';
    $ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
    $first_id = !empty($_POST["first_id"]) ? $_POST["first_id"] : '';
    $second_id = !empty($_POST["second_id"]) ? $_POST["second_id"] : '';
    $second_status = !empty($_POST["second_status"]) ? $_POST["second_status"] : '2';
    $second_name = !empty($_POST["second_name"]) ? $_POST["second_name"] : '';
    $second_tax = !empty($_POST["second_tax"]) ? $_POST["second_tax"] : '2';
    $second_tax = ($ptype == 3) ? $second_tax : '2';

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    // $return_url = !empty($first_id) ? '&id='.$first_id : '';
    $message_alert = "error";

    if(!empty($second_name) && !empty($sp_id) && !empty($ptype) && !empty($first_id))
	{
        if(empty($second_id))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO products_category_second (status, products_category_first, name, withholding_tax, trash_deleted, last_edit_time)";
            $query .= "VALUES (0, 0, '', 0, 0,now())";
            $result = mysqli_query($mysqli_p, $query);
            $second_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($second_id))
	    {
            # ---- Upload Photo ---- #
            $photo_count = !empty($_POST["photo_count"]) ? $_POST["photo_count"] : 0;
            $uploaddir = "../inc/photo/supplier/";
            $photo_time = time();
            $photo = array();
            $photo_name = array();
            $tmp_photo = array();
            $del_photo = array();
            $params = array();

            for($i=1;$i<=$photo_count;$i++)
            {
                $paramiter = "_".$i;
                $photo[$i] = !empty($_FILES["photo".$i]["tmp_name"]) ? $_FILES["photo".$i]["tmp_name"] : '';
                $photo_name[$i] = !empty($_FILES["photo".$i]["name"]) ? $_FILES["photo".$i]["name"] : '';
                $tmp_photo[$i] = !empty($_POST["tmp_photo".$i]) ? $_POST["tmp_photo".$i] : '';
                $del_photo[$i] = !empty($_POST["del_photo".$i]) ? $_POST["del_photo".$i] : '';
                // echo $photo[$i]." - ".$photo_name[$i]." - ".$tmp_photo[$i]." - ".$del_photo[$i]."<br />";

                if(!empty($del_photo[$i]))
                {
                    unlink($uploaddir.$tmp_photo[$i]);
                    $photo[$i] = "";
                }
                else
                {
                    $photo[$i] = !empty($photo[$i]) ? img_upload($photo[$i],$photo_name[$i],$tmp_photo[$i],$uploaddir,$photo_time,$paramiter) : $tmp_photo[$i];
                }

                // echo $photo[$i]."<br />";
            }

            # ---- Update to database ---- #
            $bind_types = "";
            $params = array();

            $query = "UPDATE products_category_second SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, $second_status);

                $query .= " products_category_first = ?,"; 
                $bind_types .= "i";
                array_push($params, $first_id);

                $query .= " name = ?,"; 
                $bind_types .= "s";
                array_push($params, $second_name);

                $query .= " withholding_tax = ?,";
                $bind_types .= "i";
                array_push($params, $second_tax);

                foreach($photo as $i => $item)
                {
                    if($item != "false")
                    {
                        $photo_field = "photo".$i;
                        $query .= " ".$photo_field." = ?,";
                        $bind_types .= "s";
                        array_push($params, $item);
                    }
                }

                // $query .= ($page_title == "เพิ่มข้อมูล") ? ' create_date = now(),' : '';

            $query .= " last_edit_time = now()";
            $query .= " WHERE id = '$second_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$second_id."&ptype=".$ptype."&supplier=".$sp_id."&catefirst=".$first_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/product-second-detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/list&message=".$message_alert."'\" >";
    }
?>