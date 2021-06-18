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
                                <li class="breadcrumb-item active">โอเปอเรเตอร์</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <script type="text/javascript">
                        /* script for enter to submit form */
                        document.onkeydown = function(evt) {
                            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
                            if (keyCode == 13) {
                                document.frmsearch.submit();
                            }
                        }

                        function check_confirm_op(prodval, bookval, prodtype) {
                            var check_confirm = document.getElementById('check_confirm' + prodval)
                            if (check_confirm.checked) {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'คุณแน่ใจไหม?',
                                    text: "คุณต้องการยืนยันข้อมูลนี้ใช่หรือไม่?",
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ใช่ ยืนยันข้อมูล!',
                                    cancelButtonText: 'ปิด'
                                }).then((result) => {
                                    if (result.value) {
                                        jQuery.ajax({
                                            url: "../inc/ajax/operator/" + prodtype + ".php",
                                            data: {
                                                prodval: prodval,
                                                bookval: bookval,
                                                status: '1'
                                            },
                                            type: "POST",
                                            success: function(response) {
                                                // alert(response)
                                                Swal.fire({
                                                    title: "ยืนยันข้อมูลเสร็จสิ้น!",
                                                    text: "ข้อมูลที่คุณเลือกยืนยันเรียบร้อยแล้ว",
                                                    type: "success"
                                                }).then(function() {
                                                    // location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                        ?>";
                                                });
                                            },
                                            error: function() {
                                                Swal.fire('ยืนยันสินค้าไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                            }
                                        });
                                    } else {
                                        check_confirm.checked = false
                                    }
                                })
                                return true;
                            } else {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'คุณแน่ใจไหม?',
                                    text: "คุณต้องการยกเลิกยืนยันข้อมูลนี้ใช่หรือไม่?",
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ใช่ ยกเลิกยืนยัน!',
                                    cancelButtonText: 'ปิด'
                                }).then((result) => {
                                    if (result.value) {
                                        jQuery.ajax({
                                            url: "../inc/ajax/operator/" + prodtype + ".php",
                                            data: {
                                                prodval: prodval,
                                                bookval: bookval,
                                                status: '2'
                                            },
                                            type: "POST",
                                            success: function(response) {
                                                // alert(response)
                                                Swal.fire({
                                                    title: "ยกเลิกยืนยันข้อมูลเสร็จสิ้น!",
                                                    text: "ข้อมูลที่คุณเลือกยกเลิกยืนยันเรียบร้อยแล้ว",
                                                    type: "success"
                                                }).then(function() {
                                                    // location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                        ?>";
                                                });
                                            },
                                            error: function() {
                                                Swal.fire('ยกเลิกยืนยันข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                            }
                                        });
                                    } else {
                                        check_confirm.checked = true
                                    }
                                })
                                return true;
                            }
                        }

                        function check_add_vans(prodval, bookval, prodtype) {
                            var text_vans = document.getElementById('text_vans' + prodval)
                            Swal.fire({
                                title: 'ป้ายทะเบียน',
                                text: 'หมายเลขรถ/หมายเลขป้ายทะเบียน',
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
                                        url: "../inc/ajax/operator/" + prodtype + ".php",
                                        data: {
                                            prodval: prodval,
                                            vans_name: result.value,
                                            bookval: bookval
                                        },
                                        type: "POST",
                                        success: function(response) {
                                            Swal.fire({
                                                title: "บันทึกข้อมูลสำเร็จ!",
                                                type: "success"
                                            }).then(function() {
                                                $(text_vans).html(response)
                                                // location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                    ?>";
                                            });
                                        },
                                        error: function() {
                                            Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                        }
                                    });
                                }
                            })
                        }

                        function check_add_pick_up(prodval, bookval, prodtype) {
                            var text_pikup = document.getElementById('text_pikup' + prodval)
                            Swal.fire({
                                title: 'เวลารับ',
                                html: '<input type="time" class="form-control" id="bp_pickup_time" name="bp_pickup_time" value="<?php echo date("H:i"); ?>">',
                                // input: 'text',
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
                                var bp_pickup_time = document.getElementById('bp_pickup_time').value;
                                if (result.value) {
                                    jQuery.ajax({
                                        url: "../inc/ajax/operator/" + prodtype + ".php",
                                        data: {
                                            prodval: prodval,
                                            bp_pickup_time: bp_pickup_time,
                                            bookval: bookval
                                        },
                                        type: "POST",
                                        success: function(response) {
                                            Swal.fire({
                                                title: "บันทึกข้อมูลสำเร็จ!",
                                                type: "success"
                                            }).then(function() {
                                                $(text_pikup).html(response)
                                                // location.href = "<?php // echo $_SERVER['REQUEST_URI']; 
                                                                    ?>";
                                            });
                                        },
                                        error: function() {
                                            Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                        }
                                    });
                                }
                            })
                        }
                    </script>

                    <?php
                    # check value from search 
                    $next7day = date('Y-m-d', strtotime($today . "+1 day"));
                    $search_voucher_no_val = !empty($_POST["search_voucher_no"]) ? $_POST["search_voucher_no"] : '';
                    $search_customer_firstname_val = !empty($_POST["search_customer_firstname"]) ? $_POST["search_customer_firstname"] : '';
                    $search_customer_lastname_val = !empty($_POST["search_customer_lastname"]) ? $_POST["search_customer_lastname"] : '';
                    $search_agent_val = !empty($_POST["search_agent_val"]) ? $_POST["search_agent_val"] : '';
                    $search_travel_date_from_val = !empty($_POST["search_travel_date_from"]) ? $_POST["search_travel_date_from"] : $today; // $today;
                    $search_travel_date_to_val = !empty($_POST["search_travel_date_to"]) ? $_POST["search_travel_date_to"] : $next7day; // $next7day;
                    $search_supplier_val = !empty($_POST["search_supplier_val"]) ? $_POST["search_supplier_val"] : '';
                    $search_ptype_val = !empty($_POST["search_ptype_val"]) ? $_POST["search_ptype_val"] : '';
                    $search_catesecond_val = !empty($_POST["search_catesecond_val"]) ? $_POST["search_catesecond_val"] : '';
                    $search_dropoff_val = !empty($_POST["search_dropoff_val"]) ? $_POST["search_dropoff_val"] : '';
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=<?php echo $_GET["mode"]; ?>" novalidate>
                                    <input type="hidden" name="search_ptype_val_hide" id="search_ptype_val_hide" value="<?php echo $search_ptype_val; ?>">
                                    <input type="hidden" name="search_catesecond_val_hide" id="search_catesecond_val_hide" value="<?php echo $search_catesecond_val; ?>">
                                    <input type="hidden" name="search_dropoff_val_hide" id="search_dropoff_val_hide" value="<?php echo $search_dropoff_val; ?>">
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_voucher_no">Voucher</label>
                                            <input type="text" class="form-control" id="search_voucher_no" name="search_voucher_no" placeholder="" value="<?php echo $search_voucher_no_val; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_customer_firstname">ชื่อ (ลูกค้า)</label>
                                            <input type="text" class="form-control" id="search_customer_firstname" name="search_customer_firstname" placeholder="" value="<?php echo $search_customer_firstname_val; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_customer_lastname">นามสกุล (ลูกค้า)</label>
                                            <input type="text" class="form-control" id="search_customer_lastname" name="search_customer_lastname" placeholder="" value="<?php echo $search_customer_lastname_val; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_agent_val">เอเย่นต์</label>
                                            <select class="custom-select" id="search_agent_val" name="search_agent_val">
                                                <option value="0" <?php if ($search_agent_val == 0) {
                                                                        echo "selected";
                                                                    } ?>>ทั้งหมด</option>
                                                <?php
                                                $query_agent = "SELECT * FROM agent WHERE id > '0'";
                                                $query_agent .= " ORDER BY company ASC";
                                                $result_agent = mysqli_query($mysqli_p, $query_agent);
                                                while ($row_agent = mysqli_fetch_array($result_agent, MYSQLI_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row_agent["id"]; ?>" <?php if ($search_agent_val == $row_agent["id"]) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                        <?php echo $row_agent["company"]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_travel_date_from">วันที่เที่ยว / วันที่เช็คอิน (จาก)</label>
                                            <input type="text" class="form-control" id="search_travel_date_from" name="search_travel_date_from" placeholder="" value="<?php echo $search_travel_date_from_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_travel_date_to">วันที่เที่ยว / วันที่เช็คเอาท์ (ถึง)</label>
                                            <input type="text" class="form-control" id="search_travel_date_to" name="search_travel_date_to" placeholder="" value="<?php echo $search_travel_date_to_val; ?>" readonly>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=operator/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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

                    <!-- Table Responsive -->
                    <!-- <h6 class="card-subtitle">Description</h6> -->
                    <?php
                    $report_id = array();
                    $first_before = 0;
                    $numrow_realtime = 1;
                    $price_total = 0;
                    $bo_agent = '';
                    $num_adults = 0;
                    $num_children = 0;
                    $num_infant = 0;
                    $num_foc = 0;
                    // Procedural mysqli
                    $bind_types = "";
                    $params = array();
                    $query_products = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname, products_category_first.supplier as pcfsupplier, products_type.id as ptypeid,
                                                        products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                                                        booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.voucher_no as bvoucher,
                                                        booking.agent as bagent, booking.agent_voucher as bagentv, booking.customer_firstname as bcfname, booking.customer_lastname as bclname, 
                                                        booking.customer_mobile as bcmb, booking.receipt_detail as bo_detail, place.id as place_id, place.name as place_name, place.dropoff as place_dropoff, place.pickup as place_pickup 
                                                    FROM booking_products
                                                    LEFT JOIN booking
                                                        ON booking_products.booking = booking.id
                                                    LEFT JOIN products_category_first
                                                        ON booking_products.products_category_first = products_category_first.id
                                                    LEFT JOIN products_category_second
                                                        ON booking_products.products_category_second = products_category_second.id
                                                    LEFT JOIN products_type
                                                        ON booking_products.products_type = products_type.id
                                                    LEFT JOIN booking_status_email
                                                        ON booking_products.status_email = booking_status_email.id
                                                    LEFT JOIN place
                                                        ON booking_products.dropoff = place.id
                                                    LEFT JOIN booking_status_confirm
                                                        ON booking_products.status_confirm = booking_status_confirm.id
                                                    WHERE booking_products.id > '0' AND booking.status = '1' AND booking_products.status_confirm = '1'
                                ";

                    if (!empty($search_travel_date_from_val) && !empty($search_travel_date_to_val)) {
                        # search travel date , check in , check out
                        $query_products .= " AND (booking_products.travel_date BETWEEN ? AND ?";
                        $bind_types .= "ss";
                        array_push($params, $search_travel_date_from_val, $search_travel_date_to_val);

                        $query_products .= " OR booking_products.checkin_date >= ? AND booking_products.checkout_date <= ?) ";
                        $bind_types .= "ss";
                        array_push($params, $search_travel_date_from_val, $search_travel_date_to_val);
                    }
                    if (!empty($search_voucher_no_val)) {
                        # search voucher_no
                        $query_products .= " AND booking.voucher_no = ?";
                        $bind_types .= "s";
                        array_push($params, $search_voucher_no_val);
                    }
                    if (!empty($search_customer_firstname_val)) {
                        # search customer firstname
                        $query_products .= " AND booking.customer_firstname = ?";
                        $bind_types .= "s";
                        array_push($params, $search_customer_firstname_val);
                    }
                    if (!empty($search_customer_lastname_val)) {
                        # search customer lastname
                        $query_products .= " AND booking.customer_lastname = ?";
                        $bind_types .= "s";
                        array_push($params, $search_customer_lastname_val);
                    }
                    if (!empty($search_customer_mobile_val)) {
                        # search customer mobile
                        $query_products .= " AND booking.customer_mobile = ?";
                        $bind_types .= "s";
                        array_push($params, $search_customer_mobile_val);
                    }
                    if (!empty($search_agent_val)) {
                        # search agent
                        $query_products .= " AND booking.agent = ?";
                        $bind_types .= "i";
                        array_push($params, $search_agent_val);
                    }
                    if (!empty($search_supplier_val)) {
                        # search supplier
                        $query_products .= " AND products_category_first.supplier = ?";
                        $bind_types .= "i";
                        array_push($params, $search_supplier_val);
                    }
                    if (!empty($search_ptype_val)) {
                        # search products_type
                        $query_products .= " AND products_type.id = ?";
                        $bind_types .= "i";
                        array_push($params, $search_ptype_val);
                    }
                    if (!empty($search_catesecond_val)) {
                        # search catesecond
                        $query_products .= " AND products_category_second.id = ?";
                        $bind_types .= "i";
                        array_push($params, $search_catesecond_val);
                    }
                    if (!empty($search_dropoff_val)) {
                        # search dropoff
                        $query_products .= " AND booking_products.dropoff = ?";
                        $bind_types .= "i";
                        array_push($params, $search_dropoff_val);
                    }
                    $query_products .= " ORDER BY booking_products.products_type ASC, booking_products.products_category_first ASC";
                    $query_products .= " , booking_products.travel_date ASC";

                    // echo "<br />" . $query_products . "<br />";

                    $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
                    // Check error query
                    if ($procedural_statement == false) {
                        die("<pre>" . mysqli_error($mysqli_p) . PHP_EOL . $query . "</pre>");
                    }
                    if ($bind_types != "") {
                        mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
                    }
                    mysqli_stmt_execute($procedural_statement);
                    $result = mysqli_stmt_get_result($procedural_statement);
                    $numrow_products = mysqli_num_rows($result);

                    while ($row_products = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        array_push($report_id, $row_products["id"]);
                        if ($row_products["date_not_specified"] == 2) {
                            $travel_date = date("d F Y", strtotime($row_products["travel_date"]));
                            $checkin_date = date("d F Y", strtotime($row_products["checkin_date"]));
                            $checkout_date = date("d F Y", strtotime($row_products["checkout_date"]));
                            $price_latest = ($bo_agent == 0) ? number_format($row_products["price_latest"]) : '-';
                            // $price_latest = number_format($row_products["price_latest"]);
                            $price_total = $price_total + $row_products["price_latest"];
                        } else {
                            $travel_date = "ไม่ระบุวันที่";
                            $checkin_date = "ไม่ระบุวันที่";
                            $checkout_date = "ไม่ระบุวันที่";
                            $price_latest = "-";
                        }
                        if ($first_before != $row_products["ptypeid"]) {
                            if ($numrow_realtime != 1) {
                                echo '</tbody></table></div></div></div></div>';
                                // $ptypeid = $row_products["ptypeid"] - 1;
                                $ptypeid = $second_before; ?>
                                <input type="hidden" id="num_adults_<?php echo $ptypeid; ?>" name="num_adults_<?php echo $ptypeid; ?>" value="<?php echo $num_adults; ?>">
                                <input type="hidden" id="num_children_<?php echo $ptypeid; ?>" name="num_children_<?php echo $ptypeid; ?>" value="<?php echo $num_children; ?>">
                                <input type="hidden" id="num_infant_<?php echo $ptypeid; ?>" name="num_infant_<?php echo $ptypeid; ?>" value="<?php echo $num_infant; ?>">
                                <input type="hidden" id="num_foc_<?php echo $ptypeid; ?>" name="num_foc_<?php echo $ptypeid; ?>" value="<?php echo $num_foc; ?>">
                                <script>
                                    var num_adults = document.getElementById('num_adults_<?php echo $ptypeid; ?>').value;
                                    var num_children = document.getElementById('num_children_<?php echo $ptypeid; ?>').value;
                                    var num_infant = document.getElementById('num_infant_<?php echo $ptypeid; ?>').value;
                                    var num_foc = document.getElementById('num_foc_<?php echo $ptypeid; ?>').value;
                                    document.getElementById('num_people_<?php echo $ptypeid; ?>').innerHTML = 'รวมทั้งหมด(ผู้ใหญ่/เด็ก/ทารก/FOC) : ' + num_adults + '/' + num_children + '/' + num_infant + '/' + num_foc;
                                </script>
                            <?php
                                $num_adults = 0;
                                $num_children = 0;
                                $num_infant = 0;
                                $num_foc = 0;
                            }
                            // echo ($numrow_realtime != 1) ? '</tbody></table></div></div></div></div>' : '';
                            ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive m-t-40">
                                            <h4 class="card-title">ข้อมูลการจองสินค้า<?php echo $row_products["ptypenamethai"]; ?> <span style="float:right; color:#F8A440; font-size:16px" id="num_people_<?php echo $row_products["ptypeid"]; ?>"> รวมทั้งหมด(ผู้ใหญ่/เด็ก/ทารก/FOC) : 0/0/0/0 </span></h4>
                                            <table id="booking-table-<?php echo $row_products["ptypeid"]; ?>" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                                <thead>
                                                    <?php if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) { ?>
                                                        <tr align="center">
                                                            <th style="vertical-align:middle">ยืนยัน</th>
                                                            <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                            <th style="vertical-align:middle">Voucher</th>
                                                            <th style="vertical-align:middle">วันที่เที่ยว</th>
                                                            <th style="vertical-align:middle">เอเย่นต์/</br>เอเย่นต์ Voucher</th>
                                                            <th style="vertical-align:middle">ชื่อ-นามสกุล/</br>เบอร์โทร</th>
                                                            <th style="vertical-align:middle">ผู้ใหญ่/เด็ก/ทารก/FOC</th>
                                                            <th style="vertical-align:middle">หมายเลขรถ/</br>ป้ายทะเบียน</th>
                                                            <th style="vertical-align:middle">เวลารับ</th>
                                                            <th style="vertical-align:middle">สถานที่รับ</th>
                                                            <th style="vertical-align:middle">โซน</br>(สถานที่รับ)</th>
                                                            <th style="vertical-align:middle">สถานที่ส่ง</th>
                                                            <th style="vertical-align:middle">โซน</br>(สถานที่ส่ง)</th>
                                                            <th style="vertical-align:middle">ห้องพัก</th>
                                                            <th style="vertical-align:middle">รายละเอียด</th>
                                                        </tr>
                                                    <?php } else if ($row_products["ptypeid"] == 3) { ?>
                                                        <tr align="center">
                                                            <th style="vertical-align:middle">ยืนยัน</th>
                                                            <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                            <th style="vertical-align:middle">Voucher</th>
                                                            <th style="vertical-align:middle">วันที่เที่ยว</th>
                                                            <th style="vertical-align:middle">เอเย่นต์/</br>เอเย่นต์ Voucher</th>
                                                            <th style="vertical-align:middle">ชื่อ-นามสกุล/</br>เบอร์โทร</th>
                                                            <th style="vertical-align:middle">ผู้ใหญ่/เด็ก/ทารก/FOC</th>
                                                            <th style="vertical-align:middle">หมายเลขรถ/</br>ป้ายทะเบียน</th>
                                                            <th style="vertical-align:middle">เวลารับ</th>
                                                            <th style="vertical-align:middle">จำนวน</br>รถ/ชั่วโมง</th>
                                                            <th style="vertical-align:middle">สถานที่รับ</th>
                                                            <th style="vertical-align:middle">โซน</br>(สถานที่รับ)</th>
                                                            <th style="vertical-align:middle">สถานที่ส่ง</th>
                                                            <th style="vertical-align:middle">โซน</br>(สถานที่ส่ง)</th>
                                                            <th style="vertical-align:middle">ห้องพัก</th>
                                                            <th style="vertical-align:middle">รายละเอียด</th>
                                                        </tr>
                                                    <?php } else if ($row_products["ptypeid"] == 4) { ?>
                                                        <tr align="center">
                                                            <th style="vertical-align:middle">ยืนยัน</th>
                                                            <th style="text-align:left; vertical-align:middle">สินค้า</th>
                                                            <th style="vertical-align:middle">Voucher</th>
                                                            <th style="vertical-align:middle">เอเย่นต์/</br>เอเย่นต์ Voucher</th>
                                                            <th style="vertical-align:middle">ชื่อ-นามสกุล/</br>เบอร์โทร</th>
                                                            <th style="vertical-align:middle">วันที่เช็คอิน</th>
                                                            <th style="vertical-align:middle">วันที่เช็คเอาท์</th>
                                                            <th style="vertical-align:middle">ผู้ใหญ่/เด็ก/ทารก/FOC</th>
                                                            <th style="vertical-align:middle">ห้อง/เตียงเสริม(ผู้ใหญ่)/</br>เตียงเสริม(เด็ก)/แชร์เตียง</th>
                                                            <th style="vertical-align:middle">รายละเอียด</th>
                                                        </tr>
                                                    <?php } ?>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $first_before = $row_products["ptypeid"];
                                                $second_before = $row_products["ptypeid"];
                                            }
                                                ?>
                                                <?php
                                                if ($row_products["ptypeid"] == 1 || $row_products["ptypeid"] == 2) {
                                                    # --- Pickup & Dropoff --- #
                                                    $pickup_name = $row_products["pickup"] > '0' ? get_value('place', 'id', 'name', $row_products["pickup"], $mysqli_p) : "N/A";
                                                    $zones_pickup_id = $row_products["pickup"] > '0' ? get_value('place', 'id', 'zones', $row_products["pickup"], $mysqli_p) : '0';
                                                    $zones_pickup_name = $zones_pickup_id > '0' ? get_value('zones', 'id', 'name', $zones_pickup_id, $mysqli_p) : "N/A";
                                                    $dropoff_name = $row_products["dropoff"] > '0' ? get_value('place', 'id', 'name', $row_products["dropoff"], $mysqli_p) : "N/A";
                                                    $zones_dropoff_id = $row_products["dropoff"] > '0' ? get_value('place', 'id', 'zones', $row_products["dropoff"], $mysqli_p) : '0';
                                                    $zones_dropoff_name = $zones_dropoff_id > '0' ? get_value('zones', 'id', 'name', $zones_dropoff_id, $mysqli_p) : "N/A";
                                                ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" class="custom-control-input" id="check_confirm<?php echo $row_products["id"]; ?>" name="check_confirm<?php echo $row_products["id"]; ?>" value="1" onclick="check_confirm_op('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkconfirm-op');" <?php echo $row_products["status_confirm_op"] == 1 ? 'checked' : ''; ?> <?php echo $row_products['invoice'] == '1'  ? 'disabled' : ''; ?>>
                                                                <label class="custom-control-label" for="check_confirm<?php echo $row_products["id"]; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_products["pcsname"]; ?></br>: <?php echo $row_products["pcfname"]; ?>
                                                            <?php if ($row_products["edit_date"] == '1') { ?>
                                                                <div class="notify" style="z-index: 99999;"> <span class="heartbit"></span> <span class="point"></span> </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td align="center"><?php echo $row_products["bvoucher"]; ?></td>
                                                        <td align="center"><span style="color:#2E8E9C"><?php echo $travel_date; ?></span></td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bagent"] != 0) ? get_value("agent", "id", "company", $row_products["bagent"], $mysqli_p) : "-"; ?>
                                                            <?php echo ($row_products["bagentv"] != "") ? "/ </br>" . $row_products["bagentv"] : ""; ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bcfname"] != "") ? $row_products["bcfname"] . " " . $row_products["bclname"] : "-"; ?>
                                                            <?php echo ($row_products["bcmb"] != "") ? "</br>" . $row_products["bcmb"] : ""; ?>
                                                        </td>
                                                        <td align="center"><?php echo number_format($row_products["adults"]) . "/" . number_format($row_products["children"]) . "/" . number_format($row_products["infant"]) . "/" . number_format($row_products["foc"]); ?></td>
                                                        <td align="center">
                                                            <span id="text_vans<?php echo $row_products['id']; ?>"><?php echo $row_products['vans']; ?></span>
                                                            <?php if ($row_products['vans'] != '') {
                                                                $icon_vans = "fa-edit";
                                                                $color_icon_vans = "#0000FF";
                                                            } else {
                                                                $icon_vans = "fa-plus";
                                                                $color_icon_vans = "#00FF00";
                                                            } ?>
                                                            <a href="#add" title="add" id="add_vans(<?php echo $row_products['id']; ?>)" name="add_vans(<?php echo $row_products['id']; ?>)" onclick="check_add_vans('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkaddvans');">
                                                                <i class="fas <?php echo $icon_vans; ?>" style="color:<?php echo $color_icon_vans; ?>"></i></a>
                                                        </td>
                                                        <td align="center">
                                                            <span id="text_pikup<?php echo $row_products['id']; ?>"><?php echo $row_products['pickup_time']; ?></span>
                                                            <?php if ($row_products['pickup_time'] != '') {
                                                                $icon_time = "fa-edit";
                                                                $color_icon_time = "#0000FF";
                                                                $status_time = "1";
                                                            } else {
                                                                $icon_time = "fa-plus";
                                                                $color_icon_time = "#00FF00";
                                                            } ?>
                                                            <a href="#add" title="add" id="add_pick_up(<?php echo $row_products['pickup_time']; ?>)" name="add_pick_up(<?php echo $row_products['pickup_time']; ?>)" onclick="check_add_pick_up('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkaddpick-up', <?php echo $status_time; ?>);">
                                                                <i class="fas <?php echo $icon_time; ?>" style="color:<?php echo $color_icon_time; ?>"></i></a>
                                                        </td>
                                                        <td align="center"><?php echo $pickup_name; ?></td>
                                                        <td align="center"><?php echo $zones_pickup_name; ?></td>
                                                        <td align="center"><?php echo $dropoff_name; ?></td>
                                                        <td align="center"><?php echo $zones_dropoff_name; ?></td>
                                                        <td align="center"><?php echo ($row_products["roomno"] != 0) ? $row_products["roomno"] : "N/A"; ?></td>
                                                        <td align="center"><a href="#" data-toggle="modal" data-target="#modalproducts<?php echo $row_products["id"]; ?>"><i class="fas fa-eye"></i></a></td>
                                                    </tr>
                                                    <!-- modal content detail -->
                                                    <div id="modalproducts<?php echo $row_products["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="vcenter">รายละเอียด</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo (!empty($row_products["bo_detail"])) ? '<p>' . nl2br($row_products["bo_detail"]) . '</p>' : 'ไม่มีข้อมูลรายละเอียด'; ?>
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
                                                    <!--- Num adults, children, infant, foc --->
                                                    <?php
                                                    $num_adults = $num_adults + $row_products["adults"];
                                                    $num_children = $num_children + $row_products["children"];
                                                    $num_infant = $num_infant + $row_products["infant"];
                                                    $num_foc = $num_foc + $row_products["foc"];
                                                    ?>
                                                <?php
                                                } else if ($row_products["ptypeid"] == 3) {
                                                    # --- Pickup & Dropoff --- #
                                                    $pickup_name = $row_products["pickup"] > '0' ? get_value('place', 'id', 'name', $row_products["pickup"], $mysqli_p) : "N/A";
                                                    $zones_pickup_id = $row_products["pickup"] > '0' ? get_value('place', 'id', 'zones', $row_products["pickup"], $mysqli_p) : '0';
                                                    $zones_pickup_name = $zones_pickup_id > '0' ? get_value('zones', 'id', 'name', $zones_pickup_id, $mysqli_p) : "N/A";
                                                    $dropoff_name = $row_products["dropoff"] > '0' ? get_value('place', 'id', 'name', $row_products["dropoff"], $mysqli_p) : "N/A";
                                                    $zones_dropoff_id = $row_products["dropoff"] > '0' ? get_value('place', 'id', 'zones', $row_products["dropoff"], $mysqli_p) : '0';
                                                    $zones_dropoff_name = $zones_dropoff_id > '0' ? get_value('zones', 'id', 'name', $zones_dropoff_id, $mysqli_p) : "N/A";
                                                ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" class="custom-control-input" id="check_confirm<?php echo $row_products["id"]; ?>" name="check_confirm<?php echo $row_products["id"]; ?>" value="1" onclick="check_confirm_op('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkconfirm-op');" <?php echo $row_products["status_confirm_op"] == 1 ? 'checked' : ''; ?> <?php echo $row_products['invoice'] == '1'  ? 'disabled' : ''; ?>>
                                                                <label class="custom-control-label" for="check_confirm<?php echo $row_products["id"]; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_products["pcsname"]; ?></br>: <?php echo $row_products["pcfname"]; ?>
                                                            <?php if ($row_products["edit_date"] == '1') { ?>
                                                                <div class="notify" style="z-index: 99999;"> <span class="heartbit"></span> <span class="point"></span> </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td align="center"><?php echo $row_products["bvoucher"]; ?></td>
                                                        <td align="center"><span style="color:#2E8E9C"><?php echo $travel_date; ?></span></td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bagent"] != 0) ? get_value("agent", "id", "company", $row_products["bagent"], $mysqli_p) : "-"; ?>
                                                            <?php echo ($row_products["bagentv"] != "") ? "/ </br>" . $row_products["bagentv"] : ""; ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bcfname"] != "") ? $row_products["bcfname"] . " " . $row_products["bclname"] : "-"; ?>
                                                            <?php echo ($row_products["bcmb"] != "") ? "</br>" . $row_products["bcmb"] : ""; ?>
                                                        </td>
                                                        <td align="center"><?php echo number_format($row_products["adults"]) . "/" . number_format($row_products["children"]) . "/" . number_format($row_products["infant"]) . "/" . number_format($row_products["foc"]); ?></td>
                                                        <td align="center">
                                                            <span id="text_vans<?php echo $row_products['id']; ?>"><?php echo $row_products['vans']; ?></span>
                                                            <?php
                                                            if ($row_products['vans'] != '') {
                                                                $icon_vans = "fa-edit";
                                                                $color_icon_vans = "#0000FF";
                                                            } else {
                                                                $icon_vans = "fa-plus";
                                                                $color_icon_vans = "#00FF00";
                                                            } ?>
                                                            <a href="#add" title="add" id="add_vans(<?php echo $row_products['id']; ?>)" name="add_vans(<?php echo $row_products['id']; ?>)" onclick="check_add_vans('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkaddvans');">
                                                                <i class="fas <?php echo $icon_vans; ?>" style="color:<?php echo $color_icon_vans; ?>"></i></a>
                                                        </td>
                                                        <td><?php echo $row_products["pickup_time"]; ?></td>
                                                        <td align="center"><?php echo number_format($row_products["no_cars"]) . "/" . number_format($row_products["no_hours"]); ?></td>
                                                        <td align="center"><?php echo $pickup_name; ?></td>
                                                        <td align="center"><?php echo $zones_pickup_name; ?></td>
                                                        <td align="center"><?php echo $dropoff_name; ?></td>
                                                        <td align="center"><?php echo $zones_dropoff_name; ?></td>
                                                        <td align="center"><?php echo ($row_products["roomno"] != 0) ? $row_products["roomno"] : "N/A"; ?></td>
                                                        <td align="center"><a href="#" data-toggle="modal" data-target="#modalproducts<?php echo $row_products["id"]; ?>"><i class="fas fa-eye"></i></a></td>
                                                    </tr>
                                                    <!-- modal content detail -->
                                                    <div id="modalproducts<?php echo $row_products["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="vcenter">รายละเอียด</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo (!empty($row_products["bo_detail"])) ? '<p>' . nl2br($row_products["bo_detail"]) . '</p>' : 'ไม่มีข้อมูลรายละเอียด'; ?>
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
                                                    <!--- Num adults, children, infant, foc --->
                                                    <?php
                                                    $num_adults = $num_adults + $row_products["adults"];
                                                    $num_children = $num_children + $row_products["children"];
                                                    $num_infant = $num_infant + $row_products["infant"];
                                                    $num_foc = $num_foc + $row_products["foc"];
                                                    ?>
                                                <?php } else if ($row_products["ptypeid"] == 4) { ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" class="custom-control-input" id="check_confirm<?php echo $row_products["id"]; ?>" name="check_confirm<?php echo $row_products["id"]; ?>" value="1" onclick="check_confirm_op('<?php echo $row_products['id']; ?>', '<?php echo $row_products['booking']; ?>', 'checkconfirm-op');" <?php echo $row_products["status_confirm_op"] == 1 ? 'checked' : ''; ?> <?php echo $row_products['invoice'] == '1'  ? 'disabled' : ''; ?>>
                                                                <label class="custom-control-label" for="check_confirm<?php echo $row_products["id"]; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo $row_products["pcsname"]; ?></br>: <?php echo $row_products["pcfname"]; ?>
                                                            <?php if ($row_products["edit_date"] == '1') { ?>
                                                                <div class="notify" style="z-index: 99999;"> <span class="heartbit"></span> <span class="point"></span> </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td align="center"><?php echo $row_products["bvoucher"]; ?></td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bagent"] != 0) ? get_value("agent", "id", "company", $row_products["bagent"], $mysqli_p) : "-"; ?>
                                                            <?php echo ($row_products["bagentv"] != "") ? "/ </br>" . $row_products["bagentv"] : ""; ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php echo ($row_products["bcfname"] != "") ? $row_products["bcfname"] . " " . $row_products["bclname"] : "-"; ?>
                                                            <?php echo ($row_products["bcmb"] != "") ? "</br>" . $row_products["bcmb"] : ""; ?>
                                                        </td>
                                                        <td><span style="color:#2E8E9C"><?php echo $checkin_date; ?></span></td>
                                                        <td><span style="color:#2E8E9C"><?php echo $checkout_date; ?></span></td>
                                                        <td align="center"><?php echo number_format($row_products["adults"]) . "/" . number_format($row_products["children"]) . "/" . number_format($row_products["infant"]) . "/" . number_format($row_products["foc"]); ?></td>
                                                        <td align="center"><?php echo number_format($row_products["no_rooms"]) . "/" . number_format($row_products["extra_beds_adult"]) . "/" . number_format($row_products["extra_beds_child"]) . "/" . number_format($row_products["share_bed"]); ?></td>
                                                        <td align="center"><a href="#" data-toggle="modal" data-target="#modalproducts<?php echo $row_products["id"]; ?>"><i class="fas fa-eye"></i></a></td>
                                                    </tr>
                                                    <!-- modal content detail -->
                                                    <div id="modalproducts<?php echo $row_products["id"]; ?>" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="vcenter">รายละเอียด</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo (!empty($row_products["bo_detail"])) ? '<p>' . nl2br($row_products["bo_detail"]) . '</p>' : 'ไม่มีข้อมูลรายละเอียด'; ?>
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
                                                    <!--- Num adults, children, infant, foc --->
                                                    <?php
                                                    $num_adults = $num_adults + $row_products["adults"];
                                                    $num_children = $num_children + $row_products["children"];
                                                    $num_infant = $num_infant + $row_products["infant"];
                                                    $num_foc = $num_foc + $row_products["foc"];
                                                    ?>
                                                <?php }
                                                $numrow_realtime++;
                                                ?>
                                            <?php
                                        }
                                        // echo ($numrow_products > 0) ? '</tbody></table></div></div></div></div>' : '';
                                        if ($numrow_realtime != 1) {
                                            echo '</tbody></table></div></div></div></div>';
                                            $ptypeid = $second_before; ?>
                                                <input type="hidden" id="num_adults_<?php echo $ptypeid; ?>" name="num_adults_<?php echo $ptypeid; ?>" value="<?php echo $num_adults; ?>">
                                                <input type="hidden" id="num_children_<?php echo $ptypeid; ?>" name="num_children_<?php echo $ptypeid; ?>" value="<?php echo $num_children; ?>">
                                                <input type="hidden" id="num_infant_<?php echo $ptypeid; ?>" name="num_infant_<?php echo $ptypeid; ?>" value="<?php echo $num_infant; ?>">
                                                <input type="hidden" id="num_foc_<?php echo $ptypeid; ?>" name="num_foc_<?php echo $ptypeid; ?>" value="<?php echo $num_foc; ?>">
                                                <script>
                                                    var num_adults = document.getElementById('num_adults_<?php echo $ptypeid; ?>').value;
                                                    var num_children = document.getElementById('num_children_<?php echo $ptypeid; ?>').value;
                                                    var num_infant = document.getElementById('num_infant_<?php echo $ptypeid; ?>').value;
                                                    var num_foc = document.getElementById('num_foc_<?php echo $ptypeid; ?>').value;
                                                    document.getElementById('num_people_<?php echo $ptypeid; ?>').innerHTML = 'รวมทั้งหมด(ผู้ใหญ่/เด็ก/ทารก/FOC) : ' + num_adults + '/' + num_children + '/' + num_infant + '/' + num_foc;
                                                </script>
                                            <?php
                                            $num_adults = 0;
                                            $num_children = 0;
                                            $num_infant = 0;
                                            $num_foc = 0;
                                        }
                                            ?>
                                        </div>
                                        <input type="hidden" name="result" id="result" value="<?php echo implode(',', $report_id); ?>">
                                        <div id="text_operator"></div>
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