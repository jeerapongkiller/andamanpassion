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
                                <li class="breadcrumb-item active">สร้างใบเสร็จ</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                        </div>
                    </div>
                </div>

                <script>
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

                    function checkDatetype(){
                        var search_date_type = document.getElementById('search_date_type').value;
                        var div_bi_date_from = document.getElementById("div_bi_date_from");
                        var div_bi_date_to = document.getElementById("div_bi_date_to");
                        var div_due_date_from = document.getElementById("div_due_date_from");
                        var div_due_date_to = document.getElementById("div_due_date_to");

                        if (search_date_type == 1) {
                            div_bi_date_from.style.display = "";
                            div_bi_date_to.style.display = "";
                            div_due_date_from.style.display = "none";
                            div_due_date_to.style.display = "none";
                        } else if (search_date_type == 2) {
                            div_bi_date_from.style.display = "none";
                            div_bi_date_to.style.display = "none";
                            div_due_date_from.style.display = "";
                            div_due_date_to.style.display = "";
                        } else if (search_date_type == 3) {
                            div_bi_date_from.style.display = "";
                            div_bi_date_to.style.display = "";
                            div_due_date_from.style.display = "";
                            div_due_date_to.style.display = "";
                        }
                        return true;
                    }

                    function edit_bill(bill, prodtype) {
                        // Search Data
                        var search_bill_no = document.getElementById('search_bill_no').value;
                        var search_invoice_no = document.getElementById('search_invoice_no').value;
                        var search_bi_date_from = document.getElementById('search_bi_date_from').value;
                        var search_bi_date_to = document.getElementById('search_bi_date_to').value;
                        var search_due_date_from = document.getElementById('search_due_date_from').value;
                        var search_due_date_to = document.getElementById('search_due_date_to').value;
                        var search_customer_type = document.getElementById('search_customer_type').value;
                        var search_name_val = document.getElementById('search_name_val').value;
                        var search_agent_val = document.getElementById('search_agent_val').value;

                        jQuery.ajax({
                            url: "../inc/ajax/bill/" + prodtype + ".php",
                            data: {
                                bill: bill,
                                search_bill_no: search_bill_no,
                                search_invoice_no: search_invoice_no,
                                search_bi_date_from: search_bi_date_from,
                                search_bi_date_to: search_bi_date_to,
                                search_due_date_from: search_due_date_from,
                                search_due_date_to: search_due_date_to,
                                search_customer_type: search_customer_type,
                                search_name_val: search_name_val,
                                search_agent_val: search_agent_val
                            },
                            type: "POST",
                            success: function(response) {
                                if (response) {
                                    swal.fire({
                                        title: 'สร้างใบวางบิล ',
                                        width: 1000,
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

                    function Checkfrom_bill() {
                        var check_return = '';
                        var bo_bi_name = document.getElementById('bo_bi_name').value;
                        var check_bi_name = document.getElementById('check_bi_name');
                        var bo_bi_address = document.getElementById('bo_bi_address').value;
                        var check_bi_address = document.getElementById('check_bi_address');

                        document.getElementById('check_bi_date').className = 'has-success';
                        document.getElementById('check_due_date').className = 'has-success';
                        document.getElementById('check_bi_condition').className = 'has-success';
                        document.getElementById('check_bi_con_detail').className = 'has-success';

                        if (bo_bi_name == '') {
                            check_bi_name.className = 'has-danger';
                            check_return = 'false';
                        } else {
                            check_bi_name.className = 'has-success';
                        }
                        if (bo_bi_address == '') {
                            check_bi_address.className = 'has-danger';
                            check_return = 'false';
                        } else {
                            check_bi_address.className = 'has-success';
                        }

                        if (check_return == 'false') {
                            return false;
                        } else {
                            return true;
                        }
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
                    $search_bill_no = !empty($_POST["search_bill_no"]) ? $_POST["search_bill_no"] : '';
                    $search_invoice_no = !empty($_POST["search_invoice_no"]) ? $_POST["search_invoice_no"] : '';
                    $search_customer_type = !empty($_POST["search_customer_type"]) ? $_POST["search_customer_type"] : '';
                    $search_date_type = !empty($_POST["search_date_type"]) ? $_POST["search_date_type"] : '1';
                    $search_name_val = !empty($_POST["search_name_val"]) ? $_POST["search_name_val"] : '';
                    $search_agent_val = !empty($_POST["search_agent_val"]) ? $_POST["search_agent_val"] : '';
                    $search_bi_date_from_val = !empty($_POST["search_bi_date_from"]) ? $_POST["search_bi_date_from"] : ''; // $today;
                    $search_bi_date_to_val = !empty($_POST["search_bi_date_to"]) ? $_POST["search_bi_date_to"] : ''; //$today;
                    $search_due_date_from_val = !empty($_POST["search_due_date_from"]) ? $_POST["search_due_date_from"] : ''; // $today;
                    $search_due_date_to_val = !empty($_POST["search_due_date_to"]) ? $_POST["search_due_date_to"] : ''; //$today;
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=<?php echo $_GET["mode"]; ?>" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_bill_no">เลขใบวางบิล</label>
                                            <input type="text" class="form-control" id="search_bill_no" name="search_bill_no" placeholder="" value="<?php echo $search_bill_no; ?>">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_invoice_no">เลขใบแจ้งหนี้</label>
                                            <input type="text" class="form-control" id="search_invoice_no" name="search_invoice_no" placeholder="" value="<?php echo $search_invoice_no; ?>">
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
                                        <div class="col-md-2 mb-3">
                                            <label for="search_date_type">ประเภทวันที่</label>
                                            <select class="custom-select" id="search_date_type" name="search_date_type" onchange="checkDatetype(this)">
                                                <option value="1" <?php echo $search_date_type == 1 ? "selected" : ""; ?>>วันที่เอกสารใบวางบิล</option>
                                                <option value="2" <?php echo $search_date_type == 2 ? "selected" : ""; ?>>วันที่ครบกำหนด</option>
                                                <option value="3" <?php echo $search_date_type == 3 ? "selected" : ""; ?>>ทั้งหมด</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3" id="div_bi_date_from">
                                            <label for="search_bi_date_from">วันที่เอกสารใบวางบิล (จาก)</label>
                                            <input type="text" class="form-control" id="search_bi_date_from" name="search_bi_date_from" placeholder="" value="<?php echo $search_bi_date_from_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3" id="div_bi_date_to">
                                            <label for="search_bi_date_to">วันที่เอกสารใบวางบิล (ถึง)</label>
                                            <input type="text" class="form-control" id="search_bi_date_to" name="search_bi_date_to" placeholder="" value="<?php echo $search_bi_date_to_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3" id="div_due_date_from">
                                            <label for="search_due_date_from">วันที่ครบกำหนด (จาก)</label>
                                            <input type="text" class="form-control" id="search_due_date_from" name="search_due_date_from" placeholder="" value="<?php echo $search_due_date_from_val; ?>" readonly>
                                        </div>
                                        <div class="col-md-2 mb-3" id="div_due_date_to">
                                            <label for="search_due_date_to">วันที่ครบกำหนด (ถึง)</label>
                                            <input type="text" class="form-control" id="search_due_date_to" name="search_due_date_to" placeholder="" value="<?php echo $search_due_date_to_val; ?>" readonly>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=bill/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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
                                </h4>

                                <div class="table-responsive m-t-20">
                                    <table id="bill-table" class="table display table-bordered table-striped no-wrap" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>เลขใบวางบิล</th>
                                                <th>BI Date</th>
                                                <th>DUE Date</th>
                                                <th>ชื่อ-สกุล</th>
                                                <th>เอเย่นต์</th>
                                                <th>แก้ใข</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $check_id = "";
                                            $params = array();
                                            $query = "SELECT bill.*, bill_invoice.invoice as bi_inv, bill_invoice.booking as bi_b, bill_invoice.booking_products as bi_bp,
                                                        bill_invoice.pay_bill as bi_pb, invoice.inv_full as inv_full
                                                    FROM bill
                                                    LEFT JOIN bill_invoice
                                                        ON bill.id = bill_invoice.bill
                                                    LEFT JOIN invoice
                                                        ON bill_invoice.invoice = invoice.id
                                                    WHERE bill.id > '0' 
                                            ";
                                            if (!empty($search_bill_no)) {
                                                # search invoice no
                                                $query .= " AND bill.bi_full = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_bill_no);
                                            }
                                            if (!empty($search_invoice_no)) {
                                                # search invoice no
                                                $query .= " AND invoice.inv_full = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_invoice_no);
                                            }
                                            if (!empty($search_customer_type)) {
                                                # search invoice no
                                                $query .= $search_customer_type == 1 ? " AND bill.agent = ?" : "AND bill.agent > ?";
                                                $bind_types .= "s";
                                                array_push($params, 0);
                                            }else{
                                                # search invoice no
                                                $query .= " AND bill.agent = ?";
                                                $bind_types .= "s";
                                                array_push($params, 0);
                                            }
                                            if (!empty($search_name_val) && $search_customer_type == 1) {
                                                # search name customer val
                                                $query .= " AND bill.bi_name = ?";
                                                $bind_types .= "s";
                                                array_push($params, $search_name_val);
                                            }
                                            if (!empty($search_agent_val) && $search_customer_type == 2) {
                                                # search agent val
                                                $query .= " AND bill.agent = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_agent_val);
                                            }
                                            if($search_date_type == '1' || $search_date_type == '3'){
                                                if (!empty($search_bi_date_from_val) && !empty($search_bi_date_to_val)) {
                                                    # search travel date , check in , check out
                                                    $query .= " AND bill.bi_date BETWEEN ? AND ?";
                                                    $bind_types .= "ss";
                                                    array_push($params, $search_bi_date_from_val, $search_bi_date_to_val);
                                                }
                                            }
                                            if($search_date_type == '2' || $search_date_type == '3'){
                                                if (!empty($search_due_date_from_val) && !empty($search_due_date_to_val)) {
                                                    # search travel date , check in , check out
                                                    $query .= " AND bill.due_date BETWEEN ? AND ?";
                                                    $bind_types .= "ss";
                                                    array_push($params, $search_due_date_from_val, $search_due_date_to_val);
                                                }
                                            }
                                            $query .= " ORDER BY bill.due_date ASC";

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
                                                if ($check_id != $row["id"]) {
                                                    $next_due_date = date('Y-m-d',strtotime($today . "+2 days"));
                                                    $danger_due_date = "";
                                                    if($row["due_date"] < $next_due_date){ $danger_due_date = "font-weight: bold; color: #FFA500;"; }
                                                    if($row["due_date"] <= $today){ $danger_due_date = "font-weight: bold; color: #FF0000;"; }
                                            ?>
                                                    <tr>
                                                        <td align="center"><span style="<?php echo $danger_due_date; ?>" ><?php echo $row["bi_full"]; ?></span></td>
                                                        <td align="center"><span style="<?php echo $danger_due_date; ?>" ><?php echo date("Y-m-d", strtotime($row["bi_date"])) . "<br />(" . date("d F Y", strtotime($row["bi_date"])) . ")"; ?></span></td>
                                                        <td align="center"><span style="<?php echo $danger_due_date; ?>" ><?php echo date("Y-m-d", strtotime($row["due_date"])) . "<br />(" . date("d F Y", strtotime($row["due_date"])) . ")"; ?></span></td>
                                                        <td align="center"><span style="<?php echo $danger_due_date; ?>" ><?php echo empty($row["agent"]) ? $row["bi_name"] : '-'; ?></span></td>
                                                        <td align="center"><span style="<?php echo $danger_due_date; ?>" ><?php echo !empty($row["agent"]) ? $row["bi_name"] : '-'; ?></span></td>
                                                        <td align="center"><a href="#edit" title="edit" onclick="edit_bill('<?php echo $row['id']; ?>', 'edit_bill');"><i class="fas fa-edit"></i></a></td>
                                                        <td align="center"><a href="../assets/invoice_bill/<?php echo $row['bi_full']; ?>.pdf" target="_blank"><i class="fas fa-download"></i></a></td>
                                                    </tr>
                                            <?php
                                                    $check_id = $row["id"];
                                                }
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