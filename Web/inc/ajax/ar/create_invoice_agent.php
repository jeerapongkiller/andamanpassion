<?php
require("../../../inc/connection.php");
require_once __DIR__ . '/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!empty($_POST["status"])) {
    #----- JS SET 0 IN VALUE -----#
    function setNumberLength($num, $length)
    {
        $sumstr = strlen($num);
        $zero = str_repeat("0", $length - $sumstr);
        $results = $zero . $num;

        return $results;
    }
    #----- JS Chang Price To Text ------#
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

    #--- CHECK TB : booking_products ---#
    $query = "SELECT booking_products.*, booking.agent as bagent 
                    FROM booking_products 
                    LEFT JOIN booking
                        ON booking_products.booking = booking.id
                    WHERE booking_products.status_confirm_op = '1' AND booking.agent != '0' AND booking_products.invoice = '0' ";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        #---- Check Tranfers Of Tours And Activity -----#
        $tranfer = $row["transfer"] == "1" ? "2" : "1";
        for ($no_tran = "0"; $no_tran < $tranfer; $no_tran++) {
            $date_today = "";
            if ($row["products_type"] == '4') {
                $date_today = $row["checkout_date"] < $today ? "ture" : "false";
            } else {
                $date_today = $row["travel_date"] < $today ? "ture" : "false";
            }
            if ($date_today == "ture") {
                #--- GET VALUE ---#
                $payments_vat = get_value("agent", "id", "payments_vat", $row["bagent"], $mysqli_p);
                $inv_title = $payments_vat == 1 ? "IV" : "ST";
                $today_str = explode("-", $today);
                $inv_year = $today_str[0];
                $inv_year_th_full = $today_str[0] + 543;
                $inv_year_th = substr($inv_year_th_full, -2);
                $inv_month = $today_str[1];
                $inv_no = 0;

                #--- CHECK TB : invoice ---#
                $query_inv = "SELECT * FROM invoice ";
                $query_inv .= $payments_vat == 1 ? "WHERE payments_vat = 1" : "WHERE payments_vat != 1";
                $query_inv .= " ORDER BY inv_date DESC, inv_no DESC LIMIT 0,1";
                $procedural_statement_inv = mysqli_prepare($mysqli_p, $query_inv);
                mysqli_stmt_execute($procedural_statement_inv);
                $result_inv = mysqli_stmt_get_result($procedural_statement_inv);
                $num_inv = mysqli_num_rows($result_inv);
                if ($num_inv > 0) {
                    $row_inv = mysqli_fetch_array($result_inv, MYSQLI_ASSOC);
                    $inv_month_sql = setNumberLength($row_inv["inv_month"], 2);
                    if ($inv_month_sql == $inv_month) {
                        if ($row_inv["inv_year"] != $inv_year) {
                            $inv_no = 1;
                        } else {
                            $inv_no = $row_inv["inv_no"] + 1;
                        }
                    } else {
                        $inv_no = 1;
                    }
                } else {
                    $inv_no = 1;
                }
                $inv_full = $inv_title . $inv_year_th . $inv_month . setNumberLength($inv_no, 4);

                #--- TB : invoice ---#
                $query_invoice = "INSERT INTO invoice (payments_vat, inv_title, inv_date, inv_year, inv_year_thai, inv_month, inv_no, inv_full, inv_by, agent, transfer, bill, create_date)";
                $query_invoice .= "VALUES (0, '', '', 0, 0, 0, 0, '', 0, 0, 0, 0, now())";
                $result_invoice = mysqli_query($mysqli_p, $query_invoice);
                $invoice_id = mysqli_insert_id($mysqli_p);

                $bind_types = "";
                $params = array();

                $query_invoice = "UPDATE invoice SET";

                $query_invoice .= " payments_vat = ?,";
                $bind_types .= "i";
                array_push($params, $payments_vat);

                $query_invoice .= " inv_title = ?,";
                $bind_types .= "s";
                array_push($params, $inv_title);

                $query_invoice .= " inv_date = ?,";
                $bind_types .= "s";
                array_push($params, $today);

                $query_invoice .= " inv_year = ?,";
                $bind_types .= "i";
                array_push($params, $inv_year);

                $query_invoice .= " inv_year_thai = ?,";
                $bind_types .= "i";
                array_push($params, $inv_year_th);

                $query_invoice .= " inv_month = ?,";
                $bind_types .= "i";
                array_push($params, $inv_month);

                $query_invoice .= " inv_no = ?,";
                $bind_types .= "i";
                array_push($params, setNumberLength($inv_no, 4));

                $query_invoice .= " inv_full = ?,";
                $bind_types .= "s";
                array_push($params, $inv_full);

                $query_invoice .= " inv_by = ?,";
                $bind_types .= "i";
                array_push($params, $_SESSION["admin"]["id"]);

                $query_invoice .= " agent = ?,";
                $bind_types .= "i";
                array_push($params, $row["bagent"]);

                if ($no_tran == "1") {
                    $query_invoice .= " transfer = ?,";
                    $bind_types .= "i";
                    array_push($params, 1);
                }

                $query_invoice .= " create_date = now()";
                $query_invoice .= " WHERE id = '$invoice_id'";

                $procedural_statement_invoice = mysqli_prepare($mysqli_p, $query_invoice);
                if ($bind_types != "") {
                    mysqli_stmt_bind_param($procedural_statement_invoice, $bind_types, ...$params);
                }
                mysqli_stmt_execute($procedural_statement_invoice);
                $result_invoice = mysqli_stmt_get_result($procedural_statement_invoice);

                if (!empty($invoice_id)) {
                    #--- TB : invoice_products ---#
                    $query_invpro = "INSERT INTO invoice_products (invoice, products_type, booking, booking_products, percent_invoice, total_invoice, transfer, create_date)";
                    $query_invpro .= "VALUES (0, 0, 0, 0, 0, 0, 0, now())";
                    $result_invpro = mysqli_query($mysqli_p, $query_invpro);
                    $id_inv_pro = mysqli_insert_id($mysqli_p);

                    $bind_types = "";
                    $params = array();

                    $query_invpro = "UPDATE invoice_products SET";

                    $query_invpro .= " invoice = ?,";
                    $bind_types .= "i";
                    array_push($params, $invoice_id);

                    $query_invpro .= " products_type = ?,";
                    $bind_types .= "i";
                    array_push($params, $row["products_type"]);

                    $query_invpro .= " booking = ?,";
                    $bind_types .= "i";
                    array_push($params, $row["booking"]);

                    $query_invpro .= " booking_products = ?,";
                    $bind_types .= "i";
                    array_push($params, $row["id"]);

                    $query_invpro .= " period = ?,";
                    $bind_types .= "i";
                    array_push($params, '1');

                    $query_invpro .= " percent_invoice = ?,";
                    $bind_types .= "i";
                    array_push($params, '100');

                    if ($no_tran == "1") {
                        $query_invpro .= " transfer = ?,";
                        $bind_types .= "i";
                        array_push($params, 1);
                    }

                    $query_invpro .= " create_date = now()";
                    $query_invpro .= " WHERE id = '$id_inv_pro'";

                    $procedural_statement_invpro = mysqli_prepare($mysqli_p, $query_invpro);
                    if ($bind_types != "") {
                        mysqli_stmt_bind_param($procedural_statement_invpro, $bind_types, ...$params);
                    }
                    mysqli_stmt_execute($procedural_statement_invpro);
                    $result_invpro = mysqli_stmt_get_result($procedural_statement_invpro);
                    #--- Update Status TB : booking_products ---#
                    $status_inv = '1';
                    $query_ubp = "UPDATE booking_products SET invoice = ? WHERE id = ?";
                    $procedural_statement_ubp = mysqli_prepare($mysqli_p, $query_ubp);
                    mysqli_stmt_bind_param($procedural_statement_ubp, 'ii', $status_inv, $row["id"]);
                    mysqli_stmt_execute($procedural_statement_ubp);
                    $result_ubp = mysqli_stmt_get_result($procedural_statement_ubp);

                    #--- PDF Page -----#
                    ob_start();
                    $mpdf = new \Mpdf\Mpdf([
                        'fontDir' => array_merge($fontDirs, [
                            __DIR__ . '/tmp/ttfontdata',
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

                    $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname, products_type.id as ptypeid,
                products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.voucher_no as bvoucher, booking.company as bcompany,
                booking.agent as bagent, booking.agent_voucher as bagentv, booking.receipt_name as bcrename, booking.receipt_address as bcreaddress, booking.customer_mobile as bcmb
                    FROM booking_products
                    LEFT JOIN booking
                        ON booking_products.booking = booking.id
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
                    WHERE booking_products.id = '" . $row["id"] . "'
            ";
                    $procedural_statement_products = mysqli_prepare($mysqli_p, $query_products);
                    mysqli_stmt_execute($procedural_statement_products);
                    $result_products = mysqli_stmt_get_result($procedural_statement_products);
                    $row_products = mysqli_fetch_array($result_products, MYSQLI_ASSOC);

                    #---- Agent -----#
                    if ($row_products["bagent"] != "0") {
                        $name_cus = get_value('agent', 'id', 'company_invoice', $row_products["bagent"], $mysqli_p);
                        $address_cus = get_value('agent', 'id', 'address', $row_products["bagent"], $mysqli_p);
                        $telephone_cus = get_value('agent', 'id', 'telephone', $row_products["bagent"], $mysqli_p);
                        $tax_no_cus = get_value('agent', 'id', 'tax_no', $row_products["bagent"], $mysqli_p);
                        $headquarters_cus = get_value('agent', 'id', 'headquarters', $row_products["bagent"], $mysqli_p);
                        $agent_voucher = $row_products["bagentv"];
                    } else {
                        $name_cus = $row_products["bcrename"];
                        $address_cus = $row_products["bcreaddress"];
                        $telephone_cus = $row_products["bcmb"];
                    }
                    
                    $company_name = !empty($row_products["bcompany"]) ? get_value('company', 'id', 'name', $row_products["bcompany"], $mysqli_p) : 'N/A';
                    $company_add = !empty($row_products["bcompany"]) ? get_value('company', 'id', 'address', $row_products["bcompany"], $mysqli_p) : 'N/A';

                    #---- Date Type Hotel (4) ----#
                    if ($row_products["ptypeid"] != '4') {
                        $travel_date_pro = $row_products["travel_date"] != "0000-00-00" ? date("Y-m-d", strtotime($row_products["travel_date"])) : "0000-00-00";
                        $type_date = "วันที่เที่ยว";
                    } else {
                        $travel_date_pro = $row_products["checkin_date"] != "0000-00-00" ? date("Y-m-d", strtotime($row_products["checkin_date"])) : "0000-00-00";
                        $type_date = "วันที่เช็คอิน";
                        $travel_date_pro_out = $row_products["checkout_date"] != "0000-00-00" ? date("Y-m-d", strtotime($row_products["checkout_date"])) : "0000-00-00";
                        $type_date_out = "วันที่เช็คเอาท์";
                    }

                    #---- Price ----#
                    $i = 0;
                    $rate_2 = "";
                    $rate_2_sum = "";
                    $adults = "";
                    $children = "";
                    $rate_4 = "";
                    $rate_4_sum = "";
                    $rateforpax = "";
                    $foreigner = "";
                    $foreigner_sum = "";
                    $foreigner = "";
                    $foc = $row_products["foc"];
                    $foc_text = "";
                    $pax_mix = $row_products["adults"] + $row_products["children"];
                    $check_foc = $foc != 0 && ($row_products["adults"] == 0 || $row_products["children"] == 0) ? 'True' : 'False' ;
                    if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
                        if($check_foc == 'False'){
                            if ($row_products["pax"] > 0) {
                                if ($row_products["ptypeid"] == 1) {
                                    $rateforpax = $row_products["charter_2"] != "0" ? $row_products["charter_2"] : "0";
                                } else {
                                    $rateforpax = $row_products["group_2"] != "0" ? $row_products["group_2"] : "0";
                                }
                                if ($pax_mix > $row_products["pax"]) {
                                    $pax_profuse = ($pax_mix - $row_products["pax"]) * $row_products["rate_2"];
                                    $rateforpax_avg = $rateforpax / $row_products["pax"];
                                    $no_people = $row_products["pax"];
                                    $rate_2 = $row_products["rate_2"];
                                    $rate_2_sum = $row_products["rate_2"] * ($pax_mix - $row_products["pax"]);
                                    $adults = $pax_mix - $row_products["pax"];
                                    $adults = $adults . " คน";
                                } else {
                                    $rateforpax_avg = $rateforpax / $pax_mix;
                                    $no_people = $pax_mix;
                                }
                            } else {
                                $rate_2 = $row_products["rate_2"];
                                $adults = $row_products["adults"] . " คน";
                                $rate_2_sum = $rate_2 * $row_products["adults"];

                                $rate_4 = $row_products["rate_4"];
                                $children = $row_products["children"] . " คน";
                                $rate_4_sum = $rate_4 * $row_products["children"];
                            }
                            if ($row_products["foreigner_price"] != "0") {
                                $rate_foreigner = $row_products["foreigner_price"] / $row_products["foreigner_no"];
                                $foreigner = $row_products["foreigner_no"] . " คน";
                                $rate_foreigner_sum = $row_products["foreigner_price"];
                                $foreigner_text = $row_products["pcsname"] . " (foreigner)";
                            }
                            $adults_text = $row_products["pcsname"] . " (ADL)";
                            $children_text = $row_products["pcsname"] . " (CHD)";
                        } // if($check_foc == 'False'){
                    } // if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
                    if ($row_products["ptypeid"] == 3) {
                        $rate_2 = $row_products["rate_2"];
                        $adults = $row_products["no_cars"] . " คัน";
                        $rate_2_sum = $rate_2 * $row_products["no_cars"];
                        $adults_text = $check_foc == 'False' ? $row_products["pcsname"] . " (" . $row_products["no_hours"] . " ชม.)" : $row_products["pcsname"] . " (" . $row_products["no_hours"] . " ชม.) (FOC)" ;
                        if ($row_products["no_hours"] > $row_products["hours_no"] && $check_foc == 'False') {
                            $no_hours_extra = $row_products["no_hours"] - $row_products["hours_no"];
                            $rate_4 = $row_products["extra_hour_2"];
                            $children = $row_products["no_cars"] . " คัน";
                            $rate_4_sum = ($rate_4 * $no_hours_extra) * $row_products["no_cars"];
                            $adults_text = $row_products["pcsname"] . " (" . $row_products["hours_no"] . " ชม.)";
                            $children_text = $row_products["pcsname"] . " (" . $no_hours_extra . " ชม.)";
                        }
                    }
                    if ($row_products["ptypeid"] == 4) {
                        $rate_2 = $row_products["rate_2"];
                        $adults = $row_products["no_rooms"] . " ห้อง";
                        $no_night = DateDiff($row_products["checkin_date"], $row_products["checkout_date"]);
                        $rate_2_sum = ($rate_2 * $no_night) * $row_products["no_rooms"];
                        $adults_text = $check_foc == 'False' ? $row_products["pcsname"] . " (" . $no_night . " คืน)" : $row_products["pcsname"] . " (" . $no_night . " คืน) (FOC)"; ;
                        if ($row_products["extra_beds"] != 0) {
                            $rate_4 = $row_products["extrabeds_2"];
                            $children = $row_products["extra_beds"] . " เตียง";
                            $rate_4_sum = ($rate_4 * $no_night) * $row_products["extra_beds"];
                            $children_text = $row_products["pcsname"] . " (เตียงเสริม)";
                        }
                        if ($row_products["share_bed"] != 0) {
                            $rate_foreigner = $row_products["sharingbed_2"];
                            $foreigner = $row_products["share_bed"] . " เตียง";
                            $rate_foreigner_sum = ($rate_foreigner * $no_night) * $row_products["share_bed"];
                            $foreigner_text = $row_products["pcsname"] . " (แชร์เตียง)";
                        }
                    }

                    #----- Price Tranfer -----#
                    if (($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) && $tranfer == 2) {
                        if ($check_foc == 'False') {
                            $tranfer_text = "รถตู้ Join Trip";
                            $price_tranfer = $pax_mix * $row_products["transfer_2"];
                            $price_default = $no_tran == "0" ? $row_products["price_default"] - $price_tranfer : $price_tranfer;
                            $price_discount = round((($row_products["price_default"] - $row_products["price_latest"]) / $row_products["price_default"]) * $price_default);
                            $price_latest = $price_default - $price_discount;
                            $transfer_2 = $row_products["transfer_2"];
                        } else {
                            $pax_mix = $foc;
                            $tranfer_text = "รถตู้ Join Trip (FOC)";
                            $transfer_2 = '0';
                            $price_tranfer = '0';
                            $price_discount = '0';
                            $price_latest = '0';
                            $price_default = '0';
                        }
                    } else {
                        $price_default = $row_products["price_default"];
                        $price_latest = $row_products["price_latest"];
                        $price_discount =  $price_default - $price_latest;
                    }
                    #---- Tax ----#
                    if ($payments_vat == "1") {
                        //Vat 0%
                        $price_tax = "0";
                        $total_products = round($price_latest);
                        $total_price = round($price_latest);
                        #--- Text ---#
                        // $title_tax = "0.00%";
                        $discount_text_th = "มูลค่าสินค้า";
                        $discount_text_eng = "Products Value";
                        $total_pro_th = "จำนวนเงินรวมทั้งสิ้น";
                        $total_pro_eng = "Total Invoice";
                        $total_price_text = Convert($total_price);
                    } elseif ($payments_vat == "2") {
                        //รวม Vat 7%
                        $price_tax = round($price_latest) - ((round($price_latest) * 100) / 107);
                        $total_products = round($price_latest) - $price_tax;
                        $total_price = round($price_latest);
                        #--- Text ---#
                        // $title_tax = "7.00%";
                        $discount_text_th = "มูลค่าสินค้า";
                        $discount_text_eng = "Products Value";
                        $total_pro_th = "จำนวนเงินรวมทั้งสิ้น";
                        $total_pro_eng = "Total Invoice";
                        $total_price_text = Convert($total_price);
                    } elseif ($payments_vat == "3") {
                        //แยก Vat 7%
                        $price_tax = (round($price_latest) * 7) / 100;
                        $total_products = round($price_latest) + $price_tax;
                        $total_price = round($price_latest);
                        #--- Text ---#
                        // $title_tax = "7.00%";
                        $discount_text_th = "ยอดหลังหักส่วนลด";
                        $discount_text_eng = "After Discount";
                        $total_pro_th = "จำนวนเงินรวมทั้งสิ้น";
                        $total_pro_eng = "Total Invoice";
                        $total_price_text = Convert($total_products);
                    }

                    $message = "";
                    $message = "<table width=\"700\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" valign=\"top\">";
                    $message .= "<b>" . $company_name . "</b><br />";
                    $message .= $company_add ."<br />";
                    $message .= "</td>";
                    $message .= "<td colspan=\"3\" width=\"200\" align=\"center\"><br /><br /><br /><br />";
                    $message .= "<h4>ใบแจ้งหนี้ <br /> ต้นฉบับ (สำหรับลูกค้า)</h4>";
                    $message .= "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"5\">&nbsp;</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; margin-top:10px; padding:10px 15px;\">";
                    $message .= "<span style=\"line-height: 12px;\"><b>ลูกค้า</b><br />";
                    $message .= "<span style=\"font-size:10px; line-height:12px;\">Customer</span><br /></span>";
                    $message .= "<b> " . $name_cus . " </b><br />";
                    $message .= "<b> " . $address_cus . " </b><br />";
                    $message .= "<b>โทร :</b> " . $telephone_cus . " <br />";
                    if ($row_products["bagent"] != "0") {
                        $message .= "<span style=\"line-height:12px;\"><br /><b>เลขประจำตัวผู้เสียภาษี :</b> " . $tax_no_cus . " &nbsp; " . $headquarters_cus . " <br />";
                        $message .= "<span style=\"font-size:10px;\">Tax ID</span><br /><br /></span>";
                        $message .= "<span style=\"line-height:12px;\"><b>อ้างอิง :</b> " . $agent_voucher . "<br />";
                        $message .= "<span style=\"font-size:10px;\">Reference</span></span>";
                    }
                    $message .= "</td>";
                    $message .= "<td colspan=\"3\" style=\"border:1px solid #cdcdcd; margin-top:10px; padding:10px 15px;\">";
                    $message .= "<span style=\"line-height:12px;\"><b>เลขที่</b> " . $inv_full . " <br />";
                    $message .= "<span style=\"font-size:10px;\">No.</span><br /></span>";
                    $message .= "<span style=\"line-height:12px;\"><b>วันที่</b> " . $today . " <br />";
                    $message .= "<span style=\"font-size:10px;\">Date</span><br /></span>";
                    $message .= "<br /><b> " . $type_date . " </b> " . $travel_date_pro . " <br />";
                    if ($row_products["ptypeid"] == '4') {
                        $message .= "<b> " . $type_date_out . " </b> " . $travel_date_pro_out . " <br />";
                    }
                    $message .= "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div> ลำดับ <br />No. </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div>รหัสบริการ/รายละเอียด <br />Code/Descriptions </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div>จำนวน <br />Quantity </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div>หน่วยละ <br />Unit Price </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div>จำนวนเงิน <br />Amount </div>";
                    $message .= "</td>";
                    $message .= "</tr>";
                    #---- Transfer ----#
                    if (($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) && $tranfer == 2 && $no_tran == "1") {
                        $i++;
                        $message .= "<tr>";
                        $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                        $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $tranfer_text . "</td>";
                        $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $pax_mix . " คน</td>";
                        $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($transfer_2, 2) . "</td>";
                        $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($price_tranfer, 2) . "</td>";
                        $message .= "</tr>";
                    } else {
                        #---- Group ----#
                        if ($rateforpax != 0) {
                            $i++;
                            $message .= "<tr>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                            $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $row_products["pcsname"] . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $no_people . " คน</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rateforpax_avg, 2) . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rateforpax, 2) . "</td>";
                            $message .= "</tr>";
                        }
                        #---- ADL ----#
                        if ($adults != 0) {
                            $i++;
                            $message .= "<tr>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                            $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $adults_text . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $adults . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_2, 2) . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_2_sum, 2) . "</td>";
                            $message .= "</tr>";
                        }
                        #---- CHD ----#
                        if ($children != 0) {
                            $i++;
                            $message .= "<tr>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                            $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $children_text . " </td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $children . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_4, 2) . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_4_sum, 2) . "</td>";
                            $message .= "</tr>";
                        }
                        #---- Foreigner ----#
                        if ($foreigner != 0) {
                            $i++;
                            $message .= "<tr>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                            $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $foreigner_text . " </td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $foreigner . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_foreigner, 2) . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($rate_foreigner_sum, 2) . "</td>";
                            $message .= "</tr>";
                        }
                        #---- FOC ----#
                        if ($check_foc == 'True' && ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2)) {
                            $i++;
                            $foc_text = $row_products["pcsname"] . " (FOC)";
                            $message .= "<tr>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> $i </td>";
                            $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $foc_text . " </td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . $foc . "  คน</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format(0, 2) . "</td>";
                            $message .= "<td style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format(0, 2) . "</td>";
                            $message .= "</tr>";
                        }
                    }
                    $message .= "<tr>";
                    $message .= "<td valign=\"top\" height=\"175\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> &nbsp; </td>";
                    $message .= "<td valign=\"top\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> &nbsp; </td>";
                    $message .= "<td valign=\"top\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> &nbsp; </td>";
                    $message .= "<td valign=\"top\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> &nbsp; </td>";
                    $message .= "<td valign=\"top\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> &nbsp; </td>";
                    $message .= "</tr>";

                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"><span style=\"font-size:8px;\">ใบเสร็จรับเงินฉบับนี้จะสมบูรณ์ต่อเมื่อมีลายเซ็นผู้รับมอบอำนาจและลายเซ็นผู้รับเงิน และได้เรียกเก็บเงินตามเช็คเรียบร้อยแล้ว</span></td>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">";
                    $message .= "<div style=\"line-height: 12px;\"> รวมเป็นเงิน<br /> <small>Gross Amount</small> </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: right; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($price_default, 2) . "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" valign=\"bottom\" rowspan=\"3\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-top-style: hidden;\"><span style=\"font-size:8px;\">ผิด ตก ยกเว้น E.& O.E.</span></td>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">";
                    $message .= "<div style=\"line-height: 12px;\"> หักส่วนลด <br /> <small>Less Discount</small> </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: right; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($price_discount, 2) . "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">";
                    $message .= "<div style=\"line-height: 12px;\">" . $discount_text_th . " <br /> <small>" . $discount_text_eng . " </small> </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: right; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\">" . number_format($total_price, 2) . "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div style=\"line-height: 12px;\">จำนวนภาษีมูลค่าเพิ่ม 7.00 % <br /> <small>Vat</small> </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: right; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\"> " . number_format($price_tax, 2) . " </td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">(" . $total_price_text . ")</td>";
                    $message .= "<td colspan=\"2\" style=\"border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div style=\"line-height: 12px;\"> " . $total_pro_th . " <br /> <small>" . $total_pro_eng . "</small> </div>";
                    $message .= "</td>";
                    $message .= "<td style=\"text-align: right; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">" . number_format($total_products, 2) . "</td>";
                    $message .= "</tr>";
                    $message .= "<tr>";
                    $message .= "<td colspan=\"2\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-right-style: hidden;\">&nbsp;</td>";
                    $message .= "<td colspan=\"3\" style=\"text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;\">";
                    $message .= "<div style=\"line-height: 20px;\"> <small>ในนาม</small><br /> บริษัท อันดามัน แพชชั่น จำกัด </div>";
                    $message .= "<br /><br /><br /><br />";
                    $message .= "<hr style=\"width:70%; border:1px solid #000000;\">ผู้รับมอบอำนาจ/Authorized Signature";
                    $message .= "</td>";
                    $message .= "</tr>";
                    $message .= "</table>";

                    // echo "</br>" . $message;

                    // $html = ob_get_contents();
                    // $mpdf->WriteHTML($html);

                    $mpdf->WriteHTML($message);
                    $mpdf->Output("../../../assets/invoice_pdf/" . $inv_full . ".pdf");
                    ob_end_flush();
                    $message = "";

                    if (!empty($invoice_id)) {
                        $total_invoice = preg_replace('(,)', '', number_format($total_products, 2))."</br>";
                        #--- Update Total Price Invoice TB : invoice_products ---#
                        $query_total = "UPDATE invoice_products SET total_invoice = '". $total_invoice ."' WHERE id = '". $invoice_id ."' ";
                        $result_total = mysqli_query($mysqli_p, $query_total);
                    }
                }
            }
        }
    }
}
mysqli_close($mysqli_p);
