<?php
    $ag_id = !empty($_POST["ag_id"]) ? $_POST["ag_id"] : '';
    $ag_status = !empty($_POST["ag_status"]) ? $_POST["ag_status"] : '2';
    $ag_transfer = !empty($_POST["ag_transfer"]) ? $_POST["ag_transfer"] : '2';
    $ag_payments_vat = !empty($_POST["ag_payments_vat"]) ? $_POST["ag_payments_vat"] : '';
    $ag_company = !empty($_POST["ag_company"]) ? $_POST["ag_company"] : '';
    $ag_company_invoice = !empty($_POST["ag_company_invoice"]) ? $_POST["ag_company_invoice"] : '';
    $ag_telephone = !empty($_POST["ag_telephone"]) ? $_POST["ag_telephone"] : '';
    $ag_fax = !empty($_POST["ag_fax"]) ? $_POST["ag_fax"] : '';
    $ag_email = !empty($_POST["ag_email"]) ? $_POST["ag_email"] : '';
    $ag_website = !empty($_POST["ag_website"]) ? $_POST["ag_website"] : '';
    $ag_address = !empty($_POST["ag_address"]) ? $_POST["ag_address"] : '';
    $ag_tax_no = !empty($_POST["ag_tax_no"]) ? $_POST["ag_tax_no"] : '';
    $ag_headquarters = !empty($_POST["ag_headquarters"]) ? $_POST["ag_headquarters"] : '';
    $ag_contact_person = !empty($_POST["ag_contact_person"]) ? $_POST["ag_contact_person"] : '';
    $ag_contact_position = !empty($_POST["ag_contact_position"]) ? $_POST["ag_contact_position"] : '';
    $ag_contact_telephone = !empty($_POST["ag_contact_telephone"]) ? $_POST["ag_contact_telephone"] : '';
    $ag_contact_email = !empty($_POST["ag_contact_email"]) ? $_POST["ag_contact_email"] : '';

    $page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
    $ag_company_duplicate = !empty($_POST["ag_company_duplicate"]) ? $_POST["ag_company_duplicate"] : 'false';
    $ag_company_invoice_duplicate = !empty($_POST["ag_company_invoice_duplicate"]) ? $_POST["ag_company_invoice_duplicate"] : 'false';
    $trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
    $return_url = !empty($ag_id) ? '&id='.$ag_id : '';
    $message_alert = "error";

    if(!empty($ag_company) && $ag_company_duplicate != "false" && $ag_company_invoice_duplicate != "false")
	{
        if(empty($ag_id))
	    {
            # ---- Insert to database ---- #
            $query = "INSERT INTO agent (status, company, company_invoice, address, telephone, fax, ag_email, website, tax_no, headquarters, transfer, payments_vat, contact_person, contact_position, contact_telephone, contact_email, photo1, trash_deleted, create_date, last_edit_time)";
            $query .= "VALUES (0, '', '', '', '', '', '', '', '', '', 0, 0, '', '', '', '', '', 0, now(), now())";
            $result = mysqli_query($mysqli_p, $query);
            $ag_id = mysqli_insert_id($mysqli_p);
        }

        if(!empty($ag_id))
	    {
            # ---- Upload Photo ---- #
            $photo_count = !empty($_POST["photo_count"]) ? $_POST["photo_count"] : 0;
            $uploaddir = "../inc/photo/agent/";
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

            $query = "UPDATE agent SET";

                $query .= " status = ?,";
                $bind_types .= "i";
                array_push($params, $ag_status);

                $query .= " transfer = ?,";
                $bind_types .= "i";
                array_push($params, $ag_transfer);

                $query .= " payments_vat = ?,";
                $bind_types .= "i";
                array_push($params, $ag_payments_vat);

                $query .= " company = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_company);

                $query .= " company_invoice = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_company_invoice);

                $query .= " telephone = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_telephone);

                $query .= " fax = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_fax);

                $query .= " ag_email = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_email);

                $query .= " website = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_website);

                $query .= " address = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_address);

                $query .= " tax_no = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_tax_no);

                $query .= " headquarters = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_headquarters);

                $query .= " contact_person = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_contact_person);

                $query .= " contact_position = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_contact_position);

                $query .= " contact_telephone = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_contact_telephone);

                $query .= " contact_email = ?,"; 
                $bind_types .= "s";
                array_push($params, $ag_contact_email);

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
            $query .= " WHERE id = '$ag_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            if($bind_types != "")
            { 
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            mysqli_close($mysqli_p);

            $return_url = "&id=".$ag_id;
            $message_alert = "success";
            echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/detail".$return_url."&message=".$message_alert."'\" >";
        }
    }
    else
    {
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=agent/detail".$return_url."&message=".$message_alert."'\" >";
    }
?>