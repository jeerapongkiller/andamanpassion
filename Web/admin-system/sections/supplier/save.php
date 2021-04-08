<?php
    $sp_id = !empty($_POST["sp_id"]) ? $_POST["sp_id"] : '';
    $sp_status = !empty($_POST["sp_status"]) ? $_POST["sp_status"] : '2';
    $sp_company = !empty($_POST["sp_company"]) ? $_POST["sp_company"] : '';
    $sp_telephone = !empty($_POST["sp_telephone"]) ? $_POST["sp_telephone"] : '';
    $sp_fax = !empty($_POST["sp_fax"]) ? $_POST["sp_fax"] : '';
    $sp_email = !empty($_POST["sp_email"]) ? $_POST["sp_email"] : '';
    $sp_website = !empty($_POST["sp_website"]) ? $_POST["sp_website"] : '';
    $sp_address = !empty($_POST["sp_address"]) ? $_POST["sp_address"] : '';
    $sp_contact_person = !empty($_POST["sp_contact_person"]) ? $_POST["sp_contact_person"] : '';
    $sp_contact_position = !empty($_POST["sp_contact_position"]) ? $_POST["sp_contact_position"] : '';
    $sp_contact_telephone = !empty($_POST["sp_contact_telephone"]) ? $_POST["sp_contact_telephone"] : '';
    $sp_contact_email = !empty($_POST["sp_contact_email"]) ? $_POST["sp_contact_email"] : '';

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $sp_company_duplicate = !empty($_POST["sp_company_duplicate"]) ? $_POST["sp_company_duplicate"] : 'false';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    $return_url = !empty($sp_id) ? '&id='.$sp_id : '';
    $message_alert = "error";

    if(!empty($sp_company) && $sp_company_duplicate != "false")
	{
        if(empty($sp_id))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO supplier (status, company, address, telephone, fax, sup_email, website, contact_person, contact_position, contact_telephone, contact_email, photo1, trash_deleted, create_date, last_edit_time)";
            $query .= "VALUES (0, '', '', '', '', '', '', '', '', '', '', '', 0, now(), now())";
            $result = mysqli_query($mysqli_p, $query);
            $sp_id = mysqli_insert_id($mysqli_p);

        }

        if(!empty($sp_id))
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

            $query = "UPDATE supplier SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, $sp_status);

                $query .= " company = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_company);

                $query .= " telephone = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_telephone);

                $query .= " fax = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_fax);

                $query .= " sup_email = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_email);

                $query .= " website = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_website);

                $query .= " address = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_address);

                $query .= " contact_person = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_contact_person);

                $query .= " contact_position = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_contact_position);

                $query .= " contact_telephone = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_contact_telephone);

                $query .= " contact_email = ?,"; 
                $bind_types .= "s";
                array_push($params, $sp_contact_email);

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

                $query .= ($page_title == "เพิ่มข้อมูล") ? ' create_date = now(),' : '';

            $query .= " last_edit_time = now()";
            $query .= " WHERE id = '$sp_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$sp_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/detail".$return_url."&message=".$message_alert."'\" >";
    }
?>