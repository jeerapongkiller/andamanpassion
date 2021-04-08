<?php
require("../../../inc/connection.php");

require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!empty($_POST["bo_id"])) {
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
    foreach ($_POST["id_email"] as $val_email) {
        #------- PDF Page -----------#
        ob_start();
        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                __DIR__ . '/../../../assets/tmp/ttfontdata',
            ]),
            'fontdata' => $fontData + [
                'prompt' => [
                    'R' => 'Prompt-Regular.ttf',
                    'I' => 'Prompt-Italic.ttf',
                    'B' => 'Prompt-Bold.ttf',
                ]
            ],
            'default_font' => 'prompt',
            'format' => 'A4'
        ]);

        $bo_id = $_POST["bo_id"];
        $name_by = get_value('employee', 'id', 'name', $_SESSION["admin"]["id"], $mysqli_p);
        $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname , products_category_first.supplier as pcfsupplier, products_type.id as ptypeid,
                            products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                            booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.agent as bagent, booking.voucher_no as bvoucher_no,
                            booking.sale_name as bsale_name, booking.customer_firstname as bcustomer_firstname, booking.customer_lastname as bcustomer_lastname, booking.customer_mobile as bcustomer_mobile,
                            booking.customer_email as bcustomer_email, booking.agent_voucher as bagent_voucher, booking.company as bcompany
                            FROM booking_products
                            LEFT JOIN products_category_first
                                ON booking_products.products_category_first = products_category_first.id
                            LEFT JOIN products_category_second
                                ON booking_products.products_category_second = products_category_second.id
                            LEFT JOIN products_type
                                ON booking_products.products_type = products_type.id
                            LEFT JOIN booking_status_email
                                ON booking_products.status_email = booking_status_email.id
                            LEFT JOIN booking_status_confirm
                                ON booking_products.status_confirm = booking_status_confirm.id
                            LEFT JOIN booking
                                ON booking_products.booking = booking.id
                            WHERE booking_products.id = '$val_email' AND booking_products.trash_deleted = '0'
                            ";
        $query_products .= " ORDER BY booking_products.products_type ASC, booking_products.products_category_first ASC";
        $query_products .= " , booking_products.travel_date ASC";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $row_products = mysqli_fetch_array($result, MYSQLI_ASSOC);

        #---- Set for Mail Html ----#
        $companyname = "Andaman Passion";
        // $email_passion = "monzero753@gmail.com";
        $email_passion = "rsvn@andamanpassion.com";
        $email_supplier = get_value('supplier', 'id', 'sup_email', $row_products['pcfsupplier'], $mysqli_p);
        // $email_supplier = "morn@phuketsolution.com";
        $sup_company = get_value('supplier', 'id', 'company', $row_products['pcfsupplier'], $mysqli_p);
        $title_email = $row_products["status_email"] == '2' ? "Booking details V. " . $row_products["bvoucher_no"] : "Revised booking details V.  " . $row_products["bvoucher_no"];
        $agent = $row_products["bagent"];
        $bagent_voucher = !empty($row_products["bagent_voucher"]) ? $row_products["bagent_voucher"] : '-';
        $bvoucher_no = !empty($row_products["bvoucher_no"]) ? $row_products["bvoucher_no"] : '-';
        $bcustomer_firstname = !empty($row_products["bcustomer_firstname"]) ? $row_products["bcustomer_firstname"] : '-';
        $bcustomer_lastname = !empty($row_products["bcustomer_lastname"]) ? $row_products["bcustomer_lastname"] : '-';
        $bcustomer_mobile = !empty($row_products["bcustomer_mobile"]) ? $row_products["bcustomer_mobile"] : '-';
        $bcustomer_email = !empty($row_products["bcustomer_email"]) ? $row_products["bcustomer_email"] : '-';
        $dropoff = !empty($row_products["dropoff"]) ? get_value('place', 'id', 'name', $row_products["dropoff"], $mysqli_p) : 'N/A';
        $pickup = !empty($row_products["pickup"]) ? get_value('place', 'id', 'name', $row_products["pickup"], $mysqli_p) : 'N/A';
        $company_name = !empty($row_products["bcompany"]) ? get_value('company', 'id', 'name', $row_products["bcompany"], $mysqli_p) : 'N/A';
        $company_add = !empty($row_products["bcompany"]) ? get_value('company', 'id', 'address', $row_products["bcompany"], $mysqli_p) : 'N/A';
        $company_photo = !empty($row_products["bcompany"]) ? get_value('company', 'id', 'photo1', $row_products["bcompany"], $mysqli_p) : 'N/A';
        $colspan = $row_products["ptypeid"] == '4' ? '6' : '8';

        #---- SEND EMAIL : FROM SYSTEM TO CUSTOMER ----#
        $head  = "MIME-Version: 1.0\r\n";
        $head .= "Content-type: text/html; charset=utf-8\r\n";
        $head .= "From: " . $email_passion . "\n";
        $head .= "To: " . $email_supplier . "\n";
        $head .= "X-Priority: 1 (High)\n";
        $head .= "X-Mailer: <phuketsolution.com>\n";
        $head .= "MIME-Version: 1.0\n";

        #--- Header ---#
        $message = "";
        $message .= "
    <html>
    <body>
    <style>
        table{
            font-size:14px;
        }
    </style>
    ";
        $message .= "<table width='700' border='0' cellspacing='0' cellpadding='1'>";
        $message .= "<tr>";
        $message .= "<td valign='top'>";
        $message .= "<img src='" . $company_photo . "' alt='Andaman Passion' title='Andaman Passion' width='150' border='0' />";
        $message .= "</td>";
        $message .= "<td valign='top' align='right'>";
        $message .= "<b>" . $company_name . "</b><br />";
        $message .= $company_add . "<br />";
        $message .= "</td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2'><hr></td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2'><span style='font-size: 1.2em'>รายละเอียดลูกค้า</span></td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2' align='left' style='border:1px solid #cdcdcd; padding:10px 15px;'>";
        if ($agent > 0) {
            $message .= "<b>เอเย่นต์ :</b> " . get_value('agent', 'id', 'company', $agent, $mysqli_p) . " <br />";
            if ($bagent_voucher != '') {
                $message .= "<b>Voucher No. (เอเย่นต์) :</b> " . $bagent_voucher . " <br />";
            }
        }
        $message .= "<b>Voucher No. :</b> " . $bvoucher_no . " <br />";
        $message .= "<b>ชื่อ :</b> " . $bcustomer_firstname . " <br />";
        $message .= "<b>นามสกุล :</b> " . $bcustomer_lastname . " <br />";
        $message .= "<b>เบอร์โทร :</b> " . $bcustomer_mobile . " <br />";
        $message .= "<b>Email :</b> " . $bcustomer_email . " <br />";
        $message .= "</td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2'><hr></td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2'><span style='font-size: 1.2em'>รายละเอียดการจอง</span></td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan='2' align='left'><h5><b>" . $row_products["pcfname"] . "</b></h5></td>";
        $message .= "</tr>";
        #----- Start Table ------#
        $message .= "<tr>";
        $message .= "<td colspan='2'>";
        $message .= "<table width='100%' border='0' cellspacing='0' cellpadding='1'>";
        #----- Start Thead of Table ------#
        $message .= "<tr>";
        $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> สินค้า </th>";
        if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> วันที่เที่ยว </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> ผู้ใหญ่ </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> เด็ก </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> ทารก </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> FOC </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> สถานที่รับ </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> สถานที่ส่ง </th>";
        }
        if ($row_products["ptypeid"] == 3) {
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> วันที่เที่ยว </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> จำนวนคัน </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> จำนวนชั่วโมง </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> สถานที่รับ </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> เวลารับ </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> สถานที่ส่ง </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> เวลาส่ง </th>";
        }
        if ($row_products["ptypeid"] == 4) {
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> วันที่เช็คอิน </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> วันที่เช็คเอาท์ </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> จำนวนห้อง </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> จำนวนเตียงเสริม </th>";
            $message .= "<th align='center' style='font-weight: bold; border:1px solid #cdcdcd; padding:5px 5px;'> จำนวนแชร์เตียง </th>";
        }
        $message .= "</tr>";
        #------- End Thead of Table ------#
        #------- Start Tbody of Table ------#
        $message .= "<tr>";
        $message .= "<td align='left' style='text-align: left; border:1px solid #cdcdcd; font-size:14px; padding:5px 5px;'>" . $row_products["pcsname"] . "</td>";
        if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["travel_date"])) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["adults"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["children"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["infant"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["foc"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . $pickup . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . $dropoff . "</td>";
        }
        if ($row_products["ptypeid"] == 3) {
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["travel_date"])) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["no_cars"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["no_hours"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . $pickup . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . $row_products["pickup_time"] . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . $dropoff . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . date('H:i', strtotime($row_products["dropoff_time"])) . "</td>";
        }
        if ($row_products["ptypeid"] == 4) {
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["checkin_date"])) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["checkout_date"])) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["no_rooms"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["extra_beds"]) . "</td>";
            $message .= "<td align='center' style='border:1px solid #cdcdcd; padding:5px 5px;'>" . number_format($row_products["share_bed"]) . "</td>";
        }
        $message .= "</tr>";
        #------- End Tbody of Table ------#
        #------- Start Detail of Table ------#
        $message .= "<tr>";
        $message .= "<th colspan='" . $colspan . "' align='left' style='border:1px solid #cdcdcd; padding:5px 5px;'><b>รายละเอียด : </b>" . nl2br($row_products["notes"]) . "<br /></th>";
        $message .= "</tr>";
        #------- End Detail of Table ------#
        $message .= "</table>";
        $message .= "</td>";
        $message .= "</tr>";
        #------- End Table ------#
        $message .= "<tr>";
        $message .= "<td colspan='2'><hr></td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<th align='left' colspan='2' style='border:1px solid #cdcdcd; padding:10px 15px;'>";
        $message .= "<b>Remark : </b>" . $_POST['remark_email'] . "<br /><br />";
        $message .= "<b>ส่งโดย : </b>" . $name_by . "";
        $message .= "</th>";
        $message .= "</tr>";
        $message .= "</table>";
        $message .= "
    </body>
    </html>
    ";

        $com_date = strtotime("now");
        $com_full = "confirm-" . $com_date . $val_email . "";
        $com_file = "../../../assets/confirm_pdf/" . $com_full . ".pdf";
        $com_name = $com_full.".pdf";

        $mpdf->WriteHTML($message);
        $mpdf->Output("../../../assets/confirm_pdf/" . $com_full . ".pdf");
        ob_end_flush();

        // echo $message . "</br>";

        $Content = '
        <p style="font-size:1.5em; font-family: sans-serif;"><span style="font-weight: bold; font-size:1.5em;"> ' . $title_email . ' </span><br />
        <span style="font-weight: bold;"> ชื่อ : </span> ' . $bcustomer_firstname . ' <br />
        <span style="font-weight: bold;"> นามสกุล : </span> ' . $bcustomer_lastname . ' <br />
        <span style="font-weight: bold;"> เบอร์โทร : </span> ' . $bcustomer_mobile . ' <br />
        <span style="font-size:0.8em;"> Email : </span> ' . $bcustomer_email . ' <br />
        <span style="font-weight: bold;"> สินค้า : </span><span style="font-size:0.8em;"> ' . $row_products["pcsname"] . ' </span><br />
        <span style="color:#FF0000; font-weight: bold;"> รายละเอียดตามไฟล์ที่แนบมา </span>
        </p>
        ';
        $headers = "From: $companyname" . " <" . $email_passion . ">";
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $Content . "\n\n";
        $strFilesName = $com_file;
        $strName = $com_name;
        $message .= "--{$mime_boundary}\n";
        $fp =    @fopen($strFilesName, "rb");
        $strContent =  @fread($fp, filesize($strFilesName));
        @fclose($fp);
        $strContent = chunk_split(base64_encode(file_get_contents($strFilesName)));
        $message .= "Content-Type: application/octet-stream; name=\"" . $strFilesName . "\"\n" .
            "Content-Description: " . $strFilesName . "\n" .
            "Content-Disposition: attachment;\n" . " filename=\"" . $strName . "\"; size=" . filesize($strFilesName) . ";\n" .
            "Content-Transfer-Encoding: base64\n\n" . $strContent . "\n\n";
        
        # --- SEND EMAIL --- #
        if ((mail($email_supplier, $title_email, $message, $headers, "-f$email_passion")) == false) {
            echo '<script>alert("Contact to Phuket Solution Co.,Ltd.")</script>';
            exit;
        }

        #--- Update booking products status email ---#
        if ($row_products["status_email"] == "2") {
            $status_email = "1";
            $history_type = "8";
            $description_field = "สถานะการส่งอีเมล์ : ส่งแล้ว </br>";
        } elseif ($row_products["status_email"] == "1" || $row_products["status_email"] == "3") {
            $status_email = "3";
            $history_type = "10";
            $description_field = "สถานะการส่งอีเมล์ : ส่งใหม่ </br>";

            $status_email_revise = "3";
            $query_revise = "UPDATE booking SET status_email_revise = ? WHERE id = ?";
            $procedural_statement_revise = mysqli_prepare($mysqli_p, $query_revise);
            mysqli_stmt_bind_param($procedural_statement_revise, 'ii', $status_email_revise, $bo_id);
            mysqli_stmt_execute($procedural_statement_revise);
            $result_revise = mysqli_stmt_get_result($procedural_statement_revise);
        }

        $sql = "UPDATE booking_products SET status_email = ? WHERE id = ?";
        $procedural_statement_up = mysqli_prepare($mysqli_p, $sql);
        mysqli_stmt_bind_param($procedural_statement_up, 'ii', $status_email, $row_products["id"]);
        mysqli_stmt_execute($procedural_statement_up);
        $result_up = mysqli_stmt_get_result($procedural_statement_up);

        # --- booking_history ---#
        if (!empty($row_products["id"])) {
            $description_field .= "ส่งอีเมล์โดย : " . $name_by . "</br>";
            $description_field .= "Remark : " . $_POST['remark_email'];

            # ---- Insert to booking_history ---- #
            $query_history = "INSERT INTO booking_history (booking, history_type, booking_products, description_field, employee, ip_address, create_date)";
            $query_history .= "VALUES (0, 0, 0, '', 0, '', now())";
            $result_history = mysqli_query($mysqli_p, $query_history);
            $history_id = mysqli_insert_id($mysqli_p);

            $bind_types = "";
            $params = array();

            $query_history = "UPDATE booking_history SET";

            $query_history .= " booking = ?,";
            $bind_types .= "i";
            array_push($params, $row_products["bid"]);

            $query_history .= " history_type = ?,";
            $bind_types .= "i";
            array_push($params, $history_type);

            $query_history .= " booking_products = ?,";
            $bind_types .= "i";
            array_push($params, $row_products["id"]);

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
            $procedural_statement_history = mysqli_prepare($mysqli_p, $query_history);
            if ($bind_types != "") {
                mysqli_stmt_bind_param($procedural_statement_history, $bind_types, ...$params);
            }
            mysqli_stmt_execute($procedural_statement_history);
            $result_history_up = mysqli_stmt_get_result($procedural_statement_history);
        }

        # --- booking confirm email ---#
        $query_confirm = "SELECT booking , status_email FROM booking_products WHERE id > 0 
            AND booking = ? AND trash_deleted = 0 AND status_email = 2 ";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_confirm);
        mysqli_stmt_bind_param($procedural_statement, 'i', $bo_id);
        mysqli_stmt_execute($procedural_statement);
        $result_confirm = mysqli_stmt_get_result($procedural_statement);
        $numrowconfirm = mysqli_num_rows($result_confirm);
        if ($numrowconfirm == 0) {
            $num_confirm = '1';
        } else {
            $num_confirm = '2';
        }
        $query_book = "UPDATE booking SET status_email = ? WHERE id = ?";
        $procedural_statement_book = mysqli_prepare($mysqli_p, $query_book);
        mysqli_stmt_bind_param($procedural_statement_book, 'ii', $num_confirm, $bo_id);
        mysqli_stmt_execute($procedural_statement_book);
        $result_book = mysqli_stmt_get_result($procedural_statement_book);
    }

    mysqli_close($mysqli_p);
}
