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
                                <li class="breadcrumb-item active">ใบงานรถ</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
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

                    <?php
                    # check value from search
                    $search_travel_date_car_val = !empty($_POST["search_travel_date_car"]) ? $_POST["search_travel_date_car"] : $today; // $today;
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_travel_date_car">วันที่เที่ยว / วันที่เช็คอิน</label>
                                            <input type="text" class="form-control" id="search_travel_date_car" name="search_travel_date_car" placeholder="" value="<?php echo $search_travel_date_car_val; ?>" readonly>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" onClick="this.form.target=''; this.form.action='./?mode=<?php echo $_GET["mode"]; ?>'; submit()"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=workshop/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
                                    <input type="hidden" id="travel_date" name="travel_date" value="<?php echo $search_travel_date_car_val; ?>">
                                    <button class="btn btn-primary" onClick="this.form.target='_blank'; this.form.action='sections/workshop/printcars.php'; submit();"><i class="ti-printer"></i>&nbsp;&nbsp;พิมพ์</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- target="_blank" -->

                    <?php
                    $bind_types = "";
                    $params = array();
                    $first = 0;
                    $second = 0;
                    $numrow_realtime = 1;

                    $query = "SELECT BP.*,
                                    BO.id as boID, BO.voucher_no as boVoucher, BO.agent as boAgent, BO.customer_firstname as boFirstname, BO.customer_lastname as boLastname,
                                    PCF.id as pcfID, PCF.supplier as pcfSupplier, PCF.products_type as pcfPro_type,
                                    PCS.id as pcsID, PCS.name as pcsName,
                                    SP.id as spID, SP.company as spCompany,
                                    PL.id as plID, PL.name as plName
                                    FROM booking_products BP
                                    LEFT JOIN booking BO
                                        ON BP.booking = BO.id
                                    LEFT JOIN products_category_first PCF
                                        ON BP.products_category_first = PCF.id
                                    LEFT JOIN products_category_second PCS
                                        ON BP.products_category_second = PCS.id
                                    LEFT JOIN supplier SP
                                        ON PCF.supplier = SP.id
                                    LEFT JOIN place PL
                                        ON BP.dropoff = PL.id
                                    WHERE BP.id > '0' AND BP.status_confirm = '1' 
                        ";
                    // $query .= " AND BP.status_confirm = '1' AND BP.status_confirm_op = '1' ";
                    if (!empty($search_travel_date_car_val)) {
                        # search status
                        $query .= " AND BP.travel_date = ? OR BP.checkin_date = ? ";
                        $bind_types .= "ss";
                        array_push($params, $search_travel_date_car_val, $search_travel_date_car_val);
                    }
                    $query .= " ORDER BY PCS.id, BP.dropoff ASC";
                    // echo $query;
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
                    ?>

                    <?php
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        #---- Head ----#
                        if ($first != $row["pcsID"] || $second != $row["dropoff"]) {
                            #---- Foot ----#
                            echo $numrow_realtime != 1 ? '</tbody></table></div></div></div></div>' : '';
                            $first = $row["pcsID"];
                            $second = $row["dropoff"];
                    ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-center"> <?php echo $row["spCompany"] . ' - Andaman Passion Transfer List'; ?> </h4>
                                        <h6 class="card-subtitle text-center"> <?php echo $row["pcsName"] . ' ' . date("d F Y", strtotime($search_travel_date_car_val)) . ' (รถรับ - ส่ง' . $row["plName"] . ')'; ?> </h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width:100%">
                                                <thead>
                                                    <tr align="center">
                                                        <th>Voucher</th>
                                                        <th>Agency</th>
                                                        <th>Customer's Name</th>
                                                        <th>AD</th>
                                                        <th>CHD</th>
                                                        <th>INF</th>
                                                        <th>FOC</th>
                                                        <th>Car</th>
                                                        <th>Time</th>
                                                        <th>Pickup</th>
                                                        <th>Room</th>
                                                        <th>Zone</th>
                                                        <th>Send back</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php } ?>
                                                <tr align="center">
                                                    <td> <?php echo $row["boVoucher"]; ?> </td>
                                                    <td> <?php echo get_value('agent', 'id', 'company', $row["boAgent"], $mysqli_p); ?> </td>
                                                    <td> <?php echo $row["boFirstname"] . ' ' . $row["boLastname"]; ?> </td>
                                                    <td> <?php echo $row["adults"]; ?> </td>
                                                    <td> <?php echo $row["children"]; ?> </td>
                                                    <td> <?php echo $row["infant"]; ?> </td>
                                                    <td> <?php echo $row["foc"]; ?> </td>
                                                    <td> <?php echo $row["vans"]; ?> </td>
                                                    <td> <?php echo $row["dropoff_time"]; ?> </td>
                                                    <td> <?php echo get_value('place', 'id', 'name', $row["pickup"], $mysqli_p); ?> </td>
                                                    <td> <?php echo $row["roomno"]; ?> </td>
                                                    <td> <?php echo get_value('zones', 'id', 'name', $row["zones"], $mysqli_p); ?> </td>
                                                    <td> <?php echo ''; ?> </td>
                                                </tr>
                                            <?php $numrow_realtime++;
                                        }
                                        #---- Foot ----#
                                        echo $numrow_realtime != 1 ? '</tbody></table></div></div></div></div>' : '';
                                            ?>

                                            <div id="div-cars"></div>

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