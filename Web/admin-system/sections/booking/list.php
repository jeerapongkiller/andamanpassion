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
                                <li class="breadcrumb-item active">การจอง</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-info m-l-15" onclick="window.location.href='./?mode=booking/detail'">
                                <i class="fa fa-plus-circle"></i> เพิ่มข้อมูล</button>
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

                        function deleteList(spid) {
                            Swal.fire({
                                type: 'warning',
                                title: 'คุณแน่ใจไหม?',
                                text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่?",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่ ลบข้อมูล!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.value) {
                                    jQuery.ajax({
                                        url: "../inc/ajax/booking/deletelist.php",
                                        data: {
                                            sp_id: spid
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

                        function restoreList(spid) {
                            Swal.fire({
                                type: 'warning',
                                title: 'คุณแน่ใจไหม?',
                                text: "คุณต้องการคืนข้อมูลนี้ใช่หรือไม่?",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่ คืนข้อมูล!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.value) {
                                    jQuery.ajax({
                                        url: "../inc/ajax/booking/restorelist.php",
                                        data: {
                                            sp_id: spid
                                        },
                                        type: "POST",
                                        success: function(response) {
                                            Swal.fire({
                                                title: "คืนข้อมูลเสร็จสิ้น!",
                                                text: "ข้อมูลที่คุณเลือกคืนเข้าระบบแล้ว",
                                                type: "success"
                                            }).then(function() {
                                                location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                            });
                                        },
                                        error: function() {
                                            Swal.fire('คืนข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                        }
                                    });
                                }
                            })
                            return true;
                        }
                    </script>

                    <?php
                    # check value from search
                    $search_voucher_no_val = !empty($_POST["search_voucher_no"]) ? $_POST["search_voucher_no"] : '';
                    $search_customer_firstname_val = !empty($_POST["search_customer_firstname"]) ? $_POST["search_customer_firstname"] : '';
                    $search_customer_lastname_val = !empty($_POST["search_customer_lastname"]) ? $_POST["search_customer_lastname"] : '';
                    $search_customer_mobile_val = !empty($_POST["search_customer_mobile"]) ? $_POST["search_customer_mobile"] : '';
                    $search_booking_date_from_val = !empty($_POST["search_booking_date_from"]) ? $_POST["search_booking_date_from"] : ''; //$today;
                    $search_booking_date_to_val = !empty($_POST["search_booking_date_to"]) ? $_POST["search_booking_date_to"] : ''; //$today;
                    $search_agent_val = !empty($_POST["search_agent_val"]) ? $_POST["search_agent_val"] : '';
                    $search_travel_date_from_val = !empty($_POST["search_travel_date_from"]) ? $_POST["search_travel_date_from"] : ""; // $today;
                    $search_travel_date_to_val = !empty($_POST["search_travel_date_to"]) ? $_POST["search_travel_date_to"] : ""; //$today;
                    $search_status_email_val = !empty($_POST["search_status_email"]) ? $_POST["search_status_email"] : '';
                    $search_status_confirm_val = !empty($_POST["search_status_confirm"]) ? $_POST["search_status_confirm"] : '';
                    $search_status_val = !empty($_POST["search_status"]) ? $_POST["search_status"] : '';
                    $search_products_val = !empty($_POST["search_products"]) ? $_POST["search_products"] : '';
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=<?php echo $_GET["mode"]; ?>" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_voucher_no">Voucher</label>
                                            <input type="text" class="form-control" id="search_voucher_no" name="search_voucher_no" placeholder="" value="<?php echo $search_voucher_no_val; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_products">สินค้า</label>
                                            <select class="custom-select" id="search_products" name="search_products">
                                                <option value="0" <?php if ($search_products_val == 0) {
                                                                        echo "selected";
                                                                    } ?>>ทั้งหมด</option>
                                                <?php
                                                $first_before = 0;
                                                $numrow_realtime = 1;
                                                $query_products = "SELECT PCS.*,
                                                                    PCF.id as pcfID, PCF.name as pcfName 
                                                                FROM products_category_second PCS
                                                                LEFT JOIN products_category_first PCF
                                                                    ON PCS.products_category_first = PCF.id
                                                                WHERE PCS.id > '0'";
                                                $query_products .= " ORDER BY PCF.name ASC";
                                                $result_products = mysqli_query($mysqli_p, $query_products);
                                                while ($row_products = mysqli_fetch_array($result_products, MYSQLI_ASSOC)) {
                                                    if ($first_before != $row_products["pcfID"]) {
                                                        echo ($numrow_realtime != 1) ? '</optgroup>' : '';
                                                        echo '<optgroup label=" ' . $row_products["pcfName"] . ' ">';
                                                        $first_before = $row_products["pcfID"];
                                                    }
                                                ?>
                                                    <option value="<?php echo $row_products["id"]; ?>" <?php if ($search_products_val == $row_products["id"]) {
                                                                                                            echo "selected";
                                                                                                        } ?>>
                                                        <?php echo $row_products["name"]; ?></option>
                                                <?php
                                                    echo ($numrow_realtime != 1) ? '</optgroup>' : '';
                                                }
                                                ?>
                                            </select>
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
                                            <label for="search_customer_mobile">เบอร์โทรศัพท์ (ลูกค้า)</label>
                                            <input type="text" class="form-control" id="search_customer_mobile" name="search_customer_mobile" placeholder="" value="<?php echo $search_customer_mobile_val; ?>">
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
                                            <label for="search_booking_date_from">วันที่จอง (จาก)</label>
                                            <input type="text" class="form-control" id="search_booking_date_from" name="search_booking_date_from" placeholder="" value="<?php echo $search_booking_date_from_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_booking_date_to">วันที่จอง (ถึง)</label>
                                            <input type="text" class="form-control" id="search_booking_date_to" name="search_booking_date_to" placeholder="" value="<?php echo $search_booking_date_to_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_travel_date_from">วันที่เที่ยว / วันที่เช็คอิน (จาก)</label>
                                            <input type="text" class="form-control" id="search_travel_date_from" name="search_travel_date_from" placeholder="" value="<?php echo $search_travel_date_from_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_travel_date_to">วันที่เที่ยว / วันที่เช็คเอาท์ (ถึง)</label>
                                            <input type="text" class="form-control" id="search_travel_date_to" name="search_travel_date_to" placeholder="" value="<?php echo $search_travel_date_to_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_status_email">สถานะการส่งอีเมล์</label>
                                            <select class="custom-select" id="search_status_email" name="search_status_email">
                                                <option value="0" <?php if ($search_status_email_val == 0) {
                                                                        echo "selected";
                                                                    } ?>>ทั้งหมด</option>
                                                <?php
                                                $query_statusemail = "SELECT * FROM booking_status_email WHERE id > '0'";
                                                $query_statusemail .= " ORDER BY id ASC";
                                                $result_statusemail = mysqli_query($mysqli_p, $query_statusemail);
                                                while ($row_statusemail = mysqli_fetch_array($result_statusemail, MYSQLI_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row_statusemail["id"]; ?>" <?php if ($search_status_email_val == $row_statusemail["id"]) {
                                                                                                                echo "selected";
                                                                                                            } ?>>
                                                        <?php echo $row_statusemail["name_thai"]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_status_confirm">สถานะการยืนยัน</label>
                                            <select class="custom-select" id="search_status_confirm" name="search_status_confirm">
                                                <option value="0" <?php if ($search_status_confirm_val == 0) {
                                                                        echo "selected";
                                                                    } ?>>ทั้งหมด</option>
                                                <?php
                                                $query_statusconfirm = "SELECT * FROM booking_status_confirm WHERE id > '0'";
                                                $query_statusconfirm .= " ORDER BY id ASC";
                                                $result_statusconfirm = mysqli_query($mysqli_p, $query_statusconfirm);
                                                while ($row_statusconfirm = mysqli_fetch_array($result_statusconfirm, MYSQLI_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row_statusconfirm["id"]; ?>" <?php if ($search_status_confirm_val == $row_statusconfirm["id"]) {
                                                                                                                echo "selected";
                                                                                                            } ?>>
                                                        <?php echo $row_statusconfirm["name_thai"]; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_status">สถานะ</label>
                                            <select class="custom-select" id="search_status" name="search_status">
                                                <option value="0" <?php if ($search_status_val == 0) {
                                                                        echo "selected";
                                                                    } ?>>ทั้งหมด</option>
                                                <option value="1" <?php if ($search_status_val == 1) {
                                                                        echo "selected";
                                                                    } ?>>ออนไลน์</option>
                                                <option value="2" <?php if ($search_status_val == 2) {
                                                                        echo "selected";
                                                                    } ?>>ยกเลิก</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=booking/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"> </h4>
                                <!-- <h6 class="card-subtitle">Description</h6> -->
                                <div class="table-responsive m-t-40">
                                    <table id="booking-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>สถานะ</th>
                                                <th>Voucher No.</th>
                                                <th>วันที่จอง</th>
                                                <th>เอเย่นต์</th>
                                                <th>ชื่อ-สกุล (ลูกค้า)</th>
                                                <th>เบอร์โทรศัพท์ (ลูกค้า)</th>
                                                <th>วันที่เที่ยว</th>
                                                <th>สินค้า</th>
                                                <th>ผู้จัดทำ</th>
                                                <th>โรงแรม</th>
                                                <th>ยอดเหลือ</th>
                                                <th>สถานะการส่งอีเมล์</th>
                                                <th>สถานะการยืนยัน</th>
                                                <th>แก้ไข</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            # -- Check Search Travel Date
                                            if (!empty($search_travel_date_from_val) && !empty($search_travel_date_to_val)) {
                                                $id_travels = array();
                                                $query_product = "SELECT * FROM booking_products WHERE id > '0' AND travel_date BETWEEN ? AND ? OR checkin_date >= ? AND checkout_date <= ? ";
                                                $procedural_statement = mysqli_prepare($mysqli_p, $query_product);
                                                mysqli_stmt_bind_param($procedural_statement, 'ssss', $search_travel_date_from_val, $search_travel_date_to_val, $search_travel_date_from_val, $search_travel_date_to_val);
                                                mysqli_stmt_execute($procedural_statement);
                                                $result_products = mysqli_stmt_get_result($procedural_statement);
                                                while ($row_products = mysqli_fetch_array($result_products, MYSQLI_ASSOC)) {
                                                    if ($row_products['date_not_specified'] != "1") {
                                                        array_push($id_travels, $row_products['booking']);
                                                    }
                                                }
                                            }

                                            # --- Check Search Products --- #
                                            if (!empty($search_products_val)) {
                                                $id_products = array();
                                                $query_product = "SELECT * FROM booking_products WHERE id > '0' AND products_category_second = '$search_products_val' ";
                                                $procedural_statement = mysqli_prepare($mysqli_p, $query_product);
                                                mysqli_stmt_execute($procedural_statement);
                                                $result = mysqli_stmt_get_result($procedural_statement);
                                                while ($row_products = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                    array_push($id_products, $row_products['booking']);
                                                }
                                            }

                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();

                                            $query = "SELECT booking.*, 
                                                        agent.id as agentID, 
                                                        agent.company as agentName
                                                        FROM booking 
                                                        LEFT JOIN agent 
                                                            ON booking.agent = agent.id
                                                        WHERE booking.id > '0'
                                            ";
                                            if ($_SESSION["admin"]["permission"] != 1) {
                                                $query .= " AND trash_deleted != '1'";
                                            }
                                            if (!empty($search_travel_date_from_val) && !empty($search_travel_date_to_val)) {
                                                # search travel date , check in , check out
                                                $id_booking = array_unique($id_travels);
                                                $count = array_count_values($id_booking);
                                                $i = "1";
                                                $value_id = "(";
                                                foreach ($id_booking as $value) {
                                                    $value_id .= count($id_booking) != $i ? $value . ", " : $value . ")";
                                                    $i++;
                                                }
                                                $query .= "AND booking.id IN $value_id";
                                            }
                                            if (!empty($search_voucher_no_val)) {
                                                # search voucher_no
                                                $query .= " AND booking.voucher_no = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_voucher_no_val);
                                            }
                                            if (!empty($search_products_val)) {
                                                # search voucher_no
                                                $un_products = array_unique($id_products);
                                                $count_pro = array_count_values($un_products);
                                                $i = "1";
                                                $value_id = "(";
                                                foreach ($un_products as $value) {
                                                    $value_id .= count($un_products) != $i ? $value . ", " : $value . ")";
                                                    $i++;
                                                }
                                                $query .= "AND booking.id IN $value_id";
                                            }
                                            if (!empty($search_customer_firstname_val)) {
                                                # search customer firstname
                                                $query .= " AND booking.customer_firstname = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_customer_firstname_val);
                                            }
                                            if (!empty($search_customer_lastname_val)) {
                                                # search customer lastname
                                                $query .= " AND booking.customer_lastname = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_customer_lastname_val);
                                            }
                                            if (!empty($search_customer_mobile_val)) {
                                                # search customer mobile
                                                $query .= " AND booking.customer_mobile = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_customer_mobile_val);
                                            }
                                            if (!empty($search_agent_val)) {
                                                # search agent
                                                $query .= " AND booking.agent = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_agent_val);
                                            }
                                            if (!empty($search_status_email_val)) {
                                                # search status_email
                                                if ($search_status_email_val != "3") {
                                                    $query .= " AND booking.status_email = ?";
                                                } else {
                                                    $query .= " AND booking.status_email_revise = ?";
                                                }
                                                $bind_types .= "i";
                                                array_push($params, $search_status_email_val);
                                            }
                                            if (!empty($search_status_confirm_val)) {
                                                # search status_confirm
                                                $query .= " AND booking.status_confirm = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_status_confirm_val);
                                            }
                                            if (!empty($search_status_val)) {
                                                # search status
                                                $query .= " AND booking.status = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_status_val);
                                            }
                                            if (!empty($search_booking_date_from_val) && !empty($search_booking_date_to_val)) {
                                                # search status
                                                $query .= " AND booking.booking_date BETWEEN ? AND ?";
                                                $bind_types .= "ss";
                                                array_push($params, $search_booking_date_from_val, $search_booking_date_to_val);
                                            }
                                            $query .= " ORDER BY booking_date DESC";

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
                                                $status_class = $row["status"] == 1 ? 'success' : 'danger';
                                                $status_txt = $row["status"] == 1 ? 'ออนไลน์' : 'ยกเลิก';

                                                $status_email_class = $row["status_email"] == 1 ? 'success' : 'danger';
                                                $status_email_txt = $row["status_email"] == 1 ? 'ส่งแล้ว' : 'ยังไม่ส่ง';

                                                $status_comfirm_class = $row["status_confirm"] == 1 ? 'success' : 'danger';
                                                $status_comfirm_txt = $row["status_confirm"] == 1 ? 'ยืนยันแล้ว' : 'ยังไม่ยืนยัน';

                                                $agent_name = !empty($row["agentName"]) ? $row["agentName"] : '-';
                                                $customer_fullname = !empty($row["customer_firstname"]) ? $row["customer_firstname"] . " " . $row["customer_lastname"] : '-';
                                                $customer_mobile = !empty($row["customer_mobile"]) ? $row["customer_mobile"] : '-';
                                                $products_TravelDate = $row["travel_date_min"] != "0000-00-00" ? date("Y-m-d", strtotime($row["travel_date_min"])) . "<br />(" . date("d F Y", strtotime($row["travel_date_min"])) . ")" : 'ไม่มีสินค้า';

                                                $arr_products = array();
                                                $arr_hotel = array();
                                                $arr_price_balance = array();
                                                $price_total = 0;

                                                $query_products = "SELECT BP.*,
                                                                    PCS.id as pcsID, PCS.name as pcsName,
                                                                    PCF.id as pcfID, PCF.name as pcfName,
                                                                    PT.id as ptID, PT.name_text_thai as ptName
                                                                    FROM booking_products BP
                                                                    LEFT JOIN products_category_first PCF
                                                                        ON BP.products_category_first = PCF.id
                                                                    LEFT JOIN products_category_second PCS
                                                                        ON  BP.products_category_second = PCS.id
                                                                    LEFT JOIN products_type PT
                                                                        ON BP.products_type = PT.id
                                                                    WHERE BP.id > 0 
                                                                    AND BP.booking = '" . $row["id"] . "' ";
                                                $procedural_statement_products = mysqli_prepare($mysqli_p, $query_products);
                                                mysqli_stmt_execute($procedural_statement_products);
                                                $result_products = mysqli_stmt_get_result($procedural_statement_products);
                                                while ($row_products = mysqli_fetch_array($result_products, MYSQLI_ASSOC)) {
                                                    $price_total = $price_total + $row_products["price_latest"];
                                                    if ($row_products['products_type'] != '4') {
                                                        array_push($arr_products, $row_products['pcsName']);
                                                    } else {
                                                        array_push($arr_hotel, $row_products['pcsName']);
                                                    }
                                                }

                                                $query_payment = "SELECT SUM(amount_paid) as price_amount FROM payments_booking WHERE booking = '" . $row["id"] . "' ";
                                                $result_payment = mysqli_query($mysqli_p, $query_payment);
                                                $row_payment = mysqli_fetch_array($result_payment, MYSQLI_ASSOC);
                                                $price_amount = $price_total - $row_payment['price_amount'];

                                                $query_history = "SELECT *, MIN(`create_date`) FROM `booking_history` WHERE `booking` = '" . $row["id"] . "' ";
                                                $result_history = mysqli_query($mysqli_p, $query_history);
                                                $row_history = mysqli_fetch_array($result_history, MYSQLI_ASSOC);
                                            ?>
                                                <tr>
                                                    <td align="center"><span class="label label-<?php echo $status_class; ?>"><?php echo $status_txt; ?></span></td>
                                                    <td align="center"><?php echo $row["voucher_no"]; ?></td>
                                                    <td align="center"><?php echo date("Y-m-d", strtotime($row["booking_date"])) . "<br />(" . date("d F Y", strtotime($row["booking_date"])) . ")"; ?></td>
                                                    <td align="center"><?php echo $agent_name; ?></td>
                                                    <td align="center"><?php echo $customer_fullname; ?></td>
                                                    <td><?php echo $customer_mobile; ?></td>
                                                    <td align="center"><?php echo $products_TravelDate; ?></td>
                                                    <td align="right"><?php
                                                                            $count_products = count($arr_products);
                                                                            for ($i = 0; $i < $count_products; $i++) {
                                                                                echo $arr_products[$i] . '</br>';
                                                                            }
                                                                            ?></td>
                                                    <td align="right"><?php echo get_value('employee', 'id', 'name', $row_history['employee'], $mysqli_p); ?></td>
                                                    <td align="right"><?php
                                                                            $count_hotel = count($arr_hotel);
                                                                            for ($i = 0; $i < $count_hotel; $i++) {
                                                                                echo $arr_hotel[$i] . '</br>';
                                                                            }
                                                                            ?></td>
                                                    <td align="right"><?php echo number_format($price_amount); ?></td>
                                                    <td align="center"><span class="label label-<?php echo $status_email_class; ?>"><?php echo $status_email_txt; ?></span></td>
                                                    <td align="center"><span class="label label-<?php echo $status_comfirm_class; ?>"><?php echo $status_comfirm_txt; ?></span></td>
                                                    <td align="center"><a href="./?mode=booking/detail&id=<?php echo $row["id"]; ?>"><i class="fas fa-edit"></i></a></td>
                                                </tr>
                                            <?php
                                            } /* while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ */

                                            mysqli_close($mysqli_p);
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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