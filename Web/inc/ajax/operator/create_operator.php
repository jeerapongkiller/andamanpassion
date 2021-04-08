<?php
require("../../../inc/connection.php");

require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!empty($_POST['id']) && !empty($_POST['supplier']) && !empty($_POST['ptype']) && !empty($_POST['catesecond']) && !empty($_POST['dropoff']) && !empty($_POST['travel_from']) && !empty($_POST['travel_to'])) {
    $arr_id = explode(',', $_POST['id']);
    $supplier_val = get_value("supplier", "id", "company", $_POST['supplier'], $mysqli_p);
    $ptype_val = get_value("products_type", "id", "name", $_POST['ptype'], $mysqli_p);
    $catesecond_val = get_value("products_category_second", "id", "name", $_POST['catesecond'], $mysqli_p);
    $dropoff_val = get_value("place", "id", "name", $_POST['dropoff'], $mysqli_p);
    $file_name = time();
    $date_travel = $_POST['travel_from'] == $_POST['travel_to'] ? "" : " - ".date("j F Y", strtotime($_POST['travel_to']));

    ob_start();
    $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/../../../assets/tmp/ttfontdata',
        ]),
        'fontdata' => $fontData + [
            'prompt' => [
                'R' => 'Prompt-Regular.ttf',
                'I' => 'Prompt-Italic.ttf',
                'B' => 'Prompt-Regular.ttf',
            ]
        ],
        'default_font' => 'prompt',
        'format' => 'A4-L',
        'margin_left'       => '15',
        'margin_right'      => '15',
        'margin_top'        => '34',
    ]);

    $message = "";

    $mpdf->SetHTMLHeader(' 
    <table width="1000" border="0" cellspacing="0" cellpadding="1">
    <tr>
    <td colspan="13" valign="top" style="text-align: center; font-size:18px;">
    <b>'.$supplier_val.' - '.$catesecond_val.'</b><br />
    <b>'. date("j F Y", strtotime($_POST['travel_from'])) . $date_travel .'(รถรับ-ส่ง'.$dropoff_val.')</b><br /><br /><br />
    </td>
    </tr>
    <tr>
    <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Voucher </td>
    <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Agency </td>
    <td width="159" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Customer '."'".'s Nmae </td>
    <td width="44" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> AD </td>
    <td width="53" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> CHD </td>
    <td width="45" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> INF </td>
    <td width="52" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> FOC </td>
    <td width="47" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Car </td>
    <td width="57" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Time </td>
    <td width="85" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Pickup </td>
    <td width="84" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Room </td>
    <td width="82" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Zone </td>
    <td width="140" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Send Back </td>
    </tr>
    </table>
    ');

    $message = "<table width=\"1000\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" >";

    foreach ($arr_id as $val) {
        $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname, products_category_first.supplier as pcfsupplier, products_type.id as ptypeid,
                                                        products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                                                        booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.voucher_no as bvoucher,
                                                        booking.agent as bagent, booking.agent_voucher as bagentv, booking.customer_firstname as bcfname, booking.customer_lastname as bclname, 
                                                        booking.customer_mobile as bcmb, place.id as place_id, place.name as place_name , place.dropoff as place_dropoff
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
                                                    LEFT JOIN place
                                                        ON booking_products.dropoff = place.id
                                                    LEFT JOIN booking_status_confirm
                                                        ON booking_products.status_confirm = booking_status_confirm.id
                                                    WHERE booking_products.id = '" . $val . "'
                                ";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $agent_name = $row["bagent"] != '0' ? get_value("agent", "id", "company", $row["bagent"], $mysqli_p) : '-' ;
        $customer_name = $row["bcfname"] != '' ? $row["bcfname"] . " " . $row["bclname"] : '-' ;
        $zone = $row["zones"] != '0' ? get_value("zones", "id", "name", $row["zones"], $mysqli_p) : '-' ;
        $roomno = $row['roomno'] != '' ? $row['roomno'] : '-' ;

        $message .= "<tr>";
        $message .= "<td width='94' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['bvoucher'] ."</td>";
        $message .= "<td width='94' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $agent_name ."</td>";
        $message .= "<td width='159' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $customer_name ."</td>";
        $message .= "<td width='44' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['adults'] ."</td>";
        $message .= "<td width='53' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['children'] ."</td>";
        $message .= "<td width='45' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['infant'] ."</td>";
        $message .= "<td width='52' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['foc'] ."</td>";
        $message .= "<td width='47' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['vans'] ."</td>";
        $message .= "<td width='57' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $row['pickup_time'] ."</td>";
        $message .= "<td width='85' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". get_value("place", "id", "name", $row['pickup'], $mysqli_p) ."</td>";
        $message .= "<td width='84' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $roomno ."</td>";
        $message .= "<td width='82' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">". $zone ."</td>";
        $message .= "<td width='140' style=\"text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;\">  </td>";
        $message .= "</tr>";

        #--- Update Status Edit Date ---#
        $edit_date = '2';
        $query_date = "UPDATE booking_products SET edit_date = ? WHERE id = ?";
        $procedural_statement_date = mysqli_prepare($mysqli_p, $query_date);
        mysqli_stmt_bind_param($procedural_statement_date, 'ii', $edit_date, $val);
        mysqli_stmt_execute($procedural_statement_date);
        $result_date = mysqli_stmt_get_result($procedural_statement_date);
    }
    $message .= "</table>";

    // echo $message;

    $mpdf->WriteHTML($message);
    $mpdf->Output("../../../assets/operator_pdf/" . $file_name . ".pdf");
    ob_end_flush();
    $message = "";

    echo "../assets/operator_pdf/" . $file_name . ".pdf";
}
