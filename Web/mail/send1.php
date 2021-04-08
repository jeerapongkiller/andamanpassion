<?php
require("../inc/connection.php");
$id = ['13', '8', '10', '9'];
echo $_POST['email'].'</br>';
foreach ($id as $prodval) {
    #--- Send Cancel Mail ---#
    $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname , products_category_first.supplier as pcfsupplier, products_type.id as ptypeid,
        products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
        booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.agent as bagent, booking.voucher_no as bvoucher_no,
        booking.sale_name as bsale_name, booking.customer_firstname as bcustomer_firstname, booking.customer_lastname as bcustomer_lastname, booking.customer_mobile as bcustomer_mobile,
        booking.customer_email as bcustomer_email, booking.agent_voucher as bagent_voucher
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
            WHERE booking_products.id = '$prodval'
        ";
    $query_products .= " ORDER BY booking_products.products_type ASC, booking_products.products_category_first ASC";
    $query_products .= " , booking_products.travel_date ASC";
    $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $row_products = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $companyname = "Andaman Passion";
    $email_passion = "rsvn@andamanpassion.com";
    $email_supplier = $_POST['email'];
    $sup_company = get_value('supplier', 'id', 'company', $row_products['pcfsupplier'], $mysqli_p);
    $title_email = "Cancellation booking details V. " . $row_products['bvoucher_no'];
    $agent = $row_products["bagent"];
    $bagent_voucher = !empty($row_products["bagent_voucher"]) ? $row_products["bagent_voucher"] : '-';
    $bvoucher_no = !empty($row_products["bvoucher_no"]) ? $row_products["bvoucher_no"] : '-';
    $bcustomer_firstname = !empty($row_products["bcustomer_firstname"]) ? $row_products["bcustomer_firstname"] : '-';
    $bcustomer_lastname = !empty($row_products["bcustomer_lastname"]) ? $row_products["bcustomer_lastname"] : '-';
    $bcustomer_mobile = !empty($row_products["bcustomer_mobile"]) ? $row_products["bcustomer_mobile"] : '-';
    $bcustomer_email = !empty($row_products["bcustomer_email"]) ? $row_products["bcustomer_email"] : '-';
    $name_by = get_value('employee', 'id', 'name', $_SESSION["admin"]["id"], $mysqli_p);
    $dropoff = !empty($row_products["dropoff"]) ? get_value('dropoff', 'id', 'name', $row_products["dropoff"], $mysqli_p) : 'N/A';
    $head  = "";
    $message = "";
    #---- SEND EMAIL : FROM SYSTEM TO CUSTOMER ----#
    $head  = "MIME-Version: 1.0\r\n";
    $head .= "Content-type: text/html; charset=utf-8\r\n";
    $head .= "From: " . $email_passion . "\n";
    $head .= "To: " . $email_supplier . "\n";
    $head .= "X-Priority: 1 (High)\n";
    $head .= "X-Mailer: <phuketsolution.com>\n";
    $head .= "MIME-Version: 1.0\n";

    #--- Header ---#
    // echo "<div class=\"col-12\"> <div class=\"card\"> <div class=\"card-body\">".$message."</div> </div> </div>";
    $colspan = $row_products["ptypeid"] == 4 ? '6' : '8';
    $message = "";


    $message = "
    <!DOCTYPE html>
    <html>
    <head>
    <body>
    <style>
        table {
            font-size: 1em;
            background: #CCC;
        }
        tr {
            padding:5px 5px;
        }
        td {
            background: #FFF;
            padding:5px 5px;
        }
    </style>
    ";

    $message .= "<table width='700' cellspacing='2' cellpadding='3'>";
    $message .= "<tr align='center' bgcolor='#FFF'>";
    $message .= "<td><b style='color:#FF0000; style='font-size:1.5em;'> รายละเอียดสินค้า ยกเลิก </b></td>";
    $message .= "</tr>";
    $message .= "<tr bgcolor='#FFF'>";
    $message .= "<td valign='top'>";
    $message .= "<div valign='top'><img src='https://www.phuketsolution.com/demo/andaman-passion-system/picture/logo/logo.png' alt='Andaman Passion' title='Andaman Passion' width='150' /></div>";
    $message .= "<div valign='top' align='right'><b>Andaman Passion Co., Ltd.</b><br />";
    $message .= "59/75 Moo 4 Phuket Villa Kathu,<br />Phraphuketkeaw Rd, Kathu, Phuket 83120<br />";
    $message .= "Tel. 076-390-577&nbsp;&nbsp;&nbsp;&nbsp;Email: rsvn@andamanpassion.com<br />";
    $message .= "www.andamanpassion.com<br />";
    $message .= "Hotline: 062-956-6124, 062-956-2416 (09:00 am - 08:00 pm)<br /></div>";
    $message .= "</td>";
    $message .= "</tr>";
    $message .= "<tr>";
    $message .= "<td>";
    $message .= "<span style='font-size:1.5em;'><b> รายละเอียดลูกค้า </b></span><br />";
    if ($agent > 0) {
        $message .= "<b>เอเย่นต์ :</b> " . get_value('agent', 'id', 'company', $agent, $mysqli_p) . " <br />";
        if ($bagent_voucher != '') {
            $message .= "<span style='font-size:1em; font-weight: bold;'> Voucher No. (เอเย่นต์) :</span> " . $bagent_voucher . " <br />";
        }
    }
    $message .= "<span style='font-size:0.75em;'>Voucher No. :</span> " . $bvoucher_no . " <br />";
    $message .= "ชื่อ : " . $bcustomer_firstname . " <br />";
    $message .= "นามสกุล : " . $bcustomer_lastname . " <br />";
    $message .= "เบอร์โทร : " . $bcustomer_mobile . " <br />";
    $message .= "<span style='font-size:0.75em;'>Email </span> : " . $bcustomer_email . " <br />";
    $message .= "</td>";
    $message .= "</tr>";
    $message .= "<tr>";
    $message .= "<td><span style='font-size:1.5em;'><b> รายละเอียดการจอง </b></span><br />";
    $message .= "<b> " . $row_products["pcfname"] . " </b><br />";
    $message .= "</td>";
    $message .= "</tr>";
    $message .= "</table>";

    $message .= "<table width='700' cellspacing='2' cellpadding='3'>";

    $message .= "<tr bgcolor='#FFF'>";
    $message .= "<td align='center'><b> สินค้า </b></td>";
    if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
        $message .= "<td align='center'><b> วันที่เที่ยว </b></td>";
        $message .= "<td align='center'><b> ผู้ใหญ่ </b></td>";
        $message .= "<td align='center'><b> เด็ก </b></td>";
        $message .= "<td align='center'><b> ทารก </b></td>";
        $message .= "<td align='center'><b> FOC </b></td>";
        $message .= "<td align='center'><b> สถานที่รับ </b></td>";
        $message .= "<td align='center'><b> สถานที่ส่ง </b></td>";
    }
    if ($row_products["ptypeid"] == 3) {
        $message .= "<td align='center'><b> วันที่เที่ยว </b></td>";
        $message .= "<td align='center'><b> จำนวนคัน </b></td>";
        $message .= "<td align='center'><b> จำนวนชั่วโมง </b></td>";
        $message .= "<td align='center'><b> สถานที่รับ </b></td>";
        $message .= "<td align='center'><b> เวลารับ </b></td>";
        $message .= "<td align='center'><b> สถานที่ส่ง </b></td>";
        $message .= "<td align='center'><b> เวลาส่ง </b></td>";
    }
    if ($row_products["ptypeid"] == 4) {
        $message .= "<td align='center'><b> วันที่เช็คอิน </b></td>";
        $message .= "<td align='center'><b> วันที่เช็คเอาท์ </b></td>";
        $message .= "<td align='center'><b> จำนวนห้อง </b></td>";
        $message .= "<td align='center'><b> จำนวนเตียงเสริม </b></td>";
        $message .= "<td align='center'><b> จำนวนแชร์เตียง </b></td>";
    }
    $message .= "</tr>";
    $message .= "<tr bgcolor='#FFF'>";
    $message .= "<td align='left'> " . $row_products["pcsname"] . " </td>";
    if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
        $message .= "<td align='center'> " . date("d F Y", strtotime($row_products["travel_date"])) . " </td>";
        $message .= "<td align='center'>" . number_format($row_products["adults"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["children"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["infant"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["foc"]) . "</td>";
        $message .= "<td align='center'>" . $row_products["pickup"] . "</td>";
        $message .= "<td align='center'>" . $dropoff . "</td>";
    }
    if ($row_products["ptypeid"] == 3) {
        $message .= "<td align='center'>" . date("d F Y", strtotime($row_products["travel_date"])) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["no_cars"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["no_hours"]) . "</td>";
        $message .= "<td align='center'>" . $row_products["pickup"] . "</td>";
        $message .= "<td align='center'>" . $row_products["pickup_time"] . "</td>";
        $message .= "<td align='center'>" . $dropoff . "</td>";
        $message .= "<td align='center'>" . date('H:i', strtotime($row_products["dropoff_time"])) . "</td>";
    }
    if ($row_products["ptypeid"] == 4) {
        $message .= "<td align='center'>" . date("d F Y", strtotime($row_products["checkin_date"])) . "</td>";
        $message .= "<td align='center'>" . date("d F Y", strtotime($row_products["checkout_date"])) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["no_rooms"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["extra_beds"]) . "</td>";
        $message .= "<td align='center'>" . number_format($row_products["share_bed"]) . "</td>";
    }
    $message .= "</tr>";
    $message .= "<tr bgcolor='#FFF'>";
    $message .= "<td colspan=' " . $colspan . " ' align='left'><b>รายละเอียด : </b> " . nl2br($row_products["notes"]) . " <br /></td>";
    $message .= "</tr>";

    $message .= "</table>";
    $message .= "<table width='700' cellspacing='2' cellpadding='3'>";
    $message .= "<tr bgcolor='#FFF'>";
    $message .= "<td colspan=' " . $colspan . " ' align='left'>";
    $message .= "<span> Remark </span>: ยกเลิกสินค้า <br /><br />";
    $message .= "ส่งโดย : " . $name_by . " ";
    $message .= "</td>";
    $message .= "</tr>";
    $message .= "</table>";

    $message .= "
    </body>
    </html>
    ";

    echo $message.'</br></br>';

    # --- Send Email ---#
    if ((mail($email_supplier, $title_email, $message, $head, "-f$email_passion")) == false) {
        echo "<h1 color='#FF0000'>Contact to Phuket Solution!</h1>";
        exit;
    }
    $message = "";
}
mysqli_close($mysqli_p);
