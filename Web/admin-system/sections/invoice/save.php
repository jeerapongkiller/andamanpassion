<?php
require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$bo_bi_agent = !empty($_POST["bo_bi_agent"]) ? $_POST["bo_bi_agent"] : '';
$bo_bi_name = !empty($_POST["bo_bi_name"]) ? $_POST["bo_bi_name"] : '';
$bo_bi_address = !empty($_POST["bo_bi_address"]) ? $_POST["bo_bi_address"] : '';
$bo_bi_date = !empty($_POST["bo_bi_date"]) ? $_POST["bo_bi_date"] : $today;
$bo_due_date = !empty($_POST["bo_due_date"]) ? $_POST["bo_due_date"] : $today;
$bo_bi_condition = !empty($_POST["bo_bi_condition"]) ? $_POST["bo_bi_condition"] : '';
$bo_bi_con_detail = !empty($_POST["bo_bi_con_detail"]) ? $_POST["bo_bi_con_detail"] : '';
$bo_bi_detail = !empty($_POST["bo_bi_detail"]) ? $_POST["bo_bi_detail"] : '';

#----- JS SET 0 IN VALUE -----#
function setNumberLength($num, $length)
{
    $sumstr = strlen($num);
    $zero = str_repeat("0", $length - $sumstr);
    $results = $zero . $num;

    return $results;
}
function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".", "");
    $pt = strpos($amount_number, ".");
    $number = $fraction = "";
    if ($pt === false)
        $number = $amount_number;
    else {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    $ret = "";
    $baht = ReadNumber($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000) {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    $divider = 100000;
    $pos = 0;
    while ($number > 0) {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : ((($divider == 10) && ($d == 1)) ? "" : ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}

#--- GET VALUE ---#
$bi_title = "BI";
$today_str = explode("-", $today);
$bi_year = $today_str[0];
$bi_year_th_full = $today_str[0] + 543;
$bi_year_th = substr($bi_year_th_full, -2);
$bi_month = $today_str[1];
$bi_no = 0;

#--- CHECK TB : bill ---#
$query_bi = "SELECT * FROM bill WHERE id > '0' ";
$query_bi .= " ORDER BY bi_date DESC, bi_no DESC LIMIT 0,1";
$procedural_statement = mysqli_prepare($mysqli_p, $query_bi);
mysqli_stmt_execute($procedural_statement);
$result_bi = mysqli_stmt_get_result($procedural_statement);
$num_bi = mysqli_num_rows($result_bi);
if ($num_bi > 0) {
    $row_bi = mysqli_fetch_array($result_bi, MYSQLI_ASSOC);
    $bi_month_sql = setNumberLength($row_bi["bi_month"], 2);
    if ($bi_month_sql == $bi_month) {
        if ($row_bi["bi_year"] != $bi_year) {
            $bi_no = 1;
        } else {
            $bi_no = $row_bi["bi_no"] + 1;
        }
    } else {
        $bi_no = 1;
    }
} else {
    $bi_no = 1;
}
$bi_full = $bi_title . $bi_year_th . $bi_month . setNumberLength($bi_no, 4);

if (!empty($_POST["invoice_id"]) && !empty($_POST["search_company_val"])) {
    if (!empty($bo_bi_name)) {
        # ---- Insert to database ---- #
        $query = "INSERT INTO bill (bi_date, due_date, bi_year, bi_year_thai, bi_month, bi_no, bi_full, bi_name, bi_address, bi_condition, bi_con_detail, bi_by, agent, create_date)";
        $query .= "VALUES ('', '', 0, 0, 0, 0, '', '', '', '', '', 0, 0, now())";
        $result = mysqli_query($mysqli_p, $query);
        $bill_id = mysqli_insert_id($mysqli_p);
    }

    if (!empty($bill_id)) {
        # ---- Update to database ---- #
        $bind_types = "";
        $params = array();

        $query = "UPDATE bill SET";

        $query .= " bi_date = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_date);

        $query .= " due_date = ?,";
        $bind_types .= "s";
        array_push($params, $bo_due_date);

        $query .= " bi_year = ?,";
        $bind_types .= "i";
        array_push($params, $bi_year);

        $query .= " bi_year_thai = ?,";
        $bind_types .= "i";
        array_push($params, $bi_year_th);

        $query .= " bi_month = ?,";
        $bind_types .= "i";
        array_push($params, $bi_month);

        $query .= " bi_no = ?,";
        $bind_types .= "i";
        array_push($params, $bi_no);

        $query .= " bi_full = ?,";
        $bind_types .= "s";
        array_push($params, $bi_full);

        $query .= " bi_name = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_name);

        $query .= " bi_address = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_address);

        $query .= " bi_detail = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_detail);

        $query .= " bi_condition = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_condition);

        $query .= " bi_con_detail = ?,";
        $bind_types .= "s";
        array_push($params, $bo_bi_con_detail);

        $query .= " bi_by = ?,";
        $bind_types .= "i";
        array_push($params, $_SESSION["admin"]["id"]);

        $query .= " agent = ?,";
        $bind_types .= "i";
        array_push($params, $bo_bi_agent);

        $query .= " create_date = now()";
        $query .= " WHERE id = '$bill_id'";

        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        #---- Insert Table bill_invoice ------#
        foreach ($_POST["invoice_id"] as $id_invo) {
            $pay_bill = !empty($_POST["bill_paid" . $id_invo]) ? preg_replace('(,)', '', $_POST["bill_paid" . $id_invo]) : "";
            // $pay_bill = $_POST["bill_paid" . $id_invo];
            $bo_id = get_value("invoice_products", "invoice", "booking", $id_invo, $mysqli_p);
            $bopro_id = get_value("invoice_products", "invoice", "booking_products", $id_invo, $mysqli_p);

            if (!empty($id_invo)) {
                # ---- Insert to database ---- #
                $query = "INSERT INTO bill_invoice (bill, invoice, booking, booking_products, pay_bill, create_date)";
                $query .= "VALUES (0, 0, 0, 0, 0, now())";
                $result = mysqli_query($mysqli_p, $query);
                $bi_inv_id = mysqli_insert_id($mysqli_p);
            }
            if (!empty($bi_inv_id)) {
                # ---- Update to database ---- #
                $bind_types = "";
                $params = array();

                $query = "UPDATE bill_invoice SET";

                $query .= " bill = ?,";
                $bind_types .= "i";
                array_push($params, $bill_id);

                $query .= " invoice = ?,";
                $bind_types .= "i";
                array_push($params, $id_invo);

                $query .= " booking = ?,";
                $bind_types .= "i";
                array_push($params, $bo_id);

                $query .= " booking_products = ?,";
                $bind_types .= "i";
                array_push($params, $bopro_id);

                $query .= " pay_bill = ?,";
                $bind_types .= "d";
                array_push($params, $pay_bill);

                $query .= " create_date = now()";
                $query .= " WHERE id = '$bi_inv_id'";

                $procedural_statement = mysqli_prepare($mysqli_p, $query);
                if ($bind_types != "") {
                    mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
                }
                mysqli_stmt_execute($procedural_statement);
                $result = mysqli_stmt_get_result($procedural_statement);

                if (!empty($id_invo)) {
                    #--- Update Total Price Invoice TB : invoice_products ---#
                    $query_invoice = "UPDATE invoice SET bill = '1' WHERE id = '" . $id_invo . "' ";
                    $result_invoice = mysqli_query($mysqli_p, $query_invoice);
                }
            }
        }
        $company_name = get_value('company', 'id', 'name', $_POST["search_company_val"], $mysqli_p);
        $company_add = get_value('company', 'id', 'address', $_POST["search_company_val"], $mysqli_p);
        $company_photo = get_value('company', 'id', 'photo1', $_POST["search_company_val"], $mysqli_p);

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
            'format' => 'A4',
            'margin_left'       => '15',
            'margin_right'      => '15',
            'margin_top'        => '82',
        ]);

        $message = "";

        $mpdf->SetHTMLHeader('
        <table width="700" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
        <td colspan="7" valign="top" align="center">
        <img src=" ' . $company_photo . ' " alt="Andaman Passion" title="Andaman Passion" width="120" border="0" />
        </br>
        </td>
        </tr>
        <tr>
        <td colspan="3" valign="top">
        <b>' . $company_name . '</b><br />
        ' . $company_add . '<br />
        </td>
        <td colspan="4" ><br /><br /><br /><br />
        <span style="font-size:18px;"><b>ใบวางบิล </b></span> ต้นฉบับ (สำหรับลูกค้า)
        </td>
        </tr>
        <tr>
        <td colspan="3" style="border:1px solid #cdcdcd; margin-top:10px; padding:10px 15px;">
        <span style="line-height: 12px;"><b>ลูกค้า</b><br />
        <span style="font-size:10px; line-height:12px;">Customer</span><br /></span>
        <b> ' . $bo_bi_name . ' </b><br />
        <b> ' . $bo_bi_address . ' </b><br />
        <b> หมายเหตุ </b> ' . $bo_bi_detail . ' <br />
        </td>
        <td colspan="4" style="border:1px solid #cdcdcd; margin-top:10px; padding:10px 15px;">
        <b> เลขที่ใบวางบิล </b> ' . $bi_full . ' <br /><br />
        <b> วันที่ </b> ' . date("d/m/Y", strtotime($bo_bi_date)) . ' <br /><br />
        <b> เงื่อนใขการชำระเงิน </b>' . $bo_bi_condition . '
        </td>
        </tr>
        <tr>
        <td width="80" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> No. </div>
        </td>
        <td width="150" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> เลขที่ใบสั่งแจ้งหนี้ </div>
        </td>
        <td width="115" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> วันที่จอง </div>
        </td>
        <td width="115" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> วันที่เดินทาง </div>
        </td>
        <td width="80" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> จำนวนเงิน </div>
        </td>
        <td width="80" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> ชำระแล้ว </div>
        </td>
        <td width="80" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        <div> เงินคงค้าง </div>
        </td>
        </tr>
        </table>
        ');

        $message .= "<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">";
        $i = '0';
        $total_invoice = '0';
        foreach ($_POST["invoice_id"] as $id_invo) {
            $query_invoice = "SELECT invoice.*, invoice_products.id as inv_id, invoice_products.total_invoice as inv_ttin,  booking.id as b_id, booking.booking_date as b_bd, booking_products.id as b_pid,
                booking_products.products_type as b_pty, booking_products.travel_date as b_td, booking_products.checkin_date as b_ci
                FROM invoice
                LEFT JOIN invoice_products
                    ON invoice.id = invoice_products.invoice
                LEFT JOIN booking
                    ON invoice_products.booking = booking.id
                LEFT JOIN booking_products
                    ON invoice_products.booking_products = booking_products.id
                WHERE invoice.id = '" . $id_invo . "'
                ";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_invoice);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
            while ($row_invoice = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $travel_date = $row_invoice['b_pty'] != '4' ? $row_invoice['b_td'] : $row_invoice['b_ci'];
                $total_invoice = $row_invoice['inv_ttin'] + $total_invoice;
                $i++;
                $message .= "<tr>";
                $message .= "<td width='80' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \"> $i </td>";
                $message .= "<td width='150' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \">" . $row_invoice['inv_full'] . "</td>";
                $message .= "<td width='115' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \">" . date("d/m/Y", strtotime($row_invoice['b_bd'])) . "</td>";
                $message .= "<td width='115' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \">" . date("d/m/Y", strtotime($travel_date)) . "</td>";
                $message .= "<td width='80' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \">" . number_format($row_invoice['inv_ttin'], 2) . "</td>";
                $message .= "<td width='80' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \"> </td>";
                $message .= "<td width='80' style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; \">" . number_format($row_invoice['inv_ttin'], 2) . "</td>";
                $message .= "</tr>";
            }
        }

        $message .= "<tr>";
        $message .= "<td colspan=\"6\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> <table width=\"100%\"> <tr> <td> ( " . Convert($total_invoice) . " ) </td> <td style=\"text-align: right;\"> รวมเงินทั้งสิ้น </td> </tr> </table> </td>";
        $message .= "<td colspan=\"1\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">" . number_format($total_invoice, 2) . "</td>";
        $message .= "</tr>";

        $message .= "<tr>";
        $message .= "<td colspan=\"7\" valign=\"top\" height=\"100\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $bo_bi_con_detail . " </td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan=\"7\" valign=\"bottom\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-top-style: hidden;\"> **กรุณาส่งใบนำฝากมาที่ Email : ac@andamanpassion.com ทุกครั้งหลังการโอนเงิน** </td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan=\"3\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> ชื่อผู้รับวางบิล ..................................</td>";
        $message .= "<td colspan=\"4\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> ในนาม บริษัท อันดามัน แพชชั่น จำกัด </td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan=\"3\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> วันที่รับ ............/............./.............</td>";
        $message .= "<td colspan=\"4\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> &nbsp; </td>";
        $message .= "</tr>";
        $message .= "<tr>";
        $message .= "<td colspan=\"3\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> วันที่นัดรับเช็ค ............/............./.............</td>";
        $message .= "<td colspan=\"4\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> ชื่อผู้วางบิล ..........................................</td>";
        $message .= "</tr>";

        $message .= "</table>";

        echo "</br>" . $message;

        $mpdf->WriteHTML($message);
        $mpdf->Output("../assets/bill_pdf/" . $bi_full . ".pdf");
        ob_end_flush();
        $message = "";

        $mpdf = new \Mpdf\Mpdf();

        $Mfiles = array();

        //Multiple pdf store in Mfiles array with full path.               
        array_push($Mfiles, "../assets/bill_pdf/" . $bi_full . ".pdf");
        foreach ($_POST["invoice_id"] as $id_invo) {
            $inv_full = get_value('invoice', 'id', 'inv_full', $id_invo, $mysqli_p);
            array_push($Mfiles, "../assets/invoice_pdf/" . $inv_full . ".pdf");
        }

        // Give file path with have you need to merge all pdf             
        $Mpath = "../assets/invoice_bill/" . $bi_full . ".pdf";

        // after loop we start this code
        if ($Mfiles) {
            $filesTotal = sizeof($Mfiles);
            $fileNumber = 1;

            if (!file_exists($Mpath)) {
                $handle = fopen($Mpath, 'w');
                fclose($handle);
            }
            foreach ($Mfiles as $fileName) {
                if (file_exists($fileName)) {
                    $pagesInFile = $mpdf->SetSourceFile($fileName);
                    for ($i = 1; $i <= $pagesInFile; $i++) {
                        $tplId = $mpdf->importPage($i);
                        $mpdf->UseTemplate($tplId);
                        if (($fileNumber < $filesTotal) || ($i != $pagesInFile)) {
                            $mpdf->WriteHTML('<pagebreak />');
                        }
                    }
                }
                $fileNumber++;
            }
            $mpdf->Output($Mpath);
        }
?>
        <form action="./?mode=invoice/list&message=success" method="POST" name="form_save_bill">
            <input type="hidden" name="search_voucher_no" id="search_voucher_no" value="<?php echo $_POST['search_voucher_no']; ?>">
            <input type="hidden" name="search_invoice_no" id="search_invoice_no" value="<?php echo $_POST['search_invoice_no']; ?>">
            <input type="hidden" name="search_travel_date_from" id="search_travel_date_from" value="<?php echo $_POST['search_travel_date_from']; ?>">
            <input type="hidden" name="search_travel_date_to" id="search_travel_date_to" value="<?php echo $_POST['search_travel_date_to']; ?>">
            <input type="hidden" name="search_customer_type" id="search_customer_type" value="<?php echo $_POST['search_customer_type']; ?>">
            <input type="hidden" name="search_name_val" id="search_name_val" value="<?php echo $_POST['search_name_val']; ?>">
            <input type="hidden" name="search_agent_val" id="search_agent_val" value="<?php echo $_POST['search_agent_val']; ?>">
            <input type="hidden" name="search_company_val" id="search_company_val" value="<?php echo $_POST['search_company_val']; ?>">
            <button type="submit" value="submit">save</button>
        </form>
        <script>
            window.onload = function() {
                document.forms['form_save_bill'].submit();
            }
        </script>
<?php
    }
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=invoice/list" . $return_url . "&message=" . $message_alert . "'\" >";
}


#---- Ceate New Table ------#
// โอนเข้าบัญชีชื่อ บริษัท อันดามัน แพชชั่น จำกัด สาขา เซ็นทรัน เฟสติวัล ภูเก็ต ธนาคารกสิกรไทยเลขที่ 482 2 45388 0
?>