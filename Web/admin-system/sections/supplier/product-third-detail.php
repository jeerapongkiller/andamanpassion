<?php
$ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
$supplier = !empty($_GET["supplier"]) ? $_GET["supplier"] : '';
$catefirst = !empty($_GET["catefirst"]) ? $_GET["catefirst"] : '';
$catesecond = !empty($_GET["catesecond"]) ? $_GET["catesecond"] : '';

if (empty($ptype) || empty($supplier) || empty($catefirst) || empty($catesecond)) {
    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
}

$ptype_name = get_value("products_type", "id", "name_text_thai", $ptype, $mysqli_p);
$sp_company = get_value("supplier", "id", "company", $supplier, $mysqli_p);
$first_name = get_value("products_category_first", "id", "name", $catefirst, $mysqli_p);
$second_name = get_value("products_category_second", "id", "name", $catesecond, $mysqli_p);
$sp_id = !empty($supplier) ? $supplier : '0';

if (!empty($_GET["id"])) {
    $query = "SELECT * FROM products_category_third WHERE id > '0'";
    $query .= " AND id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $page_title = date("d F Y", strtotime($row["periods_from"])) . " - " . date("d F Y", strtotime($row["periods_to"]));
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
    }
} else {
    $page_title = "เพิ่มข้อมูลราคา";
}

$third_id = !empty($row["id"]) ? $row["id"] : '0';
$third_status = !empty($row["status"]) ? $row["status"] : '2';
$third_periods_from = !empty($row["periods_from"]) ? $row["periods_from"] : $today;
$third_periods_to = !empty($row["periods_to"]) ? $row["periods_to"] : $today;
$third_rate_1 = !empty($row["rate_1"]) ? $row["rate_1"] : '0';
$third_rate_2 = !empty($row["rate_2"]) ? $row["rate_2"] : '0';
$third_rate_3 = !empty($row["rate_3"]) ? $row["rate_3"] : '0';
$third_rate_4 = !empty($row["rate_4"]) ? $row["rate_4"] : '0';
$third_charter_1 = !empty($row["charter_1"]) ? $row["charter_1"] : '0';
$third_charter_2 = !empty($row["charter_2"]) ? $row["charter_2"] : '0';
$third_group_1 = !empty($row["group_1"]) ? $row["group_1"] : '0';
$third_group_2 = !empty($row["group_2"]) ? $row["group_2"] : '0';
$third_transfer_1 = !empty($row["transfer_1"]) ? $row["transfer_1"] : '0';
$third_transfer_2 = !empty($row["transfer_2"]) ? $row["transfer_2"] : '0';
$third_pax = !empty($row["pax"]) ? $row["pax"] : '0';
$third_hours_no = !empty($row["hours_no"]) ? $row["hours_no"] : '0';
$third_extra_hour_1 = !empty($row["extra_hour_1"]) ? $row["extra_hour_1"] : '0';
$third_extra_hour_2 = !empty($row["extra_hour_2"]) ? $row["extra_hour_2"] : '0';
$third_extrabeds_1 = !empty($row["extrabeds_1"]) ? $row["extrabeds_1"] : '0';
$third_extrabeds_2 = !empty($row["extrabeds_2"]) ? $row["extrabeds_2"] : '0';
$third_extrabeds_3 = !empty($row["extrabeds_3"]) ? $row["extrabeds_3"] : '0';
$third_extrabeds_4 = !empty($row["extrabeds_4"]) ? $row["extrabeds_4"] : '0';
$third_sharingbed_1 = !empty($row["sharingbed_1"]) ? $row["sharingbed_1"] : '0';
$third_sharingbed_2 = !empty($row["sharingbed_2"]) ? $row["sharingbed_2"] : '0';
$third_season = !empty($row["season"]) ? $row["season"] : '0';
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
                        <li class="breadcrumb-item"><a href="./?mode=supplier/list">ซัพพลายเออร์</a></li>
                        <li class="breadcrumb-item"><a href="./?mode=supplier/detail&id=<?php echo $sp_id; ?>"><?php echo $sp_company; ?></a></li>
                        <li class="breadcrumb-item">
                            <a href="./?mode=supplier/product-list&ptype=<?php echo $ptype; ?>&supplier=<?php echo $sp_id; ?>">รายการสินค้า (<?php echo $ptype_name; ?>)</a>
                        </li>
                        <!-- <li class="breadcrumb-item">
                                    <a href="./?mode=supplier/product-first-detail&id=<?php echo $catefirst; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>"><?php echo $first_name; ?></a></li>
                                <li class="breadcrumb-item">
                                    <a href="./?mode=supplier/product-second-detail&id=<?php echo $catesecond; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $catefirst; ?>"><?php echo $second_name; ?></a></li> -->
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
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=supplier/save-product-third" novalidate>
                            <input type="hidden" id="sp_id" name="sp_id" value="<?php echo $sp_id; ?>">
                            <input type="hidden" id="ptype" name="ptype" value="<?php echo $ptype; ?>">
                            <input type="hidden" id="first_id" name="first_id" value="<?php echo $catefirst; ?>">
                            <input type="hidden" id="second_id" name="second_id" value="<?php echo $catesecond; ?>">
                            <input type="hidden" id="third_id" name="third_id" value="<?php echo $third_id; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <input type="hidden" id="txt_periods" name="txt_periods" value="" required>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="third_status">สถานะ</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="third_status" name="third_status" <?php if ($third_status != 2 || !isset($third_status)) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?> value="1">
                                        <label class="custom-control-label" for="third_status">ออนไลน์</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="products_category_second">สินค้า</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputSecond"><i class="ti-pin-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="products_category_second" name="products_category_second" placeholder="" aria-describedby="inputSecond" autocomplete="off" value="<?php echo htmlspecialchars($second_name); ?>" readonly>
                                        <div class="invalid-feedback">กรุณาระบุชื่อสินค้า</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="third_periods_from">ระยะเวลาที่เริ่ม</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFrom"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_periods_from" name="third_periods_from" placeholder="" aria-describedby="inputFrom" value="<?php echo $third_periods_from; ?>" onchange="checkPeriods()" readonly required>
                                        <div class="invalid-feedback">กรุณาระบุระยะเวลาที่เริ่ม</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="third_periods_to">ระยะเวลาที่สิ้นสุด</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputTo"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_periods_to" name="third_periods_to" placeholder="" aria-describedby="inputTo" value="<?php echo $third_periods_to; ?>" onchange="checkPeriods()" readonly required>
                                        <div class="invalid-feedback">กรุณาระบุระยะเวลาที่สิ้นสุด</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="third_rate_1" id="label_third_rate_1">ราคาผู้ใหญ่/คัน/ห้อง/คืน</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputRate1"><i class="ti-wallet"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_rate_1" name="third_rate_1" placeholder="" aria-describedby="inputRate1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_1); ?>" oninput="priceformat('third_rate_1');" required>
                                        <div class="invalid-feedback">กรุณาระบุราคาผู้ใหญ่/คัน/ห้อง/คืน</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="third_rate_2" id="label_third_rate_2">ราคาผู้ใหญ่/คัน/ห้อง/คืน ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputRate2"><i class="ti-wallet"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_rate_2" name="third_rate_2" placeholder="" aria-describedby="inputRate2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_2); ?>" oninput="priceformat('third_rate_2');" required>
                                        <div class="invalid-feedback">กรุณาระบุราคาผู้ใหญ่/คัน/ห้อง/คืน (ราคาขาย)</div>
                                    </div>
                                </div>
                                <?php if ($ptype == '1' || $ptype == '2') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_rate_3" id="label_third_rate_3">ราคาเด็ก</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputRate3"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_rate_3" name="third_rate_3" placeholder="" aria-describedby="inputRate3" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_3); ?>" oninput="priceformat('third_rate_3');" required <?php if ($ptype != 1 && $ptype != 2) {
                                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเด็ก</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_rate_4" id="label_third_rate_4">ราคาเด็ก ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputRate4"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_rate_4" name="third_rate_4" placeholder="" aria-describedby="inputRate4" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_4); ?>" oninput="priceformat('third_rate_4');" required <?php if ($ptype != 1 && $ptype != 2) {
                                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเด็ก (ราคาขาย)</div>
                                        </div>
                                    </div>
                                <?php }
                                if ($ptype == '1') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_charter_1" id="label_third_charter_1">ราคาเช่าเหมาลำ ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputCharter1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_charter_1" name="third_charter_1" placeholder="" aria-describedby="inputCharter1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_charter_1); ?>" oninput="priceformat('third_charter_1');" required <?php if ($ptype != 1) {
                                                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเช่าเหมาลำ</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_charter_2" id="label_third_charter_2">ราคาเช่าเหมาลำ ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputCharter2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_charter_2" name="third_charter_2" placeholder="" aria-describedby="inputCharter2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_charter_2); ?>" oninput="priceformat('third_charter_2');" required <?php if ($ptype != 1) {
                                                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเช่าเหมาลำ (ราคาขาย)</div>
                                        </div>
                                    </div>
                                <?php }
                                if ($ptype == '2') { ?>
                                    <!-- <div class="col-md-3 mb-3">
                                        <label for="third_group_1" id="label_third_group_1">ราคากลุ่ม ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_group_1" name="third_group_1" placeholder="" aria-describedby="inputGroup1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_group_1); ?>" oninput="priceformat('third_group_1');" required <?php if ($ptype != 2) {
                                                                                                                                                                                                                                                                                                                            echo "readonly";
                                                                                                                                                                                                                                                                                                                        } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคากลุ่ม (ราคาทุน)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_group_2" id="label_third_group_2">ราคากลุ่ม ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_group_2" name="third_group_2" placeholder="" aria-describedby="inputGroup2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_group_2); ?>" oninput="priceformat('third_group_2');" required <?php if ($ptype != 2) {
                                                                                                                                                                                                                                                                                                                            echo "readonly";
                                                                                                                                                                                                                                                                                                                        } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคากลุ่ม (ราคาขาย)</div>
                                        </div>
                                    </div> -->
                                <?php }
                                if ($ptype == '1' || $ptype == '2') {
                                    if($ptype == '1') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_pax" id="label_third_pax">จำนวนคน (<span style="color:#3DB3E4; font-weight:bold">ราคาเช่าเหมาลำ/ราคากลุ่ม</span>)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputPax"><i class="ti-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_pax" name="third_pax" placeholder="" aria-describedby="inputPax" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_pax); ?>" oninput="priceformat('third_pax');" required <?php if ($ptype != 1 && $ptype != 2) {
                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุจำนวนคน</div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_transfer_1" id="label_third_transfer_1">ราคารถรับส่ง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputTransfer1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_transfer_1" name="third_transfer_1" placeholder="" aria-describedby="inputTransfer1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_transfer_1); ?>" oninput="priceformat('third_transfer_1');" required <?php if ($ptype != 1 && $ptype != 2) {
                                                                                                                                                                                                                                                                                                                                            echo "readonly";
                                                                                                                                                                                                                                                                                                                                        } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคารถรับส่ง (ราคาทุน)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_transfer_2" id="label_third_transfer_2">ราคารถรับส่ง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputTransfer2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_transfer_2" name="third_transfer_2" placeholder="" aria-describedby="inputTransfer2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_transfer_2); ?>" oninput="priceformat('third_transfer_2');" required <?php if ($ptype != 1 && $ptype != 2) {
                                                                                                                                                                                                                                                                                                                                            echo "readonly";
                                                                                                                                                                                                                                                                                                                                        } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคารถรับส่ง (ราคาขาย)</div>
                                        </div>
                                    </div>
                                <?php }
                                if ($ptype == '3') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_hours_no" id="label_third_hours_no">จำนวนชั่วโมง</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputHours"><i class="ti-timer"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_hours_no" name="third_hours_no" placeholder="" aria-describedby="inputHours" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_hours_no); ?>" oninput="priceformat('third_hours_no');" required <?php if ($ptype != 3) {
                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุจำนวนชั่วโมง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extra_hour_1" id="label_third_extra_hour_1">ราคาต่อชั่วโมง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrahour1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extra_hour_1" name="third_extra_hour_1" placeholder="" aria-describedby="inputExtrahour1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extra_hour_1); ?>" oninput="priceformat('third_extra_hour_1');" required <?php if ($ptype != 3) {
                                                                                                                                                                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                                                                                                                                                                } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาต่อชั่วโมง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extra_hour_2" id="label_third_extra_hour_2">ราคาต่อชั่วโมง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrahour2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extra_hour_2" name="third_extra_hour_2" placeholder="" aria-describedby="inputExtrahour2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extra_hour_2); ?>" oninput="priceformat('third_extra_hour_2');" required <?php if ($ptype != 3) {
                                                                                                                                                                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                                                                                                                                                                } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาต่อชั่วโมง (ราคาขาย)</div>
                                        </div>
                                    </div>
                                <?php }
                                if ($ptype == '4') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extrabeds_1" id="label_third_extrabeds_1">ราคาเตียงเสริมผู้ใหญ่ ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_1" name="third_extrabeds_1" placeholder="" aria-describedby="inputExtrabeds1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_1); ?>" oninput="priceformat('third_extrabeds_1');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริมผู้ใหญ่ (ทุน)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extrabeds_2" id="label_third_extrabeds_2">ราคาเตียงเสริมผู้ใหญ่ ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_2" name="third_extrabeds_2" placeholder="" aria-describedby="inputExtrabeds2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_2); ?>" oninput="priceformat('third_extrabeds_2');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริมผู้ใหญ่ (ราคาขาย)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extrabeds_3" id="label_third_extrabeds_3">ราคาเตียงเสริมเด็ก ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_3" name="third_extrabeds_3" placeholder="" aria-describedby="inputExtrabeds1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_3); ?>" oninput="priceformat('third_extrabeds_3');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริมเด็ก (ทุน)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extrabeds_4" id="label_third_extrabeds_4">ราคาเตียงเสริมเด็ก ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_4" name="third_extrabeds_4" placeholder="" aria-describedby="inputExtrabeds2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_4); ?>" oninput="priceformat('third_extrabeds_4');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริมเด็ก (ราคาขาย)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_sharingbed_1" id="label_third_sharingbed_1">ราคาแชร์เตียง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputSharingbed1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_sharingbed_1" name="third_sharingbed_1" placeholder="" aria-describedby="inputSharingbed1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_sharingbed_1); ?>" oninput="priceformat('third_sharingbed_1');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                                                                                                                                                                } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาแชร์เตียง (ทุน)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_sharingbed_2" id="label_third_sharingbed_2">ราคาแชร์เตียง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputSharingbed2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_sharingbed_2" name="third_sharingbed_2" placeholder="" aria-describedby="inputSharingbed2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_sharingbed_2); ?>" oninput="priceformat('third_sharingbed_2');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                    echo "readonly";
                                                                                                                                                                                                                                                                                                                                                } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาแชร์เตียง (ราคาขาย)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_season" id="label_third_season">ราคาเสาร์-อาทิตย์, เทศกาล</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputSeason"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_season" name="third_season" placeholder="" aria-describedby="inputSeason" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_season); ?>" oninput="priceformat('third_season');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                        echo "readonly";
                                                                                                                                                                                                                                                                                                                    } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเสาร์-อาทิตย์, เทศกาล</div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="form-row" id="div-form">
                            </div>

                            <!-- <hr>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="remark">Remark</label>
                                            <div class="input-group">
                                                <span class="help-block" style="color:#FF0000">
                                                    <small>Support "<b>PDF</b>" format only and file size <b>must not exceed 2MB per file</b>. Should be fix to <b>width</b> 600px, <b>height</b> 600px</small></span>
                                            </div>
                                        </div>
                                    </div> -->

                            <div class="form-row">
                                <?php
                                for ($i = 1; $i <= 0; $i++) {
                                    $img_tmp = !empty($row["photo" . $i]) ? $row["photo" . $i] : '';
                                    $pathpicture = !empty($img_tmp) ? '../inc/photo/supplier/' . $img_tmp : '';
                                ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="photo<?php echo $i; ?>">ภาพที่ # <span style="font-weight:bold; color:#FF0000"><?php echo $i; ?></span></label>
                                        <?php if ($img_tmp != "") { ?>
                                            <label class="m-l-15">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="del_photo<?php echo $i; ?>" name="del_photo<?php echo $i; ?>" value="1">
                                                    <label class="custom-control-label" for="del_photo<?php echo $i; ?>">ลบภาพ</label>
                                                    <input type="hidden" class="form-control" id="tmp_photo<?php echo $i; ?>" name="tmp_photo<?php echo $i; ?>" value="<?php echo $img_tmp; ?>">
                                                </div>
                                            </label>
                                        <?php } ?>
                                        <div class="input-group">
                                            <input type="file" id="photo<?php echo $i; ?>" name="photo<?php echo $i; ?>" class="dropify" data-default-file="<?php echo $pathpicture; ?>" data-show-remove="false" data-max-file-size="2M" data-allowed-file-extensions="pdf" />
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <input type="hidden" class="form-control" id="photo_count" name="photo_count" value="<?php echo $i - 1; ?>">
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
                                            var txt_periods = document.getElementById('txt_periods').value;
                                            if (form.checkValidity() === false || txt_periods === "") {
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
    function labelProducts() {
        var ptype = document.getElementById('ptype');
        var label_third_rate_1 = document.getElementById('label_third_rate_1');
        var label_third_rate_2 = document.getElementById('label_third_rate_2');
        var label_third_rate_3 = document.getElementById('label_third_rate_3');
        var label_third_rate_4 = document.getElementById('label_third_rate_4');
        if (ptype.value == 1) {
            label_third_rate_1.innerHTML = 'ราคาผู้ใหญ่ ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_2.innerHTML = 'ราคาผู้ใหญ่ ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
            label_third_rate_3.innerHTML = 'ราคาเด็ก ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_4.innerHTML = 'ราคาเด็ก ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
        }
        if (ptype.value == 2) {
            label_third_rate_1.innerHTML = 'ราคาผู้ใหญ่ ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_2.innerHTML = 'ราคาผู้ใหญ่ ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
            label_third_rate_3.innerHTML = 'ราคาเด็ก ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_4.innerHTML = 'ราคาเด็ก ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
        }
        if (ptype.value == 3) {
            label_third_rate_1.innerHTML = 'ราคา/คัน ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_2.innerHTML = 'ราคา/คัน ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
        }
        if (ptype.value == 4) {
            label_third_rate_1.innerHTML = 'ราคา/ห้อง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_2.innerHTML = 'ราคา/ห้อง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
        }
    }

    function checkPeriods() {
        jQuery.ajax({
            url: "../inc/ajax/supplier/checkperiods.php",
            data: {
                second_id: $("#second_id").val(),
                third_id: $("#third_id").val(),
                third_periods_from: $("#third_periods_from").val(),
                third_periods_to: $("#third_periods_to").val()
            },
            type: "POST",
            success: function(response) {
                if (response == "true") {
                    document.getElementById("txt_periods").value = true;
                } else {
                    document.getElementById("txt_periods").value = "";

                    Swal.fire({
                        // position: 'top-end',
                        type: 'error',
                        // title: '',
                        text: 'กรุณาเลือกระยะเวลาใหม่ เพราะมีช่วงระยะเวลาที่ซ้ำกัน',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
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
            document.getElementById(inputfield).value = 0;
        } else {
            document.getElementById(inputfield).value = n;
        }
    }
</script>