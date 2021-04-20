<?php
require("../../../inc/connection.php");

require_once __DIR__ . '/../../../assets/mpdf/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!empty($_POST["travel_date"])) {
    $bind_types = "";
    $params = array();
    $first = 0;
    $second = 0;
    $numrow_realtime = 1;
    $message = '';
    $date = time();

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

    $query = "SELECT BP.*,
                    BO.id as boID, BO.voucher_no as boVoucher, BO.agent as boAgent, BO.customer_firstname as boFirstname, BO.customer_lastname as boLastname,
                    PCF.id as pcfID, PCF.supplier as pcfSupplier, PCF.products_type as pcfPro_type,
                    PCS.id as pcsID, PCS.name as pcsName,
                    SP.id as spID, SP.company as spCompany,
                    PL.id as plID, PL.name as plName
                    FROM booking_products BP
                    LEFT JOIN booking BO
                        ON BP.booking = BO.id
                    LEFT JOIN products_category_first PCF
                        ON BP.products_category_first = PCF.id
                    LEFT JOIN products_category_second PCS
                        ON BP.products_category_second = PCS.id
                    LEFT JOIN supplier SP
                        ON PCF.supplier = SP.id
                    LEFT JOIN place PL
                        ON BP.dropoff = PL.id
                    WHERE BP.id > '0' AND BP.travel_date = '" . $_POST["travel_date"] . "' OR BP.checkin_date = '" . $_POST["travel_date"] . "'
            ";
    $query .= " ORDER BY PCS.id, BP.dropoff ASC";
    // echo $query;
    $procedural_statement = mysqli_prepare($mysqli_p, $query);

    // Check error query
    if ($procedural_statement == false) {
        die("<pre>" . mysqli_error($mysqli_p) . PHP_EOL . $query . "</pre>");
    }

    if ($bind_types != "") {
        mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
    }

    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        #---- Head ----#
        if ($first != $row["pcsID"] || $second != $row["dropoff"]) {
            #---- Foot ----#
            $message .= $numrow_realtime != 1 ? '</table>' : '';
            $first = $row["pcsID"];
            $second = $row["dropoff"];
            $mpdf->SetHTMLHeader(' 
                <table width="1000" border="0" cellspacing="0" cellpadding="1">
                <tr>
                <td colspan="13" style="text-align: center; font-size:18px;">
                <b>' . $row["spCompany"] . ' - Andaman Passion Transfer List</b><br />
                <b>' . $row["pcsName"] . ' ' . date("d F Y", strtotime($_POST["travel_date"])) . ' (รถรับ - ส่ง' . $row["plName"] . ')</b><br /><br /><br />
                </td>
                </tr>
                <tr>
                <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Voucher </td>
                <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Agency </td>
                <td width="159" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Customer`s Name </td>
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

            $message .= '<table width="1000" border="0" cellspacing="0" cellpadding="1">';
        }
        $message .= '
            <tr align="center">
                <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["boVoucher"] . ' </td>
                <td width="94" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . get_value('agent', 'id', 'company', $row["boAgent"], $mysqli_p) . ' </td>
                <td width="159" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["boFirstname"] . ' ' . $row["boLastname"] . ' </td>
                <td width="44" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["adults"] . ' </td>
                <td width="53" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["children"] . ' </td>
                <td width="45" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["infant"] . ' </td>
                <td width="52" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["foc"] . ' </td>
                <td width="47" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["vans"] . ' </td>
                <td width="57" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["dropoff_time"] . ' </td>
                <td width="85" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . get_value('place', 'id', 'name', $row["pickup"], $mysqli_p) . ' </td>
                <td width="84" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . $row["roomno"] . ' </td>
                <td width="82" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> ' . get_value('zones', 'id', 'name', $row["zones"], $mysqli_p) . ' </td>
                <td width="140" style="text-align: center; border:1px solid #cdcdcd; font-size:16px; padding:5px 5px;"> Send </td>
            </tr>
        ';
        $numrow_realtime++;
    }
    #---- Foot ----#
    $message .= $numrow_realtime != 1 ? '</table>' : '';
    // echo $message;
    
    $mpdf->WriteHTML($message);
    $mpdf->Output("netsheet-$date.pdf",'I');
}
