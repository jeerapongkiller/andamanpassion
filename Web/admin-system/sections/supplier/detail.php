<?php
    if(!empty($_GET["id"])){
        $query = "SELECT * FROM supplier WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $page_title = stripslashes($row["company"]);
        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=supplier/list'\" >";
        }
    }else{
        $page_title = "เพิ่มข้อมูล";
    }
    
    $sp_id = !empty($row["id"]) ? $row["id"] : '0';
    $sp_status = !empty($row["status"]) ? $row["status"] : '2';
    $sp_company = !empty($row["company"]) ? $row["company"] : '';
    $sp_telephone = !empty($row["telephone"]) ? $row["telephone"] : '';
    $sp_fax = !empty($row["fax"]) ? $row["fax"] : '';
    $sp_email = !empty($row["sup_email"]) ? $row["sup_email"] : '';
    $sp_website = !empty($row["website"]) ? $row["website"] : '';
    $sp_address = !empty($row["address"]) ? $row["address"] : '';
    $sp_contact_person = !empty($row["contact_person"]) ? $row["contact_person"] : '';
    $sp_contact_position = !empty($row["contact_position"]) ? $row["contact_position"] : '';
    $sp_contact_telephone = !empty($row["contact_telephone"]) ? $row["contact_telephone"] : '';
    $sp_contact_email = !empty($row["contact_email"]) ? $row["contact_email"] : '';
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

                <?php if($sp_id > 0){ ?>
                <!-- ============================================================== -->
                <!-- Info box -->
                <!-- ============================================================== -->
                <div class="card-group">
                <?php
                    $query_ptype = "SELECT * FROM products_type WHERE id > '0'";
                    $query_ptype .= " ORDER BY id ASC";
                    $result_ptype = mysqli_query($mysqli_p, $query_ptype);
                    while($row_ptype = mysqli_fetch_array($result_ptype, MYSQLI_ASSOC))
                    {
                        if($row_ptype["id"] == 1){
                            $icon = "ti-map-alt";
                            $bgcol = "bg-info";
                        }elseif($row_ptype["id"] == 2){
                            $icon = "icon-support";
                            $bgcol = "bg-success";
                        }elseif($row_ptype["id"] == 3){
                            $icon = "ti-car";
                            $bgcol = "bg-danger";
                        }elseif($row_ptype["id"] == 4){
                            $icon = "ti-home";
                            $bgcol = "bg-warning";
                        }else{
                            $icon = "ti-home";
                            $bgcol = "bg-info";
                        }

                        $query_products = "SELECT * FROM products_category_first WHERE id > '0'";
                        $query_products .= " AND products_type = ?";
                        $query_products .= " AND supplier = ?";
                        $procedural_statement = mysqli_prepare($mysqli_p, $query_products);
                        mysqli_stmt_bind_param($procedural_statement, 'ii', $row_ptype["id"], $sp_id);
                        mysqli_stmt_execute($procedural_statement);
                        $result = mysqli_stmt_get_result($procedural_statement);
                        $numrow = mysqli_num_rows($result);
                ?>
                    <!-- Column -->
                    <div class="card col-md-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h3><i class="<?php echo $icon; ?>"></i>
                                                <button type="button" class="btn btn-info m-l-15" 
                                                    onclick="window.location.href='./?mode=supplier/product-list&ptype=<?php echo $row_ptype['id']; ?>&supplier=<?php echo $sp_id; ?>'">
                                                <i class="icon-magnifier m-r-5"></i> รายการสินค้า</button></h3>
                                            <p class="text-muted font-bold"><?php echo $row_ptype["name_text_thai"]; ?></p>
                                        </div>
                                        <div class="ml-auto">
                                            <h2 class="counter text-cyan"><b><?php echo number_format($numrow); ?></b></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress">
                                        <div class="progress-bar <?php echo $bgcol; ?> progress-bar-striped" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" 
                                            style="width:85%;height:7px;"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                <?php
                    } /* while($row_ptype = mysqli_fetch_array($result_ptype, MYSQLI_ASSOC)) */
                ?>
                </div>
                <!-- ============================================================== -->
                <!-- End Info box -->
                <!-- ============================================================== -->
                <?php } /* if($sp_id > 0) */ ?>

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

                    <!-- Validation Form -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Detail</h4> -->
                                <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=supplier/save" novalidate>
                                    <input type="hidden" id="sp_id" name="sp_id" value="<?php echo $sp_id; ?>">
                                    <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                                    <input type="hidden" id="sp_company_duplicate" name="sp_company_duplicate" value="false">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="sp_status">สถานะ</label>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="sp_status" name="sp_status"
                                                <?php if($sp_status != 2 || !isset($sp_status)){ echo "checked"; } ?> value="1">
                                                <label class="custom-control-label" for="sp_status">ออนไลน์</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_company">ชื่อบริษัท</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputCompany"><i class="ti-home"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_company" name="sp_company" placeholder="" aria-describedby="inputCompany"
                                                    autocomplete="off" onkeyup="checkCompany();" value="<?php echo htmlspecialchars($sp_company); ?>" required
                                                    <?php echo ($sp_id > 0) ? 'readonly' : ''; ?>>
                                                <div class="invalid-feedback" id="sp_company_feedback">กรุณาระบุชื่อบริษัท</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_telephone">เบอร์โทรศัพท์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputTelephone"><i class="ti-mobile"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_telephone" name="sp_telephone" placeholder="" aria-describedby="inputTelephone"
                                                    value="<?php echo $sp_telephone; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_fax">แฟกซ์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputFax"><i class="ti-printer"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_fax" name="sp_fax" placeholder="" aria-describedby="inputFax"
                                                    value="<?php echo $sp_fax; ?>">
                                                <div class="invalid-feedback">กรุณาระบุแฟกซ์</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_email">อีเมล์ (การจอง)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputEmail"><i class="ti-email"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_email" name="sp_email" placeholder="" aria-describedby="inputEmail"
                                                    value="<?php echo $sp_email; ?>" required>
                                                <div class="invalid-feedback">กรุณาระบุอีเมล์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_website">เว็บไซต์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputWebsite"><i class="ti-world"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_website" name="sp_website" placeholder="" aria-describedby="inputWebsite"
                                                    value="<?php echo $sp_website; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเว็บไซต์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="sp_address">ที่อยู่</label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="sp_address" name="sp_address" aria-describedby="inputAddress" rows="3"><?php echo $sp_address; ?></textarea>
                                                <div class="invalid-feedback">กรุณาระบุที่อยู่</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <label for="sp_contact_person">ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactPerson"><i class="ti-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_contact_person" name="sp_contact_person" placeholder="" aria-describedby="inputContactPerson"
                                                    value="<?php echo $sp_contact_person; ?>">
                                                <div class="invalid-feedback">กรุณาระบุผู้ติดต่อ</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sp_contact_position">ตำแหน่ง</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactPosition"><i class="ti-briefcase"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_contact_position" name="sp_contact_position" placeholder="" aria-describedby="inputContactPosition"
                                                    value="<?php echo $sp_contact_position; ?>">
                                                <div class="invalid-feedback">กรุณาระบุตำแหน่ง</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sp_contact_telephone">เบอร์โทรศัพท์ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactTelephone"><i class="ti-mobile"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_contact_telephone" name="sp_contact_telephone" placeholder="" aria-describedby="inputContactTelephone"
                                                    value="<?php echo $sp_contact_telephone; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์ผู้ติดต่อ</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sp_contact_email">อีเมล์ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactEmail"><i class="ti-email"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="sp_contact_email" name="sp_contact_email" placeholder="" aria-describedby="inputContactEmail"
                                                    value="<?php echo $sp_contact_email; ?>">
                                                <div class="invalid-feedback">กรุณาระบุอีเมล์ผู้ติดต่อ</div>
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
                                        for($i=1;$i<=1;$i++)
                                        {
                                            $img_tmp = !empty($row["photo".$i]) ? $row["photo".$i] : '';
                                            $pathpicture = !empty($img_tmp) ? '../inc/photo/supplier/'.$img_tmp : '';
                                    ?>
                                        <div class="col-md-3 mb-3">
                                            <label for="photo<?php echo $i; ?>">ภาพที่ # <span style="font-weight:bold; color:#FF0000"><?php echo $i; ?></span></label>
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
                                                <input type="file" id="photo<?php echo $i; ?>" name="photo<?php echo $i; ?>" class="dropify" data-default-file="<?php echo $pathpicture; ?>" data-show-remove="false" data-max-file-size="2M" data-allowed-file-extensions="jpg png" />
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
                                    function checkCompany() {
                                        var sp_company = document.getElementById('sp_company');
                                        // sp_company.value = sp_company.value.replace(/[^A-Za-z0-9]+/, '');
                                        var sp_company_duplicate = document.getElementById('sp_company_duplicate');

                                        jQuery.ajax({
                                            url: "../inc/ajax/supplier/checkcompany.php",
                                            data: {sp_company: $("#sp_company").val(), sp_id: $("#sp_id").val()},
                                            type: "POST",
                                            success:function(response){
                                                // alert(response);

                                                if(response == "duplicate"){
                                                    sp_company_duplicate.value = false;
                                                    $("#sp_company_feedback").html("กรุณาระบุชื่อบริษัทใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว");

                                                    Swal.fire({
                                                        // position: 'top-end',
                                                        type: 'error',
                                                        // title: '',
                                                        text: 'กรุณาระบุชื่อบริษัทใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว',
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }else if(response == "true"){
                                                    sp_company_duplicate.value = true;
                                                    $("#sp_company_feedback").html("กรุณาระบุชื่อบริษัท");
                                                }else{
                                                    sp_company_duplicate.value = false;
                                                    $("#sp_company_feedback").html("กรุณาระบุชื่อบริษัทใหม่ เพราะยังไม่ได้ระบุชื่อบริษัทหรือชื่อบริษัทที่ระบุถูกใช้งานแล้ว");
                                                }
                                            },
                                            error:function(){}
                                        });
                                    }

                                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                                    (function() {
                                        'use strict';
                                        window.addEventListener('load', function() {
                                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                            var forms = document.getElementsByClassName('needs-validation');
                                            // Loop over them and prevent submission
                                            var validation = Array.prototype.filter.call(forms, function(form) {
                                                form.addEventListener('submit', function(event) {
                                                    var sp_company = document.getElementById('sp_company').value;
                                                    var sp_company_duplicate = document.getElementById('sp_company_duplicate').value;

                                                    if (sp_company_duplicate == "false") { 
                                                        sp_company_duplicate = false;
                                                    } else { 
                                                        sp_company_duplicate = true;
                                                    }
                                                    
                                                    if (form.checkValidity() === false || sp_company_duplicate === false) {
                                                        // $("#sp_company").next("div.invalid-feedback").text("Please enter a new username because the username is a duplicate or username has not been specified.");
                                                        if(sp_company_duplicate === false){
                                                            // sp_company.value = "";
                                                            // alert(sp_company);
                                                            document.getElementById('sp_company').value = "";
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