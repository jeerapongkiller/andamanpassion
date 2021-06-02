<?php
$ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
$supplier = !empty($_GET["supplier"]) ? $_GET["supplier"] : '';
$catefirst = !empty($_GET["catefirst"]) ? $_GET["catefirst"] : '';
$catesecond = !empty($_GET["catesecond"]) ? $_GET["catesecond"] : '';
$agent = !empty($_GET["agent"]) ? $_GET["agent"] : '';
$combine = !empty($_GET["combine"]) ? $_GET["combine"] : '';

if (empty($ptype) || empty($supplier) || empty($catefirst) || empty($catesecond) || empty($agent) || empty($combine)) {
    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
}

$ptype_name = get_value("products_type", "id", "name_text_thai", $ptype, $mysqli_p);
$sp_company = get_value("supplier", "id", "company", $supplier, $mysqli_p);
$first_name = get_value("products_category_first", "id", "name", $catefirst, $mysqli_p);
$second_name = get_value("products_category_second", "id", "name", $catesecond, $mysqli_p);
$agent_name = get_value("agent", "id", "company", $agent, $mysqli_p);
$sp_id = !empty($supplier) ? $supplier : '0';

if (!empty($_GET["id"])) {
    $query = "SELECT products_category_third.*, 
                            products_category_third_combine.id as mainidCombine, 
                            products_category_third_combine.combine_agentxsupplier as xidCombine, 
                            products_category_third_combine.products_category_third as thirdidCombine, 
                            products_category_third_combine.rate_2 as rate2Combine, 
                            products_category_third_combine.rate_4 as rate4Combine, 
                            products_category_third_combine.charter_2 as charter2Combine, 
                            products_category_third_combine.group_2 as group2Combine, 
                            products_category_third_combine.transfer_2 as transfer2Combine, 
                            products_category_third_combine.extra_hour_2 as extrahour2Combine, 
                            products_category_third_combine.extrabeds_2 as extrabeds2Combine, 
                            products_category_third_combine.sharingbed_2 as sharingbed2Combine 
                        FROM products_category_third 
                        LEFT JOIN products_category_third_combine 
                            ON products_category_third.id = products_category_third_combine.products_category_third
                            AND products_category_third_combine.combine_agentxsupplier = ?
                        WHERE products_category_third.id > '0' 
        ";

    // $query = "SELECT * FROM products_category_third WHERE id > '0'";
    $query .= " AND products_category_third.id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'ii', $combine, $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $page_title = date("d F Y", strtotime($row["periods_from"])) . " - " . date("d F Y", strtotime($row["periods_to"]));
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
    }
} else {
    $page_title = "เพิ่มข้อมูลราคา";
}

$third_id = !empty($row["id"]) ? $row["id"] : '0';
$third_status = !empty($row["status"]) ? $row["status"] : '2';
$third_periods_from = !empty($row["periods_from"]) ? $row["periods_from"] : $today;
$third_periods_to = !empty($row["periods_to"]) ? $row["periods_to"] : $today;
$third_rate_1 = !empty($row["rate_1"]) ? $row["rate_1"] : '0';
$third_rate_2 = !empty($row["rate2Combine"]) ? $row["rate2Combine"] : '0';
$third_rate_3 = !empty($row["rate_3"]) ? $row["rate_3"] : '0';
$third_rate_4 = !empty($row["rate4Combine"]) ? $row["rate4Combine"] : '0';
$third_charter_1 = !empty($row["charter_1"]) ? $row["charter_1"] : '0';
$third_charter_2 = !empty($row["charter2Combine"]) ? $row["charter2Combine"] : '0';
$third_group_1 = !empty($row["group_1"]) ? $row["group_1"] : '0';
$third_group_2 = !empty($row["group2Combine"]) ? $row["group2Combine"] : '0';
$third_transfer_1 = !empty($row["transfer_1"]) ? $row["transfer_1"] : '0';
$third_transfer_2 = !empty($row["transfer2Combine"]) ? $row["transfer2Combine"] : '0';
$third_pax = !empty($row["pax"]) ? $row["pax"] : '0';
$third_hours_no = !empty($row["hours_no"]) ? $row["hours_no"] : '0';
$third_extra_hour_1 = !empty($row["extra_hour_1"]) ? $row["extra_hour_1"] : '0';
$third_extra_hour_2 = !empty($row["extrahour2Combine"]) ? $row["extrahour2Combine"] : '0';
$third_extrabeds_1 = !empty($row["extrabeds_1"]) ? $row["extrabeds_1"] : '0';
$third_extrabeds_2 = !empty($row["extrabeds2Combine"]) ? $row["extrabeds2Combine"] : '0';
$third_sharingbed_1 = !empty($row["sharingbed_1"]) ? $row["sharingbed_1"] : '0';
$third_sharingbed_2 = !empty($row["sharingbed2Combine"]) ? $row["sharingbed2Combine"] : '0';
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
                        <li class="breadcrumb-item"><a href="./?mode=agent/list">เอเย่นต์</a></li>
                        <li class="breadcrumb-item"><a href="./?mode=agent/detail&id=<?php echo $agent; ?>"><?php echo $agent_name; ?></a></li>
                        <li class="breadcrumb-item"><a href="./?mode=agent/combine-agentxsupplier&ptype=<?php echo $ptype; ?>&agent=<?php echo $agent; ?>">เอเย่นต์ x ซัพพลายเออร์ (<?php echo $ptype_name; ?>)</a></li>
                        <li class="breadcrumb-item"><?php echo $sp_company; ?></li>
                        <li class="breadcrumb-item">
                            <a href="./?mode=agent/combine-product-list&ptype=<?php echo $ptype; ?>&supplier=<?php echo $sp_id; ?>&agent=<?php echo $agent; ?>&combine=<?php echo $combine; ?>">รายการสินค้า (<?php echo $ptype_name; ?>)</a>
                        </li>
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
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=agent/save-combine-product-third" novalidate>
                            <input type="hidden" id="mainid" name="mainid" value="<?php echo $row["mainidCombine"]; ?>">
                            <input type="hidden" id="sp_id" name="sp_id" value="<?php echo $sp_id; ?>">
                            <input type="hidden" id="ptype" name="ptype" value="<?php echo $ptype; ?>">
                            <input type="hidden" id="agent" name="agent" value="<?php echo $agent; ?>">
                            <input type="hidden" id="combine" name="combine" value="<?php echo $combine; ?>">
                            <input type="hidden" id="first_id" name="first_id" value="<?php echo $catefirst; ?>">
                            <input type="hidden" id="second_id" name="second_id" value="<?php echo $catesecond; ?>">
                            <input type="hidden" id="third_id" name="third_id" value="<?php echo $third_id; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="products_category_second">สินค้า</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputSecond"><i class="ti-pin-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="products_category_second" name="products_category_second" placeholder="" aria-describedby="inputSecond" autocomplete="off" value="<?php echo htmlspecialchars($second_name); ?>" disabled>
                                        <div class="invalid-feedback">กรุณาระบุชื่อสินค้า</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="third_periods_from">ระยะเวลาที่เริ่ม</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFrom"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_periods_from" name="third_periods_from" placeholder="" aria-describedby="inputFrom" value="<?php echo $third_periods_from; ?>" disabled required>
                                        <div class="invalid-feedback">กรุณาระบุระยะเวลาที่เริ่ม</div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="third_periods_to">ระยะเวลาที่สิ้นสุด</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputTo"><i class="ti-timer"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_periods_to" name="third_periods_to" placeholder="" aria-describedby="inputTo" value="<?php echo $third_periods_to; ?>" disabled required>
                                        <div class="invalid-feedback">กรุณาระบุระยะเวลาที่สิ้นสุด</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="third_rate_1" id="label_third_rate_1">ราคาผู้ใหญ่/คัน/ห้อง/คืน</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputRate1"><i class="ti-wallet"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="third_rate_1" name="third_rate_1" placeholder="" aria-describedby="inputRate1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_1); ?>" oninput="priceformat('third_rate_1');" disabled>
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
                                            <input type="text" class="form-control" id="third_rate_3" name="third_rate_3" placeholder="" aria-describedby="inputRate3" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_rate_3); ?>" oninput="priceformat('third_rate_3');" disabled>
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
                                        <label for="third_charter_1">ราคาเช่าเหมาลำ ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputCharter1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_charter_1" name="third_charter_1" placeholder="" aria-describedby="inputCharter1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_charter_1); ?>" oninput="priceformat('third_charter_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคาเช่าเหมาลำ</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_charter_2">ราคาเช่าเหมาลำ ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
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
                                    <div class="col-md-3 mb-3">
                                        <label for="third_group_1">ราคากลุ่ม ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_group_1" name="third_group_1" placeholder="" aria-describedby="inputGroup1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_group_1); ?>" oninput="priceformat('third_group_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคากลุ่ม</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_group_2">ราคากลุ่ม ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_group_2" name="third_group_2" placeholder="" aria-describedby="inputGroup2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_group_2); ?>" oninput="priceformat('third_group_2');" required <?php if ($ptype != 2) {
                                                                                                                                                                                                                                                                                                                            echo "readonly";
                                                                                                                                                                                                                                                                                                                        } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคากลุ่ม (ราคาขาย)</div>
                                        </div>
                                    </div>
                                <?php }
                                if ($ptype == '1' || $ptype == '2') { ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_pax">จำนวนคน (<span style="color:#3DB3E4; font-weight:bold">ราคาเช่าเหมาลำ/ราคากลุ่ม</span>)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputPax"><i class="ti-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_pax" name="third_pax" placeholder="" aria-describedby="inputPax" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_pax); ?>" oninput="priceformat('third_pax');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุจำนวนคน</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_transfer_1">ราคารถรับส่ง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputTransfer1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_transfer_1" name="third_transfer_1" placeholder="" aria-describedby="inputTransfer1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_transfer_1); ?>" oninput="priceformat('third_transfer_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคารถรับส่ง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_transfer_2">ราคารถรับส่ง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
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
                                        <label for="third_hours_no">จำนวนชั่วโมง</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputHours"><i class="ti-timer"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_hours_no" name="third_hours_no" placeholder="" aria-describedby="inputHours" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_hours_no); ?>" oninput="priceformat('third_hours_no');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุจำนวนชั่วโมง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extra_hour_1">ราคาต่อชั่วโมง ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrahour1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extra_hour_1" name="third_extra_hour_1" placeholder="" aria-describedby="inputExtrahour1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extra_hour_1); ?>" oninput="priceformat('third_extra_hour_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคาต่อชั่วโมง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extra_hour_2">ราคาต่อชั่วโมง ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
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
                                        <label for="third_extrabeds_1">ราคาเตียงเสริม</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_1" name="third_extrabeds_1" placeholder="" aria-describedby="inputExtrabeds1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_1); ?>" oninput="priceformat('third_extrabeds_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริม</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_extrabeds_2">ราคาเตียงเสริม ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputExtrabeds2"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_extrabeds_2" name="third_extrabeds_2" placeholder="" aria-describedby="inputExtrabeds2" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_extrabeds_2); ?>" oninput="priceformat('third_extrabeds_2');" required <?php if ($ptype != 4) {
                                                                                                                                                                                                                                                                                                                                                echo "readonly";
                                                                                                                                                                                                                                                                                                                                            } ?>>
                                            <div class="invalid-feedback">กรุณาระบุราคาเตียงเสริม (ราคาขาย)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_sharingbed_1">ราคาแชร์เตียง</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputSharingbed1"><i class="ti-wallet"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="third_sharingbed_1" name="third_sharingbed_1" placeholder="" aria-describedby="inputSharingbed1" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($third_sharingbed_1); ?>" oninput="priceformat('third_sharingbed_1');" disabled>
                                            <div class="invalid-feedback">กรุณาระบุราคาแชร์เตียง</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="third_sharingbed_2">ราคาแชร์เตียง ( <span style="color:#3DB3E4; font-weight:bold">ราคาขาย</span> )</label>
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
                                <?php } ?>
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
        if (ptype.value == 3) {
            label_third_rate_1.innerHTML = 'ราคา/คัน ( <span style="color:#FF0000; font-weight:bold">ราคาทุน</span> )';
            label_third_rate_2.innerHTML = 'ราคา/คัน ( <span style="color:#F9A035; font-weight:bold">ราคาขาย</span> )';
        }
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
            document.getElementById(inputfield).value = '';
        } else {
            document.getElementById(inputfield).value = n;
        }
    }
</script>