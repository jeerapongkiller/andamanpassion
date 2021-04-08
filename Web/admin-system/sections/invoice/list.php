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
                                <li class="breadcrumb-item active">สร้างใบวางบิล</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                        </div>
                    </div>
                </div>

                <script>
                    function checkall_invioce(source) {
                        checkboxes = document.getElementsByName('check_invoice[]');
                        for (var i = 0, n = checkboxes.length; i < n; i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }

                    function create_bill(prodtype) {
                        // Search Data
                        var search_voucher_no = document.getElementById('search_voucher_no').value;
                        var search_invoice_no = document.getElementById('search_invoice_no').value;
                        var search_travel_date_from = document.getElementById('search_travel_date_from').value;
                        var search_travel_date_to = document.getElementById('search_travel_date_to').value;
                        var search_customer_type = document.getElementById('search_customer_type').value;
                        var search_name_val = document.getElementById('search_name_val').value;
                        var search_agent_val = document.getElementById('search_agent_val').value; 
                        var search_company_val = document.getElementById('search_company_val').value;

                        var type_cus = document.getElementById('search_customer_type').value;
                        if (type_cus == 1) {
                            var agent = '0'
                        } else {
                            var agent = document.getElementById('search_agent_val').value;
                        }

                        var total_balance = [];
                        var value_balance = document.getElementsByName('total_balance[]');
                        var invoice_id = [];
                        var els = document.getElementsByName('check_invoice[]');
                        var period = [];
                        var period_id = document.getElementsByName('period[]');

                        for (var i = 0; i < els.length; i++) {
                            if (els[i].checked) {
                                invoice_id.push(els[i].value);
                                total_balance.push(value_balance[i].value);
                                period.push(period_id[i].value);
                            }
                        }

                        if (invoice_id != '') {
                            jQuery.ajax({
                                url: "../inc/ajax/invoice/" + prodtype + ".php",
                                data: {
                                    invoice_id: invoice_id,
                                    total_balance: total_balance,
                                    agent: agent,
                                    period: period,
                                    search_voucher_no: search_voucher_no,
                                    search_invoice_no: search_invoice_no,
                                    search_travel_date_from: search_travel_date_from,
                                    search_travel_date_to: search_travel_date_to,
                                    search_customer_type: search_customer_type,
                                    search_name_val: search_name_val,
                                    search_agent_val: search_agent_val,
                                    search_company_val: search_company_val
                                },
                                type: "POST",
                                success: function(response) {
                                    if (response) {
                                        swal.fire({
                                            title: 'สร้างใบวางบิล ',
                                            width: 1000,
                                            // html: ` `,
                                            html: response,
                                            showConfirmButton: false,
                                            showCancelButton: false,
                                            showCloseButton: true
                                        }).then((result) => {

                                        });
                                    }
                                }
                            });
                        } else {
                            Swal.fire('สร้างใบวางบิลไม่สำเร็จ!', 'กรุณาเลือกสินค้า', 'error')
                        }
                    }

                    function checkCustomertype() {
                        var search_customer_type = document.getElementById('search_customer_type').value;
                        var agentdiv = document.getElementById("agentdiv");
                        var customerdiv = document.getElementById("customerdiv");

                        if (search_customer_type == 2) {
                            agentdiv.style.display = "";
                            customerdiv.style.display = "none";
                        } else {
                            agentdiv.style.display = "none";
                            customerdiv.style.display = "";
                        }
                        return true;
                    }

                    function priceformat(inputfield, balance, total_balance) {
                        var total = '0';
                        var balance = document.getElementById(balance).value;
                        var paid = formatCurrency(inputfield).replace(",", "");
                        total = balance - paid;
                        if (total < 0) {
                            document.getElementById(inputfield).value = balance;
                            document.getElementById(total_balance).innerHTML = '0';
                        } else {
                            var parts = total.toString().split(".");
                            var num = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
                            document.getElementById(total_balance).innerHTML = num;

                        }
                    }

                    function formatNumber(n) {
                        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    }

                    function formatCurrency(input) {
                        var input2 = document.getElementById(input);
                        var input_val = input2.value;
                        // var input_val = input.val();
                        if (input_val === "") {
                            return;
                        }
                        var original_len = input_val.length;
                        // var caret_pos = input2.prop("selectionStart");

                        if (input_val.indexOf(".") >= 0) {
                            var decimal_pos = input_val.indexOf(".");
                            var left_side = input_val.substring(0, decimal_pos);
                            var right_side = input_val.substring(decimal_pos);
                            left_side = formatNumber(left_side);
                            right_side = formatNumber(right_side);
                            right_side = right_side.substring(0, 2);
                            input_val = left_side + "." + right_side;

                        } else {
                            input_val = formatNumber(input_val);
                            input_val = input_val;
                        }

                        input2.value = input_val;
                        if(input2.value == 0){
                            input2.value = '0';
                            return input2.value
                        }else{
                            return input2.value
                        }
                    }

                    function Checkfrom_bill(){
                        var check_return = '';
                        var bo_bi_name = document.getElementById('bo_bi_name').value;
                        var check_bi_name = document.getElementById('check_bi_name');
                        var bo_bi_address = document.getElementById('bo_bi_address').value;
                        var check_bi_address = document.getElementById('check_bi_address');

                        document.getElementById('check_bi_date').className = 'has-success';
                        document.getElementById('check_due_date').className = 'has-success';
                        document.getElementById('check_bi_condition').className = 'has-success';
                        document.getElementById('check_bi_con_detail').className = 'has-success';

                        var els = document.getElementsByName('invoice_id[]');
                        for (var i = 0; i < els.length; i++) {
                            var bill_paid = document.getElementById('bill_paid'+els[i].value).value;
                            var check_bill_paid = document.getElementById('check_bill_paid'+els[i].value);
                            if(bill_paid == ''){ check_bill_paid.className = 'has-danger'; check_return = 'false'; }else{ check_bill_paid.className = 'has-success'; }
                        }

                        if(bo_bi_name == ''){ check_bi_name.className = 'has-danger'; check_return = 'false'; }else{ check_bi_name.className = 'has-success'; }
                        if(bo_bi_address == ''){ check_bi_address.className = 'has-danger'; check_return = 'false'; }else{ check_bi_address.className = 'has-success'; }

                        if(check_return == 'false'){ return false; }else{ return true; }
                        
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
                    $search_company_val = !empty($_POST["search_company_val"]) ? $_POST["search_company_val"] : '2';
                    $search_voucher_no = !empty($_POST["search_voucher_no"]) ? $_POST["search_voucher_no"] : '';
                    $search_invoice_no = !empty($_POST["search_invoice_no"]) ? $_POST["search_invoice_no"] : '';
                    $search_travel_date_from_val = !empty($_POST["search_travel_date_from"]) ? $_POST["search_travel_date_from"] : ''; // $today;
                    $search_travel_date_to_val = !empty($_POST["search_travel_date_to"]) ? $_POST["search_travel_date_to"] : ''; //$today;
                    $search_customer_type = !empty($_POST["search_customer_type"]) ? $_POST["search_customer_type"] : '';
                    $search_name_val = !empty($_POST["search_name_val"]) ? $_POST["search_name_val"] : '';
                    $search_agent_val = !empty($_POST["search_agent_val"]) ? $_POST["search_agent_val"] : '';
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=<?php echo $_GET["mode"]; ?>" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_company_val">บริษัท</label>
                                            <select class="custom-select" id="search_company_val" name="search_company_val">
                                                <?php
                                                $query_com = "SELECT * FROM company WHERE id > '0'";
                                                $query_com .= " ORDER BY name ASC";
                                                $result_com = mysqli_query($mysqli_p, $query_com);
                                                while ($row_com = mysqli_fetch_array($result_com, MYSQLI_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row_com["id"]; ?>" <?php if ($search_company_val == $row_com["id"]) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                        <?php echo $row_com["name"]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_voucher_no">Voucher</label>
                                            <input type="text" class="form-control" id="search_voucher_no" name="search_voucher_no" placeholder="" value="<?php echo $search_voucher_no; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_invoice_no">เลขใบแจ้งหนี้</label>
                                            <input type="text" class="form-control" id="search_invoice_no" name="search_invoice_no" placeholder="" value="<?php echo $search_invoice_no; ?>">
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
                                            <label for="search_customer_type">ประเภทลูกค้า</label>
                                            <select class="custom-select" id="search_customer_type" name="search_customer_type" onchange="checkCustomertype(this)">
                                                <option value="1" <?php echo $search_customer_type == 1 ? "selected" : ""; ?>>ลูกค้าทั่วไป</option>
                                                <option value="2" <?php echo $search_customer_type == 2 ? "selected" : ""; ?>>เอเย่นต์</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3" id="customerdiv">
                                            <label for="search_name_val">ชื่อ</label>
                                            <input type="text" class="form-control" id="search_name_val" name="search_name_val" placeholder="" value="<?php echo $search_name_val; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3" id="agentdiv">
                                            <label for="search_agent_val">เอเย่นต์</label>
                                            <select class="custom-select" id="search_agent_val" name="search_agent_val">
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
                                    </div>
                                    <button class="btn btn-primary" type="submit"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=invoice/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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
                                    <button class="btn btn-success" onclick="create_bill('create_bill');">สร้างใบวางบิล</button>
                                </h4>

                                <div class="table-responsive m-t-20">
                                    <table id="invoice-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>
                                                    <input type="checkbox" id="invoice_all" name="invoice_all" style="transform: scale(1.2);" value="1" onclick="checkall_invioce(this);">
                                                    &nbsp;&nbsp;เลือกทั้งหมด
                                                </th>
                                                <th>เลขใบแจ้งหนี้</th>
                                                <th>วันที่เที่ยว</th>
                                                <th>เอเย่นต์</th>
                                                <th>ชื่อ-สกุล (ลูกค้า)</th>
                                                <th>รวมทั้งหมด</th>
                                                <th>จ่ายแล้ว</th>
                                                <th>ยอดเหลือ</th>
                                                <th>VC / Booking date / Agent VC</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();
                                            $query = "SELECT invoice.*, invoice_products.products_type as inv_pt, invoice_products.booking as inv_b, invoice_products.booking_products as inv_bp,
                                                        invoice_products.total_invoice as inv_total, invoice_products.transfer as inv_t, invoice_products.period as inv_p, booking.voucher_no as b_vn,
                                                        booking.booking_date as b_bate, booking.receipt_name as b_rename, booking.agent_voucher as b_agenyv, booking_products.travel_date as bptd,
                                                        booking_products.checkout_date as bpcd, booking_products.products_type as ptypeid
                                                    FROM invoice
                                                    LEFT JOIN invoice_products
                                                        ON invoice.id = invoice_products.invoice
                                                    LEFT JOIN booking
                                                        ON invoice_products.booking = booking.id
                                                    LEFT JOIN booking_products
                                                        ON invoice_products.booking_products = booking_products.id
                                                    WHERE invoice.id > '0' AND invoice.bill = '0'
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
                                            if (!empty($search_customer_type)) {
                                                # search customer type
                                                $query .= $search_customer_type == 1 ? " AND invoice.agent = ?" : "AND invoice.agent > ?";
                                                $bind_types .= "s";
                                                array_push($params, 0);
                                            }else{
                                                # search customer type
                                                $query .= " AND invoice.agent = ?";
                                                $bind_types .= "s";
                                                array_push($params, 0);
                                            }
                                            if (!empty($search_name_val) && $search_customer_type == 1) {
                                                # search name customer val
                                                $query .= " AND booking.receipt_name = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_name_val);
                                            }
                                            if (!empty($search_agent_val) && $search_customer_type == 2) {
                                                # search agent val
                                                $query .= " AND invoice.agent = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_agent_val);
                                            }
                                            if (!empty($search_voucher_no)) {
                                                # search voucher no
                                                $query .= " AND booking.voucher_no = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_voucher_no);
                                            }
                                            if (!empty($search_invoice_no)) {
                                                # search invoice no
                                                $query .= " AND invoice.inv_full = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_invoice_no);
                                            }
                                            if (!empty($search_company_val)) {
                                                # search company no
                                                $query .= " AND booking.company = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_company_val);
                                            }

                                            $query .= " ORDER BY invoice.inv_full ASC";

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
                                                if ($row["ptypeid"] != '4') {
                                                    $travel_date_pro = $row["bptd"] != "0000-00-00" ? date("Y-m-d", strtotime($row["bptd"])) . "<br />(" . date("d F Y", strtotime($row["bptd"])) . ")" : 'ไม่มีสินค้า';
                                                } else {
                                                    $travel_date_pro = $row["bpcd"] != "0000-00-00" ? date("Y-m-d", strtotime($row["bpcd"])) . "<br />(" . date("d F Y", strtotime($row["bpcd"])) . ")" : 'ไม่มีสินค้า';
                                                }

                                                #------ Pay Bill -----#
                                                $total_pay = '0';
                                                $total_balance = '0';
                                                $query_bill = "SELECT * FROM bill_invoice WHERE invoice = '" . $row['id'] . "' ";
                                                $result_bill = mysqli_query($mysqli_p, $query_bill);
                                                while ($row_bill = mysqli_fetch_array($result_bill, MYSQLI_ASSOC)) {
                                                    $total_pay = $total_pay + $row_bill['pay_bill'];
                                                }
                                                $total_balance = $row["inv_total"] - $total_pay;
                                            ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div class="custom-control custom-checkbox mb-3">
                                                                <input type="checkbox" class="custom-control-input" id="check_invoice<?php echo $row["id"]; ?>" name="check_invoice[]" value="<?php echo $row["id"]; ?>">
                                                                <label class="custom-control-label" for="check_invoice<?php echo $row["id"]; ?>"></label>
                                                            </div>
                                                            <input type="hidden" name="total_balance[]" id="total_balance<?php echo $row["id"]; ?>" value="<?php echo $total_balance; ?>">
                                                        </td>
                                                        <td align="center"><?php echo $row["inv_full"]; ?></td>
                                                        <td align="center"><?php echo $travel_date_pro; ?></td>
                                                        <td align="center"><?php echo !empty($row["agent"]) ? get_value("agent", "id", "company", $row["agent"], $mysqli_p) : "-"; ?></td>
                                                        <td align="center"><?php echo !empty($row["b_rename"]) ? $row["b_rename"] : "-"; ?></td>
                                                        <td align="center"><?php echo number_format($row["inv_total"], 2); ?></td>
                                                        <td align="center"><?php echo number_format($total_pay, 2); ?></td>
                                                        <td align="center"><?php echo number_format($total_balance, 2); ?></td>
                                                        <td align="center"><?php echo $row["b_vn"]." (งวดที่ ".$row['inv_p'].")"; ?>
                                                            <input type="hidden" name="period[]" id="period<?php echo $row["id"]; ?>" value="<?php echo $row['inv_p']; ?>">
                                                        </td>
                                                        <!-- <td align="center"><a href="./?mode=invoice/download_invoice&name=<?php echo $row['inv_full']; ?>" target="_blank"><i class="fas fa-download"></i></a></td> -->
                                                        <td align="center"><a href="../assets/invoice_pdf/<?php echo $row['inv_full']; ?>.pdf" target="_blank"><i class="fas fa-download"></i></a></td>
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