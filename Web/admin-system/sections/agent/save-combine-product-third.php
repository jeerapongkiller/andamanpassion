<?php
    $mainid = !empty($_POST["mainid"]) ? $_POST["mainid"] : '';
    $sp_id = !empty($_POST["sp_id"]) ? $_POST["sp_id"] : '';
    $ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
    $agent = !empty($_POST["agent"]) ? $_POST["agent"] : '';
    $combine = !empty($_POST["combine"]) ? $_POST["combine"] : '';
    $first_id = !empty($_POST["first_id"]) ? $_POST["first_id"] : '';
    $second_id = !empty($_POST["second_id"]) ? $_POST["second_id"] : '';
    $third_id = !empty($_POST["third_id"]) ? $_POST["third_id"] : '';
    $third_rate_2 = !empty($_POST["third_rate_2"]) ? preg_replace('(,)','',$_POST["third_rate_2"]) : '0';
    $third_rate_4 = !empty($_POST["third_rate_4"]) ? preg_replace('(,)','',$_POST["third_rate_4"]) : '0';
    $third_charter_2 = !empty($_POST["third_charter_2"]) ? preg_replace('(,)','',$_POST["third_charter_2"]) : '0';
    $third_group_2 = !empty($_POST["third_group_2"]) ? preg_replace('(,)','',$_POST["third_group_2"]) : '0';
    $third_transfer_2 = !empty($_POST["third_transfer_2"]) ? preg_replace('(,)','',$_POST["third_transfer_2"]) : '0';
    $third_extra_hour_2 = !empty($_POST["third_extra_hour_2"]) ? preg_replace('(,)','',$_POST["third_extra_hour_2"]) : '0';
    $third_extrabeds_2 = !empty($_POST["third_extrabeds_2"]) ? preg_replace('(,)','',$_POST["third_extrabeds_2"]) : '0';
    $third_sharingbed_2 = !empty($_POST["third_sharingbed_2"]) ? preg_replace('(,)','',$_POST["third_sharingbed_2"]) : '0';

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    // $return_url = !empty($first_id) ? '&id='.$first_id : '';
    $message_alert = "error";

    if(!empty($agent) && !empty($combine) && !empty($sp_id) && !empty($ptype) && !empty($first_id) && !empty($second_id))
	{
        if(empty($mainid))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO products_category_third_combine (combine_agentxsupplier, products_category_first, products_category_second, products_category_third, rate_2, rate_4, charter_2, group_2, transfer_2, extra_hour_2, extrabeds_2, sharingbed_2, trash_deleted, last_edit_time) "; 
            $query .= "VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, now())";
            $result = mysqli_query($mysqli_p, $query);
            $mainid = mysqli_insert_id($mysqli_p);
        }

        if(!empty($mainid))
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

            $query = "UPDATE products_category_third_combine SET";

                $query .= " combine_agentxsupplier = ?,";
                $bind_types .= "i";
                array_push($params, $combine);

                $query .= " products_category_first = ?,"; 
                $bind_types .= "i";
                array_push($params, $first_id);

                $query .= " products_category_second = ?,"; 
                $bind_types .= "i";
                array_push($params, $second_id);

                $query .= " products_category_third = ?,"; 
                $bind_types .= "i";
                array_push($params, $third_id);

                $query .= " rate_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_rate_2);

                $query .= " rate_4 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_rate_4);

                $query .= " charter_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_charter_2);

                $query .= " group_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_group_2);

                $query .= " transfer_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_transfer_2);

                $query .= " extra_hour_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_extra_hour_2);

                $query .= " extrabeds_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_extrabeds_2);

                $query .= " sharingbed_2 = ?,"; 
                $bind_types .= "d";
                array_push($params, $third_sharingbed_2);

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
            $query .= " WHERE id = '$mainid'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$third_id."&ptype=".$ptype."&supplier=".$sp_id."&catefirst=".$first_id."&catesecond=".$second_id."&agent=".$agent."&combine=".$combine;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/combine-product-third-detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/list&message=".$message_alert."'\" >";
    }
?>