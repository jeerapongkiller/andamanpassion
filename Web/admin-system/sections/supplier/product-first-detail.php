<?php
    $ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
    $supplier = !empty($_GET["supplier"]) ? $_GET["supplier"] : '';

    if(empty($ptype) || empty($supplier)){ echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >"; }
    
    $ptype_name = get_value("products_type","id","name_text_thai",$ptype,$mysqli_p);
    $sp_company = get_value("supplier","id","company",$supplier,$mysqli_p);
    $sp_id = !empty($supplier) ? $supplier : '0';

    if(!empty($_GET["id"])){
        // $query = "SELECT products_category_first.*, supplier.id as supplierID, supplier.company as company 
        //             FROM products_category_first 
        //             LEFT JOIN supplier 
        //                 ON products_category_first.supplier = supplier.id
        //             WHERE products_category_first.id = ? 
        //                 AND products_category_first.products_type = ? 
        //                 AND products_category_first.supplier = ? 
        // ";

        $query = "SELECT * FROM products_category_first WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " AND products_type = ?";
        $query .= " AND supplier = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'iii', $_GET["id"], $ptype, $supplier);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $page_title = stripslashes($row["name"]);

            // if(!empty($ptype)){
            //     $query_ptype = "SELECT * FROM products_type WHERE id > '0'";
            //     $query_ptype .= " AND id = ?";
            //     $query_ptype .= " LIMIT 1";
            //     $procedural_statement = mysqli_prepare($mysqli_p, $query_ptype);
            //     mysqli_stmt_bind_param($procedural_statement, 'i', $ptype);
            //     mysqli_stmt_execute($procedural_statement);
            //     $result = mysqli_stmt_get_result($procedural_statement);
            //     $numrow = mysqli_num_rows($result);
            //     if($numrow > 0){
            //         $row_ptype = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //         $ptype_name = stripslashes($row_ptype["name"]);
            //     }else{
            //         echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
            //     }
            // }else{
            //     echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
            // }

        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
        }
    }else{
        $page_title = "เพิ่มข้อมูล".$ptype_name;
    }
    
    $first_id = !empty($row["id"]) ? $row["id"] : '0';
    $first_status = !empty($row["status"]) ? $row["status"] : '2';
    $first_name = !empty($row["name"]) ? $row["name"] : '';
    $first_validity_from = !empty($row["validity_from"]) ? $row["validity_from"] : $today;
    $first_validity_to = !empty($row["validity_to"]) ? $row["validity_to"] : $today;
    $first_procode = !empty($row["procode"]) ? $row["procode"] : '';
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
                    <div class="col-md-8 align-self-center">
                        <!-- <h4 class="text-themecolor">Supplier</h4> -->
                        <div class="d-flex align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=supplier/list">ซัพพลายเออร์</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=supplier/detail&id=<?php echo $sp_id; ?>"><?php echo $sp_company; ?></a></li>
                                <li class="breadcrumb-item">
                                    <a href="./?mode=supplier/product-list&ptype=<?php echo $ptype; ?>&supplier=<?php echo $sp_id; ?>">รายการสินค้า (<?php echo $ptype_name; ?>)</a></li>
                                <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-4 align-self-center text-right">
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
                                <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=supplier/save-product-first" novalidate>
                                    <input type="hidden" id="sp_id" name="sp_id" value="<?php echo $sp_id; ?>">
                                    <input type="hidden" id="ptype" name="ptype" value="<?php echo $ptype; ?>">
                                    <input type="hidden" id="first_id" name="first_id" value="<?php echo $first_id; ?>">
                                    <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="first_status">สถานะ</label>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="first_status" name="first_status"
                                                <?php if($first_status != 2 || !isset($first_status)){ echo "checked"; } ?> value="1">
                                                <label class="custom-control-label" for="first_status">ออนไลน์</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_name">ชื่อ<?php echo $ptype_name; ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputName"><i class="ti-pin-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" aria-describedby="inputName"
                                                    autocomplete="off" value="<?php echo htmlspecialchars($first_name); ?>" required>
                                                <div class="invalid-feedback">กรุณาระบุชื่อ<?php echo $ptype_name; ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="first_validity_from">วันที่เริ่ม</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputFrom"><i class="ti-timer"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="first_validity_from" name="first_validity_from" placeholder="" aria-describedby="inputFrom"
                                                    value="<?php echo $first_validity_from; ?>" readonly required>
                                                <div class="invalid-feedback">กรุณาระบุวันที่เริ่ม</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="first_validity_to">วันที่สิ้นสุด</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputTo"><i class="ti-timer"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="first_validity_to" name="first_validity_to" placeholder="" aria-describedby="inputTo"
                                                    value="<?php echo $first_validity_to; ?>" readonly required>
                                                <div class="invalid-feedback">กรุณาระบุวันที่สิ้นสุด</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="first_procode">รหัสโปรโมชั่น</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputProcode"><i class="ti-ticket"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="first_procode" name="first_procode" placeholder="" aria-describedby="inputProcode"
                                                    value="<?php echo $first_procode; ?>">
                                                <div class="invalid-feedback">กรุณาระบุรหัสโปรโมชั่น</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="remark">หมายเหตุ</label>
                                            <div class="input-group">
                                                <span class="help-block" style="color:#FF0000">
                                                    <small>Support "<b>PDF</b>" format only and file size <b>must not exceed 2MB per file</b>. Should be fix to <b>width</b> 600px, <b>height</b> 600px</small></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                    <?php 
                                        for($i=1;$i<=3;$i++)
                                        {
                                            $img_tmp = !empty($row["photo".$i]) ? $row["photo".$i] : '';
                                            $pathpicture = !empty($img_tmp) ? '../inc/photo/supplier/'.$img_tmp : '';
                                    ?>
                                        <div class="col-md-3 mb-3">
                                            <label for="photo<?php echo $i; ?>">ไฟล์ที่ # <span style="font-weight:bold; color:#FF0000"><?php echo $i; ?></span></label>
                                            <?php if($img_tmp != ""){ ?>
                                            <label class="m-l-15">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="del_photo<?php echo $i; ?>" name="del_photo<?php echo $i; ?>" value="1">
                                                    <label class="custom-control-label" for="del_photo<?php echo $i; ?>">ลบภาพ</label>
                                                    <input type="hidden" class="form-control" id="tmp_photo<?php echo $i; ?>" name="tmp_photo<?php echo $i; ?>" value="<?php echo $img_tmp; ?>">
                                                </div>
                                            </label>
                                            <?php } ?>
                                            <div class="input-group">
                                                <input type="file" id="photo<?php echo $i; ?>" name="photo<?php echo $i; ?>" class="dropify" data-default-file="<?php echo $pathpicture; ?>" data-show-remove="false" data-max-file-size="2M" data-allowed-file-extensions="pdf" />
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                        <input type="hidden" class="form-control" id="photo_count" name="photo_count" value="<?php echo $i-1; ?>">
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