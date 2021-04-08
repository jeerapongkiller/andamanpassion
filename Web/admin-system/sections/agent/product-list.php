<?php
    $ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
    $supplier = !empty($_GET["supplier"]) ? $_GET["supplier"] : '';

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
                    echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
                }
            }else{
                echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
            }
        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
        }
    }else{
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
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
                        <!-- <h4 class="text-themecolor">Supplier</h4> -->
                        <div class="d-flex align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=supplier/list">ซัพพลายเออร์</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=supplier/detail&id=<?php echo $supplier; ?>"><?php echo $supplier_name; ?></a></li>
                                <li class="breadcrumb-item active">รายการสินค้า (<?php echo $ptype_name; ?>)</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-info m-l-15" 
                                onclick="window.location.href='./?mode=supplier/product-first-detail&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>'">
                            <i class="fa fa-plus-circle"></i> เพิ่มข้อมูล<?php echo $ptype_name; ?></button>
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

                        function deleteCate(cateval, catetype){
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
                                        url: "../inc/ajax/supplier/" + catetype + ".php",
                                        data: {cateval: cateval},
                                        type: "POST",
                                        success:function(response){
                                            Swal.fire({
                                                title: "ลบข้อมูลเสร็จสิ้น!",
                                                text: "ข้อมูลที่คุณเลือกถูกลบออกจากระบบแล้ว",
                                                type: "success"
                                            }).then(function() {
                                                location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                            });
                                        },
                                        error:function(){
                                            Swal.fire('ลบข้อมูลไม่สำเร็จ!','กรุณาลองใหม่อีกครั้ง','error')
                                        }
                                    });
                                }
                            })
                            return true;
                        }

                        function restoreCate(cateval, catetype){
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
                                        url: "../inc/ajax/supplier/" + catetype + ".php",
                                        data: {cateval: cateval},
                                        type: "POST",
                                        success:function(response){
                                            Swal.fire({
                                                title: "คืนข้อมูลเสร็จสิ้น!",
                                                text: "ข้อมูลที่คุณเลือกคืนเข้าระบบแล้ว",
                                                type: "success"
                                            }).then(function() {
                                                location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                            });
                                        },
                                        error:function(){
                                            Swal.fire('คืนข้อมูลไม่สำเร็จ!','กรุณาลองใหม่อีกครั้ง','error')
                                        }
                                    });
                                }
                            })
                            return true;
                        }

                        function copySecond(cateval){
                            Swal.fire({
                                type: 'warning',
                                title: 'คุณแน่ใจไหม?',
                                text: "คุณต้องการคัดลอกข้อมูลนี้ใช่หรือไม่?",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่ คัดลอกข้อมูล!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.value) {
                                    jQuery.ajax({
                                        url: "../inc/ajax/supplier/copysecond.php",
                                        data: {cateval: cateval},
                                        type: "POST",
                                        success:function(response){
                                            if(response == "success"){
                                                Swal.fire({
                                                    title: "คัดลอกข้อมูลเสร็จสิ้น!",
                                                    text: "ข้อมูลที่คุณเลือกคัดลอกเข้าระบบแล้ว",
                                                    type: "success"
                                                }).then(function() {
                                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                                });
                                            }else{
                                                Swal.fire({
                                                    title: "คัดลอกข้อมูลไม่สำเร็จ!",
                                                    text: "กรุณาลองใหม่อีกครั้ง",
                                                    type: "error"
                                                }).then(function() {
                                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
                                                });
                                            }
                                        },
                                        error:function(){
                                            Swal.fire('คัดลอกข้อมูลไม่สำเร็จ!','กรุณาลองใหม่อีกครั้ง','error')
                                        }
                                    });
                                }
                            })
                            return true;
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
                                                <th>แก้ไข</th>
                                                <th>ลบ</th>
                                                <?php if($_SESSION["admin"]["permission"] == 1){ echo "<th>คืน</th>"; } ?>
                                                <th>&nbsp;</th>
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

                                                <td align="center">
                                                    <a href="./?mode=supplier/product-first-detail&id=<?php echo $row["id"]; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>" title="แก้ไขข้อมูล"><i class="fas fa-edit"></i></a></td>
                                                <td align="center"><a href="#deleted" onclick="deleteCate('<?php echo $row['id']; ?>', 'deletefirst');"><i class="fas fa-trash-alt" style="color:#FF0000" title="ลบข้อมูล"></i></a></td>
                                                <?php if($_SESSION["admin"]["permission"] == 1){ ?>
                                                <td align="center">
                                                    <?php if($row["trash_deleted"] == 1){ ?>
                                                        <a href="#restore" onclick="restoreCate('<?php echo $row['id']; ?>', 'restorefirst');" title="คืนข้อมูล"><i class="ti-reload" style="color:#0CDE66"></i></a><?php } ?>
                                                </td>
                                                <?php } ?>
                                                <td align="center">
                                                    <button type="button" class="btn btn-info" style="padding: 3px 8px" onclick="window.location.href='./?mode=supplier/product-second-detail&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $row['id']; ?>'">
                                                        <i class="fa fa-plus-circle"></i> เพิ่มข้อมูลสินค้า</button></td>
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
                                    <?php if($row_second["withholding_tax"] == 1){ echo "( 1% Withholding Tax )"; } ?></span> &nbsp;&nbsp; 
                                    <a href="./?mode=supplier/product-second-detail&id=<?php echo $row_second["id"]; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $row["id"]; ?>" title="แก้ไขข้อมูล">
                                        <i class="fas fa-edit" style="color:#0D84DE"></i></a> &nbsp;&nbsp; 
                                    <a href="#deleted" onclick="deleteCate('<?php echo $row_second['id']; ?>', 'deletesecond');" title="ลบข้อมูล">
                                        <i class="fas fa-trash-alt" style="color:#FF0000"></i></a> &nbsp;&nbsp; 
                                    <?php if($_SESSION["admin"]["permission"] == 1){ ?>
                                        <?php if($row_second["trash_deleted"] == 1){ ?>
                                            <a href="#restore" onclick="restoreCate('<?php echo $row_second['id']; ?>', 'restoresecond');" title="คืนข้อมูล"><i class="ti-reload" style="color:#0CDE66"></i></a>
                                            &nbsp;&nbsp; 
                                        <?php } ?>
                                    <?php } ?>
                                    <a href="#copy" onclick="copySecond(<?php echo $row_second['id']; ?>);" title="คัดลอกข้อมูล"><i class="fas fa-copy" style="color:#FA9F32"></i></a>
                                </p>

                                <div class="table-responsive">
                                    <table class="table color-bordered-table muted-bordered-table">
                                        <thead>
                                            <tr align="center">
                                                <th style="text-align:left">ระยะเวลา &nbsp;&nbsp; 
                                                    <button type="button" class="btn btn-info" style="padding: 3px 8px" 
                                                        onclick="window.location.href='./?mode=supplier/product-third-detail&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $row['id']; ?>&catesecond=<?php echo $row_second['id']; ?>'">
                                                        <i class="fa fa-plus-circle"></i> เพิ่มข้อมูลราคา</button></th>
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
                                                <th>ลบ</th>
                                                <?php if($_SESSION["admin"]["permission"] == 1){ echo "<th>คืน</th>"; } ?>
                                            </tr>
                                        </thead>
                                        <tbody style="font-weight:500">

                                        <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();

                                            $query_third = "SELECT * FROM products_category_third WHERE id > '0'";
                                            if($_SESSION["admin"]["permission"] != 1){ $query_third .= " AND trash_deleted != '1'"; }
                                            if(!empty($row_second["id"]))
                                            { 
                                                # products_category_second
                                                $query_third .= " AND products_category_second = ?"; 
                                                $bind_types .= "i";
                                                array_push($params, $row_second["id"]);
                                            }
                                            $query_third .= " ORDER BY periods_from ASC";
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
                                                $rate_2 = $row_third["rate_2"] != '0.00' ? number_format($row_third["rate_2"]) : '-';
                                                $rate_3 = $row_third["rate_3"] != '0.00' ? number_format($row_third["rate_3"]) : '-';
                                                $rate_4 = $row_third["rate_4"] != '0.00' ? number_format($row_third["rate_4"]) : '-';
                                                $charter_1 = $row_third["charter_1"] != '0.00' ? number_format($row_third["charter_1"]) : '-';
                                                $charter_2 = $row_third["charter_2"] != '0.00' ? number_format($row_third["charter_2"]) : '-';
                                                $group_1 = $row_third["group_1"] != '0.00' ? number_format($row_third["group_1"]) : '-';
                                                $group_2 = $row_third["group_2"] != '0.00' ? number_format($row_third["group_2"]) : '-';
                                                $transfer_1 = $row_third["transfer_1"] != '0.00' ? number_format($row_third["transfer_1"]) : '-';
                                                $transfer_2 = $row_third["transfer_2"] != '0.00' ? number_format($row_third["transfer_2"]) : '-';
                                                $pax = $row_third["pax"] != '0' ? number_format($row_third["pax"]) : '-';
                                                $extra_hour_1 = $row_third["extra_hour_1"] != '0.00' ? number_format($row_third["extra_hour_1"]) : '-';
                                                $extra_hour_2 = $row_third["extra_hour_2"] != '0.00' ? number_format($row_third["extra_hour_2"]) : '-';
                                                $hours_no = $row_third["hours_no"] != '0' ? number_format($row_third["hours_no"]) : '-';
                                                $extrabeds_1 = $row_third["extrabeds_1"] != '0.00' ? number_format($row_third["extrabeds_1"]) : '-';
                                                $extrabeds_2 = $row_third["extrabeds_2"] != '0.00' ? number_format($row_third["extrabeds_2"]) : '-';
                                                $sharingbed_1 = $row_third["sharingbed_1"] != '0.00' ? number_format($row_third["sharingbed_1"]) : '-';
                                                $sharingbed_2 = $row_third["sharingbed_2"] != '0.00' ? number_format($row_third["sharingbed_2"]) : '-';
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
                                                    <a href="./?mode=supplier/product-third-detail&id=<?php echo $row_third["id"]; ?>&ptype=<?php echo $ptype; ?>&supplier=<?php echo $supplier; ?>&catefirst=<?php echo $row["id"]; ?>&catesecond=<?php echo $row_second["id"]; ?>" title="Edit"><i class="fas fa-edit" style="color:#0D84DE"></i></a></td>
                                                <td align="center" width="7%">
                                                    <a href="#deleted" onclick="deleteCate('<?php echo $row_third['id']; ?>', 'deletethird');" title="Delete">
                                                        <i class="fas fa-trash-alt" style="color:#FF0000"></i></a></td>
                                                <?php if($_SESSION["admin"]["permission"] == 1){ ?>
                                                <td align="center" width="7%">
                                                    <?php if($row_third["trash_deleted"] == 1){ ?>
                                                        <a href="#restore" onclick="restoreCate('<?php echo $row_third['id']; ?>', 'restorethird');" title="Restore"><i class="ti-reload" style="color:#0CDE66"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
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