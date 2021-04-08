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
                                <li class="breadcrumb-item active">สร้างใบแจ้งหนี้</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                        </div>
                    </div>
                </div>

                <script>
                    function checkall_product(source) {
                        checkboxes = document.getElementsByName('create_invoice[]');
                        for (var i = 0, n = checkboxes.length; i < n; i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }

                    function createinvoice_agent(prodtype){
                        Swal.fire({
                            type: 'warning',
                            title: 'สร้างใบแจ้งหนี้(สำหรับเอเย่นต์)?',
                            text: "ต้องการสร้างใบแจ้งหนี้ใช่หรือไม่?",
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่ สร้างใบแจ้งหนี้!',
                            cancelButtonText: 'ปิด'
                        }).then((result) => {
                            if(result.value){
                                jQuery.ajax({
                                    url: "../inc/ajax/ar/" + prodtype + ".php",
                                    data: {
                                        status: '1'
                                    },
                                    type: "POST",
                                    success: function(response) {
                                        Swal.fire({
                                            title: "สร้างใบแจ้งหนี้สำหรับเอเย่นต์สำเร็จ!",
                                            type: "success"
                                        }).then(function() {
                                            // $("#print_invioce").html(response)
                                            location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                        });
                                    },
                                    error: function() {
                                        Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', 'กรุณาลองใหม่อีกครั้ง', 'error')
                                        exit
                                    }
                                });
                            }
                        })
                    }

                    function createinvoice_normal(prodtype) {
                        var products_id = []
                        <?php
                        $query_dp = "SELECT * FROM booking_products WHERE status_confirm_op = '1' ";
                        $procedural_statement_dp = mysqli_prepare($mysqli_p, $query_dp);
                        mysqli_stmt_execute($procedural_statement_dp);
                        $result_dp = mysqli_stmt_get_result($procedural_statement_dp);
                        while ($row_dp = mysqli_fetch_array($result_dp, MYSQLI_ASSOC)) {
                        ?>
                            var create_invoice = document.getElementById('create_invoice' + <?php echo $row_dp['id']; ?>)
                            if (create_invoice) {
                                if (create_invoice.checked) {
                                    products_id.push(<?php echo $row_dp['id']; ?>)
                                }
                            }
                        <?php  }  ?>
                        if (products_id == '') {
                            Swal.fire('สร้างใบแจ้งหนี้ไม่สำเร็จ!', 'กรุณาเลือกสินค้า', 'error')
                            return false
                        }
                        (async () => {
                            // Select Vat
                            const radioOptions = new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve({
                                        '3': 'แยก VAT',
                                        '2': 'รวม VAT',
                                        '1': 'VAT 0%'
                                    })
                                }, 0)
                            })
                            const { value: vat } = await Swal.fire({
                                type: "question",
                                title: 'เลือก VAT เพื่อส้รางใบแจ้งหนี้',
                                input: 'radio',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ถัดไป',
                                cancelButtonText: 'ปิด',
                                inputOptions: radioOptions,
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'กรุณาเลือกรายการ!'
                                    }
                                }
                            })
                            if (vat) {
                                // Select Period
                                const { value: period } = await Swal.fire({
                                    type: "question",
                                    title: 'เลือกจำนวนงวดแบ่งจ่าย',
                                    input: 'select',
                                    inputOptions: {
                                        '1': '1 งวด',
                                        '2': '2 งวด',
                                        '3': '3 งวด',
                                        '4': '4 งวด',
                                        '5': '5 งวด'
                                    },
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ถัดไป',
                                    cancelButtonText: 'ปิด'
                                })
                                if (period) {
                                    var search_voucher_no = document.getElementById('search_voucher_no').value;
                                    var search_customer_firstname = document.getElementById('search_customer_firstname').value;
                                    var search_customer_lastname = document.getElementById('search_customer_lastname').value;
                                    var search_travel_date_from = document.getElementById('search_travel_date_from').value;
                                    var search_travel_date_to = document.getElementById('search_travel_date_to').value;
                                    // Select Percent
                                    jQuery.ajax({
                                        url: "../inc/ajax/ar/select_percent.php",
                                        data: {
                                            period: period,
                                            vat: vat,
                                            products_id: products_id,
                                            search_voucher_no: search_voucher_no,
                                            search_customer_firstname: search_customer_firstname,
                                            search_customer_lastname: search_customer_lastname,
                                            search_travel_date_from: search_travel_date_from,
                                            search_travel_date_to: search_travel_date_to
                                        },
                                        type: "POST",
                                        success: function(response) {
                                            if (response) {
                                                swal.fire({
                                                    type: "question",
                                                    title: 'เลือกเปอร์เซ็นในแต่ล่ะงวด! ',
                                                    // width: 1000,
                                                    html: response,
                                                    showConfirmButton: false,
                                                    showCancelButton: false,
                                                    showCloseButton: true
                                                }).then((result) => {

                                                });
                                            }
                                        }
                                    });
                                }
                            }
                        })()
                    }
                </script>

                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

                    <?php
                    # check value from search
                    $search_voucher_no_val = !empty($_POST["search_voucher_no"]) ? $_POST["search_voucher_no"] : '';
                    $search_customer_firstname_val = !empty($_POST["search_customer_firstname"]) ? $_POST["search_customer_firstname"] : '';
                    $search_customer_lastname_val = !empty($_POST["search_customer_lastname"]) ? $_POST["search_customer_lastname"] : '';
                    $search_agent_val = !empty($_POST["search_agent_val"]) ? $_POST["search_agent_val"] : '';
                    $search_travel_date_from_val = !empty($_POST["search_travel_date_from"]) ? $_POST["search_travel_date_from"] : ''; // $today;
                    $search_travel_date_to_val = !empty($_POST["search_travel_date_to"]) ? $_POST["search_travel_date_to"] : ''; //$today;
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
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=ar/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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
                                <h4 class="card-title">
                                    <button class="btn btn-success" onclick="createinvoice_normal('create_invoice_normal');">สร้างใบแจ้งหนี้ (สำหรับลูกค้าทั่วไป)</button>
                                    <button class="btn btn-primary" style="float:right;" onclick="createinvoice_agent('create_invoice_agent');">สร้างใบแจ้งหนี้ (สำหรับลูกค้าเอเย่นต์)</button>
                                </h4>

                                <div class="table-responsive m-t-20">
                                    <table id="ar-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>
                                                    <input type="checkbox" id="product_all" name="product_all" style="transform: scale(1.2);" value="1" onclick="checkall_product(this);">
                                                    &nbsp;&nbsp;เลือกทั้งหมด
                                                </th>
                                                <th>Voucher No.</th>
                                                <th>เอเยนต์</th>
                                                <th>ชื่อ-สกุล (ลูกค้า)</th>
                                                <th>เบอร์โทรศัพท์ (ลูกค้า)</th>
                                                <th>สินค้า</th>
                                                <th>วันที่เที่ยว/วันที่เช็คเอาท์</th>
                                                <th>รวมทั้งหมด</th>
                                                <th>จ่ายแล้ว</th>
                                                <th>ยอดเหลือ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();
                                            $query = "SELECT booking_products.*, products_category_first.id as pcfid, products_category_first.name as pcfname, products_type.id as ptypeid,
                                                        products_type.name_text_thai as ptypenamethai, products_category_second.id as pcsid, products_category_second.name as pcsname, 
                                                        booking_status_email.name_thai as emailthai, booking_status_confirm.name_thai as confirmthai, booking.id as bid, booking.voucher_no as bvoucher,
                                                        booking.agent as bagent, booking.agent_voucher as bagentv, booking.customer_firstname as bcfname, booking.customer_lastname as bclname, booking.customer_mobile as bcmb
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
                                                    LEFT JOIN booking_status_confirm
                                                        ON booking_products.status_confirm = booking_status_confirm.id
                                                    WHERE booking_products.id > '0' AND booking_products.status_confirm_op = '1' AND booking_products.invoice = '0'
                                            ";

                                            if (!empty($search_travel_date_from_val) && !empty($search_travel_date_to_val)) {
                                                # search travel date , check in , check out
                                                $query .= " AND (booking_products.travel_date BETWEEN ? AND ?";
                                                $bind_types .= "ss";
                                                array_push($params, $search_travel_date_from_val, $search_travel_date_to_val);

                                                $query .= " OR booking_products.checkout_date >= ? AND booking_products.checkout_date <= ?) ";
                                                $bind_types .= "ss";
                                                array_push($params, $search_travel_date_from_val, $search_travel_date_to_val);
                                            }
                                            if (!empty($search_voucher_no_val)) {
                                                # search voucher_no
                                                $query .= " AND booking.voucher_no = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_voucher_no_val);
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

                                            // echo $query;

                                            $procedural_statement = mysqli_prepare($mysqli_p, $query);

                                            // Check error query
                                            if ($procedural_statement == false) {
                                                die("<pre>" . mysqli_error($mysqli_p) . PHP_EOL . $query . "</pre>");
                                            }

                                            // mysqli_stmt_bind_param($procedural_statement, 'ss', $search_name_val, $search_username_val);
                                            if ($bind_types != "") {
                                                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
                                            }

                                            mysqli_stmt_execute($procedural_statement);
                                            $result = mysqli_stmt_get_result($procedural_statement);
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                                $customer_fullname = !empty($row["bcfname"]) ? $row["bcfname"] . " " . $row["bclname"] : '-';
                                                $customer_mobile = !empty($row["bcmb"]) ? $row["bcmb"] : '-';
                                                if ($row["ptypeid"] != '4') {
                                                    $travel_date_pro = $row["travel_date"] != "0000-00-00" ? date("Y-m-d", strtotime($row["travel_date"])) . "<br />(" . date("d F Y", strtotime($row["travel_date"])) . ")" : 'ไม่มีสินค้า';
                                                } else {
                                                    $travel_date_pro = $row["checkout_date"] != "0000-00-00" ? date("Y-m-d", strtotime($row["checkout_date"])) . "<br />(" . date("d F Y", strtotime($row["checkout_date"])) . ")" : 'ไม่มีสินค้า';
                                                }
                                            ?>
                                                <tr>
                                                    <td align="center">
                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <?php if($row["bagent"] == 0){ ?>
                                                                <input type="checkbox" class="custom-control-input" id="create_invoice<?php echo $row["id"]; ?>" name="create_invoice[]" value="1">
                                                            <?php } else { ?>
                                                                <input type="checkbox" class="custom-control-input" id="invoice" name="invoice" disabled>
                                                            <?php } ?>
                                                            <label class="custom-control-label" for="create_invoice<?php echo $row["id"]; ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td align="center"><?php echo $row["bvoucher"]; ?></td>
                                                    <td align="center"><?php echo $row["bagent"] != 0 ? get_value("agent", "id", "company", $row["bagent"], $mysqli_p) : "-"; ?></td>
                                                    <td align="center"><?php echo $customer_fullname; ?></td>
                                                    <td><?php echo $customer_mobile; ?></td>
                                                    <td><?php echo $row["pcsname"]; ?></br>: <?php echo $row["pcfname"]; ?></td>
                                                    <td align="center"><?php echo $travel_date_pro; ?></td>
                                                    <td align="center"><?php echo number_format($row["price_latest"]); ?></td>
                                                    <td align="center"><?php echo number_format('0'); ?></td>
                                                    <td align="center"><?php echo number_format($row["price_latest"]); ?></td>
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

                    <div id="print_invioce"></div>

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