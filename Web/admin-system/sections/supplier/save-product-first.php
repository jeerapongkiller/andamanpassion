<?php
    $sp_id = !empty($_POST["sp_id"]) ? $_POST["sp_id"] : '';
    $ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
    $first_id = !empty($_POST["first_id"]) ? $_POST["first_id"] : '';
    $first_status = !empty($_POST["first_status"]) ? $_POST["first_status"] : '2';
    $first_name = !empty($_POST["first_name"]) ? $_POST["first_name"] : '';
    $first_validity_from = !empty($_POST["first_validity_from"]) ? $_POST["first_validity_from"] : $today;
    $first_validity_to = !empty($_POST["first_validity_to"]) ? $_POST["first_validity_to"] : $today;
    $first_procode = !empty($_POST["first_procode"]) ? $_POST["first_procode"] : '';

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    // $return_url = !empty($first_id) ? '&id='.$first_id : '';
    $message_alert = "error";

    if(!empty($first_name) && !empty($sp_id) && !empty($ptype))
	{
        if(empty($first_id))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO products_category_first (status, products_type, supplier, name, validity_from, validity_to, procode, photo1, photo2, photo3, trash_deleted, last_edit_time)";
            $query .= "VALUES (0, 0, 0, '', '', '', '', '', '', '', 0, now())";
            $result = mysqli_query($mysqli_p, $query);
            $first_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($first_id))
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

            $query = "UPDATE products_category_first SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, $first_status);

                $query .= " products_type = ?,"; 
                $bind_types .= "i";
                array_push($params, $ptype);

                $query .= " supplier = ?,"; 
                $bind_types .= "i";
                array_push($params, $sp_id);

                $query .= " name = ?,"; 
                $bind_types .= "s";
                array_push($params, $first_name);

                $query .= " validity_from = ?,"; 
                $bind_types .= "s";
                array_push($params, $first_validity_from);

                $query .= " validity_to = ?,"; 
                $bind_types .= "s";
                array_push($params, $first_validity_to);

                $query .= " procode = ?,"; 
                $bind_types .= "s";
                array_push($params, $first_procode);

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
            $query .= " WHERE id = '$first_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$first_id."&ptype=".$ptype."&supplier=".$sp_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/product-first-detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/list&message=".$message_alert."'\" >";
    }
?>