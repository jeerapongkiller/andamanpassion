<?php
if (!empty($_GET["id"])) {
    $query = "SELECT * FROM payments_booking WHERE id > '0'";
    $query .= " AND id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $page_title = 'เลขที่ใบเสร็จ # '.$row['receip_no'];
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=booking/detail&id='" . $_GET["booking"] . "'\" >";
    }
} else {
    $page_title = "เพิ่มข้อมูลธุรกรรมการเงิน";
}
$pm_id = !empty($row["id"]) ? $row["id"] : '0';
$pm_status = !empty($row["status"]) ? $row["status"] : '0';
$pm_booking = !empty($_GET["booking"]) ? $_GET["booking"] : '0';
$pm_booking_products = !empty($row["booking_products"]) ? $row["booking_products"] : '0';
$pm_accounts = !empty($row["accounts"]) ? $row["accounts"] : '0';
$pm_bank = !empty($row["bank"]) ? $row["bank"] : '0';
$pm_date_paid = !empty($row["date_paid"]) ? $row["date_paid"] : $today;
$pm_receip_no = !empty($row["receip_no"]) ? $row["receip_no"] : '';
$pm_amount_paid = !empty($row["amount_paid"]) ? $row["amount_paid"] : '';
$pm_receiver_name = !empty($row["receiver_name"]) ? $row["receiver_name"] : '';
$pm_bank_name = !empty($row["bank_name"]) ? $row["bank_name"] : '';
$pm_bank_no = !empty($row["bank_no"]) ? $row["bank_no"] : '';
$pm_photo1 = !empty($row["photo1"]) ? $row["photo1"] : '';
# --- Price --- #
$pm_total = !empty($_POST["pm_total"]) ? $_POST["pm_total"] : '0';
$pm_paid = !empty($_POST["pm_paid"]) ? $_POST["pm_paid"] : '0';
$pm_balance = !empty($_POST["pm_balance"]) ? $_POST["pm_balance"] : '0';
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
                        <li class="breadcrumb-item"><a href="./?mode=booking/detail&id=<?php echo $_GET["booking"]; ?>"> Voucher No. # <?php echo stripslashes(get_value('booking', 'id', 'voucher_no', $_GET["booking"], $mysqli_p)); ?> </a></li>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                    </ol>
                </div>
            </div>
            <div class="col-md-7 align-self-center text-right">
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
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=booking/save-payment" novalidate>
                            <input type="hidden" id="pm_id" name="pm_id" value="<?php echo $pm_id; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <input type="hidden" id="pm_booking" name="pm_booking" value="<?php echo $pm_booking; ?>">
                            <input type="hidden" id="pm_total" name="pm_total" value="<?php echo $pm_total; ?>" >
                            <input type="hidden" id="pm_paid" name="pm_paid" value="<?php echo $pm_paid; ?>" >
                            <input type="hidden" id="pm_balance" name="pm_balance" value="<?php echo $pm_balance; ?>" >
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="pm_status">สถานะ</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="pm_status" name="pm_status" <?php if ($pm_status != 2 || !isset($pm_status)) {
                                                                                                                                echo "checked";
                                                                                                                            } ?> value="1">
                                        <label class="custom-control-label" for="pm_status">ออนไลน์</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3 text-center">
                                    <label for="pm_voucher"> Voucher No. </label></br>
                                    <span style="font-size:16px; font-weight:bold; color:#059DDE;"> <?php echo get_value('booking', 'id', 'voucher_no', $pm_booking, $mysqli_p); ?> </span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pm_receip_no"> เลขใบเสร็จ <span style="color:#FF0000;">*</span> </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputCompanyinvoice"><i class="ti-ticket"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="pm_receip_no" name="pm_receip_no" value="<?php echo $pm_receip_no; ?>" required>
                                        <div class="invalid-feedback" id="pm_receip_no_feedback"> กรุณาระบุเลขใบเสร็จ </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pm_date_paid"> วันที่ชำระเงิน </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputTelephone"><i class="ti-calendar"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="pm_date_paid" name="pm_date_paid" placeholder="" value="<?php echo $pm_date_paid; ?>" required readonly>
                                        <div class="invalid-feedback" id="pm_date_paid_feedback"> กรุณาระบุวันที่ชำระเงิน </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="pm_accounts"> ประเภทการจ่ายเงิน </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFax"><i class="ti-wallet"></i></span>
                                        </div>
                                        <select class="form-control" name="pm_accounts" id="pm_accounts" required>
                                            <option value="" <?php if ($pm_accounts == 0) {
                                                                    echo "selected";
                                                                } ?>>-</option>
                                            <?php
                                            $query_accounts = "SELECT * FROM accounts WHERE id > '0' AND status != '2' ";
                                            $query_accounts .= " ORDER BY name ASC";
                                            $result_accounts = mysqli_query($mysqli_p, $query_accounts);
                                            while ($row_accounts = mysqli_fetch_array($result_accounts, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row_accounts["id"]; ?>" <?php if ($pm_accounts == $row_accounts["id"]) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                    <?php echo $row_accounts["name"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback" id="pm_accounts_feedback"> กรุณาเลือกประเภทการจ่ายเงิน </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pm_booking_products"> สินค้า </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFax"><i class="ti-package"></i></span>
                                        </div>
                                        <select class="form-control" name="pm_booking_products" id="pm_booking_products" required>
                                            <option value="" <?php if ($pm_booking_products == 0) {
                                                                    echo "selected";
                                                                } ?>>-</option>
                                            <?php
                                            $query_bp = "SELECT BP.*,
                                                         PCS.id as pcsID, PCS.name as pcsName 
                                                         FROM booking_products BP
                                                         LEFT JOIN products_category_second PCS
                                                            ON BP.products_category_second = PCS.id
                                                         WHERE BP.booking = '" . $_GET['booking'] . "' ";
                                            $result_bp = mysqli_query($mysqli_p, $query_bp);
                                            while ($row_bp = mysqli_fetch_array($result_bp, MYSQLI_ASSOC)) {
                                                if ($row_bp['price_latest'] > 0) {
                                                    $date_travel = $row_bp["products_type"] == 4 ? $row_bp["checkin_date"] : $row_bp["travel_date"];
                                            ?>
                                                    <option value="<?php echo $row_bp["id"]; ?>" <?php if ($pm_booking_products == $row_bp["id"]) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                        <?php echo $row_bp["pcsName"] . ' (' . date("d F Y", strtotime($date_travel)) . ')'; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <option value="0" <?php echo $pm_booking_products == 0 ? 'selected' : ''; ?>>ชำระทั้งหมด</option>
                                        </select>
                                        <div class="invalid-feedback" id="pm_booking_products_feedback"> กรุณาเลือกสินค้า </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4 mb-3 text-center">
                                    <label for="paid_no"> จำนวนเงิน </label></br>
                                    <span style="font-size:16px; font-weight:bold; color:#BD1A2A;" id="paid_no"> กรุณาเลือกสินค้า </span>
                                </div> -->
                                <div class="col-md-4 mb-3">
                                    <label for="pm_amount_paid"> จำนวนเงินที่ต้องการชำระ </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputEmail"><i class="ti-money"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="pm_amount_paid" name="pm_amount_paid" value="<?php echo $pm_amount_paid; ?>" oninput="priceformat('pm_amount_paid')" required>
                                        <div class="invalid-feedback" id="pm_amount_paid_feedback"> กรุณาระบุจำนวนเงินที่ต้องชำระ </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pm_receiver_name"> ชื่อผู้ดำเนิน </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputEmail"><i class="ti-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="pm_receiver_name" name="pm_receiver_name" value="<?php echo $pm_receiver_name; ?>" required>
                                        <div class="invalid-feedback" id="pm_receiver_name_feedback"> กรุณาระบุชื่อผู้ดำเนิน </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label for="remark">หมายเหตุ</label>
                                    <div class="input-group">
                                        <span class="help-block" style="color:#FF0000">
                                            <small>Support "<b>JPG, PNG</b>" format only and file size <b>must not exceed 2MB per file</b>. Should be fix to <b>width</b> 600px, <b>height</b> 600px</small></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <?php
                                for ($i = 1; $i <= 1; $i++) {
                                    $img_tmp = !empty($row["photo" . $i]) ? $row["photo" . $i] : '';
                                    $pathpicture = !empty($img_tmp) ? '../inc/photo/booking/' . $img_tmp : '';
                                ?>
                                    <div class="col-md-3 mb-3">
                                        <label for="photo<?php echo $i; ?>">ไฟล์ที่ # <span style="font-weight:bold; color:#FF0000"><?php echo $i; ?></span></label>
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
                                            <input type="file" id="photo<?php echo $i; ?>" name="photo<?php echo $i; ?>" class="dropify" data-default-file="<?php echo $pathpicture; ?>" data-show-remove="false" data-max-file-size="2M" data-allowed-file-extensions="jpg png pdf" />
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
    function checkProducts() {
        pm_booking
        var pm_booking_products = document.getElementById('pm_booking_products');
        var pm_booking = document.getElementById('pm_booking');
        jQuery.ajax({
            url: "../inc/ajax/booking/checkproducts.php",
            data: {
                booking_products: pm_booking_products.value,
                booking: pm_booking.value
            },
            type: "POST",
            success: function(response) {
                // alert(response);
                $("#paid_no").html(response);
            },
            error: function() {}
        });
    }

    function priceformat(inputfield) {
        var pm_balance = document.getElementById('pm_balance')
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
        if (inputfield == 'pm_amount_paid') {
            var pm_amount_paid = document.getElementById(inputfield)
            if(d > pm_balance.value) {
                pm_amount_paid.value = pm_balance.value
            }
        }
    }

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            var pm_amount_paid = document.getElementById('pm_amount_paid').value;
            var pm_amount_paid_feedback = document.getElementById('pm_amount_paid_feedback')
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false || pm_amount_paid === 0) {
                        if(pm_amount_paid === 0){
                            pm_amount_paid_feedback.html('กรุณาระบุจำนวนเงินที่ต้องชำระ')
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