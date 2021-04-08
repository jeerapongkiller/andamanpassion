<?php
require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

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

foreach ($_POST["products_id"] as $products_id) {
    $period = $_POST["period"];
    echo "id : " . $products_id . "</br>";
    for ($no_per = 1; $no_per <= $_POST["period"]; $no_per++) {
        $percent = !empty($_POST["percent_$no_per"]) ? $_POST["percent_$no_per"] : "100";
        // echo "งวดที่ : " . $no_per . " จ่าย : " . $percent . "% " . "Tax : " . $_POST["vat"] . "</br>";
        $tranfer = get_value("booking_products", "id", "transfer", $products_id, $mysqli_p) == "1" ? "2" : "1";
        for ($no_tran = "0"; $no_tran < $tranfer; $no_tran++) {

            #--- GET VALUE ---#
            $payments_vat = $_POST["vat"];
            $inv_title = $_POST["vat"] == 1 ? "IV" : "ST";
            $today_str = explode("-", $today);
            $inv_year = $today_str[0];
            $inv_year_th_full = $today_str[0] + 543;
            $inv_year_th = substr($inv_year_th_full, -2);
            $inv_month = $today_str[1];
            $inv_no = 0;

            #--- CHECK TB : invoice ---#
            $query_inv = "SELECT * FROM invoice ";
            $query_inv .= $_POST["vat"] == 1 ? "WHERE payments_vat = 1" : "WHERE payments_vat != 1";
            $query_inv .= " ORDER BY inv_date DESC, inv_no DESC LIMIT 0,1";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_inv);
            mysqli_stmt_execute($procedural_statement);
            $result_inv = mysqli_stmt_get_result($procedural_statement);
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
            $result = mysqli_query($mysqli_p, $query_invoice);
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
            array_push($params, 0);

            if ($no_tran == "1") {
                $query_invoice .= " transfer = ?,";
                $bind_types .= "i";
                array_push($params, 1);
            }

            $query_invoice .= " create_date = now()";
            $query_invoice .= " WHERE id = '$invoice_id'";

            $procedural_statement = mysqli_prepare($mysqli_p, $query_invoice);
            if ($bind_types != "") {
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);

            if (!empty($invoice_id)) {
                $num_products_id = count($_POST["products_id"]);
                $products_type = get_value("booking_products", "id", "products_type", $products_id, $mysqli_p);
                $booking = get_value("booking_products", "id", "booking", $products_id, $mysqli_p);
                $transfer = get_value("booking_products", "id", "transfer", $products_id, $mysqli_p);

                #--- TB : invoice_products ---#
                $query_invpro = "INSERT INTO invoice_products (invoice, products_type, booking, booking_products, percent_invoice, total_invoice, transfer, create_date)";
                $query_invpro .= "VALUES (0, 0, 0, 0, 0, 0, 0, now())";
                $result = mysqli_query($mysqli_p, $query_invpro);
                $id_inv_pro = mysqli_insert_id($mysqli_p);

                $bind_types = "";
                $params = array();

                $query_invpro = "UPDATE invoice_products SET";

                $query_invpro .= " invoice = ?,";
                $bind_types .= "i";
                array_push($params, $invoice_id);

                $query_invpro .= " products_type = ?,";
                $bind_types .= "i";
                array_push($params, $products_type);

                $query_invpro .= " booking = ?,";
                $bind_types .= "i";
                array_push($params, $booking);

                $query_invpro .= " booking_products = ?,";
                $bind_types .= "i";
                array_push($params, $products_id);

                $query_invpro .= " period = ?,";
                $bind_types .= "i";
                array_push($params, $no_per);

                $query_invpro .= " percent_invoice = ?,";
                $bind_types .= "i";
                array_push($params, $percent);

                if ($no_tran == "1") {
                    $query_invpro .= " transfer = ?,";
                    $bind_types .= "i";
                    array_push($params, 1);
                }

                $query_invpro .= " create_date = now()";
                $query_invpro .= " WHERE id = '$id_inv_pro'";

                $procedural_statement = mysqli_prepare($mysqli_p, $query_invpro);
                if ($bind_types != "") {
                    mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
                }
                mysqli_stmt_execute($procedural_statement);
                $result = mysqli_stmt_get_result($procedural_statement);

                #--- Update Status TB : booking_products ---#
                $status_inv = '1';
                $query = "UPDATE booking_products SET invoice = ? WHERE id = ?";
                $procedural_statement = mysqli_prepare($mysqli_p, $query);
                mysqli_stmt_bind_param($procedural_statement, 'ii', $status_inv, $products_id);
                mysqli_stmt_execute($procedural_statement);
                $result = mysqli_stmt_get_result($procedural_statement);
            }

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
                WHERE booking_products.id = '" . $products_id . "'
                ";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
            $row_products = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $name_cus = $row_products["bcrename"];
            $address_cus = $row_products["bcreaddress"];
            $telephone_cus = $row_products["bcmb"];
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
            $check_foc = $foc != 0 && ($row_products["adults"] == 0 || $row_products["children"] == 0) ? 'True' : 'False';
            if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
                if ($check_foc == 'False') {
                    if ($row_products["pax"] > 0) {
                        if ($row_products["ptypeid"] == 1) {
                            $rateforpax = $row_products["charter_2"] != "0" ? $row_products["charter_2"] : "0";
                        } else {
                            $rateforpax = $row_products["group_2"] != "0" ? $row_products["group_2"] : "0";
                        }
                        if ($pax_mix > $row_products["pax"]) {
                            $pax_profuse = ($pax_mix - $row_products["pax"]) * $row_products["rate_2"];
                            $rateforpax_avg = $period > '1' ? (($rateforpax * $percent) / 100) / $row_products["pax"] : $rateforpax / $row_products["pax"];
                            $no_people = $row_products["pax"];
                            $rate_2 = $row_products["rate_2"];
                            $rate_2_sum = $row_products["rate_2"] * ($pax_mix - $row_products["pax"]);
                            $adults = $pax_mix - $row_products["pax"];
                            $adults = $adults . " คน";
                            $adults_text = $period > '1' ? $row_products["pcsname"] . " (ADL) (งวดที่ " . $no_per . " : " . number_format($rate_2_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (ADL)";
                            $rate_2 = $period > '1' ? ($rate_2 * $percent) / 100 : $rate_2;
                            $rate_2_sum = $period > '1' ? ($rate_2_sum * $percent) / 100 : $rate_2_sum;
                        } else {
                            $rateforpax_avg = $period > '1' ? (($rateforpax * $percent) / 100) / $pax_mix : $rateforpax / $pax_mix;
                            $no_people = $pax_mix;
                        }
                        $rateforpax_text = $period > '1' ? $row_products["pcsname"] . " (งวดที่ " . $no_per . " : " . number_format($rateforpax) . " X " . $percent . "%)" : $row_products["pcsname"];
                        $rateforpax = $period > '1' ? ($rateforpax * $percent) / 100 : $rateforpax;
                    } else {
                        $rate_2 = $row_products["rate_2"];
                        $adults = $row_products["adults"] . " คน";
                        $rate_2_sum = $rate_2 * $row_products["adults"];

                        $rate_4 = $row_products["rate_4"];
                        $children = $row_products["children"] . " คน";
                        $rate_4_sum = $rate_4 * $row_products["children"];

                        $adults_text = $period > '1' ? $row_products["pcsname"] . " (ADL) (งวดที่ " . $no_per . " : " . number_format($rate_2_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (ADL)";
                        $children_text = $period > '1' ? $row_products["pcsname"] . " (CHD) (งวดที่ " . $no_per . " : " . number_format($rate_4_sum) . " X " . $percent . "%)"  : $row_products["pcsname"] . " (CHD)";
                        $rate_2 = $period > '1' ? ($rate_2 * $percent) / 100 : $rate_2;
                        $rate_4 = $period > '1' ? ($rate_4 * $percent) / 100 : $rate_4;
                        $rate_2_sum = $period > '1' ? ($rate_2_sum * $percent) / 100 : $rate_2_sum;
                        $rate_4_sum = $period > '1' ? ($rate_4_sum * $percent) / 100 : $rate_4_sum;
                    }
                    if ($row_products["foreigner_price"] != "0") {
                        $rate_foreigner = $row_products["foreigner_price"] / $row_products["foreigner_no"];
                        $foreigner = $row_products["foreigner_no"] . " คน";
                        $rate_foreigner_sum = $row_products["foreigner_price"];
                        $foreigner_text = $period > '1' ? $row_products["pcsname"] . " (Foreigner) (งวดที่ " . $no_per . " : " . number_format($rate_foreigner_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (Foreigner)";
                    }
                } // if($check_foc == 'False'){
            } // if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
            if ($row_products["ptypeid"] == 3) {
                $rate_2 = $row_products["rate_2"];
                $adults = $row_products["no_cars"] . " คัน";
                $rate_2_sum = $rate_2 * $row_products["no_cars"];
                $adults_text = $period > '1' ? $row_products["pcsname"] . " (" . $row_products["no_hours"] . " ชม.) (งวดที่ " . $no_per . " : " . number_format($rate_2_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (" . $row_products["no_hours"] . " ชม.)";
                $adults_text = $check_foc == 'False' ? $adults_text : $row_products["pcsname"] . " (" . $row_products["no_hours"] . " ชม.) (FOC)";
                $rate_2 = $period > '1' ? ($rate_2 * $percent) / 100 : $rate_2;
                $rate_2_sum = $period > '1' ? ($rate_2_sum * $percent) / 100 : $rate_2_sum;
                if ($row_products["no_hours"] > $row_products["hours_no"] && $check_foc == 'False') {
                    $no_hours_extra = $row_products["no_hours"] - $row_products["hours_no"];
                    $rate_4 = $row_products["extra_hour_2"];
                    $children = $row_products["no_cars"] . " คัน";
                    $rate_4_sum = ($rate_4 * $no_hours_extra) * $row_products["no_cars"];
                    $children_text = $period > '1' ? $row_products["pcsname"] . " (" . $no_hours_extra . " ชม.) (งวดที่ " . $no_per . " : " . number_format($rate_4_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (" . $no_hours_extra . " ชม.)";
                    $rate_4 = $period > '1' ? ($rate_4 * $percent) / 100 : $rate_4;
                    $rate_4_sum = $period > '1' ? ($rate_4_sum * $percent) / 100 : $rate_4_sum;
                }
            }
            if ($row_products["ptypeid"] == 4) {
                $rate_2 = $row_products["rate_2"];
                $adults = $row_products["no_rooms"] . " ห้อง";
                $no_night = DateDiff($row_products["checkin_date"], $row_products["checkout_date"]);
                $rate_2_sum = ($rate_2 * $no_night) * $row_products["no_rooms"];
                $adults_text = $period > '1' ? $row_products["pcsname"] . " (" . $no_night . " คืน)  (งวดที่ " . $no_per . " : " . number_format($rate_2_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (" . $no_night . " คืน)";
                $adults_text = $check_foc == 'False' ? $adults_text : $row_products["pcsname"] . " (" . $no_night . " คืน) (FOC)";
                $rate_2 = $period > '1' ? ($rate_2 * $percent) / 100 : $rate_2;
                $rate_2_sum = $period > '1' ? ($rate_2_sum * $percent) / 100 : $rate_2_sum;
                if ($row_products["extra_beds"] != 0) {
                    $rate_4 = $row_products["extrabeds_2"];
                    $children = $row_products["extra_beds"] . " เตียง";
                    $rate_4_sum = ($rate_4 * $no_night) * $row_products["extra_beds"];
                    $children_text = $period > '1' ? $row_products["pcsname"] . " (เตียงเสริม) (งวดที่ " . $no_per . " : " . number_format($rate_4_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (เตียงเสริม)";
                    $rate_4 = $period > '1' ? ($rate_4 * $percent) / 100 : $rate_4;
                    $rate_4_sum = $period > '1' ? ($rate_4_sum * $percent) / 100 : $rate_4_sum;
                }
                if ($row_products["share_bed"] != 0) {
                    $rate_foreigner = $row_products["sharingbed_2"];
                    $foreigner = $row_products["share_bed"] . " เตียง";
                    $rate_foreigner_sum = ($rate_foreigner * $no_night) * $row_products["share_bed"];
                    $foreigner_text = $period > '1' ? $row_products["pcsname"] . " (แชร์เตียง) (งวดที่ " . $no_per . " : " . number_format($rate_foreigner_sum) . " X " . $percent . "%)" : $row_products["pcsname"] . " (แชร์เตียง)";
                    $rate_foreigner = $period > '1' ? ($rate_foreigner * $percent) / 100 : $rate_foreigner;
                    $rate_foreigner_sum = $period > '1' ? ($rate_foreigner_sum * $percent) / 100 : $rate_foreigner_sum;
                }
            }

            #----- Price Tranfer -----#
            if (($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) && $tranfer == 2) {
                if ($check_foc == 'False') {
                    $price_tranfer_de = $pax_mix * $row_products["transfer_2"];
                    $tranfer_text = $period > '1' ? "รถตู้ Join Trip (งวดที่ " . $no_per . " : " . $price_tranfer_de . " X " . $percent . "%)" : "รถตู้ Join Trip";
                    $transfer_2 = $period > '1' ? ($row_products["transfer_2"] * $percent) / 100 : $row_products["transfer_2"];
                    $price_tranfer = $pax_mix * $transfer_2;
                    if ($no_tran == '0') {
                        $price_default = $period > '1' ? (($row_products["price_default"] - $price_tranfer_de) * $percent) / 100 : $row_products["price_default"] - $price_tranfer_de;
                    } else {
                        $price_default = $price_tranfer;
                    }
                    $price_discount = round((($row_products["price_default"] - $row_products["price_latest"]) / $row_products["price_default"]) * $price_default);
                    $price_latest = $price_default - $price_discount;
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
                $price_default = $period > '1' ? ($row_products["price_default"] * $percent) / 100 : $row_products["price_default"];
                $price_latest = $period > '1' ? ($row_products["price_latest"] * $percent) / 100 : $row_products["price_latest"];
                $price_discount =  $price_default - $price_latest;
            }

            #---- Tax ----#
            if ($_POST["vat"] == "1") {
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
            } elseif ($_POST["vat"] == "2") {
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
            } elseif ($_POST["vat"] == "3") {
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
            $message .= $company_add . "<br />";
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
            $message .= "<br /><b> " . $type_date . " </b> " . date("d/m/Y", strtotime($travel_date_pro)) . " <br />";
            if ($row_products["ptypeid"] == '4') {
                $message .= "<b> " . $type_date_out . " </b> " . date("d/m/Y", strtotime($travel_date_pro_out)) . " <br />";
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
                    $message .= "<td style=\"text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px; border-bottom-style: hidden;\"> " . $rateforpax_text . "</td>";
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

            // echo $company_name . "</br>" . $message;

            // $html = ob_get_contents();
            // $mpdf->WriteHTML($html);

            $mpdf->WriteHTML($message);
            $mpdf->Output("../assets/invoice_pdf/" . $inv_full . ".pdf");
            ob_end_flush();
            $message = "";

            if (!empty($invoice_id)) {
                $total_invoice = preg_replace('(,)', '', number_format($total_products, 2)) . "</br>";
                #--- Update Total Price Invoice TB : invoice_products ---#
                $query_total = "UPDATE invoice_products SET total_invoice = '" . $total_invoice . "' WHERE id = '" . $invoice_id . "' ";
                $result_total = mysqli_query($mysqli_p, $query_total);
            }

        } // for ($no_tran = "0"; $no_tran < $tranfer; $no_tran++) {

    } // for($i=1;$i<=$_POST["period"];$i++){

} // foreach ($_POST["products_id"] as $products_id) {

?>

<form action="./?mode=ar/list&message=success" method="POST" name="form_save_ar">
    <input type="hidden" name="search_voucher_no" id="search_voucher_no" value="<?php echo $_POST['search_voucher_no']; ?>">
    <input type="hidden" name="search_customer_firstname" id="search_customer_firstname" value="<?php echo $_POST['search_customer_firstname']; ?>">
    <input type="hidden" name="search_customer_lastname" id="search_customer_lastname" value="<?php echo $_POST['search_customer_lastname']; ?>">
    <input type="hidden" name="search_travel_date_from" id="search_travel_date_from" value="<?php echo $_POST['search_travel_date_from']; ?>">
    <input type="hidden" name="search_travel_date_to" id="search_travel_date_to" value="<?php echo $_POST['search_travel_date_to']; ?>">
    <button type="submit" value="submit">save</button>
</form>
<script>
    window.onload = function() {
        document.forms['form_save_ar'].submit();
    }
</script>

<?php

mysqli_close($mysqli_p);
