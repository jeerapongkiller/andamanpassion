<?php
$bo_id = !empty($_POST["bo_id"]) ? $_POST["bo_id"] : '';
$bo_status = !empty($_POST["bo_status"]) ? $_POST["bo_status"] : '1';
$bo_voucher_no = !empty($_POST["bo_voucher_no"]) ? $_POST["bo_voucher_no"] : '';
$bo_booking_date = !empty($_POST["bo_booking_date"]) ? $today : '';
$bo_booking_time = $times;
$bo_full_receipt = !empty($_POST["bo_full_receipt"]) ? $_POST["bo_full_receipt"] : '2';
$bo_receipt_name = !empty($_POST["bo_receipt_name"]) ? $_POST["bo_receipt_name"] : '';
$bo_receipt_address = !empty($_POST["bo_receipt_address"]) ? $_POST["bo_receipt_address"] : '';
$bo_receipt_taxid = !empty($_POST["bo_receipt_taxid"]) ? $_POST["bo_receipt_taxid"] : '';
$bo_receipt_detail = !empty($_POST["bo_receipt_detail"]) ? $_POST["bo_receipt_detail"] : '';
$bo_status_email = !empty($_POST["bo_status_email"]) ? $_POST["bo_status_email"] : '';
$bo_status_email_revise = !empty($_POST["bo_status_email_revise"]) ? $_POST["bo_status_email_revise"] : '';
$bo_status_confirm = !empty($_POST["bo_status_confirm"]) ? $_POST["bo_status_confirm"] : '';
$bo_customertype = !empty($_POST["bo_customertype"]) ? $_POST["bo_customertype"] : '0';
$bo_company = !empty($_POST["bo_company"]) ? $_POST["bo_company"] : '2';

if ($bo_customertype == 1) {
    $bo_agent = !empty($_POST["bo_agent"]) ? $_POST["bo_agent"] : '0';
    $bo_agent_voucher = !empty($_POST["bo_agent_voucher"]) ? $_POST["bo_agent_voucher"] : '';
    $bo_sale_name = !empty($_POST["bo_sale_name"]) ? $_POST["bo_sale_name"] : '';
    $bo_customer_firstname = !empty($_POST["bo_customer_firstname"]) ? $_POST["bo_customer_firstname"] : '';
    $bo_customer_lastname = !empty($_POST["bo_customer_lastname"]) ? $_POST["bo_customer_lastname"] : '';
    $bo_customer_mobile = !empty($_POST["bo_customer_mobile"]) ? $_POST["bo_customer_mobile"] : '';
    $bo_customer_email = "";
}

if ($bo_customertype == 2) {
    $bo_agent = 0;
    $bo_agent_voucher = "";
    $bo_sale_name = "-";
    $bo_customer_firstname = !empty($_POST["bo_customer_firstname"]) ? $_POST["bo_customer_firstname"] : '';
    $bo_customer_lastname = !empty($_POST["bo_customer_lastname"]) ? $_POST["bo_customer_lastname"] : '';
    $bo_customer_mobile = !empty($_POST["bo_customer_mobile"]) ? $_POST["bo_customer_mobile"] : '';
    $bo_customer_email = !empty($_POST["bo_customer_email"]) ? $_POST["bo_customer_email"] : '';
}

$page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
$trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
$return_url = !empty($bo_id) ? '&id=' . $bo_id : '';
$message_alert = "error";

if (!empty($bo_voucher_no) && $bo_customertype > 0) {
    if (empty($bo_id)) {
        # ---- Insert to database ---- #
        $query = "INSERT INTO booking (voucher_no, booking_date, booking_time, agent, agent_voucher, company, sale_name, customer_firstname, customer_lastname, customer_mobile, customer_email, full_receipt, receipt_name, receipt_address, receipt_taxid, receipt_detail, travel_date_min, status_email, status_email_revise, status_confirm, status, create_date, last_edit_time)";
        $query .= "VALUES ('', '', '', 0, '', 0, '', '', '', '', '', 0, '', '', '', '', '', 0, 0, 0, 0, now(), now())";
        $result = mysqli_query($mysqli_p, $query);
        $bo_id = mysqli_insert_id($mysqli_p);
    }

    if (!empty($bo_id)) {
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
        $description_field = "";

        $bo_status_text = ($bo_status != 2) ? 'ออนไลน์' : 'ยกเลิก';
        $bo_customertype_text = ($bo_customertype == 1) ? 'เอเย่นต์' : 'ลูกค้าทั่วไป';
        $bo_agent_text = ($bo_agent > 0) ? get_value("agent", "id", "company", $bo_agent, $mysqli_p) : '-';
        $bo_agent_voucher_text = ($bo_agent_voucher != "") ? $bo_agent_voucher : '-';
        $bo_customer_firstname_text = ($bo_customer_firstname != "") ? $bo_customer_firstname : '-';
        $bo_customer_lastname_text = ($bo_customer_lastname != "") ? $bo_customer_lastname : '-';
        $bo_customer_mobile_text = ($bo_customer_mobile != "") ? $bo_customer_mobile : '-';
        $bo_customer_email_text = ($bo_customer_email != "") ? $bo_customer_email : '-';
        $bo_full_receipt_text = ($bo_full_receipt != 2) ? 'แบบเต็ม' : 'ไม่ใช่แบบเต็ม';
        $bo_receipt_name_text = ($bo_receipt_name != "") ? $bo_receipt_name : '-';
        $bo_receipt_address_text = ($bo_receipt_address != "") ? $bo_receipt_address : '-';
        $bo_receipt_taxid_text = ($bo_receipt_taxid != "") ? $bo_receipt_taxid : '-';
        $bo_receipt_detail_text = ($bo_receipt_detail != "") ? $bo_receipt_detail : '-';
        $bo_company_text = ($bo_company != "") ? get_value('company', 'id', 'name', $bo_company, $mysqli_p) : '-';

        # --- Check history
        if ($page_title == "เพิ่มข้อมูล") {
            $description_field .= "สถานะ : " . $bo_status_text . "\n";
            $description_field .= "ประเภทลูกค้า : " . $bo_customertype_text . "\n";
            $description_field .= "Voucher No. : " . $bo_voucher_no . "\n";
            $description_field .= "วันที่จอง : " . date("d F Y", strtotime($bo_booking_date)) . "\n";
            $description_field .= "เอเย่นต์ : " . $bo_agent_text . "\n";
            $description_field .= "Voucher No. (เอเย่นต์) : " . $bo_agent_voucher_text . "\n";
            $description_field .= "ชื่อเซลล์ : " . $bo_sale_name . "\n";
            $description_field .= "ชื่อ (ลูกค้า) : " . $bo_customer_firstname_text . "\n";
            $description_field .= "นามสกุล (ลูกค้า) : " . $bo_customer_lastname_text . "\n";
            $description_field .= "เบอร์โทรศัพท์ (ลูกค้า) : " . $bo_customer_mobile_text . "\n";
            $description_field .= "อีเมล์ (ลูกค้า) : " . $bo_customer_email_text . "\n";
            $description_field .= "ใบเสร็จรับเงิน : " . $bo_full_receipt_text . "\n";
            $description_field .= "ชื่อ : " . $bo_receipt_name_text . "\n";
            $description_field .= "ที่อยู่ : " . $bo_receipt_address_text . "\n";
            $description_field .= "หมายเลขประจำตัวผู้เสียภาษี : " . $bo_receipt_taxid_text . "\n";
            $description_field .= "รายละเอียดใบเสร็จ : " . $bo_receipt_detail_text . "\n";
            $description_field .= "บริษัท : " . $bo_company_text . "\n";
        } else {
            $query_booking = "SELECT * FROM booking WHERE id > '0'";
            $query_booking .= " AND id = ?";
            $query_booking .= " LIMIT 1";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_booking);
            mysqli_stmt_bind_param($procedural_statement, 'i', $bo_id);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
            $row_booking = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $chang_status = ($bo_status != $row_booking["status"]) ? 'true' : 'false' ;

            $description_field .= ($bo_status != $row_booking["status"]) ? "สถานะ : " . $bo_status_text . "\n" : "";
            $description_field .= ($bo_voucher_no != $row_booking["voucher_no"]) ? "Voucher No. : " . $bo_voucher_no . "\n" : "";
            $description_field .= ($bo_agent != $row_booking["agent"]) ? "เอเย่นต์ : " . $bo_agent_text . "\n" : "";
            $description_field .= ($bo_agent_voucher != $row_booking["agent_voucher"]) ? "Voucher No. (เอเย่นต์) : " . $bo_agent_voucher_text . "\n" : "";
            $description_field .= ($bo_sale_name != $row_booking["sale_name"]) ? "ชื่อเซลล์ : " . $bo_sale_name . "\n" : "";
            $description_field .= ($bo_customer_firstname != $row_booking["customer_firstname"]) ? "ชื่อ (ลูกค้า) : " . $bo_customer_firstname_text . "\n" : "";
            $description_field .= ($bo_customer_lastname != $row_booking["customer_lastname"]) ? "นามสกุล (ลูกค้า) : " . $bo_customer_lastname_text . "\n" : "";
            $description_field .= ($bo_customer_mobile != $row_booking["customer_mobile"]) ? "เบอร์โทรศัพท์ (ลูกค้า) : " . $bo_customer_mobile_text . "\n" : "";
            $description_field .= ($bo_customer_email != $row_booking["customer_email"]) ? "อีเมล์ (ลูกค้า) : " . $bo_customer_email_text . "\n" : "";
            $description_field .= ($bo_full_receipt != $row_booking["full_receipt"]) ? "ใบเสร็จรับเงิน : " . $bo_full_receipt_text . "\n" : "";
            $description_field .= ($bo_receipt_name != $row_booking["receipt_name"]) ? "ชื่อ : " . $bo_receipt_name_text . "\n" : "";
            $description_field .= ($bo_receipt_address != $row_booking["receipt_address"]) ? "ที่อยู่ : " . $bo_receipt_address_text . "\n" : "";
            $description_field .= ($bo_receipt_taxid != $row_booking["receipt_taxid"]) ? "หมายเลขประจำตัวผู้เสียภาษี : " . $bo_receipt_taxid_text . "\n" : "";
            $description_field .= ($bo_receipt_detail != $row_booking["receipt_detail"]) ? "รายละเอียดใบเสร็จ : " . $bo_receipt_detail_text . "\n" : "";
            $description_field .= ($bo_company != $row_booking["company"]) ? "บริษัท : " . $bo_company_text . "\n" : "";
        }

        # ---- Update to database ---- #
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking SET";

        $query .= " status = ?,";
        $bind_types .= "i";
        array_push($params, $bo_status);

        $query .= " voucher_no = ?,";
        $bind_types .= "s";
        array_push($params, $bo_voucher_no);

        $query .= " company = ?,";
        $bind_types .= "i";
        array_push($params, $bo_company);

        $query .= " agent = ?,";
        $bind_types .= "i";
        array_push($params, $bo_agent);

        $query .= " agent_voucher = ?,";
        $bind_types .= "s";
        array_push($params, $bo_agent_voucher);

        $query .= " sale_name = ?,";
        $bind_types .= "s";
        array_push($params, $bo_sale_name);

        $query .= " customer_firstname = ?,";
        $bind_types .= "s";
        array_push($params, $bo_customer_firstname);

        $query .= " customer_lastname = ?,";
        $bind_types .= "s";
        array_push($params, $bo_customer_lastname);

        $query .= " customer_mobile = ?,";
        $bind_types .= "s";
        array_push($params, $bo_customer_mobile);

        $query .= " customer_email = ?,";
        $bind_types .= "s";
        array_push($params, $bo_customer_email);

        $query .= " full_receipt = ?,";
        $bind_types .= "i";
        array_push($params, $bo_full_receipt);

        $query .= " receipt_name = ?,";
        $bind_types .= "s";
        array_push($params, $bo_receipt_name);

        $query .= " receipt_address = ?,";
        $bind_types .= "s";
        array_push($params, $bo_receipt_address);

        $query .= " receipt_taxid = ?,";
        $bind_types .= "s";
        array_push($params, $bo_receipt_taxid);

        $query .= " receipt_detail = ?,";
        $bind_types .= "s";
        array_push($params, $bo_receipt_detail);

        $query .= " status_email = ?,";
        $bind_types .= "s";
        array_push($params, $bo_status_email);

        $query .= " status_email_revise = ?,";
        $bind_types .= "s";
        array_push($params, $bo_status_email_revise);

        $query .= " status_confirm = ?,";
        $bind_types .= "s";
        array_push($params, $bo_status_confirm);

        if ($page_title == "เพิ่มข้อมูล") {
            $query .= " booking_date = ?,";
            $bind_types .= "s";
            array_push($params, $bo_booking_date);

            $query .= " booking_time = ?,";
            $bind_types .= "s";
            array_push($params, $bo_booking_time);

            $query .= " create_date = now(),";
        }

        $query .= " last_edit_time = now()";
        $query .= " WHERE id = '$bo_id'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        # --- Cancle Booking --- #
        if(!empty($bo_status) && !empty($chang_status) && $chang_status == 'true'){
            $bind_types = "";
            $params = array();
            $query_cancel = "UPDATE booking_products SET";

            if($bo_status == '2'){
                $query_cancel .= " trash_deleted = ?,";
                $bind_types .= "i";
                array_push($params, '1');

                $query_cancel .= " status_email = ?,";
                $bind_types .= "i";
                array_push($params, '2');

                $query_cancel .= " status_confirm = ?,";
                $bind_types .= "i";
                array_push($params, '2');

                $query_cancel .= " status_confirm_by = ?,";
                $bind_types .= "s";
                array_push($params, '');

                $query_cancel .= " status_confirm_op = ?";
                $bind_types .= "i";
                array_push($params, '2');
            }else{
                $query_cancel .= " trash_deleted = ? ";
                $bind_types .= "i";
                array_push($params, '0');
            }

            $query_cancel .= " WHERE booking = '$bo_id'";

            $procedural_statement = mysqli_prepare($mysqli_p, $query_cancel);
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
        }

        # --- booking_history
        if (!empty($bo_id) && $description_field != "") {
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
            array_push($params, $bo_id);

            if ($page_title == "เพิ่มข้อมูล") {
                $query_history .= " history_type = ?,";
                $bind_types .= "i";
                array_push($params, '1');
            } else {
                $query_history .= " history_type = ?,";
                $bind_types .= "i";
                array_push($params, '2');
            }

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

        $return_url = "&id=" . $bo_id;
        $message_alert = "success";
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/detail" . $return_url . "&message=" . $message_alert . "'\" >";
    }
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/detail" . $return_url . "&message=" . $message_alert . "'\" >";
}
