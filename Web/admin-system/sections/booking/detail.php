<?php
if (!empty($_GET["id"])) {
    $query = "SELECT * FROM booking WHERE id > '0'";
    $query .= " AND id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $page_title = "Voucher No. #" . stripslashes($row["voucher_no"]);
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/list'\" >";
    }
} else {
    $page_title = "เพิ่มข้อมูล";
}

$bo_id = !empty($row["id"]) ? $row["id"] : '0';
$bo_status = !empty($row["status"]) ? $row["status"] : '1';
$bo_voucher_no = !empty($row["voucher_no"]) ? $row["voucher_no"] : '';
$bo_booking_date = !empty($row["booking_date"]) ? $row["booking_date"] : $today;
$bo_booking_date_text = !empty($row["booking_date"]) ? date("d F Y", strtotime($row["booking_date"])) : date("d F Y", strtotime($today));
$bo_agent = !empty($row["agent"]) ? $row["agent"] : '0';
$bo_customertype = ($bo_agent > 0 || $bo_id == 0) ? '1' : '2';
$bo_agent_voucher = !empty($row["agent_voucher"]) ? $row["agent_voucher"] : '';
$bo_sale_name = !empty($row["sale_name"]) ? $row["sale_name"] : '';
$bo_customer_firstname = !empty($row["customer_firstname"]) ? $row["customer_firstname"] : '';
$bo_customer_lastname = !empty($row["customer_lastname"]) ? $row["customer_lastname"] : '';
$bo_customer_mobile = !empty($row["customer_mobile"]) ? $row["customer_mobile"] : '';
$bo_customer_email = !empty($row["customer_email"]) ? $row["customer_email"] : '';
$bo_full_receipt = !empty($row["full_receipt"]) ? $row["full_receipt"] : '2';
$bo_receipt_name = !empty($row["receipt_name"]) ? $row["receipt_name"] : '';
$bo_receipt_address = !empty($row["receipt_address"]) ? $row["receipt_address"] : '';
$bo_receipt_taxid = !empty($row["receipt_taxid"]) ? $row["receipt_taxid"] : '';
$bo_receipt_detail = !empty($row["receipt_detail"]) ? $row["receipt_detail"] : '';
$bo_status_email = !empty($row["status_email"]) ? $row["status_email"] : '2';
$bo_status_email_revise = !empty($row["status_email_revise"]) ? $row["status_email_revise"] : '0';
$bo_status_confirm = !empty($row["status_confirm"]) ? $row["status_confirm"] : '2';
$bo_company = !empty($row["company"]) ? $row["company"] : '2';
$bo_amount = !empty($bo_amount) ? number_format($bo_amount) : '-';
$bo_paid = !empty($bo_paid) ? number_format($bo_paid) : '-';
$bo_balance = !empty($bo_balance) ? number_format($bo_balance) : '-';
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
            <div class="col-md-5 align-self-center">
                <!-- <h4 class="text-themecolor">Supplier</h4> -->
                <div class="d-flex align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
            <div class="col-md-7 align-self-center text-right">
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
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=booking/save" novalidate>
                            <input type="hidden" id="bo_id" name="bo_id" value="<?php echo $bo_id; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <input type="hidden" id="bo_voucher_no_duplicate" name="bo_voucher_no_duplicate" value="false">
                            <input type="hidden" id="bo_status_email" name="bo_status_email" value="<?php echo $bo_status_email; ?>">
                            <input type="hidden" id="bo_status_email_revise" name="bo_status_email_revise" value="<?php echo $bo_status_email_revise; ?>">
                            <input type="hidden" id="bo_status_confirm" name="bo_status_confirm" value="<?php echo $bo_status_confirm; ?>">
                            <div class="form-row">
                                <div class="col-md-1 mb-3">
                                    <label for="bo_status">สถานะ</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bo_status" name="bo_status" <?php if ($bo_status == 2) {
                                                                                                                                echo "checked";
                                                                                                                            } ?> value="2">
                                        <label class="custom-control-label" for="bo_status">ยกเลิก</label>
                                    </div>
                                </div>
                                <div class="col-md-1 mb-3">
                                    <label for="bo_customertype">ประเภทลูกค้า</label>
                                    <?php
                                    if ($bo_id > 0) {
                                        $customertype = ($bo_agent > 0) ? '1' : '2';
                                        $customertype_name = ($bo_agent > 0) ? 'เอเย่นต์' : 'ลูกค้าทั่วไป';
                                        echo "<br /><span style=\"font-size:16px; font-weight:bold; color:#0D84DE\">" . $customertype_name . "</span>";
                                        echo "<br /><input type=\"hidden\" id=\"bo_customertype\" name=\"bo_customertype\" value=\"" . $customertype . "\">";
                                    } else {
                                    ?>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customertype1" name="bo_customertype" class="custom-control-input" <?php if ($bo_customertype == 1) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?> value="1" onclick="checkCustomertype(this)" required>
                                            <label class="custom-control-label" for="customertype1">เอเย่นต์</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="customertype2" name="bo_customertype" class="custom-control-input" <?php if ($bo_customertype == 2) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?> value="2" onclick="checkCustomertype(this)" required>
                                            <label class="custom-control-label" for="customertype2">ลูกค้าทั่วไป</label>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <label for="bo_voucher_no">Voucher No.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputVoucher"><i class="ti-ticket"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_voucher_no" name="bo_voucher_no" placeholder="" aria-describedby="inputVoucher" pattern="^[A-Za-z0-9]+$" autocomplete="off" onkeyup="checkVoucher();" value="<?php echo $bo_voucher_no; ?>" required <?php echo ($bo_id > 0) ? 'readonly' : ''; ?>>
                                        <div class="invalid-feedback" id="bo_voucher_no_feedback">กรุณาระบุ Voucher No.</div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <label for="bo_booking_date">วันที่จอง</label><br />
                                    <span style="font-size:16px"><?php echo $bo_booking_date_text; ?></span>
                                    <input type="hidden" class="form-control" id="bo_booking_date" name="bo_booking_date" placeholder="" value="<?php echo $bo_booking_date; ?>" readonly>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <label for="bo_amount">รวมทั้งหมด</label><br />
                                    <span style="font-size:16px; font-weight:bold; color:#059DDE" id="price_total"></span>
                                    <?php // echo $bo_amount; 
                                    ?>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <label for="bo_paid">จ่ายแล้ว</label><br />
                                    <span style="font-size:16px; font-weight:bold; color:#12AC5D" id="price_paid"></span>
                                    <?php // echo $bo_paid; 
                                    ?>
                                </div>
                                <div class="col-md-2 mb-3 text-center">
                                    <label for="bo_balance">ยอดเหลือ</label><br />
                                    <span style="font-size:16px; font-weight:bold; color:#BD1A2A" id="price_balance"></span>
                                    <?php // echo $bo_balance; 
                                    ?>
                                </div>
                            </div>

                            <hr>

                            <!-- <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="bo_customertype">เอเย่นต์ / ลูกค้าทั่วไป</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customertype1" name="bo_customertype" class="custom-control-input"
                                                <?php if ($bo_customertype == 1) {
                                                    echo "checked";
                                                } ?> value="1" onclick="checkCustomertype(this)" required>
                                                <label class="custom-control-label" for="customertype1">เอเย่นต์</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customertype2" name="bo_customertype" class="custom-control-input"
                                                <?php if ($bo_customertype == 2) {
                                                    echo "checked";
                                                } ?> value="2" onclick="checkCustomertype(this)" required>
                                                <label class="custom-control-label" for="customertype2">ลูกค้าทั่วไป</label>
                                            </div>
                                        </div>
                                    </div>

                                    <hr> -->

                            <!-- <p><span class="card-title" style="font-size: 1.125rem">ข้อมูลเอเย่นต์</span></p> -->
                            <div class="form-row" id="agentdiv">
                                <div class="col-md-3 mb-3">
                                    <label for="bo_agent">เอเย่นต์</label>
                                    <?php
                                    if ($bo_id > 0) {
                                        echo "<br /><span style=\"font-size:16px\">" . get_value("agent", "id", "company", $bo_agent, $mysqli_p) . "</span>";
                                        echo "<br /><input type=\"hidden\" id=\"bo_agent\" name=\"bo_agent\" value=\"" . $bo_agent . "\">";
                                    } else {
                                    ?>
                                        <select class="custom-select" id="bo_agent" name="bo_agent">
                                            <option value="0" <?php if ($bo_agent == 0) {
                                                                    echo "selected";
                                                                } ?>>-</option>
                                            <?php
                                            $query_agent = "SELECT * FROM agent WHERE id > '0'";
                                            $query_agent .= " ORDER BY company ASC";
                                            $result_agent = mysqli_query($mysqli_p, $query_agent);
                                            while ($row_agent = mysqli_fetch_array($result_agent, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row_agent["id"]; ?>" <?php if ($bo_agent == $row_agent["id"]) {
                                                                                                    echo "selected";
                                                                                                } ?>>
                                                    <?php echo $row_agent["company"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_agent_voucher">Voucher No. (เอเย่นต์)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputAgentvoucher"><i class="ti-ticket"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_agent_voucher" name="bo_agent_voucher" placeholder="" aria-describedby="inputAgentvoucher" value="<?php echo $bo_agent_voucher; ?>">
                                        <div class="invalid-feedback">กรุณาระบุ Voucher No. (เอเย่นต์)</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_sale_name">ชื่อเซลล์</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputSale"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_sale_name" name="bo_sale_name" placeholder="" aria-describedby="inputSale" value="<?php echo $bo_sale_name; ?>">
                                        <div class="invalid-feedback">กรุณาระบุชื่อเซลล์</div>
                                    </div>
                                </div>
                            </div>

                            <!-- <p><span class="card-title" style="font-size: 1.125rem">ข้อมูลลูกค้า</span></p> -->
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="bo_sale_name">ชื่อ (ลูกค้า)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFirstname"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_customer_firstname" name="bo_customer_firstname" placeholder="" aria-describedby="inputFirstname" value="<?php echo $bo_customer_firstname; ?>">
                                        <div class="invalid-feedback">กรุณาระบุชื่อ (ลูกค้า)</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_customer_lastname">นามสกุล (ลูกค้า)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputLastname"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_customer_lastname" name="bo_customer_lastname" placeholder="" aria-describedby="inputLastname" value="<?php echo $bo_customer_lastname; ?>">
                                        <div class="invalid-feedback">กรุณาระบุนามสกุล (ลูกค้า)</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_customer_mobile">เบอร์โทรศัพท์ (ลูกค้า)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputMobile"><i class="ti-mobile"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_customer_mobile" name="bo_customer_mobile" placeholder="" aria-describedby="inputMobile" value="<?php echo $bo_customer_mobile; ?>">
                                        <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์ (ลูกค้า)</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3" id="customerdiv">
                                    <label for="bo_customer_email">อีเมล์ (ลูกค้า)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputEmail"><i class="ti-email"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="bo_customer_email" name="bo_customer_email" placeholder="" aria-describedby="inputEmail" value="<?php echo $bo_customer_email; ?>">
                                        <div class="invalid-feedback">กรุณาระบุอีเมล์ (ลูกค้า)</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="bo_full_receipt">ใบเสร็จรับเงิน</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="bo_full_receipt" name="bo_full_receipt" <?php if ($bo_full_receipt != 2 || !isset($bo_full_receipt)) {
                                                                                                                                            echo "checked";
                                                                                                                                        } ?> value="1">
                                        <label class="custom-control-label" for="bo_full_receipt">แบบเต็ม</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_receipt_name">ชื่อ</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bo_receipt_name" name="bo_receipt_name" placeholder="" aria-describedby="inputReceiptname" value="<?php echo $bo_receipt_name; ?>">
                                        <div class="invalid-feedback">กรุณาระบุชื่อ</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_receipt_address">ที่อยู่</label>
                                    <div class="input-group">
                                        <textarea class="form-control" id="bo_receipt_address" name="bo_receipt_address" aria-describedby="inputReceiptaddress" rows="3"><?php echo $bo_receipt_address; ?></textarea>
                                        <div class="invalid-feedback">กรุณาระบุที่อยู่</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_receipt_taxid">หมายเลขประจำตัวผู้เสียภาษี</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bo_receipt_taxid" name="bo_receipt_taxid" placeholder="" aria-describedby="inputTaxid" value="<?php echo $bo_receipt_taxid; ?>">
                                        <div class="invalid-feedback">กรุณาระบุหมายเลขประจำตัวผู้เสียภาษี</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="bo_receipt_name">รายละเอียด</label>
                                    <div class="input-group">
                                        <textarea class="form-control" id="bo_receipt_detail" name="bo_receipt_detail" aria-describedby="inputReceiptdetail" rows="3"><?php echo $bo_receipt_detail; ?></textarea>
                                        <div class="invalid-feedback">กรุณาระบุชื่อ</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="bo_company">บริษัท</label>
                                    <div class="input-group">
                                        <select class="custom-select" id="bo_company" name="bo_company" required aria-describedby="inputCompany">
                                            <option value="" <?php if ($bo_company == 0) {
                                                                    echo "selected";
                                                                } ?>>-</option>
                                            <?php
                                            $query_com = "SELECT * FROM company WHERE id > '0'";
                                            $query_com .= " ORDER BY name ASC";
                                            $result_com = mysqli_query($mysqli_p, $query_com);
                                            while ($row_com = mysqli_fetch_array($result_com, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row_com["id"]; ?>" <?php if ($bo_company == $row_com["id"]) {
                                                                                                    echo "selected";
                                                                                                } ?>>
                                                    <?php echo $row_com["name"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">กรุณาระบุ บริษัท</div>
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
                                            var bo_voucher_no = document.getElementById('bo_voucher_no').value;
                                            var bo_voucher_no_duplicate = document.getElementById('bo_voucher_no_duplicate').value;

                                            if (bo_voucher_no_duplicate == "false") {
                                                bo_voucher_no_duplicate = false;
                                            } else {
                                                bo_voucher_no_duplicate = true;
                                            }

                                            if (form.checkValidity() === false || bo_voucher_no_duplicate === false) {
                                                // $("#bo_voucher_no").next("div.invalid-feedback").text("Please enter a new username because the username is a duplicate or username has not been specified.");
                                                if (bo_voucher_no_duplicate === false) {
                                                    // bo_voucher_no.value = "";
                                                    // alert(bo_voucher_no);
                                                    document.getElementById('bo_voucher_no').value = "";
                                                }
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

            <?php
            if (!empty($bo_id)) {
            ?>

                <!-- Payment Table -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> ธุรกรรมการชำระเงิน</h4>
                            <!-- <h6 class="card-subtitle">Description</h6> -->
                            <a href="<?php echo './?mode=booking/payment-detail&booking=' . $bo_id; ?>" class="btn btn-info m-t-10 m-r-15"><i class="fa fa-plus-circle"></i> เพิ่มธุรกรรม </a>
                            <a href="#print" onclick="printPayment(<?php echo $bo_id; ?>)" class="btn btn-info m-t-10 m-r-15" style="float:right;"><i class="fa fa-print"></i> พิมพ์ Voucher </a>
                            <!-- <a href="<?php echo './?mode=booking/print-payment&id=' . $bo_id; ?>" class="btn btn-info m-t-10 m-r-15" style="float:right;"><i class="fa fa-print"></i> พิมพ์ Voucher </a> -->
                            <div class="table-responsive m-t-10">
                                <table id="paid-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                    <thead>
                                        <tr align="center">
                                            <th>สถานะ</th>
                                            <th>เลขใบเสร็จ</th>
                                            <th>สินค้า (วันที่เที่ยว)</th>
                                            <th>วันที่ชำระ</th>
                                            <th>จำนวนเงิน</th>
                                            <th>แก้ไข / ดู</th>
                                            <th>ยกเลิก / ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $date_travel = '';
                                        $sum_paid = 0;
                                        $query_pb = "SELECT PB.*,
                                                     BK.id as bkID, BK.voucher_no as bkVoucher,
                                                     BP.id as bpID, BP.products_category_second as bpPCS, BP.products_type as bpType, 
                                                     BP.travel_date as bpTravel_date, BP.checkin_date as bpCheckin_date,
                                                     PCS.id as pcsID, PCS.name as pcsName
                                                     FROM payments_booking PB
                                                     LEFT JOIN booking BK
                                                        ON PB.booking = BK.id
                                                     LEFT JOIN booking_products BP
                                                        ON PB.booking_products = BP.id
                                                     LEFT JOIN products_category_second PCS
                                                        ON BP.products_category_second = PCS.id
                                                     WHERE PB.booking = '" . $bo_id . "' ";
                                        $procedural_statement_pb = mysqli_prepare($mysqli_p, $query_pb);
                                        mysqli_stmt_execute($procedural_statement_pb);
                                        $result_pb = mysqli_stmt_get_result($procedural_statement_pb);
                                        while ($row_pb = mysqli_fetch_array($result_pb, MYSQLI_ASSOC)) {
                                            $status_class = $row_pb["status"] == 1 ? 'success' : 'danger';
                                            $status_txt = $row_pb["status"] == 1 ? 'ออนไลน์' : 'ยกเลิก';
                                            $date_travel = $row_pb["bpType"] == 4 ? $row_pb["bpCheckin_date"] : $row_pb["bpTravel_date"];
                                            $name_payment = $row_pb["booking_products"] == 0 ? 'ชำระทั้งหมด' : $row_pb['pcsName'] . '<br />(' . date("d F Y", strtotime($date_travel)) . ')';
                                            $sum_paid = $row_pb["status"] == 1 ? $sum_paid + $row_pb['amount_paid'] : $sum_paid;
                                        ?>
                                            <tr>
                                                <td align="center"> <span class="label label-<?php echo $status_class; ?>"><?php echo $status_txt; ?></span> </td>
                                                <td align="center" style="font-weight: bold"> <?php echo $row_pb['receip_no']; ?> </td>
                                                <td align="center"> <?php echo $name_payment; ?> </td>
                                                <td align="center"> <?php echo $row_pb['date_paid']; ?> </td>
                                                <td align="center"> <?php echo number_format($row_pb['amount_paid']); ?> </td>
                                                <td align="center" width="7%">
                                                    <a href="./?mode=booking/payment-detail&booking=<?php echo $bo_id; ?>&id=<?php echo $row_pb["id"]; ?>" title="Edit"><i class="fas fa-edit" style="color:#0D84DE"></i></a>
                                                </td>
                                                <td align="center" width="7%">
                                                    <?php if ($_SESSION["admin"]["permission"] == 1) { ?>
                                                        <?php if ($row_pb['status'] == '2' && $row_pb["trash_deleted"] == '1') { ?>
                                                            <a href="#restore" onclick="restorePayment('<?php echo $row_pb['id']; ?>', '<?php echo $row_pb['booking']; ?>', 'restorepayment');" title="Restore"><i class="ti-reload" style="color:#0CDE66"></i></a>
                                                        <?php } else { ?>
                                                            <a href="#deleted" onclick="deletePayment('<?php echo $row_pb['id']; ?>', '<?php echo $row_pb['booking']; ?>', 'deletepayment');" title="Delete"><i class="fas fa-trash-alt" style="color:#FF0000"></i></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                                <input type="hidden" id="type_pay<?php echo $row_pb["id"]; ?>" name="type_pay[]" value="<?php echo $row_pb["accounts"]; ?>">
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Print Payment Hidden -->
                <input type="hidden" id="sum_paid" name="sum_paid" value="<?php echo $sum_paid; ?>">
                <input type="hidden" id="total_balance" name="total_balance" value="">

                <!-- Validation Form -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">อีเมล์</h4>
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="remark_email">เพิ่มเติม (Remark)</label>
                                    <div class="input-group">
                                        <textarea class="form-control" id="remark_email" name="remark_email" rows="5"></textarea>
                                        <div class="invalid-feedback">กรุณาระบุที่อยู่</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <button class="btn btn-primary" type="submit" onclick="check_confirm_email('checkemailconfirm');">ส่งอีเมล์</button>

                        </div>
                    </div>
                </div>


                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">รายการสินค้า</h4>
                            <?php
                            $query_ptype = "SELECT * FROM products_type WHERE id > '0'";
                            $query_ptype .= " ORDER BY id ASC";
                            $procedural_statement = mysqli_prepare($mysqli_p, $query_ptype);
                            mysqli_stmt_execute($procedural_statement);
                            $result = mysqli_stmt_get_result($procedural_statement);

                            while ($row_ptype = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $ptype = $row_ptype["id"];
                                $ptype_name = stripslashes($row_ptype["name_text_thai"]);
                            ?>
                                <button type="button" class="btn btn-info m-b-10 m-r-15" onclick="window.location.href='./?mode=booking/product-detail&ptype=<?php echo $ptype; ?>&booking=<?php echo $bo_id; ?>'">
                                    <i class="fa fa-plus-circle"></i> เพิ่มสินค้า<?php echo $ptype_name; ?></button>
                            <?php
                            }
                            ?>

                            <?php
                            $first_before = 0;
                            $numrow_realtime = 1;
                            $price_total = 0;
                            $check_cancel = 0;
                            $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname, products_type.id as ptypeid,
                                                    products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                                                    booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai
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
                                                WHERE booking_products.booking = '$bo_id'
                            ";
                            $query_products .= " ORDER BY booking_products.products_type ASC, booking_products.products_category_first ASC";
                            $query_products .= " , booking_products.travel_date ASC";
                            // echo "<br />" . $query_products . "<br />";
                            $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
                            mysqli_stmt_execute($procedural_statement);
                            $result = mysqli_stmt_get_result($procedural_statement);
                            $numrow_products = mysqli_num_rows($result);

                            while ($row_products = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                $check_cancel = $row_products["status_confirm_op"] == '1' ? $check_cancel + 1 : $check_cancel;
                                if ($row_products["date_not_specified"] == 2) {
                                    $travel_date = date("d F Y", strtotime($row_products["travel_date"]));
                                    $checkin_date = date("d F Y", strtotime($row_products["checkin_date"]));
                                    $checkout_date = date("d F Y", strtotime($row_products["checkout_date"]));
                                    $price_latest = ($bo_agent == 0) ? number_format($row_products["price_latest"]) : '-';
                                    // $price_latest = number_format($row_products["price_latest"]);
                                    // $price_total = $row_products["trash_deleted"] != 1 ? $price_total + $row_products["price_latest"] : $price_total;
                                    $price_total = $price_total + $row_products["price_latest"];
                                } else {
                                    $travel_date = "ไม่ระบุวันที่";
                                    $checkin_date = "ไม่ระบุวันที่";
                                    $checkout_date = "ไม่ระบุวันที่";
                                    $price_latest = "-";
                                }
                                if ($first_before != $row_products["pcfid"]) {
                                    echo ($numrow_realtime != 1) ? '</tbody></table></div>' : '';
                            ?>
                                    <hr>
                                    <h4 class="card-title"><?php echo $row_products["pcfname"]; ?> <span style="float:right; color:#F8A440; font-size:16px">ข้อมูลการจองสินค้า<?php echo $row_products["ptypenamethai"]; ?></span></h4>

                                    <div class="table-responsive">
                                        <table class="table color-bordered-table muted-bordered-table">
                                            <thead>
                                                <?php if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) { ?>
                                                    <tr align="center">
                                                        <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                        <th style="vertical-align:middle">วันที่เที่ยว</th>
                                                        <th style="vertical-align:middle">ผู้ใหญ่</th>
                                                        <th style="vertical-align:middle">เด็ก</th>
                                                        <th style="vertical-align:middle">ทารก</th>
                                                        <th style="vertical-align:middle">FOC</th>
                                                        <th style="vertical-align:middle">สถานที่รับ</th>
                                                        <th style="vertical-align:middle">สถานที่ส่ง</th>
                                                        <th style="vertical-align:middle">ชาวต่างชาติ</th>
                                                        <th style="vertical-align:middle">ราคา</th>
                                                        <th style="vertical-align:middle">สถานะการส่งอีเมล์</th>
                                                        <th style="vertical-align:middle">สถานะการยืนยัน</th>
                                                        <th style="vertical-align:middle">หมายเหตุ</th>
                                                        <th style="vertical-align:middle">แก้ไข / ดู</th>
                                                        <th style="vertical-align:middle">ยกเลิก / ลบ</th>
                                                        <?php if ($_SESSION["admin"]["permission"] == 1 && $bo_status == '1') {
                                                            echo "<th>คืน</th>";
                                                        } ?>
                                                    </tr>

                                                <?php } else if ($row_products["ptypeid"] == 3) { ?>
                                                    <tr align="center">
                                                        <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                        <th style="vertical-align:middle">วันที่เดินทาง</th>
                                                        <th style="vertical-align:middle">จำนวนคัน</th>
                                                        <th style="vertical-align:middle">จำนวนชั่วโมง</th>
                                                        <th style="vertical-align:middle">สถานที่รับ</th>
                                                        <th style="vertical-align:middle">เวลารับ</th>
                                                        <th style="vertical-align:middle">สถานที่ส่ง</th>
                                                        <th style="vertical-align:middle">เวลาส่ง</th>
                                                        <th style="vertical-align:middle">ราคา</th>
                                                        <th style="vertical-align:middle">สถานะการส่งอีเมล์</th>
                                                        <th style="vertical-align:middle">สถานะการยืนยัน</th>
                                                        <th style="vertical-align:middle">หมายเหตุ</th>
                                                        <th style="vertical-align:middle">แก้ไข / ดู</th>
                                                        <th style="vertical-align:middle">ยกเลิก / ลบ</th>
                                                        <?php if ($_SESSION["admin"]["permission"] == 1 && $bo_status == '1') {
                                                            echo "<th>คืน</th>";
                                                        } ?>
                                                    </tr>

                                                <?php } else if ($row_products["ptypeid"] == 4) { ?>
                                                    <tr align="center">
                                                        <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                        <th style="vertical-align:middle">วันที่เช็คอิน</th>
                                                        <th style="vertical-align:middle">วันที่เช็คเอาท์</th>
                                                        <th style="vertical-align:middle">จำนวนห้อง</th>
                                                        <th style="vertical-align:middle">จำนวนเตียงเสริม</th>
                                                        <th style="vertical-align:middle">จำนวนแชร์เตียง</th>
                                                        <th style="vertical-align:middle">ราคา</th>
                                                        <th style="vertical-align:middle">สถานะการส่งอีเมล์</th>
                                                        <th style="vertical-align:middle">สถานะการยืนยัน</th>
                                                        <th style="vertical-align:middle">หมายเหตุ</th>
                                                        <th style="vertical-align:middle">แก้ไข / ดู</th>
                                                        <th style="vertical-align:middle">ยกเลิก / ลบ</th>
                                                        <?php if ($_SESSION["admin"]["permission"] == 1 && $bo_status == '1') {
                                                            echo "<th>คืน</th>";
                                                        } ?>
                                                    </tr>
                                                <?php } ?>
                                            </thead>
                                            <tbody style="font-weight:500">
                                            <?php
                                            $first_before = $row_products["pcfid"];
                                        }
                                            ?>
                                            <tr>
                                                <td width="15%"><?php echo $row_products["pcsname"]; ?></td>
                                                <?php if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) { ?>
                                                    <td align="center"><span style="color:#2E8E9C"><?php echo $travel_date; ?></span></td>
                                                    <td align="center"><?php echo number_format($row_products["adults"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["children"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["infant"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["foc"]); ?></td>
                                                    <td align="center"><?php echo ($row_products["pickup"] > 0) ? get_value("place", "id", "name", $row_products["pickup"], $mysqli_p) : 'N/A'; ?></td>
                                                    <td align="center"><?php echo ($row_products["dropoff"] > 0) ? get_value("place", "id", "name", $row_products["dropoff"], $mysqli_p) : 'N/A'; ?></td>
                                                    <td align="center"><?php echo number_format($row_products["foreigner_no"]); ?></td>
                                                <?php } else if ($row_products["ptypeid"] == 3) { ?>
                                                    <td align="center"><span style="color:#2E8E9C"><?php echo $travel_date; ?></span></td>
                                                    <td align="center"><?php echo number_format($row_products["no_cars"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["no_hours"]); ?></td>
                                                    <td align="center"><?php echo ($row_products["pickup"] > 0) ? get_value("place", "id", "name", $row_products["pickup"], $mysqli_p) : 'N/A'; ?></td>
                                                    <td align="center"><?php echo $row_products["pickup_time"]; ?></td>
                                                    <td align="center"><?php echo ($row_products["dropoff"] > 0) ? get_value("place", "id", "name", $row_products["dropoff"], $mysqli_p) : 'N/A'; ?></td>
                                                    <td align="center"><?php echo date('H:i', strtotime($row_products["dropoff_time"])); ?></td>
                                                <?php } else if ($row_products["ptypeid"] == 4) { ?>
                                                    <td align="center"><span style="color:#2E8E9C"><?php echo $checkin_date; ?></span></td>
                                                    <td align="center"><span style="color:#2E8E9C"><?php echo $checkout_date; ?></span></td>
                                                    <td align="center"><?php echo number_format($row_products["no_rooms"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["extra_beds"]); ?></td>
                                                    <td align="center"><?php echo number_format($row_products["share_bed"]); ?></td>
                                                <?php } ?>
                                                <td align="center"><span style="color:#0D84DE"><?php echo $price_latest; ?></span></td>
                                                <!-- <td align="center"><?php echo $row_products["emailthai"]; ?></td> -->
                                                <td align="center">
                                                    <div class="custom-control custom-checkbox mb-3">
                                                        <input type="checkbox" class="custom-control-input" id="check_email<?php echo $row_products["id"]; ?>" name="check_email[]" value="<?php echo $row_products["id"]; ?>" <?php if ($row_products["trash_deleted"] == 1) {
                                                                                                                                                                                                                                    echo 'disabled';
                                                                                                                                                                                                                                } ?>>
                                                        <label class="custom-control-label" for="check_email<?php echo $row_products["id"]; ?>"></label>
                                                    </div>
                                                    <?php if ($row_products["trash_deleted"] == 0) { ?> <span><?php echo $row_products["emailthai"]; ?> </span> <?php } ?>
                                                </td>
                                                <td align="center">
                                                    <div class="custom-control custom-checkbox mb-3">
                                                        <input type="checkbox" class="custom-control-input" id="check_confirm<?php echo $row_products["id"]; ?>" name="check_confirm<?php echo $row_products["id"]; ?>" <?php if ($row_products["status_confirm"] != 2) {
                                                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                                                        } ?> value="1" <?php if ($row_products["trash_deleted"] == 1 || $row_products["status_confirm_op"] == 1 || $row_products["date_not_specified"] == 1) {
                                                                                                                                                                                                                                            echo 'disabled';
                                                                                                                                                                                                                                        } ?> onclick="check_confirm_name('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checknameconfirm');">
                                                        <label class="custom-control-label" for="check_confirm<?php echo $row_products["id"]; ?>"></label>
                                                    </div>
                                                </td>
                                                <!-- <td align="center"><a href="#" data-toggle="modal" data-target="#modalproducts<?php echo $row_products["id"]; ?>" <?php echo (empty($row_products["notes"])) ? 'class="disabled"' : ''; ?>><i class="fas fa-eye"></i></a></td> -->
                                                <td align="center"><a href="#" data-toggle="modal" data-target="#modalproducts<?php echo $row_products["id"]; ?>"><i class="fas fa-eye"></i></a></td>
                                                <td align="center" width="7%">
                                                    <?php if ($row_products["invoice"] == 1) { ?>
                                                        <a href="#" data-toggle="modal" data-target="#modaldetail<?php echo $row_products["id"]; ?>"><i class="fas fa-eye"></i></a>
                                                    <?php } else { ?>
                                                        <a href="./?mode=booking/product-detail&ptype=<?php echo $row_products["ptypeid"]; ?>&booking=<?php echo $bo_id; ?>&id=<?php echo $row_products["id"]; ?>" title="Edit"><i class="fas fa-edit" style="color:#0D84DE"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td align="center" width="7%">
                                                    <a href="#deleted" onclick="deleteProduct('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'deleteproducts');" title="Delete">
                                                        <i class="fas fa-trash-alt" style="color:#FF0000"></i></a>
                                                </td>
                                                <?php if ($_SESSION["admin"]["permission"] == 1 && $bo_status == '1') { ?>
                                                    <td align="center" width="7%">
                                                        <?php if ($row_products["trash_deleted"] == 1) { ?>
                                                            <a href="#restore" onclick="restoreProduct('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', '<?php echo $row_products['ptypeid']; ?>', '<?php echo $row_products['products_category_second']; ?>', 'restoreproducts');" title="Restore"><i class="ti-reload" style="color:#0CDE66"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <!-- modal content detail -->
                                            <div id="modalproducts<?php echo $row_products["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="vcenter">หมายเหตุ</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                <?php echo 'สถานะการยืนยัน : ' . $row_products["confirmthai"]; ?>
                                                                <?php echo $row_products["status_confirm"] == 1 ? '</br> ยืนยันโดย : ' . $row_products["status_confirm_by"] : '' ?>
                                                            </p>
                                                            <?php if (!empty($row_products["notes"])) { ?><p><?php echo nl2br($row_products["notes"]); ?></p><?php } ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal detail -->

                                            <!-- modal content edit -->
                                            <div id="modaldetail<?php echo $row_products["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" id="vcenter">รายละเอียด</h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p style="font-size: 1.2em;">
                                                                <?php
                                                                $m_pickup = ($row_products["pickup"] > 0) ? get_value("place", "id", "name", $row_products["pickup"], $mysqli_p) : 'N/A';
                                                                $m_dropoff = ($row_products["dropoff"] > 0) ? get_value("place", "id", "name", $row_products["dropoff"], $mysqli_p) : 'N/A';
                                                                $m_zones = ($row_products["zones"] > 0) ? get_value("zones", "id", "name", $row_products["zones"], $mysqli_p) : 'N/A';
                                                                $m_roomno = (!empty($row_products["roomno"])) ? $row_products["roomno"]  : 'N/A';
                                                                if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
                                                                    $m_transfer = ($row_products["transfer"] == 1) ? 'ใช่' : 'ไม่ใช่';
                                                                    echo "<span style='font-weight: bold;'> วันที่เที่ยว / วันที่เดินทาง : </span>" . $travel_date . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สินค้า : </span>" . $row_products["pcsname"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ผู้ใหญ๋/เด็ก/ทารก/FOC : </span>" . $row_products["adults"] . "/" . $row_products["children"] . "/" . $row_products["infant"] . "/" . $row_products["foc"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> เพิ่มรถรับส่ง : </span>" . $m_transfer . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่รับ : </span>" . $m_pickup . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่ส่ง : </span>" . $m_dropoff . "</br>";
                                                                    echo "<span style='font-weight: bold;'> โซน : </span>" . $m_zones . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ห้องพัก : </span>" . $m_roomno . "</br>";
                                                                    if ($row_products["foreigner"] == 1) {
                                                                        echo "<span style='font-weight: bold;'> ชาวต่างชาติ : </span> มี </br>";
                                                                        echo "<span style='font-weight: bold;'> จำนวนคน : </span>" . $row_products["foreigner_no"] . "</br>";
                                                                    }
                                                                    echo "<span style='font-weight: bold;'> ราคา : </span>" . $price_latest;
                                                                } else if ($row_products["ptypeid"] == 3) {
                                                                    echo "<span style='font-weight: bold;'> วันที่เที่ยว / วันที่เดินทาง : </span>" . $travel_date . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สินค้า : </span>" . $row_products["pcsname"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ผู้ใหญ๋/เด็ก/ทารก/FOC : </span>" . $row_products["adults"] . "/" . $row_products["children"] . "/" . $row_products["infant"] . "/" . $row_products["foc"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่รับ : </span>" . $m_pickup . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่ส่ง : </span>" . $m_dropoff . "</br>";
                                                                    echo "<span style='font-weight: bold;'> โซน : </span>" . $m_zones . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ห้องพัก : </span>" . $m_roomno . "</br>";
                                                                    echo "<span style='font-weight: bold;'> จำนวนคัน : </span>" . $row_products["no_cars"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> จำนวนชั่วโมง : </span>" . $row_products["no_hours"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> เวลารับ : </span>" . $row_products["pickup_time"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> เวลาส่ง : </span>" . $row_products["dropoff_time"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ราคา : </span>" . $price_latest;
                                                                } else if ($row_products["ptypeid"] == 4) {
                                                                    echo "<span style='font-weight: bold;'> วันที่เช็คอิน : </span>" . $checkin_date . "</br>";
                                                                    echo "<span style='font-weight: bold;'> วันที่เช็คเอาท์ : </span>" . $checkout_date . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สินค้า : </span>" . $row_products["pcsname"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ผู้ใหญ๋/เด็ก/ทารก/FOC : </span>" . $row_products["adults"] . "/" . $row_products["children"] . "/" . $row_products["infant"] . "/" . $row_products["foc"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> จำนวนห้อง : </span>" . $row_products["no_rooms"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> จำนวนเตียงเสริม : </span>" . $row_products["extra_beds"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> จำนวนแชร์เตียง : </span>" . $row_products["share_bed"] . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่รับ : </span>" . $m_pickup . "</br>";
                                                                    echo "<span style='font-weight: bold;'> สถานที่ส่ง : </span>" . $m_dropoff . "</br>";
                                                                    echo "<span style='font-weight: bold;'> โซน : </span>" . $m_zones . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ห้องพัก : </span>" . $m_roomno . "</br>";
                                                                    echo "<span style='font-weight: bold;'> ราคา : </span>" . $price_latest;
                                                                } ?>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal edit -->
                                        <?php
                                        $numrow_realtime++;
                                    }

                                    echo ($numrow_products > 0) ? '</tbody></table></div>' : '';
                                        ?>

                                    </div>
                        </div>
                    </div>
                    <!------ Check Cancel ----->
                    <input type="hidden" id="check_cancel" name="check_cancel" value="<?php echo $check_cancel; ?>" onchange="checkCancel()">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ข้อมูลย้อนหลัง</h4>
                                <!-- <h6 class="card-subtitle">Description</h6> -->
                                <div class="table-responsive m-t-40">
                                    <table id="history-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>สถานะ</th>
                                                <th>สินค้า</th>
                                                <th>ทำรายการโดย</th>
                                                <th>IP Address</th>
                                                <th>วันที่ / เวลา</th>
                                                <th>รายละเอียด</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();

                                            $query_history = "SELECT booking_history.*, history_type.id as historyID, history_type.name_thai as historyName, 
                                                                products_category_second.name as secondName
                                                            FROM booking_history
                                                            LEFT JOIN history_type
                                                                ON booking_history.history_type = history_type.id
                                                            LEFT JOIN booking_products
                                                                ON booking_history.booking_products = booking_products.id
                                                            LEFT JOIN products_category_second
                                                                ON booking_products.products_category_second = products_category_second.id
                                                            WHERE booking_history.booking = ?
                                            ";

                                            if (!empty($bo_id)) {
                                                $bind_types .= "i";
                                                array_push($params, $bo_id);
                                            }
                                            $procedural_statement = mysqli_prepare($mysqli_p, $query_history);

                                            // Check error query
                                            if ($procedural_statement == false) {
                                                die("<pre>" . mysqli_error($mysqli_p) . PHP_EOL . $query_history . "</pre>");
                                            }

                                            if ($bind_types != "") {
                                                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
                                            }

                                            mysqli_stmt_execute($procedural_statement);
                                            $result = mysqli_stmt_get_result($procedural_statement);
                                            $numrow = mysqli_num_rows($result);
                                            while ($row_history = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                $history_name = !empty($row_history["historyName"]) ? $row_history["historyName"] : '-';
                                                $employee_name = !empty($row_history["employee"]) ? get_value("employee", "id", "name", $row_history["employee"], $mysqli_p) : '-';
                                                // $customer_mobile = !empty($row["customer_mobile"]) ? $row["customer_mobile"] : '-';
                                            ?>
                                                <tr>
                                                    <td align="center" style="font-weight: bold"><?php echo $history_name; ?></td>
                                                    <td align="center"><?php echo $row_history["secondName"]; ?></td>
                                                    <td align="center"><?php echo $employee_name; ?></td>
                                                    <td align="center"><?php echo $row_history["ip_address"]; ?></td>
                                                    <td align="center"><?php echo $row_history["create_date"] . " (" . date("d F Y", strtotime($row_history["create_date"])) . ")"; ?></td>
                                                    <td align="center"><a href="#" data-toggle="modal" data-target="#modalhistory<?php echo $row_history["id"]; ?>"><i class="fas fa-eye"></i></a></td>
                                                </tr>

                                                <!-- modal content -->
                                                <div id="modalhistory<?php echo $row_history["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="vcenter"><?php echo $history_name; ?></h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><?php echo nl2br($row_history["description_field"]); ?></p>
                                                                <p style="font-weight:500">( ทำรายการโดย <?php echo $employee_name; ?>, <?php echo $row_history["create_date"]; ?> )</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">ปิด</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            <?php
                                            } /* while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ */

                                            //mysqli_close($mysqli_p);
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="test-mail"></div>

                    <div id="test-delete-email"></div>

                <?php
                $price_paid = '0';
                $tax_in = '';
                $query_bill = "SELECT * FROM bill_invoice WHERE booking = '" . $bo_id . "'";
                $procedural_statement_bill = mysqli_prepare($mysqli_p, $query_bill);
                mysqli_stmt_execute($procedural_statement_bill);
                $result_bill = mysqli_stmt_get_result($procedural_statement_bill);
                while ($row_bill = mysqli_fetch_array($result_bill, MYSQLI_ASSOC)) {
                    $tax_in = get_value("invoice", "id", "payments_vat", $row_bill["invoice"], $mysqli_p);
                    $price_vat = '0';
                    $total_vat = '0';
                    if ($tax_in == '1') {
                        // Vat 0%
                        $price_paid = $price_paid + $row_bill["pay_bill"];
                    } elseif ($tax_in == '2') {
                        // Vat รวม
                        $price_vat = (round($row_bill["pay_bill"]) * 7) / 100;
                        $total_vat = round($row_bill["pay_bill"]) + $price_vat;
                        $price_paid = $price_paid + $total_vat;
                    } elseif ($tax_in == '3') {
                        // Vat แยก
                        $price_vat = round($row_bill["pay_bill"]) - ((round($row_bill["pay_bill"]) * 100) / 107);
                        $total_vat = round($row_bill["pay_bill"]) - $price_vat;
                        $price_paid = $price_paid + $total_vat;
                    }
                    // echo "booking - ".$row_bill["booking_products"]." Vat - ".$tax_in." | total vat - ".$total_vat." | pay bill = ".$row_bill["pay_bill"]."</br>";
                }
                $price_balance = $price_total - $price_paid;
            } /* if(!empty($bo_id)) */
                ?>

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
        <?php
        $price_total = !empty($price_total) ? $price_total : '0';
        $price_paid = !empty($price_paid) ? $price_paid : '0';
        $price_balance = !empty($price_balance) ? $price_balance : '0';
        if ($sum_paid > 0) {
            $price_paid = $sum_paid;
            $price_balance = $price_total - $price_paid;
        }
        ?>
        document.getElementById('price_total').innerHTML = '<?php echo number_format($price_total); ?>';
        document.getElementById('price_paid').innerHTML = '<?php echo number_format($price_paid); ?>';
        document.getElementById('price_balance').innerHTML = '<?php echo number_format($price_balance); ?>';
        document.getElementById('total_balance').value = <?php echo $price_balance; ?>;

        function checkCancel() {
            var check_cancel = document.getElementById('check_cancel')
            var bo_status = document.getElementById('bo_status')
            if (check_cancel.value > '0') {
                bo_status.disabled = true
            }
        }

        function printPayment() {
            var bo_id = document.getElementById('bo_id');
            var sum_paid = document.getElementById('sum_paid');
            var total_balance = document.getElementById('total_balance');
            var type_arr = [];
            var type_pay = document.getElementsByName('type_pay[]');
            for (var i = 0; i < type_pay.length; i++) {
                type_arr.push(type_pay[i].value);
            }
            jQuery.ajax({
                url: "../inc/ajax/booking/printpayment.php",
                data: {
                    bookval: bo_id.value,
                    sum_paid: sum_paid.value,
                    total_balance: total_balance.value,
                    type_arr: type_arr
                },
                type: "POST",
                success: function(response) {
                    // $("#test-mail").html(response)
                    if(response) {
                        window.open(response, '_blank');
                    }
                },
                error: function() {
                    Swal.fire('พิมพ์ข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                }
            });
        }

        function deletePayment(payval, bookval, prodtype) {
            Swal.fire({
                type: 'warning',
                title: 'คุณแน่ใจไหม?',
                text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ ลบข้อมูล!',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.value) {
                    jQuery.ajax({
                        url: "../inc/ajax/booking/" + prodtype + ".php",
                        data: {
                            payval: payval,
                            bookval: bookval
                        },
                        type: "POST",
                        success: function(response) {
                            Swal.fire({
                                title: "ลบข้อมูลเสร็จสิ้น!",
                                text: "ข้อมูลที่คุณเลือกถูกลบออกจากระบบแล้ว",
                                type: "success"
                            }).then(function() {
                                location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                            });
                        },
                        error: function() {
                            Swal.fire('ลบข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        }
                    });
                }
            })
            return true;
        }

        function restorePayment(payval, bookval, prodtype) {
            Swal.fire({
                type: 'warning',
                title: 'คุณแน่ใจไหม?',
                text: "คุณต้องการคืนข้อมูลนี้ใช่หรือไม่?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ คืนข้อมูล!',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.value) {
                    jQuery.ajax({
                        url: "../inc/ajax/booking/" + prodtype + ".php",
                        data: {
                            payval: payval,
                            bookval: bookval
                        },
                        type: "POST",
                        success: function(response) {
                            if (response == 0) {
                                Swal.fire({
                                    title: "คืนข้อมูลเสร็จสิ้น!",
                                    text: "ข้อมูลที่คุณเลือกคืนเข้าระบบแล้ว",
                                    type: "success"
                                }).then(function() {
                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                });
                            } else {
                                Swal.fire('สินค้าซ้ำ คืนข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                            }
                        },
                        error: function() {
                            Swal.fire('คืนข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        }
                    });
                }
            })
            return true;
        }

        /* script for enter to submit form */
        document.onkeydown = function(evt) {
            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
            if (keyCode == 13) {
                document.frmsearch.submit();
            }
        }

        function checkCustomertype() {
            var bo_id = document.getElementById('bo_id').value;
            var agentdiv = document.getElementById("agentdiv");
            var customerdiv = document.getElementById("customerdiv");

            if (bo_id > 0) {
                var customertype = document.getElementById('bo_customertype').value;
            } else {
                var customertype = $('[name="bo_customertype"]:checked').val();
            }

            if (customertype == 1) {
                agentdiv.style.display = "";
                customerdiv.style.display = "none";
            } else {
                agentdiv.style.display = "none";
                customerdiv.style.display = "";
            }

            return true;
        }

        function checkVoucher() {
            var bo_voucher_no = document.getElementById('bo_voucher_no');
            // bo_voucher_no.value = bo_voucher_no.value.replace(/[^A-Za-z0-9]+/, '');
            var bo_voucher_no_duplicate = document.getElementById('bo_voucher_no_duplicate');

            jQuery.ajax({
                url: "../inc/ajax/booking/checkvoucherno.php",
                data: {
                    bo_voucher_no: $("#bo_voucher_no").val(),
                    bo_id: $("#bo_id").val()
                },
                type: "POST",
                success: function(response) {
                    // alert(response);

                    if (response == "duplicate") {
                        bo_voucher_no_duplicate.value = false;
                        $("#bo_voucher_no_feedback").html("กรุณาระบุ Voucher No. ใหม่ เพราะ Voucher No. ที่ระบุถูกใช้งานแล้ว");

                        Swal.fire({
                            // position: 'top-end',
                            type: 'error',
                            // title: '',
                            text: 'กรุณาระบุ Voucher No. ใหม่ เพราะ Voucher No. ที่ระบุถูกใช้งานแล้ว',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else if (response == "true") {
                        bo_voucher_no_duplicate.value = true;
                        $("#bo_voucher_no_feedback").html("กรุณาระบุ Voucher No.");
                    } else {
                        bo_voucher_no_duplicate.value = false;
                        $("#bo_voucher_no_feedback").html("กรุณาระบุ Voucher No. ใหม่ เพราะยังไม่ได้ระบุ Voucher No. หรือ Voucher No. ที่ระบุถูกใช้งานแล้ว");
                    }
                },
                error: function() {}
            });
        }

        function deleteProduct(prodval, bookval, prodtype) {
            Swal.fire({
                type: 'warning',
                title: 'คุณแน่ใจไหม?',
                text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ ลบข้อมูล!',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.value) {
                    jQuery.ajax({
                        url: "../inc/ajax/booking/" + prodtype + ".php",
                        data: {
                            prodval: prodval,
                            bookval: bookval
                        },
                        type: "POST",
                        success: function(response) {
                            Swal.fire({
                                title: "ลบข้อมูลเสร็จสิ้น!",
                                text: "ข้อมูลที่คุณเลือกถูกลบออกจากระบบแล้ว",
                                type: "success"
                            }).then(function() {
                                // $("#test-delete-email").html(response)
                                location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                // setTimeout(function () {
                                //     window.location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                ?>";
                                // }, 6000);
                            });
                        },
                        error: function() {
                            Swal.fire('ลบข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        }
                    });
                }
            })
            return true;
        }

        function restoreProduct(prodval, bookval, id_type, catesecond, prodtype) {
            Swal.fire({
                type: 'warning',
                title: 'คุณแน่ใจไหม?',
                text: "คุณต้องการคืนข้อมูลนี้ใช่หรือไม่?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ คืนข้อมูล!',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.value) {
                    jQuery.ajax({
                        url: "../inc/ajax/booking/" + prodtype + ".php",
                        data: {
                            prodval: prodval,
                            bookval: bookval,
                            id_type: id_type,
                            catesecond: catesecond
                        },
                        type: "POST",
                        success: function(response) {
                            if (response == 0) {
                                Swal.fire({
                                    title: "คืนข้อมูลเสร็จสิ้น!",
                                    text: "ข้อมูลที่คุณเลือกคืนเข้าระบบแล้ว",
                                    type: "success"
                                }).then(function() {
                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                });
                            } else {
                                Swal.fire('สินค้าซ้ำ คืนข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                            }
                        },
                        error: function() {
                            Swal.fire('คืนข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        }
                    });
                }
            })
            return true;
        }

        function check_confirm_name(prodval, bookval, prodtype) {
            var check_confirm = document.getElementById('check_confirm' + prodval)
            if (!check_confirm.checked) {
                (async () => {
                    const inputOptions = new Promise((resolve) => {
                        setTimeout(() => {
                            resolve({
                                '3': 'แก้ใขชื่อผู้ยืนยัน',
                                '2': 'ยกเลิกการยืนยัน'
                            })
                        }, 1000)
                    })
                    const {
                        value: status
                    } = await Swal.fire({
                        type: "warning",
                        title: 'ยกเลิก/แก้ใข สถานะการยืนยัน',
                        input: 'radio',
                        inputOptions: inputOptions,
                        inputValidator: (value) => {
                            if (!value) {
                                return 'กรุณาเลือกรายการ!'
                            }
                        }
                    })
                    if (!status) {
                        check_confirm.checked = true
                    }
                    if (status == 3) {
                        Swal.fire({
                            title: 'สถานะการยืนยัน',
                            text: 'ชื่อผู้ยืนยัน',
                            input: 'text',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonText: 'บันทึกข้อมูล',
                            cancelButtonText: 'ปิด',
                            inputValidator: (result) => {
                                if (!result) {
                                    return 'กรุณาเลือกรายการ!'
                                }
                            }
                        }).then((result) => {
                            if (result.value) {
                                jQuery.ajax({
                                    url: "../inc/ajax/booking/" + prodtype + ".php",
                                    data: {
                                        prodval: prodval,
                                        confirm_name: result.value,
                                        bookval: bookval,
                                        status: '3'
                                    },
                                    type: "POST",
                                    success: function(response) {
                                        Swal.fire({
                                            title: "บันทึกข้อมูลสำเร็จ!",
                                            type: "success"
                                        }).then(function() {
                                            location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                        });
                                    },
                                    error: function() {
                                        Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                    }
                                });
                            } else {
                                check_confirm.checked = true
                            }
                        })
                    }
                    if (status == 2) {
                        Swal.fire({
                            type: 'warning',
                            title: 'ยกเลิกสถานะการยืนยัน?',
                            text: "ยกเลิกสถานะการยืนยันใช่หรือไม่?",
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่ ยกเลิกสถานะ!',
                            cancelButtonText: 'ปิด'
                        }).then((result) => {
                            if (result.value) {
                                jQuery.ajax({
                                    url: "../inc/ajax/booking/" + prodtype + ".php",
                                    data: {
                                        prodval: prodval,
                                        bookval: bookval,
                                        status: '2'
                                    },
                                    type: "POST",
                                    success: function(response) {
                                        // alert(response)
                                        Swal.fire({
                                            title: "ยกเลิกสถานะการยืนยันสำเร็จ!",
                                            type: "success"
                                        }).then(function() {
                                            location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                        });
                                    },
                                    error: function() {
                                        Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                        exit
                                    }
                                });
                            } else {
                                check_confirm.checked = true
                            }
                        })
                    }
                })()
            } else {
                Swal.fire({
                    title: 'สถานะการยืนยัน',
                    text: 'ชื่อผู้ยืนยัน',
                    input: 'text',
                    showCancelButton: true,
                    showCloseButton: true,
                    confirmButtonText: 'บันทึกข้อมูล',
                    cancelButtonText: 'ปิด',
                    inputValidator: (result) => {
                        if (!result) {
                            return 'กรุณาเลือกรายการ!'
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                        jQuery.ajax({
                            url: "../inc/ajax/booking/" + prodtype + ".php",
                            data: {
                                prodval: prodval,
                                confirm_name: result.value,
                                bookval: bookval,
                                status: '1'
                            },
                            type: "POST",
                            success: function(response) {
                                // alert(response)
                                Swal.fire({
                                    title: "บันทึกข้อมูลสำเร็จ!",
                                    type: "success"
                                }).then(function() {
                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                });
                            },
                            error: function() {
                                Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                            }
                        });
                    } else {
                        //Swal.fire('กรุณาใส่ชื่อผู้ยืนยัน!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        check_confirm.checked = false
                        // .then(function() {
                        //     location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                        // });
                    }
                })
            }
            return true;
        }

        function check_confirm_email(prodtype) {
            var remark_email = document.getElementById('remark_email').value
            var bo_id = document.getElementById('bo_id').value
            var id_email = [];
            var els = document.getElementsByName('check_email[]');
            for (var i = 0; i < els.length; i++) {
                if (els[i].checked) {
                    id_email.push(els[i].value);
                }
            }
            if (id_email == '') {
                Swal.fire('ส่งอีเมล์ไม่สำเร็จ!', 'กรุณาเลือกสินค้า', 'error')
                exit
            }

            Swal.fire({
                type: 'warning',
                title: 'คุณแน่ใจไหม?',
                text: "คุณต้องการส่งอีเมล์ใช่หรือไม่?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ส่งอีเมล์!',
                cancelButtonText: 'ปิด'
            }).then((result) => {
                if (result.value) {
                    // alert(remark_email+' - '+bo_id+' - '+id_email)
                    jQuery.ajax({
                        url: "../inc/ajax/booking/" + prodtype + ".php",
                        data: {
                            remark_email: remark_email,
                            bo_id: bo_id,
                            id_email: id_email
                        },
                        type: "POST",
                        success: function(response) {
                            // $("#test-mail").html(response)
                            Swal.fire({
                                    title: "ส่งอีเมล์เสร็จสิ้น!",
                                    text: "ข้อมูลที่คุณเลือกถูกส่งอีเมล์แล้ว",
                                    type: "success"
                                })
                                .then(function() {
                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                    // setTimeout(function () {
                                    //     window.location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                    ?>";
                                    // }, 6000);
                                });
                        },
                        error: function() {
                            Swal.fire('ส่งอีเมล์ไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                        }
                    });
                }
            })
            return true;
        }
    </script>