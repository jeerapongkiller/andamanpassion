<?php require("../inc/connection.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Theme Made By www.w3schools.com - No Copyright -->
  <title>Bootstrap Theme The Band</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    body {
      font: 400 15px/1.8 Lato, sans-serif;
      color: #777;
    }

    h3,
    h4 {
      margin: 10px 0 30px 0;
      letter-spacing: 10px;
      font-size: 20px;
      color: #111;
    }

    .container {
      padding: 80px 120px;
    }

    .person {
      border: 10px solid transparent;
      margin-bottom: 25px;
      width: 80%;
      height: 80%;
      opacity: 0.7;
    }

    .person:hover {
      border-color: #f1f1f1;
    }

    .carousel-inner img {
      -webkit-filter: grayscale(90%);
      filter: grayscale(90%);
      /* make all photos black and white */
      width: 100%;
      /* Set width to 100% */
      margin: auto;
    }

    .carousel-caption h3 {
      color: #fff !important;
    }

    @media (max-width: 600px) {
      .carousel-caption {
        display: none;
        /* Hide the carousel text when the screen is less than 600 pixels wide */
      }
    }

    .bg-1 {
      background: #2d2d30;
      color: #bdbdbd;
    }

    .bg-1 h3 {
      color: #fff;
    }

    .bg-1 p {
      font-style: italic;
    }

    .list-group-item:first-child {
      border-top-right-radius: 0;
      border-top-left-radius: 0;
    }

    .list-group-item:last-child {
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }

    .thumbnail {
      padding: 0 0 15px 0;
      border: none;
      border-radius: 0;
    }

    .thumbnail p {
      margin-top: 15px;
      color: #555;
    }

    .btn {
      padding: 10px 20px;
      background-color: #333;
      color: #f1f1f1;
      border-radius: 0;
      transition: .2s;
    }

    .btn:hover,
    .btn:focus {
      border: 1px solid #333;
      background-color: #fff;
      color: #000;
    }

    .modal-header,
    h4,
    .close {
      background-color: #333;
      color: #fff !important;
      text-align: center;
      font-size: 30px;
    }

    .modal-header,
    .modal-body {
      padding: 40px 50px;
    }

    .nav-tabs li a {
      color: #777;
    }

    .navbar {
      font-family: Montserrat, sans-serif;
      margin-bottom: 0;
      background-color: #2d2d30;
      border: 0;
      font-size: 11px !important;
      letter-spacing: 4px;
      opacity: 0.9;
    }

    .navbar li a,
    .navbar .navbar-brand {
      color: #d5d5d5 !important;
    }

    .navbar-nav li a:hover {
      color: #fff !important;
    }

    .navbar-nav li.active a {
      color: #fff !important;
      background-color: #29292c !important;
    }

    .navbar-default .navbar-toggle {
      border-color: transparent;
    }

    .open .dropdown-toggle {
      color: #fff;
      background-color: #555 !important;
    }

    .dropdown-menu li a {
      color: #000 !important;
    }

    .dropdown-menu li a:hover {
      background-color: red !important;
    }

    footer {
      background-color: #2d2d30;
      color: #f5f5f5;
      padding: 32px;
    }

    footer a {
      color: #f5f5f5;
    }

    footer a:hover {
      color: #777;
      text-decoration: none;
    }

    .form-control {
      border-radius: 0;
    }

    textarea {
      resize: none;
    }
  </style>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

  <!-- Container (Contact Section) -->
  <div id="contact" class="container">
    <h3 class="text-center">Contact</h3>

    <div class="row">
      <div class="col-md-12">
        <div class="col-sm-12 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 form-group">
            <button class="btn pull-right" type="submit">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="row">
  <div class="col-md-12">
    <?php
    require_once __DIR__ . '/../assets/mpdf/vendor/autoload.php';

    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $id = ['13', '8', '10', '9'];
    foreach ($id as $prodval) {

      #------- PDF Page -----------#
      ob_start();
      $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
          __DIR__ . '/../assets/tmp/ttfontdata',
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
WHERE booking_products.id = '$prodval' AND booking_products.trash_deleted = '0'
";
      $query_products .= " ORDER BY booking_products.products_type ASC, booking_products.products_category_first ASC";
      $query_products .= " , booking_products.travel_date ASC";
      $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
      mysqli_stmt_execute($procedural_statement);
      $result = mysqli_stmt_get_result($procedural_statement);
      $row_products = mysqli_fetch_array($result, MYSQLI_ASSOC);

      #---- Set for Mail Html ----#
      $companyname = "Andaman Passion";
      $email_passion = "monzero753@gmail.com";
      $email_supplier = "morn@phuketsolution.com";
      $sup_company = get_value('supplier', 'id', 'company', $row_products['pcfsupplier'], $mysqli_p);
      $title_email = $row_products["status_email"] == '2' ? "Booking details V. " . $row_products["bvoucher_no"] : "Revised booking details V.  " . $row_products["bvoucher_no"];
      $agent = $row_products["bagent"];
      $bagent_voucher = !empty($row_products["bagent_voucher"]) ? $row_products["bagent_voucher"] : '-';
      $bvoucher_no = !empty($row_products["bvoucher_no"]) ? $row_products["bvoucher_no"] : '-';
      $bcustomer_firstname = !empty($row_products["bcustomer_firstname"]) ? $row_products["bcustomer_firstname"] : '-';
      $bcustomer_lastname = !empty($row_products["bcustomer_lastname"]) ? $row_products["bcustomer_lastname"] : '-';
      $bcustomer_mobile = !empty($row_products["bcustomer_mobile"]) ? $row_products["bcustomer_mobile"] : '-';
      $bcustomer_email = !empty($row_products["bcustomer_email"]) ? $row_products["bcustomer_email"] : '-';
      $dropoff = !empty($row_products["dropoff"]) ? get_value('dropoff', 'id', 'name', $row_products["dropoff"], $mysqli_p) : 'N/A';
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
      $message .= "<table width='700' border='0' cellspacing='0' cellpadding='1' align='center' style='font-size:14px;'>";
      $message .= "<tr>";
      $message .= "<td valign='top' colspan='2'>";
      $message .= "<img src='https://www.phuketsolution.com/demo/andaman-passion-system/picture/logo/logo.png' alt='Andaman Passion' title='Andaman Passion' width='150' border='0' />";
      $message .= "</td>";
      $message .= "<td valign='top' colspan='6' align='right'>";
      $message .= "<b>Andaman Passion Co., Ltd.</b><br />";
      $message .= "59/75 Moo 4 Phuket Villa Kathu,<br />Phraphuketkeaw Rd, Kathu, Phuket 83120<br />";
      $message .= "Tel. 076-390-577&nbsp;&nbsp;&nbsp;&nbsp;Email: rsvn@andamanpassion.com<br />";
      $message .= "www.andamanpassion.com<br />";
      $message .= "Hotline: 062-956-6124, 062-956-2416 (09:00 am - 08:00 pm)<br />";
      $message .= "</td>";
      $message .= "<td colspan='8' align='center'>&nbsp;</td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<td colspan='8' align='center'><hr></td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<td colspan='8'><span style='font-size: 1.5em'>รายละเอียดลูกค้า</span></td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<th colspan='8' style='border:1px solid #cdcdcd; padding:10px 15px;'>";
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
      $message .= "</th>";
      $message .= "<th colspan='8' align='center'>&nbsp;</th>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<td colspan='8' align='center'><hr></td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<td colspan='8'><span style='font-size: 1.5em'>รายละเอียดการจอง</span></td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<td colspan='8' align='left'><h5><b>" . $row_products["pcfname"] . "</b></h5></td>";
      $message .= "</tr>";
      #----- Start Table ------#
      $message .= "<tr>";
      $message .= "<td colspan='8'>";
      $message .= "<table width='700' border='0' cellspacing='0' cellpadding='1' align='center' style='font-size:14px;'>";
      #----- Start Thead of Table ------#
      $message .= "<tr>";
      $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> สินค้า </th>";
      if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> วันที่เที่ยว </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> ผู้ใหญ่ </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> เด็ก </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> ทารก </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> FOC </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> สถานที่รับ </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> สถานที่ส่ง </th>";
      }
      if ($row_products["ptypeid"] == 3) {
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> วันที่เที่ยว </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> จำนวนคัน </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> จำนวนชั่วโมง </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> สถานที่รับ </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> เวลารับ </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> สถานที่ส่ง </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> เวลาส่ง </th>";
      }
      if ($row_products["ptypeid"] == 4) {
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> วันที่เช็คอิน </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> วันที่เช็คเอาท์ </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> จำนวนห้อง </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> จำนวนเตียงเสริม </th>";
        $message .= "<th style='font-weight: bold; text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'> จำนวนแชร์เตียง </th>";
      }
      $message .= "</tr>";
      #------- End Thead of Table ------#
      #------- Start Tbody of Table ------#
      $message .= "<tr>";
      $message .= "<td style='text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $row_products["pcsname"] . "</td>";
      if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["travel_date"])) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["adults"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["children"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["infant"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["foc"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $row_products["pickup"] . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $dropoff . "</td>";
      }
      if ($row_products["ptypeid"] == 3) {
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["travel_date"])) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["no_cars"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["no_hours"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $row_products["pickup"] . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $row_products["pickup_time"] . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . $dropoff . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . date('H:i', strtotime($row_products["dropoff_time"])) . "</td>";
      }
      if ($row_products["ptypeid"] == 4) {
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["checkin_date"])) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . date("d F Y", strtotime($row_products["checkout_date"])) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["no_rooms"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["extra_beds"]) . "</td>";
        $message .= "<td style='text-align: center; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'>" . number_format($row_products["share_bed"]) . "</td>";
      }
      $message .= "</tr>";
      #------- End Tbody of Table ------#
      #------- Start Detail of Table ------#
      $message .= "<tr>";
      $message .= "<th colspan='8' style='text-align: left; border:1px solid #cdcdcd; font-size:12px; padding:5px 5px;'><b>รายละเอียด : </b>" . nl2br($row_products["notes"]) . "<br /></th>";
      $message .= "</tr>";
      #------- End Detail of Table ------#
      $message .= "</table>";
      $message .= "</td>";
      $message .= "</tr>";
      #------- End Table ------#
      $message .= "<tr>";
      $message .= "<td colspan='8'><hr></td>";
      $message .= "</tr>";
      $message .= "<tr>";
      $message .= "<th colspan='8' style='border:1px solid #cdcdcd; padding:10px 15px;'>";
      $message .= "<b>Remark : </b> Test <br /><br />";
      $message .= "<b>ส่งโดย : </b> Test ";
      $message .= "</th>";
      $message .= "<th colspan='8' align='center'>&nbsp;</th>";
      $message .= "</tr>";
      $message .= "</table>";

      echo $message . '<br/><br/>';

      echo $query_products.'<br/>';

      // $mpdf->WriteHTML($message);
      // $mpdf->Output("../assets/test/Test_" . $prodval . ".pdf");
      // ob_end_flush();

      // $im = new Imagick();

      // $im->setResolution(300,300);
      // $im->readimage('document.pdf[0]'); 
      // $im->setImageFormat('jpeg');    
      // $im->writeImage('thumb.jpg'); 
      // $im->clear(); 
      // $im->destroy();

      // $pdf_file = '../assets/test/Test_'.$prodval.'.pdf';
      // $savepath = '../assets/image_test/Test_'.$prodval.'.jpg';
      // $img = new imagick($pdf_file);
      // $img->setImageFormat('jpg');
      // $img->writeImage($savepath);
      // echo "<img src='../assets/image_test/Test_".$prodval.".jpg' />";

      $message = "";
    }
    ?>
  </div>
</div>
</body>

</html>