<?php
    $ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
    $supplier = !empty($_GET["supplier"]) ? $_GET["supplier"] : '';
    $agent = !empty($_GET["agent"]) ? $_GET["agent"] : '';
    $combine = !empty($_GET["combine"]) ? $_GET["combine"] : '';

    if(!empty($supplier)){
        $query = "SELECT * FROM supplier WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'i', $supplier);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $supplier_name = stripslashes($row["company"]);

            if(!empty($ptype)){
                $query_ptype = "SELECT * FROM products_type WHERE id > '0'";
                $query_ptype .= " AND id = ?";
                $query_ptype .= " LIMIT 1";
                $procedural_statement = mysqli_prepare($mysqli_p, $query_ptype);
                mysqli_stmt_bind_param($procedural_statement, 'i', $ptype);
                mysqli_stmt_execute($procedural_statement);
                $result = mysqli_stmt_get_result($procedural_statement);
                $numrow = mysqli_num_rows($result);
                if($numrow > 0){
                    $row_ptype = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $ptype_name = stripslashes($row_ptype["name_text_thai"]);
                }else{
                    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
                }
            }else{
                echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
            }

            if(!empty($agent)){
                $query_agent = "SELECT * FROM agent WHERE id > '0'";
                $query_agent .= " AND id = ?";
                $query_agent .= " LIMIT 1";
                $procedural_statement = mysqli_prepare($mysqli_p, $query_agent);
                mysqli_stmt_bind_param($procedural_statement, 'i', $agent);
                mysqli_stmt_execute($procedural_statement);
                $result = mysqli_stmt_get_result($procedural_statement);
                $numrow = mysqli_num_rows($result);
                if($numrow > 0){
                    $row_agent = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $agent_name = stripslashes($row_agent["company"]);
                }else{
                    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
                }
            }else{
                echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
            }
        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
        }
    }else{
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
    }
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
                        <!-- <h4 class="text-themecolor">Agent</h4> -->
                        <div class="d-flex align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=agent/list">เอเย่นต์</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=agent/detail&id=<?php echo $agent; ?>"><?php echo $agent_name; ?></a></li>
                                <li class="breadcrumb-item"><a href="./?mode=agent/combine-agentxsupplier&ptype=<?php echo $ptype; ?>&agent=<?php echo $agent; ?>">เอเย่นต์ x ซัพพลายเออร์ (<?php echo $ptype_name; ?>)</a></li>
                                <li class="breadcrumb-item"><?php echo $supplier_name; ?></li>
                                <li class="breadcrumb-item active">รายการสินค้า (<?php echo $ptype_name; ?>)</li>
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
                        document.onkeydown=function(evt){
                            var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
                            if(keyCode == 13){ document.frmsearch.submit(); }
                        }
                    </script>

                    <?php
                        // Procedural mysqli
                        $bind_types = "";
                        $params = array();

                        $query = "SELECT * FROM products_category_first WHERE id > '0'";
                        if($_SESSION["admin"]["permission"] != 1){ $query .= " AND trash_deleted != '1'"; }
                        if(!empty($ptype))
                        { 
                            # products_type
                            $query .= " AND products_type = ?"; 
                            $bind_types .= "i";
                            array_push($params, $ptype);
                        }
                        if(!empty($supplier))
                        { 
                            # supplier
                            $query .= " AND supplier = ?";
                            $bind_types .= "i";
                            array_push($params, $supplier);
                        }
                        $query .= " ORDER BY status ASC, name ASC";
                        $procedural_statement = mysqli_prepare($mysqli_p, $query);

                        // Check error query
                        if($procedural_statement == false) {
                            die("<pre>".mysqli_error($mysqli_p).PHP_EOL.$query."</pre>");
                        }

                        if($bind_types != "")
                        { 
                            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
                        }

                        mysqli_stmt_execute($procedural_statement);
                        $result = mysqli_stmt_get_result($procedural_statement);
                        $numrow = mysqli_num_rows($result);
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                        {
                            $status_class = $row["status"] == 1 ? 'success' : 'danger';
                            $status_txt = $row["status"] == 1 ? 'ออนไลน์' : 'ออฟไลน์';
                            $procode = !empty($row["procode"]) ? $row["procode"] : '-';
                    ?>

                    <!-- Table Responsive -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title"><?php echo $supplier_name; ?> &nbsp; - &nbsp; Product List (<?php echo $ptype_name; ?>) </h4> -->
                                <!-- <h6 class="card-subtitle">Description</h6> -->

                                <div class="table-responsive m-b-20">
                                    <table class="table color-bordered-table info-bordered-table">
                                        <thead>
                                            <tr align="center" style="color:#FFFFFF">
                                                <th>สถานะ</th>
                                                <th><?php echo $ptype_name; ?></th>
                                                <th>วันที่เริ่ม</th>
                                                <th>วันที่สิ้นสุด</th>
                                                <th>รหัสโปรโมชั่น</th>
                                                <?php if($row["photo1"] != ""){ ?><th>ไฟล์ที่ # <span style="color:#FFFF20; font-weight:bold">1</span></th><?php } ?>
                                                <?php if($row["photo2"] != ""){ ?><th>ไฟล์ที่ # <span style="color:#FFFF20; font-weight:bold">2</span></th><?php } ?>
                                                <?php if($row["photo3"] != ""){ ?><th>ไฟล์ที่ # <span style="color:#FFFF20; font-weight:bold">3</span></th><?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody style="font-weight:500">
                                            <tr>
                                                <td align="center"><span class="label label-<?php echo $status_class; ?>"><?php echo $status_txt; ?></span></td>
                                                <td align="center"><b><?php echo $row["name"]; ?></b></td>
                                                <td align="center"><?php echo date("d F Y", strtotime($row["validity_from"])); ?></td>
                                                <td align="center"><?php echo date("d F Y", strtotime($row["validity_to"])); ?></td>
                                                <td align="center"><?php echo $procode; ?></td>

                                                <?php if($row["photo1"] != ""){ ?>
                                                <td align="center">
                                                    <a href="../inc/photo/supplier/<?php echo $row["photo1"]; ?>" target="_blank" style="font-size:24px; color:#CB453B">
                                                        <i class="mdi mdi-file-pdf"></i></a></td>
                                                <?php } ?>

                                                <?php if($row["photo2"] != ""){ ?>
                                                <td align="center">
                                                    <a href="../inc/photo/supplier/<?php echo $row["photo2"]; ?>" target="_blank" style="font-size:24px; color:#CB453B">
                                                        <i class="mdi mdi-file-pdf"></i></a></td>
                                                <?php } ?>

                                                <?php if($row["photo3"] != ""){ ?>
                                                <td align="center">
                                                    <a href="../inc/photo/supplier/<?php echo $row["photo3"]; ?>" target="_blank" style="font-size:24px; color:#CB453B">
                                                        <i class="mdi mdi-file-pdf"></i></a></td>
                                                <?php } ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                    // Procedural mysqli
                                    $bind_types = "";
                                    $params = array();

                                    $query_second = "SELECT * FROM products_category_second WHERE id > '0'";
                                    if($_SESSION["admin"]["permission"] != 1){ $query_second .= " AND trash_deleted != '1'"; }
                                    if(!empty($row["id"]))
                                    { 
                                        # products_category_first
                                        $query_second .= " AND products_category_first = ?"; 
                                        $bind_types .= "i";
                                        array_push($params, $row["id"]);
                                    }
                                    $query_second .= " ORDER BY name ASC";
                                    $procedural_statement = mysqli_prepare($mysqli_p, $query_second);

                                    // Check error query
                                    if($procedural_statement == false) {
                                        die("<pre>".mysqli_error($mysqli_p).PHP_EOL.$query_second."</pre>");
                                    }

                                    if($bind_types != "")
                                    { 
                                        mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
                                    }

                                    mysqli_stmt_execute($procedural_statement);
                                    $result_second = mysqli_stmt_get_result($procedural_statement);
                                    $numrow_second = mysqli_num_rows($result_second);
                                    while($row_second = mysqli_fetch_array($result_second, MYSQLI_ASSOC))
                                    {
                                        $status_class = $row_second["status"] == 1 ? 'success' : 'danger';
                                        $status_txt = $row_second["status"] == 1 ? 'ออนไลน์' : 'ออฟไลน์';
                                ?>

                                <p><span class="card-title" style="font-size: 1.125rem"><?php echo $row_second["name"]; ?> &nbsp;&nbsp; 
                                    <span class="label label-<?php echo $status_class; ?>" style="font-size: 11px"><?php echo $status_txt; ?></span> &nbsp;&nbsp; 
                                    <?php if($row_second["withholding_tax"] == 1){ echo "( 1% Withholding Tax )"; } ?></span>
                                </p>

                                <div class="table-responsive">
                                    <table class="table color-bordered-table muted-bordered-table">
                                        <thead>
                                            <tr align="center">
                                                <th style="text-align:left">ระยะเวลา</th>
                                                <?php if($ptype == 1 || $ptype == 2){ ?><th colspan="2">ราคาผู้ใหญ่</th><?php } ?>
                                                <?php if($ptype == 3){ ?><th colspan="2">ราคาต่อคัน</th><?php } ?>
                                                <?php if($ptype == 4){ ?><th colspan="2">ราคาต่อห้อง/คืน</th><?php } ?>
                                                <?php if($ptype == 1 || $ptype == 2){ ?><th colspan="2">ราคาเด็ก</th><?php } ?>
                                                <?php if($ptype == 1){ ?><th colspan="2">เช่าเหมาลำ</th><?php } ?>
                                                <?php if($ptype == 2){ ?><th colspan="2">ราคากลุ่ม</th><?php } ?>
                                                <?php if($ptype == 1 || $ptype == 2){ ?><th>จำนวนคน</th><?php } ?>
                                                <?php if($ptype == 1 || $ptype == 2){ ?><th colspan="2">รถรับส่ง</th><?php } ?>
                                                <?php if($ptype == 3){ ?><th colspan="2">ราคาต่อชั่วโมง</th><?php } ?>
                                                <?php if($ptype == 3){ ?><th>จำนวนชั่วโมง</th><?php } ?>
                                                <?php if($ptype == 4){ ?><th colspan="2">ราคาเตียงเสริม</th><?php } ?>
                                                <?php if($ptype == 4){ ?><th colspan="2">ราคาแชร์เตียง</th><?php } ?>
                                                <th>แก้ไข</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-weight:500">

                                        <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();

                                            $query_third = "SELECT products_category_third.*, 
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

                                            // $query_third = "SELECT * FROM products_category_third WHERE id > '0'";
                                            if($_SESSION["admin"]["permission"] != 1){ $query_third .= " AND products_category_third.trash_deleted != '1'"; }
                                            if(!empty($row_second["id"]))
                                            { 
                                                # combine_agentxsupplier
                                                $bind_types .= "i";
                                                array_push($params, $combine);
                                            }
                                            if(!empty($row_second["id"]))
                                            { 
                                                # products_category_second
                                                $query_third .= " AND products_category_third.products_category_second = ?"; 
                                                $bind_types .= "i";
                                                array_push($params, $row_second["id"]);
                                            }
                                            $query_third .= " ORDER BY products_category_third.periods_from ASC";
                                            $procedural_statement = mysqli_prepare($mysqli_p, $query_third);

                                            // Check error query
                                            if($procedural_statement == false) {
                                                die("<pre>".mysqli_error($mysqli_p).PHP_EOL.$query_third."</pre>");
                                            }

                                            if($bind_types != "")
                                            { 
                                                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
                                            }

                                            mysqli_stmt_execute($procedural_statement);
                                            $result_third = mysqli_stmt_get_result($procedural_statement);
                                            $numrow_third = mysqli_num_rows($result_third);
                                            while($row_third = mysqli_fetch_array($result_third, MYSQLI_ASSOC))
                                            {
                                                $status_class = $row_third["status"] == 1 ? 'success' : 'danger';
                                                $status_txt = $row_third["status"] == 1 ? 'ออนไลน์' : 'ออฟไลน์';
                                                // $status_style = $row_third["status"] == 1 ? 'color:#2E8E9C' : 'color:#D26560';
                                                $rate_1 = $row_third["rate_1"] != '0.00' ? number_format($row_third["rate_1"]) : '-';
                                                $rate_2 = ($row_third["rate2Combine"] != '0.00' && !empty($row_third["rate2Combine"])) ? number_format($row_third["rate2Combine"]) : '-';
                                                $rate_3 = $row_third["rate_3"] != '0.00' ? number_format($row_third["rate_3"]) : '-';
                                                $rate_4 = ($row_third["rate4Combine"] != '0.00' && !empty($row_third["rate4Combine"])) ? number_format($row_third["rate4Combine"]) : '-';
                                                $charter_1 = $row_third["charter_1"] != '0.00' ? number_format($row_third["charter_1"]) : '-';
                                                $charter_2 = ($row_third["charter2Combine"] != '0.00' && !empty($row_third["charter2Combine"])) ? number_format($row_third["charter2Combine"]) : '-';
                                                $group_1 = $row_third["group_1"] != '0.00' ? number_format($row_third["group_1"]) : '-';
                                                $group_2 = ($row_third["group2Combine"] != '0.00' && !empty($row_third["group2Combine"])) ? number_format($row_third["group2Combine"]) : '-';
                                                $transfer_1 = $row_third["transfer_1"] != '0.00' ? number_format($row_third["transfer_1"]) : '-';
                                                $transfer_2 = ($row_third["transfer2Combine"] != '0.00' && !empty($row_third["transfer2Combine"])) ? number_format($row_third["transfer2Combine"]) : '-';
                                                $pax = $row_third["pax"] != '0' ? number_format($row_third["pax"]) : '-';
                                                $extra_hour_1 = $row_third["extra_hour_1"] != '0.00' ? number_format($row_third["extra_hour_1"]) : '-';
                                                $extra_hour_2 = ($row_third["extrahour2Combine"] != '0.00' && !empty($row_third["extrahour2Combine"])) ? number_format($row_third["extrahour2Combine"]) : '-';
                                                $hours_no = $row_third["hours_no"] != '0' ? number_format($row_third["hours_no"]) : '-';
                                                $extrabeds_1 = $row_third["extrabeds_1"] != '0.00' ? number_format($row_third["extrabeds_1"]) : '-';
                                                $extrabeds_2 = ($row_third["extrabeds2Combine"] != '0.00' && !empty($row_third["extrabeds2Combine"])) ? number_format($row_third["extrabeds2Combine"]) : '-';
                                                $sharingbed_1 = $row_third["sharingbed_1"] != '0.00' ? number_format($row_third["sharingbed_1"]) : '-';
                                                $sharingbed_2 = ($row_third["sharingbed2Combine"] != '0.00' && !empty($row_third["sharingbed2Combine"])) ? number_format($row_third["sharingbed2Combine"]) : '-';
                                        ?>

                                            <tr>
                                                <td width="25%">
                                                    <span class="label label-<?php echo $status_class; ?>" style="font-size: 11px"><?php echo $status_txt; ?></span> &nbsp;&nbsp; 
                                                    <span style="color:#2E8E9C"><?php echo date("d F Y", strtotime($row_third["periods_from"])); ?> - <?php echo date("d F Y", strtotime($row_third["periods_to"])); ?></span></td>
                                                <td align="center" width="7%"><?php echo $rate_1; ?></td>
                                                <td align="center" width="7%" style="background-color:#FAB867"><?php echo $rate_2; ?></td>

                                                <?php if($ptype == 1 || $ptype == 2){ ?>
                                                <td align="center" width="7%"><?php echo $rate_3; ?></td>
                                                <td align="center" width="7%" style="background-color:#FAB867"><?php echo $rate_4; ?></td><?php } ?>

                                                <?php if($ptype == 1){ ?>
                                                    <td align="center" width="7%"><?php echo $charter_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#FAB867"><?php echo $charter_2; ?></td><?php } ?>

                                                <?php if($ptype == 2){ ?>
                                                    <td align="center" width="7%"><?php echo $group_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#FAB867"><?php echo $group_2; ?></td><?php } ?>
                                                
                                                <?php if($ptype == 1 || $ptype == 2){ ?>
                                                    <td align="center" width="7%"><?php echo $pax; ?></td><?php } ?>

                                                <?php if($ptype == 1 || $ptype == 2){ ?>
                                                    <td align="center" width="7%"><?php echo $transfer_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#9AD7F1"><?php echo $transfer_2; ?></td><?php } ?>
                                                
                                                <?php if($ptype == 3){ ?>
                                                    <td align="center" width="7%"><?php echo $extra_hour_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#FAB867"><?php echo $extra_hour_2; ?></td><?php } ?>

                                                <?php if($ptype == 3){ ?>
                                                    <td align="center" width="7%"><?php echo $hours_no; ?></td><?php } ?>

                                                <?php if($ptype == 4){ ?>
                                                    <td align="center" width="7%"><?php echo $extrabeds_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#FAB867"><?php echo $extrabeds_2; ?></td><?php } ?>

                                                <?php if($ptype == 4){ ?>
                                                    <td align="center" width="7%"><?php echo $sharingbed_1; ?></td>
                                                    <td align="center" width="7%" style="background-color:#9AD7F1"><?php echo $sharingbed_2; ?></td><?php } ?>

                                                <td align="center" width="7%">
                                                    <a href="./?mode=agent/combine-product-third-detail&id=<?php echo $row_third["id"]; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $row["id"]; ?>&catesecond=<?php echo $row_second["id"]; ?>&agent=<?php echo $agent; ?>&combine=<?php echo $combine; ?>" title="Edit"><i class="fas fa-edit" style="color:#0D84DE"></i></a></td>
                                            </tr>

                                        <?php
                                            } /* while($row_third = mysqli_fetch_array($result_third, MYSQLI_ASSOC)) */
                                        ?>

                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                    } /* while($row_second = mysqli_fetch_array($result_second, MYSQLI_ASSOC)) */
                                ?>

                            </div>
                        </div>
                    </div>

                    <?php
                        } /* while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) */
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