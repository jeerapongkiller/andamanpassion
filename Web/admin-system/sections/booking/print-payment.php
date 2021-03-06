<?php
if (!empty($_GET['id'])) {
    $message = '';
    $message .= '<table width="700" border="0" cellspacing="0" cellpadding="1" align="center">
                <tr>
                <td colspan="1" valign="top" align="left">
                <img src="https://ams.andamanpassion.com/assets/images/logo/passion-vacation.png" alt="Andaman Passion" title="Andaman Passion" width="120" border="0" />
                </br>
                </td>
                <td colspan="1" align="left">
                    176 หมูที่ 2 ตำบลไม้ขาว อำเภอถลาง จังหวัดภูเก็ต </br>
                    Tel.092-2239078 Fax.076-355988 </br>
                    เลขประจำตัวผู้เสียภาษี/Tax ID 0835562017311 สำนักงานใหญ่ </br>
                </td>
                </tr>
                <tr>
                <td colspan="2" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
                    <span> โปรแกรม Products </span>
                </td>
                </tr>
                <tr>
                <td colspan="1" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
                    <span> โปรแกรม Products </span>
                </td>
                <td colspan="1" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
                    <span> โปรแกรม Products </span>
                </td>
                </tr>
                </table>';
    //echo $message;
}
?>

<?php
$bookpro_arr = array();
$book_arr = array();
// $query_pb = "SELECT PB.*,
//     BK.id as bkID, BK.voucher_no as bkVoucher, BK.customer_firstname as bkCFname , BK.customer_lastname as bkCLname,
//     BK.customer_mobile as bkCmobile, BK.customer_email as bkCemail,
//     BP.id as bpID, BP.products_category_second as bpPCS, BP.products_type as bpType, 
//     BP.travel_date as bpTravel_date, BP.checkin_date as bpCheckin_date,
//     PCS.id as pcsID, PCS.name as pcsName,
//     CM.id as cmID, CM.name as cmName, CM.address as cmAddress, CM.photo1 as cmPhoto
//     FROM payments_booking PB
//     LEFT JOIN booking BK
//        ON PB.booking = BK.id
//     LEFT JOIN booking_products BP
//        ON PB.booking_products = BP.id
//     LEFT JOIN products_category_second PCS
//        ON BP.products_category_second = PCS.id
//     LEFT JOIN company CM
//        ON BK.company = CM.id
//     WHERE PB.booking = '" . $_GET['id'] . "' ";
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
            WHERE BP.booking = '" . $_GET['id'] . "'
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
?>

<table width="700" border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
        <td colspan="3" width="50%" valign="top" align="left">
            <img src="<?php echo $book_arr['company_photo']; ?>" alt="Andaman Passion" title="Andaman Passion" width="120" border="0" />
            </br>
        </td>
        <td colspan="3" width="50%" valign="top" align="left">
            <span style="font-weight:bold; font-size:16px;"> <?php echo $book_arr['company_name']; ?> </span> </br>
            <?php echo $book_arr['company_address']; ?> </br>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span style="font-size:16px; padding:5px 5px;"> เอกสารยืนยันการจอง </span></br>
            <span style="font-size:12px; padding:5px 5px;"> Voucher no. <?php echo $book_arr['voucher']; ?> </span></br>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> วันที่ : <?php echo date("d F Y", strtotime($book_arr['date_payment'])); ?> </span>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> ชื่อลูกค้า : <?php echo $book_arr['customer_firstname'] . ' ' . $book_arr['customer_lastname']; ?> </span>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> อีเมล์ : <?php echo $book_arr['customer_email']; ?> </span></br>
            <span> เบอร์โทร : <?php echo $book_arr['customer_mobile']; ?> </span>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> Facebook : </span></br>
            <span> Line : </span>
        </td>
    </tr>
    <!-- Head Start -->
    <tr>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ชื่อสินค้า </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> วันที่ใช้บริการ </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ราคา </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> จำนวน </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวม </td>
    </tr>
    <!-- Head End -->
    <!-- Products Start -->
    <?php 
        $total_price = 0;
        foreach ($bookpro_arr as $key => $value) {
        
    ?>
        <tr>
            <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo $bookpro_arr[$key]['bp_name']; ?> </td>
            <!-- FOC -->
            <?php if ($bookpro_arr[$key]['bp_adults'] == 0 && $bookpro_arr[$key]['bp_children'] == 0) { ?>
                <td rowspan="2" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> FOC </td>
            <?php } else { ?>
                <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ADL </td>
            <?php } ?>
            <!-- Type Hotel -->
            <?php if ($bookpro_arr[$key]['bp_type'] == 4) { ?>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo date("d F Y", strtotime($bookpro_arr[$key]['bp_checkin_date'])); ?> </td>
            <?php } else { ?>
                <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo date("d F Y", strtotime($bookpro_arr[$key]['bp_travel_date'])); ?> </td>
            <?php } ?>
            <!-- FOC -->
            <?php if ($bookpro_arr[$key]['bp_adults'] == 0 && $bookpro_arr[$key]['bp_children'] == 0) { ?>
                <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 0 </td>
                <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo $bookpro_arr[$key]['bp_foc']; ?> </td>
            <?php } else { ?>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo number_format($bookpro_arr[$key]['bp_rate_2']); ?> </td>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo $bookpro_arr[$key]['bp_adults']; ?> </td>
            <?php } ?>
            <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo number_format($bookpro_arr[$key]['bp_price_latest']); ?> </td>
        </tr>
        <tr>
            <!-- FOC -->
            <?php if ($bookpro_arr[$key]['bp_adults'] != 0) { ?>
                <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> CHD </td>
            <?php } ?>
            <!-- Type Hotel -->
            <?php if ($bookpro_arr[$key]['bp_type'] == 4) { ?>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo date("d F Y", strtotime($bookpro_arr[$key]['bp_checkout_date'])); ?> </td>
            <?php } ?>
            <!-- FOC -->
            <?php if ($bookpro_arr[$key]['bp_adults'] != 0) { ?>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo number_format($bookpro_arr[$key]['bp_rate_4']); ?> </td>
                <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> <?php echo $bookpro_arr[$key]['bp_children']; ?> </td>
            <?php } ?>
        </tr>
        <?php if ($bookpro_arr[$key]['bp_transfer'] == 1) { ?>
            <tr>
                <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่รับ : <?php echo get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_pickup'], $mysqli_p); ?> </td>
                <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่ส่ง : <?php echo get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_dropoff'], $mysqli_p); ?> </td>
                <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> โซน : <?php echo $bookpro_arr[$key]['bp_zones']; ?> </td>
                <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ห้องพัก : <?php echo $bookpro_arr[$key]['bp_roomno']; ?> </td>
            </tr>
        <?php } ?>
    <?php } ?>
    <!-- Products End -->
    <!-- <tr>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> test1 </td>
        <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ADL </td>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 20/24/0201 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 2300 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 2 </td>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 5000 </td>
    </tr>
    <tr>
        <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> CHD </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 200 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 1 </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่รับ : </td>
        <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่ส่ง : </td>
        <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> โซน : </td>
        <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ห้องพัก : </td>
    </tr> -->
    <!-- Footer Start -->
    <tr>
        <td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวมทั้งหมด </td>
        <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 7000 </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            ประเภทการจ่ายเงิน :
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นลูกค้า </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นผู้รับเงิน </td>
    </tr>
    <!-- Footer End -->
</table>



<table width="700" border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
        <td colspan="3" width="50%" valign="top" align="left">
            <img src="' . $book_arr['company_photo']. '" alt="Andaman Passion" title="Andaman Passion" width="120" border="0" />
            </br>
        </td>
        <td colspan="3" width="50%" valign="top" align="left">
            <span style="font-weight:bold; font-size:16px;"> ' . $book_arr['company_name']. ' </span> </br>
            ' . $book_arr['company_address']. ' </br>
        </td>
    </tr>
    <tr bgcolor="#DDD">
        <td colspan="6" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span style="font-size:16px; padding:5px 5px;"> เอกสารยืนยันการจอง </span></br>
            <span style="font-size:12px; padding:5px 5px;"> Voucher no. ' . $book_arr['voucher']. ' </span></br>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <table>
                <tr>
                    <td style="padding-right: 20px;">
                        <small>DATE</small></br>
                        วันที่
                    </td>
                    <td> ' . date("d F Y", strtotime($book_arr['date_payment'])). ' </td>
                </tr>
            </table>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <table>
                <tr>
                    <td style="padding-right: 20px;">
                        <small>GUEST NAME</small></br>
                        ชื่อลูกค้า
                    </td>
                    <td> ' . $book_arr['customer_firstname'] . ' ' . $book_arr['customer_lastname']. ' </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> Email : ' . $book_arr['customer_email']. ' </span></br>
            <span> TEL / เบอร์โทร : ' . $book_arr['customer_mobile']. ' </span>
        </td>
        <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
            <span> Facebook : </span></br>
            <span> Line : </span>
        </td>
    </tr>
    <!-- Head Start -->
<tr>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ชื่อสินค้า </td>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> </td>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> วันที่ใช้บริการ </td>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ราคา </td>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> จำนวน </td>
    <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวม </td>
</tr>
<!-- Head End -->
<!-- Products Start -->
<?php
$total_price = 0;
foreach ($bookpro_arr as $key => $value) {
?>
    <tr>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . $bookpro_arr[$key]['bp_name']. ' </td>
        <!-- FOC -->
        <?php if ($bookpro_arr[$key]['bp_adults'] == 0 && $bookpro_arr[$key]['bp_children'] == 0) { ?>
            <td rowspan="2" style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> FOC </td>
        <?php } else { ?>
            <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ADL </td>
        <?php } ?>
        <!-- Type Hotel -->
        <?php if ($bookpro_arr[$key]['bp_type'] == 4) { ?>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_checkin_date'])). ' </td>
        <?php } else { ?>
            <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_travel_date'])). ' </td>
        <?php } ?>
        <!-- FOC -->
        <?php if ($bookpro_arr[$key]['bp_adults'] == 0 && $bookpro_arr[$key]['bp_children'] == 0) { ?>
            <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 0 </td>
            <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . $bookpro_arr[$key]['bp_foc']. ' </td>
        <?php } else { ?>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . number_format($bookpro_arr[$key]['bp_rate_2']). ' </td>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . $bookpro_arr[$key]['bp_adults']. ' </td>
        <?php } ?>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . number_format($bookpro_arr[$key]['bp_price_latest']). ' </td>
    </tr>
    <tr>
        <!-- FOC -->
        <?php if ($bookpro_arr[$key]['bp_adults'] != 0) { ?>
            <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> CHD </td>
        <?php } ?>
        <!-- Type Hotel -->
        <?php if ($bookpro_arr[$key]['bp_type'] == 4) { ?>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . date("d F Y", strtotime($bookpro_arr[$key]['bp_checkout_date'])). ' </td>
        <?php } ?>
        <!-- FOC -->
        <?php if ($bookpro_arr[$key]['bp_adults'] != 0) { ?>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . number_format($bookpro_arr[$key]['bp_rate_4']). ' </td>
            <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . $bookpro_arr[$key]['bp_children']. ' </td>
        <?php } ?>
    </tr>
    <?php if ($bookpro_arr[$key]['bp_transfer'] == 1) { ?>
        <tr>
            <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่รับ : ' . get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_pickup'], $mysqli_p). ' </td>
            <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่ส่ง : ' . get_value('place', 'id', 'name', $bookpro_arr[$key]['bp_dropoff'], $mysqli_p). ' </td>
            <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> โซน : ' . $bookpro_arr[$key]['bp_zones']. ' </td>
            <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ห้องพัก : ' . $bookpro_arr[$key]['bp_roomno']. ' </td>
        </tr>
    <?php } ?>
<?php } ?>
<!-- Products End -->
<!-- <tr>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> test1 </td>
        <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ADL </td>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 20/24/0201 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 2300 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 2 </td>
        <td rowspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 5000 </td>
    </tr>
    <tr>
        <td style="text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> CHD </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 200 </td>
        <td style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> 1 </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่รับ : </td>
        <td colspan="2" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> สถานที่ส่ง : </td>
        <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> โซน : </td>
        <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ห้องพัก : </td>
    </tr> -->
<!-- Footer Start -->
<tr>
    <td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> รวมทั้งหมด </td>
    <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . number_format($_POST['sum_paid']) ?> </td>
</tr>
<tr>
    <td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> มัดจำ </td>
    <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> </td>
</tr>
<tr>
    <td colspan="5" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> คงเลือ </td>
    <td colspan="1" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ' . number_format($_POST['total_balance']) ?> </td>
</tr>
<tr>
    <td colspan="6" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;">
        ประเภทการจ่ายเงิน : </br>
        <table width="100%">
            <?php foreach ($_POST['type_arr'] as $i => $value) {
                echo ($i == 0 || $i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 15) ? '<tr>' : '';
                echo '<td width="33%"> <i class="ti-check-box"></i> ' . get_value('accounts', 'id', 'name', $value, $mysqli_p) . ' </td>';
                echo ($i == 2 || $i == 5 || $i == 8 || $i == 11 || $i == 14) ? '</tr>' : '';
            } ?>
        </table>
    </td>
</tr>
<tr>
    <td colspan="3" height="70" valign="top" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นลูกค้า </td>
    <td colspan="3" height="70" valign="top" style="text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;"> ลายเซ็นผู้รับเงิน </td>
</tr>
<!-- Footer End -->
</table>