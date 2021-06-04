<?php
$ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
$booking = !empty($_GET["booking"]) ? $_GET["booking"] : '';

if (empty($ptype) || empty($booking)) {
    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/list'\" >";
}

// $ptype_name = get_value("products_type", "id", "name_text_thai", $ptype, $mysqli_p);
$bk_voucher_no = get_value("booking", "id", "voucher_no", $booking, $mysqli_p);
$bk_agent = get_value("booking", "id", "agent", $booking, $mysqli_p);
// $booking_id = !empty($booking) ? $booking : '0';

$query_type = "SELECT * FROM products_type WHERE id > '0'";
$query_type .= " AND id = ?";
$query_type .= " LIMIT 1";
$procedural_statement = mysqli_prepare($mysqli_p, $query_type);
mysqli_stmt_bind_param($procedural_statement, 'i', $ptype);
mysqli_stmt_execute($procedural_statement);
$result = mysqli_stmt_get_result($procedural_statement);
$numrow = mysqli_num_rows($result);
if ($numrow > 0) {
    $row_type = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $ptype_name = $row_type["name_text_thai"];
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/list'\" >";
}

if (!empty($_GET["id"])) {
    $query = "SELECT * FROM booking_products WHERE id > '0'";
    $query .= " AND id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $products_category_first = get_value("products_category_first", "id", "name", $row["products_category_first"], $mysqli_p);
        $products_category_second = get_value("products_category_second", "id", "name", $row["products_category_second"], $mysqli_p);
        $page_title = $products_category_first . " (" . $products_category_second . ")";
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/list'\" >";
    }
} else {
    $page_title = "เพิ่มสินค้า" . $ptype_name;
}

$bp_id = !empty($row["id"]) ? $row["id"] : '0';
$bp_date_not_specified = !empty($row["date_not_specified"]) ? $row["date_not_specified"] : '2';
$bp_travel_date = !empty($row["travel_date"]) ? $row["travel_date"] : $today;
$bp_checkin_date = !empty($row["checkin_date"]) ? $row["checkin_date"] : $today;
$bp_checkout_date = !empty($row["checkout_date"]) ? $row["checkout_date"] : $today;
$bp_products_category_first = !empty($row["products_category_first"]) ? $row["products_category_first"] : '0';
$bp_products_category_second = !empty($row["products_category_second"]) ? $row["products_category_second"] : '0';
$bp_products_category_third = !empty($row["products_category_third"]) ? $row["products_category_third"] : '0';
$bp_products_category_third_combine = !empty($row["products_category_third_combine"]) ? $row["products_category_third_combine"] : '0';
$bp_adults = !empty($row["adults"]) ? $row["adults"] : '0';
$bp_children = !empty($row["children"]) ? $row["children"] : '0';
$bp_infant = !empty($row["infant"]) ? $row["infant"] : '0';
$bp_foc = !empty($row["foc"]) ? $row["foc"] : '0';
$bp_transfer = !empty($row["transfer"]) ? $row["transfer"] : '2';
$bp_pickup = !empty($row["pickup"]) ? $row["pickup"] : '';
$bp_pickup_time = !empty($row["pickup_time"]) ? $row["pickup_time"] : date("H:i");
$bp_dropoff = !empty($row["dropoff"]) ? $row["dropoff"] : '';
$bp_dropoff_time = !empty($row["dropoff_time"]) ? $row["dropoff_time"] : date("H:i");
$bp_zones = !empty($row["zones"]) ? $row["zones"] : '0';
$bp_roomno = !empty($row["roomno"]) ? $row["roomno"] : '';
$bp_notes = !empty($row["notes"]) ? $row["notes"] : '';
$bp_no_cars = !empty($row["no_cars"]) ? $row["no_cars"] : '1';
$bp_no_hours = !empty($row["no_hours"]) ? $row["no_hours"] : '1';
$bp_no_rooms = !empty($row["no_rooms"]) ? $row["no_rooms"] : '1';
$bp_season_no = !empty($row["season_no"]) ? $row["season_no"] : '2';
$bp_extra_beds_adult = !empty($row["extra_beds_adult"]) ? $row["extra_beds_adult"] : '0';
$bp_extra_beds_child = !empty($row["extra_beds_child"]) ? $row["extra_beds_child"] : '0';
$bp_share_bed = !empty($row["share_bed"]) ? $row["share_bed"] : '0';
$bp_foreigner = !empty($row["foreigner"]) ? $row["foreigner"] : '2';
$bp_foreigner_no = !empty($row["foreigner_no"]) ? $row["foreigner_no"] : '0';
$bp_foreigner_price = !empty($row["foreigner_price"]) ? $row["foreigner_price"] : '0';
$bp_price_latest = !empty($row["price_latest"]) ? $row["price_latest"] : '0';
$bp_price_default = !empty($row["price_default"]) ? $row["price_default"] : '0';
$bp_status_email = !empty($row["status_email"]) ? $row["status_email"] : '2';
$bp_status_confirm = !empty($row["status_confirm"]) ? $row["status_confirm"] : '2';
$bp_status_confirm_op = !empty($row["status_confirm_op"]) ? $row["status_confirm_op"] : '2';
?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-8 align-self-center">
                <!-- <h4 class="text-themecolor">Supplier</h4> -->
                <div class="d-flex align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                        <li class="breadcrumb-item"><a href="./?mode=booking/detail&id=<?php echo $booking; ?>">Voucher No. #<?php echo $bk_voucher_no; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 align-self-center text-right">
                <!-- <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                        </div> -->
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">

            <!-- Validation Form -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">Detail</h4> -->
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=booking/save-product" onsubmit="return check_num()" novalidate>
                            <input type="hidden" id="bp_id" name="bp_id" value="<?php echo $bp_id; ?>">
                            <input type="hidden" id="ptype" name="ptype" value="<?php echo $ptype; ?>" onchange="inputHide()">
                            <input type="hidden" id="ptype_name" name="ptype_name" value="<?php echo $ptype_name; ?>">
                            <input type="hidden" id="booking" name="booking" value="<?php echo $booking; ?>">
                            <input type="hidden" id="agent" name="agent" value="<?php echo $bk_agent; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <input type="hidden" id="products_category_first" name="products_category_first" value="<?php echo $bp_products_category_first; ?>">
                            <input type="hidden" id="products_category_second" name="products_category_second" value="<?php echo $bp_products_category_second; ?>">
                            <input type="hidden" id="products_category_third" name="products_category_third" value="<?php echo $bp_products_category_third; ?>">
                            <input type="hidden" id="products_category_third_combine" name="products_category_third_combine" value="<?php echo $bp_products_category_third_combine; ?>">
                            <input type="hidden" id="price_latest_before" name="price_latest_before" value="<?php echo $bp_price_latest; ?>">
                            <input type="hidden" id="bp_status_email" name="bp_status_email" value="<?php echo $bp_status_email; ?>">
                            <input type="hidden" id="bp_status_confirm" name="bp_status_confirm" value="<?php echo $bp_status_confirm; ?>">
                            <input type="hidden" id="bp_status_confirm_op" name="bp_status_confirm_op" value="<?php echo $bp_status_confirm_op; ?>">
                            <input type="hidden" id="bp_dropoff_check" name="bp_dropoff_check" value="<?php echo $bp_dropoff; ?>">

                            <div class="form-row">
                                <div class="col-md-3 mb-3" id="div-date-not-specified">
                                    <label for="bp_date_not_specified">ไม่ระบุวันที่</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bp_date_not_specified" name="bp_date_not_specified" <?php echo ($bp_date_not_specified != 2 || !isset($bp_date_not_specified)) ? 'checked' : ''; ?> value="1">
                                        <label class="custom-control-label" for="bp_date_not_specified">ใช่</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3" id="div-travel-date">
                                    <label for="bp_travel_date">วันที่เที่ยว / วันที่เดินทาง</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFrom"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_travel_date" name="bp_travel_date" placeholder="" aria-describedby="inputFrom" value="<?php echo $bp_travel_date; ?>" onchange="checkSupplier()" <?php echo ($bp_status_confirm_op == 1) ? 'disabled' : 'readonly'; ?> required>
                                        <div class="invalid-feedback">กรุณาระบุวันที่เที่ยว</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3" id="div-checkin-date">
                                    <label for="bp_checkin_date"> วันที่เช็คอิน </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะสินค้าประเภทโรงแรมเท่านั้น</span>) --->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputCheckin"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_checkin_date" name="bp_checkin_date" placeholder="" aria-describedby="inputCheckin" value="<?php echo $bp_checkin_date; ?>" onchange="checkSupplier()" <?php echo ($bp_status_confirm_op == 1) ? 'disabled' : 'readonly'; ?> required>
                                        <div class="invalid-feedback">กรุณาระบุวันที่เช็คอิน</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3" id="div-checkout-date">
                                    <label for="bp_checkout_date">วันที่เช็คเอาท์ </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะสินค้าประเภทโรงแรมเท่านั้น</span>) --->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputCheckout"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_checkout_date" name="bp_checkout_date" placeholder="" aria-describedby="inputCheckout" value="<?php echo $bp_checkout_date; ?>" onchange="checkSupplier()" <?php echo ($bp_status_confirm_op == 1) ? 'disabled' : 'readonly'; ?> required>
                                        <div class="invalid-feedback">กรุณาระบุวันที่เช็คเอาท์</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div id="div-catefirst">
                                    <!-- function: checkCatefirst -->
                                </div>
                                <div class="col-md-3 mb-3" id="div-checksupplier">
                                    <!-- function: checksupplier -->
                                </div>
                                <div class="col-md-3 mb-3" id="div-catesecond">
                                    <!-- function: checkCatesecond -->
                                </div>
                                <div class="col-md-2 mb-3" id="div-catethird" style="text-align:center">
                                    <!-- function: checkCatethird -->
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_adults">ผู้ใหญ่</label>
                                    <select class="custom-select" id="bp_adults" name="bp_adults" onchange="calRate()">
                                        <?php list_number($bp_adults, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_children">เด็ก</label>
                                    <select class="custom-select" id="bp_children" name="bp_children" onchange="calRate()">
                                        <?php list_number($bp_children, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_infant">ทารก</label>
                                    <select class="custom-select" id="bp_infant" name="bp_infant">
                                        <?php list_number($bp_infant, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_foc">FOC</label>
                                    <select class="custom-select" id="bp_foc" name="bp_foc" onchange="calRate()">
                                        <?php list_number($bp_foc, '0', '501'); ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-row">
                                <div class="col-md-2 mb-3" id="div-transfer">
                                    <label for="bp_transfer"> เพิ่มรถรับส่ง </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะทัวร์และกิจกรรมเท่านั้น</span>) --->
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bp_transfer" name="bp_transfer" <?php echo ($bp_transfer != 2 || !isset($bp_transfer)) ? 'checked' : ''; ?> value="1" onclick="calRate()" <?php echo ($ptype != 1 && $ptype != 2) ? 'disabled' : ''; ?>>
                                        <label class="custom-control-label" for="bp_transfer">ใช่</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="bp_pickup">สถานที่รับ <a href="#pickup" onclick="add_place('pickup', 'add_place')"><i class="fas fa-plus" style="color:#0FFF00"></i></a> </label>
                                    <div class="input-group" id="select_pickup">
                                        <select class="form-control custom-select" id="bp_pickup" name="bp_pickup" onchange="checkdropoff('chang', 'checkdropoff');">
                                            <option value="0" id="zero_dropoff">กรุณาเลือกสถานที่รับ</option>
                                            <?php
                                            $query_pickup = "SELECT * FROM place WHERE status = '1' AND pickup = '1' ";
                                            $query_pickup .= " ORDER BY name ASC";
                                            $procedural_statement = mysqli_prepare($mysqli_p, $query_pickup);
                                            mysqli_stmt_execute($procedural_statement);
                                            $result_pickup = mysqli_stmt_get_result($procedural_statement);
                                            while ($row_pickup = mysqli_fetch_array($result_pickup, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row_pickup["id"]; ?>" <?php echo ($row_pickup["id"] == $bp_pickup) ? 'selected' : ''; ?>><?php echo $row_pickup["name"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bp_dropoff">สถานที่ส่ง <a href="#dropoff" onclick="add_place('dropoff', 'add_place')"><i class="fas fa-plus" style="color:#0FFF00"></i></a> </label>
                                    <div class="input-group" id="select_dropoff">
                                        <select class="form-control custom-select" id="bp_dropoff" name="bp_dropoff">
                                            <option value="0" id="zero_dropoff">กรุณาเลือกสถานที่ส่ง</option>
                                            <?php
                                            $query_dropoff = "SELECT * FROM place WHERE status = '1' AND dropoff = '1' ";
                                            $query_dropoff .= " ORDER BY name ASC";
                                            $procedural_statement = mysqli_prepare($mysqli_p, $query_dropoff);
                                            mysqli_stmt_execute($procedural_statement);
                                            $result_dropoff = mysqli_stmt_get_result($procedural_statement);
                                            while ($row_dropoff = mysqli_fetch_array($result_dropoff, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row_dropoff["id"]; ?>" <?php echo ($row_dropoff["id"] == $bp_dropoff) ? 'selected' : ''; ?>><?php echo $row_dropoff["name"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="bp_zones">โซน</label>
                                    <select class="custom-select" id="bp_zones" name="bp_zones">
                                        <option value="0">กรุณาเลือกโซน</option>
                                        <?php
                                        $query_zones = "SELECT * FROM zones";
                                        $query_zones .= " ORDER BY name ASC";
                                        $procedural_statement = mysqli_prepare($mysqli_p, $query_zones);
                                        mysqli_stmt_execute($procedural_statement);
                                        $result_zones = mysqli_stmt_get_result($procedural_statement);
                                        while ($row_zones = mysqli_fetch_array($result_zones, MYSQLI_ASSOC)) {
                                        ?>
                                            <option value="<?php echo $row_zones["id"]; ?>" <?php echo ($row_zones["id"] == $bp_zones) ? 'selected' : ''; ?>><?php echo $row_zones["name"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="bp_roomno">ห้องพัก</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bp_roomno" name="bp_roomno" placeholder="" aria-describedby="inputRoom" value="<?php echo $bp_roomno; ?>">
                                        <div class="invalid-feedback">กรุณาระบุห้องพัก</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row" id="div-type-transfer">
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_no_cars">จำนวนคัน</label>
                                    <select class="custom-select" id="bp_no_cars" name="bp_no_cars" onchange="calRate()" <?php echo ($ptype != 3) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_no_cars, '1', '100'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_no_hours">จำนวนชั่วโมง</label>
                                    <select class="custom-select" id="bp_no_hours" name="bp_no_hours" onchange="checkHours()" <?php echo ($ptype != 3) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_no_hours, '1', '168'); ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bp_pickup_time">เวลารับ </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะสินค้าประเภทรถรับส่งเท่านั้น</span>) --->
                                    <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true" onchange="checkHours()">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputPickuptime"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_pickup_time" name="bp_pickup_time" placeholder="" aria-describedby="inputPickuptime" value="<?php echo ($bp_id > 0) ? date('H:i', strtotime($bp_pickup_time)) : date("H:i"); ?>" readonly>
                                        <div class="invalid-feedback">กรุณาระบุเวลารับ</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bp_dropoff_time">เวลาส่ง </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะสินค้าประเภทรถรับส่งเท่านั้น</span>) --->
                                    <div class="input-group">
                                        <!-- <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true"> -->
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputDropofftime"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_dropoff_time" name="bp_dropoff_time" placeholder="" aria-describedby="inputDropofftime" value="" readonly>
                                        <div class="invalid-feedback">กรุณาระบุเวลาส่ง</div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-row" id="div-type-hotel">
                                <div class="col-md-1 mb-3">
                                    <label for="bp_season_no">เทศกาล, วันหยุด </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะทัวร์และกิจกรรมเท่านั้น</span>) --->
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bp_season_no" name="bp_season_no" <?php echo ($bp_season_no != 2 || !isset($bp_season_no)) ? 'checked' : ''; ?> value="1" onclick="calRate()" <?php echo ($ptype != 4) ? 'disabled' : ''; ?>>
                                        <label class="custom-control-label" for="bp_season_no">เพิ่ม</label>
                                    </div>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_no_rooms">จำนวนห้อง</label>
                                    <select class="custom-select" id="bp_no_rooms" name="bp_no_rooms" onchange="calRate()" <?php echo ($ptype != 4) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_no_rooms, '1', '500'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_extra_beds_adult">จำนวนเตียงเสริมผู้ใหญ่</label>
                                    <select class="custom-select" id="bp_extra_beds_adult" name="bp_extra_beds_adult" onchange="calRate()" <?php echo ($ptype != 4) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_extra_beds_adult, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_extra_beds_child">จำนวนเตียงเสริมเด็ก</label>
                                    <select class="custom-select" id="bp_extra_beds_child" name="bp_extra_beds_child" onchange="calRate()" <?php echo ($ptype != 4) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_extra_beds_child, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_share_bed">จำนวนแชร์เตียง</label>
                                    <select class="custom-select" id="bp_share_bed" name="bp_share_bed" onchange="calRate()" <?php echo ($ptype != 4) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_share_bed, '0', '501'); ?>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="form-row" id="type-tour-activity">
                                <div class="col-md-3 mb-3">
                                    <label for="bp_foreigner">ชาวต่างชาติ </label>
                                    <!--- (<span style="color:#FF0000">เฉพาะทัวร์และกิจกรรมเท่านั้น</span>) --->
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bp_foreigner" name="bp_foreigner" <?php echo ($bp_foreigner != 2 || !isset($bp_foreigner)) ? 'checked' : ''; ?> value="1" onclick="calRate()" <?php echo ($ptype != 1 && $ptype != 2) ? 'disabled' : ''; ?>>
                                        <label class="custom-control-label" for="bp_foreigner">มี</label>
                                    </div>
                                </div>
                                <div class="col-md-1 mb-3" style="text-align:center">
                                    <label for="bp_foreigner_no">จำนวนคน</label>
                                    <!--- (<span style="color:#FF0000">ชาวต่างชาติ</span>) --->
                                    <select class="custom-select" id="bp_foreigner_no" name="bp_foreigner_no" <?php echo ($ptype != 1 && $ptype != 2) ? 'disabled' : ''; ?>>
                                        <?php list_number($bp_foreigner_no, '0', '501'); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3" style="text-align:center">
                                    <label for="bp_foreigner_price">ราคาเพิ่มเติมสำหรับชาวต่างชาติ (<span style="color:#FF0000">บาท</span>)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputForeigner"><i class="ti-wallet"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bp_foreigner_price" name="bp_foreigner_price" placeholder="" aria-describedby="inputForeigner" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($bp_foreigner_price); ?>" oninput="priceformat('bp_foreigner_price');" <?php echo ($ptype != 1 && $ptype != 2) ? 'disabled' : ''; ?>>
                                        <div class="invalid-feedback">กรุณาระบุราคาเพิ่มเติมสำหรับชาวต่างชาติ</div>
                                    </div>
                                </div>
                            </div>

                            <div id="div-calrateproduct">
                            </div>

                            <!-- <div class="form-row" >
                                <div class="col-md-12 mb-3" style="text-align:center">
                                    <span style="font-size:16px; font-weight:bold; color:#FF0000; margin-left: auto; margin-right: auto">กรุณาเลือกสินค้าก่อน</span>
                                </div>
                            </div> -->

                            <hr>

                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="bp_notes">หมายเหตุ</label>
                                    <div class="input-group">
                                        <textarea type="text" class="form-control" id="bp_notes" name="bp_notes" placeholder="" cols="30" rows="5"><?php echo $bp_notes; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <button class="btn btn-primary" type="submit">บันทึกข้อมูล</button>
                        </form>
                        <script>
                            // Example starter JavaScript for disabling form submissions if there are invalid fields
                            (function() {
                                'use strict';
                                window.addEventListener('load', function() {
                                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                    var forms = document.getElementsByClassName('needs-validation');
                                    // Loop over them and prevent submission
                                    var validation = Array.prototype.filter.call(forms, function(form) {
                                        form.addEventListener('submit', function(event) {
                                            if (form.checkValidity() === false) {
                                                event.preventDefault();
                                                event.stopPropagation();
                                            }
                                            form.classList.add('was-validated');
                                        }, false);
                                    });
                                }, false);
                            })();
                        </script>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->

<script>
    // function checkCatefirst() {
    //     calRate();
    //     jQuery.ajax({
    //         url: "../inc/ajax/booking/checkcatefirst.php",
    //         data: {
    //             bp_id: $("#bp_id").val(),
    //             ptype: $("#ptype").val(),
    //             ptype_name: $("#ptype_name").val(),
    //             booking: $("#booking").val(),
    //             agent: $("#agent").val(),
    //             bp_travel_date: $("#bp_travel_date").val(),
    //             bp_checkin_date: $("#bp_checkin_date").val(),
    //             bp_checkout_date: $("#bp_checkout_date").val(),
    //             products_category_first: $("#products_category_first").val()
    //         },
    //         type: "POST",
    //         success: function(response) {
    //             $("#div-catefirst").html(response);
    //             checkSupplier();
    //         },
    //         error: function() {}
    //     });
    // }

    function check_num() {
        var bp_adults = document.getElementById('bp_adults').value;
        var bp_children = document.getElementById('bp_children').value;
        var bp_infant = document.getElementById('bp_infant').value;
        var bp_foc = document.getElementById('bp_foc').value;
        bp_adults = parseInt(bp_adults);
        bp_children = parseInt(bp_children);
        bp_infant = parseInt(bp_infant);
        bp_foc = parseInt(bp_foc);
        var num = bp_adults + bp_children + bp_infant + bp_foc;
        if (num == '0') {
            Swal.fire({
                type: 'error',
                text: 'กรุณา ระบุจำนวนคน!',
                showConfirmButton: false,
                timer: 2000
            });
            return false
        }
    }

    function add_place(type, prodtype) {
        Swal.fire({
            title: 'สถานที่',
            input: 'text',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: 'บันทึกข้อมูล',
            cancelButtonText: 'ปิด',
            inputValidator: (result) => {
                if (!result) {
                    return 'กรุณากรอกข้อมูล!'
                }
            }
        }).then((result) => {
            if (result.value) {
                jQuery.ajax({
                    url: "../inc/ajax/booking/" + prodtype + ".php",
                    data: {
                        name: result.value,
                        type: type
                    },
                    type: "POST",
                    success: function(response) {
                        Swal.fire({
                            title: "บันทึกข้อมูลสำเร็จ!",
                            type: "success"
                        }).then(function() {
                            $("#select_pickup").html(response);
                            checkdropoff('add');
                        });
                    },
                    error: function() {
                        Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                    }
                });
            }
        })
    }

    function checkdropoff(type) {
        var pickup = document.getElementById('bp_pickup').value;
        var dropoff = document.getElementById('bp_dropoff_check').value;
        jQuery.ajax({
            url: "../inc/ajax/booking/checkdropoff.php",
            data: {
                type: type,
                pickup: pickup,
                dropoff: dropoff
            },
            type: "POST",
            success: function(response) {
                $("#select_dropoff").html(response);
            }
        });
    }

    function checkSupplier() {
        jQuery.ajax({
            url: "../inc/ajax/booking/checksupplier.php",
            data: {
                bp_id: $("#bp_id").val(),
                ptype: $("#ptype").val(),
                ptype_name: $("#ptype_name").val(),
                booking: $("#booking").val(),
                agent: $("#agent").val(),
                bp_travel_date: $("#bp_travel_date").val(),
                bp_checkin_date: $("#bp_checkin_date").val(),
                bp_checkout_date: $("#bp_checkout_date").val(),
                products_category_first: $("#products_category_first").val(),
                bp_status_confirm_op: $("#bp_status_confirm_op").val()
            },
            type: "POST",
            success: function(response) {
                $("#div-checksupplier").html(response);
                checkCatesecond();
            },
            error: function() {}
        });
    }

    function checkCatesecond() {
        calRate();
        jQuery.ajax({
            url: "../inc/ajax/booking/checkcatesecond.php",
            data: {
                bp_id: $("#bp_id").val(),
                ptype: $("#ptype").val(),
                ptype_name: $("#ptype_name").val(),
                booking: $("#booking").val(),
                agent: $("#agent").val(),
                bp_travel_date: $("#bp_travel_date").val(),
                bp_checkin_date: $("#bp_checkin_date").val(),
                bp_checkout_date: $("#bp_checkout_date").val(),
                catesupplier: $("#catesupplier").val(),
                products_category_second: $("#products_category_second").val(),
                products_category_third: $("#products_category_third").val(),
                bp_status_confirm_op: $("#bp_status_confirm_op").val()
            },
            type: "POST",
            success: function(response) {
                $("#div-catesecond").html(response);
                checkCatethird();
            },
            error: function() {}
        });
    }

    function checkCatethird() {
        jQuery.ajax({
            url: "../inc/ajax/booking/checkcatethird.php",
            data: {
                bp_id: $("#bp_id").val(),
                ptype: $("#ptype").val(),
                agent: $("#agent").val(),
                catefirst: $("#catefirst").val(),
                catethird: $("#catethird").val()
            },
            type: "POST",
            success: function(response) {
                $("#div-catethird").html(response);
                calRate();
                checkCatefirst();
            },
            error: function() {}
        });
    }

    function checkCatefirst() {
        jQuery.ajax({
            url: "../inc/ajax/booking/checkcatefirst.php",
            data: {
                bp_id: $("#bp_id").val(),
                ptype: $("#ptype").val(),
                agent: $("#agent").val(),
                catethird: $("#catethird").val()
            },
            type: "POST",
            success: function(response) {
                $("#div-catefirst").html(response);
            },
            error: function() {}
        });
    }

    function checkHours() {
        var no_hours = $("#bp_no_hours").val();
        var travel_date = $('#bp_travel_date').val();
        var pickup_time = $('#bp_pickup_time').val() + ':00';
        var mixdatetime = travel_date + " " + pickup_time;
        var droptime = new Date(new Date(mixdatetime).getTime() + no_hours * 60 * 60 * 1000).toLocaleTimeString(navigator.language, {
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('bp_dropoff_time').value = droptime;
        calRate();
    }

    function calRate() {
        var catethird = $("#catethird").val();
        var transfer = document.getElementById("bp_transfer").checked;
        var foreigner = document.getElementById("bp_foreigner").checked;
        var foreigner_price = document.getElementById("bp_foreigner_price").value;
        if (foreigner_price != "") {
            var foreigner_price_rep = foreigner_price.replace(",", "");
        } else {
            var foreigner_price_rep = 0;
        }
        var season_no = document.getElementById('bp_season_no').checked;

        jQuery.ajax({
            url: "../inc/ajax/booking/calrateproduct.php",
            data: {
                bp_id: $("#bp_id").val(),
                ptype: $("#ptype").val(),
                agent: $("#agent").val(),
                catethird: catethird,
                bp_checkin_date: $("#bp_checkin_date").val(),
                bp_checkout_date: $("#bp_checkout_date").val(),
                bp_adults: $("#bp_adults").val(),
                bp_children: $("#bp_children").val(),
                bp_foc: $("#bp_foc").val(),
                bp_transfer: transfer,
                bp_no_cars: $("#bp_no_cars").val(),
                bp_no_hours: $("#bp_no_hours").val(),
                bp_no_rooms: $("#bp_no_rooms").val(),
                bp_extra_beds_adult: $("#bp_extra_beds_adult").val(),
                bp_extra_beds_child: $("#bp_extra_beds_child").val(),
                bp_share_bed: $("#bp_share_bed").val(),
                bp_foreigner: foreigner,
                bp_season_no: season_no,
                bp_foreigner_price: foreigner_price_rep,
                price_latest_before: $("#price_latest_before").val()
            },
            type: "POST",
            success: function(response) {
                $("#div-calrateproduct").html(response);
            },
            error: function() {}
        });
    }

    function priceformat(inputfield) {
        var i = 0,
            num = 0;
        var j = document.getElementById(inputfield).value;
        while (i < j.length) {
            if (j[i] != ',') {
                num += j[i];
            }
            i++;
        }
        var d = new Number(parseInt(num));
        var n = d.toLocaleString();
        if (n == 0) {
            document.getElementById(inputfield).value = '0';
        } else {
            document.getElementById(inputfield).value = n;
        }

        if (inputfield == "bp_foreigner_price") {
            calRate();
        }

        if (inputfield == 'bp_price_latest') {
            var price_latest = document.getElementById('bp_price_latest').value;
            var price_default = document.getElementById('bp_price_default').value;
            price_latest = parseFloat(price_latest.replace(/,/g, ''));
            price_default = parseFloat(price_default.replace(/,/g, ''));
            if (price_latest > price_default) {
                Swal.fire({
                    type: 'error',
                    text: 'ไม่สามารถระบุราคาแก้ใข เกินจากราคาตั้งต้นได้',
                    showConfirmButton: false,
                    timer: 2000
                });
                document.getElementById('bp_price_latest').value = document.getElementById('bp_price_default').value;
            }
        }

    }

    function inputHide() {
        var ptype = document.getElementById('ptype')
        if (ptype.value == '1' || ptype.value == '2') {
            // Tour and Activity 
            document.getElementById('div-checkin-date').style.display = "none"
            document.getElementById('div-checkout-date').style.display = "none"
            document.getElementById('div-type-transfer').style.display = "none"
            document.getElementById('div-type-hotel').style.display = "none"
        } else if (ptype.value == '3') {
            // transfer
            document.getElementById('div-checkin-date').style.display = "none"
            document.getElementById('div-checkout-date').style.display = "none"
            document.getElementById('div-type-hotel').style.display = "none"
            document.getElementById('type-tour-activity').style.display = "none"
            document.getElementById('div-transfer').style.display = "none"
        } else if (ptype.value == '4') {
            // Hotel
            document.getElementById('div-travel-date').style.display = "none"
            document.getElementById('type-tour-activity').style.display = "none"
            document.getElementById('div-type-transfer').style.display = "none"
            document.getElementById('div-transfer').style.display = "none"
        }
    }
</script>