<?php
require("../../../inc/connection.php");

require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!empty($_POST['bookval']) && !empty($_POST['sum_paid'])) {
    $bookpro_arr = array();
    $book_arr = array();
    $type_arr = array_unique($_POST['type_arr']);
    $query_pb = "SELECT BP.*,
            BK.id as bkID, BK.voucher_no as bkVoucher, BK.customer_firstname as bkCFname , BK.customer_lastname as bkCLname,
            BK.customer_mobile as bkCmobile, BK.customer_email as bkCemail,
            PCS.id as pcsID, PCS.name as pcsName,
            CM.id as cmID, CM.name as cmName, CM.address as cmAddress, CM.photo1 as cmPhoto
            FROM booking_products BP
            LEFT JOIN booking BK
                ON BP.booking = BK.id
            LEFT JOIN products_category_second PCS
                ON BP.products_category_second = PCS.id
            LEFT JOIN company CM
                ON BK.company = CM.id
            WHERE BP.booking = '" . $_POST['bookval'] . "'
            ORDER BY BP.travel_date DESC";
    $procedural_statement_pb = mysqli_prepare($mysqli_p, $query_pb);
    mysqli_stmt_execute($procedural_statement_pb);
    $result_bk = mysqli_stmt_get_result($procedural_statement_pb);
    $row_bk = mysqli_fetch_array($result_bk, MYSQLI_ASSOC);
    $book_arr = array(
        "voucher" => $row_bk['bkVoucher'],
        "date_payment" => $today,
        "customer_firstname" => $row_bk['bkCFname'],
        "customer_lastname" => $row_bk['bkCLname'],
        "customer_mobile" => $row_bk['bkCmobile'],
        "customer_email" => $row_bk['bkCemail'],
        "company_name" => $row_bk['cmName'],
        "company_address" => $row_bk['cmAddress'],
        "company_photo" => $row_bk['cmPhoto']
    );
    mysqli_stmt_execute($procedural_statement_pb);
    $result_pb = mysqli_stmt_get_result($procedural_statement_pb);
    while ($row_pb = mysqli_fetch_array($result_pb, MYSQLI_ASSOC)) {
        $bookpro_arr[] = array(
            "bp_type" => $row_pb['products_type'],
            "bp_name" => $row_pb['pcsName'],
            "bp_adults" => $row_pb['adults'],
            "bp_children" => $row_pb['children'],
            "bp_foc" => $row_pb['foc'],
            "bp_travel_date" => $row_pb['travel_date'],
            "bp_checkin_date" => $row_pb['checkin_date'],
            "bp_checkout_date" => $row_pb['checkout_date'],
            "bp_price_latest" => $row_pb['price_latest'],
            "bp_rate_2" => $row_pb['rate_2'],
            "bp_rate_4" => $row_pb['rate_4'],
            "bp_zones" => $row_pb['zones'],
            "bp_roomno" => $row_pb['roomno'],
            "bp_pickup" => $row_pb['pickup'],
            "bp_dropoff" => $row_pb['dropoff'],
            "bp_transfer" => $row_pb['transfer']
        );
    }

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
        'margin_left'  => '15',
        'margin_right' => '15',
        'margin_top'   => '76',
        'margin_bottom'   => '15',
    ]);

    $mpdf->SetHTMLHeader('
    <table width="700" border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
        <td height="100" colspan="3" width="50%" valign="top" align="left">
            <img src="' . $book_arr['company_photo'] . '" alt="Andaman Passion" title="Andaman Passion" width="120" border="0" />
            <br/>
        </td>
        <td height="100" colspan="3" width="50%" valign="top" align="left">
            <span style="font-weight:bold; font-size:16px;"> ' . $book_arr['company_name'] . ' </span> <br/>
            ' . $book_arr['company_address'] . ' <br/>
        </td>
    </tr>
    <tr bgcolor="#DDD">
        <td colspan="6" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span style="font-size:16px; padding:5px 5px;"> เอกสารยืนยันการจอง </span><br/>
            <span style="font-size:12px; padding:5px 5px;"> Voucher no. ' . $book_arr['voucher'] . ' </span><br/>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <table>
                <tr>
                    <td style="padding-right: 20px;">
                        <span style="font-size:10px;"> DATE </span><br/>
                        วันที่
                    </td>
                    <td> ' . date("d F Y", strtotime($book_arr['date_payment'])) . ' </td>
                </tr>
            </table>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <table>
                <tr>
                    <td style="padding-right: 20px;">
                        <span style="font-size:10px;"> GUEST NAME </span><br/>
                        ชื่อลูกค้า
                    </td>
                    <td> ' . $book_arr['customer_firstname'] . ' ' . $book_arr['customer_lastname'] . ' </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> Email : ' . $book_arr['customer_email'] . ' </span><br/>
            <span> TEL / เบอร์โทร : ' . $book_arr['customer_mobile'] . ' </span>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> Facebook : </span><br/>
            <span> Line : </span>
        </td>
    </tr>
    <tr>
        <td width="30%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ชื่อสินค้า </td>
        <td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> </td>
        <td width="20%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> วันที่ใช้บริการ </td>
        <td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ราคา </td>
        <td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> จำนวน </td>
        <td width="20%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวม </td>
    </tr>
    </table>
        ');

    $message = '';
    $message .= '<table width="700" border="0" cellspacing="0" cellpadding="1" align="center">';

    $total_price = 0;
    foreach ($bookpro_arr as $key => $value) {

        $message .= '<tr>';
        $message .= '<td width="30%" rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . $bookpro_arr[$key]['bp_name'] . ' ';
        $message .= ($bookpro_arr[$key]['bp_adults'] == 0 && $bookpro_arr[$key]['bp_children'] == 0) ? ' [FOC]' : '';
        $message .= '</td>';
        $message .= '<td width="10%" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ADL </td>';
        if ($bookpro_arr[$key]['bp_type'] != 4) {
            $message .= '<td width="20%" rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_travel_date'])) . ' </td>';
        } else {
            $message .= '<td width="20%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_checkin_date'])) . ' </td>';
        }
        $message .= '<td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
        $message .= !empty($bookpro_arr[$key]['bp_rate_2']) ? number_format($bookpro_arr[$key]['bp_rate_2']) : '-';
        $message .= '</td>';
        $message .= '<td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
        $message .= !empty($bookpro_arr[$key]['bp_adults']) ? number_format($bookpro_arr[$key]['bp_adults']) : '-';
        $message .= '</td>';
        $message .= '<td width="20%" rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
        $message .= !empty($bookpro_arr[$key]['bp_price_latest']) ? number_format($bookpro_arr[$key]['bp_price_latest']) : '-';
        $message .= '</td>';
        $message .= '</tr>';

        $message .= '<tr>';
        $message .= '<td width="10%" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> CHD </td>';
        if ($bookpro_arr[$key]['bp_type'] == 4) {
            $message .= '<td width="20%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_checkout_date'])) . ' </td>';
        }
        $message .= '<td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
        $message .= !empty($bookpro_arr[$key]['bp_rate_4']) ? number_format($bookpro_arr[$key]['bp_rate_4']) : '-';
        $message .= '</td>';
        $message .= '<td width="10%" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
        $message .= !empty($bookpro_arr[$key]['bp_children']) ? number_format($bookpro_arr[$key]['bp_children']) : '-';
        $message .= '</td>';
        $message .= '</tr>';

        $message .= '</tr>';
        if ($bookpro_arr[$key]['bp_transfer'] == 1) {
            $message .= '
            <tr>
                <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่รับ : ' . get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_pickup'], $mysqli_p) . ' </td>
                <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่ส่ง : ' . get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_dropoff'], $mysqli_p) . ' </td>
                <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> โซน : ' . get_value('zones', 'id', 'name', $bookpro_arr[$key]['bp_zones'], $mysqli_p) . ' </td>
                <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ห้องพัก : ' . $bookpro_arr[$key]['bp_roomno'] . ' </td>
            </tr>';
        }
    }

    $message .= '<tr>';
    $message .= '<td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวมทั้งหมด </td>';
    $message .= '<td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ';
    $message .= $_POST['total_balance'] <= 0 ? number_format($_POST['sum_paid']) : '-';
    $message .= ' </td>';
    $message .= '</tr>';

    $message .= '<tr>';
    $message .= '<td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> มัดจำ </td>';
    $message .= '<td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">';
    $message .= $_POST['total_balance'] > 0 ? number_format($_POST['sum_paid']) : '-';
    $message .= '</td>';
    $message .= '</tr>';

    $message .= '<tr>';
    $message .= '<td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> คงเลือ </td>';
    $message .= '<td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ';
    $message .= $_POST['total_balance'] > 0 ? number_format($_POST['total_balance']) : '-';
    $message .= '</td>';
    $message .= '</tr>';

    $message .= '<tr>
        <td colspan="6" style="text-align: left; border:1px solid #cdcdcd; padding:5px 5px;">
            ประเภทการจ่ายเงิน : <br/>
            <table width="100%">';
    foreach ($type_arr as $i => $value) {
        $message .= ($i == 0 || $i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 15) ? '<tr>' : '';
        $message .= '<td width="33%" style="font-size:11px;"> <img src="https://ams.andamanpassion.com/assets/images/check.png" width="12" /> ' . get_value('accounts', 'id', 'name', $value, $mysqli_p) . ' </td>';
        $message .= ($i == 2 || $i == 5 || $i == 8 || $i == 11 || $i == 14) ? '</tr>' : '';
    }
    $message .= '</table>
        </td>
    </tr>
    <tr>
        <td colspan="3" height="70" valign="top" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นลูกค้า </td>
        <td colspan="3" height="70" valign="top" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นผู้รับเงิน </td>
    </tr>
</table>';
    // echo $message;

    $mpdf->WriteHTML($message);
    $mpdf->Output("../../../assets/payment_pdf/" . $book_arr['voucher'] . ".pdf");
    ob_end_flush();
    $message = "";

    echo "../assets/payment_pdf/" . $book_arr['voucher'] . ".pdf";
}
