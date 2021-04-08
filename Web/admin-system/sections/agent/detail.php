<?php
    if(!empty($_GET["id"])){
        $query = "SELECT * FROM agent WHERE id > '0'";
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
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
        }
    }else{
        $page_title = "เพิ่มข้อมูล";
    }
    
    $ag_id = !empty($row["id"]) ? $row["id"] : '0';
    $ag_status = !empty($row["status"]) ? $row["status"] : '2';
    $ag_company = !empty($row["company"]) ? $row["company"] : '';
    $ag_company_invoice = !empty($row["company_invoice"]) ? $row["company_invoice"] : '';
    $ag_address = !empty($row["address"]) ? $row["address"] : '';
    $ag_telephone = !empty($row["telephone"]) ? $row["telephone"] : '';
    $ag_fax = !empty($row["fax"]) ? $row["fax"] : '';
    $ag_email = !empty($row["ag_email"]) ? $row["ag_email"] : '';
    $ag_website = !empty($row["website"]) ? $row["website"] : '';
    $ag_tax_no = !empty($row["tax_no"]) ? $row["tax_no"] : '';
    $ag_headquarters = !empty($row["headquarters"]) ? $row["headquarters"] : '';
    $ag_transfer = !empty($row["transfer"]) ? $row["transfer"] : '';
    $ag_payments_vat = !empty($row["payments_vat"]) ? $row["payments_vat"] : '';
    $ag_contact_person = !empty($row["contact_person"]) ? $row["contact_person"] : '';
    $ag_contact_position = !empty($row["contact_position"]) ? $row["contact_position"] : '';
    $ag_contact_telephone = !empty($row["contact_telephone"]) ? $row["contact_telephone"] : '';
    $ag_contact_email = !empty($row["contact_email"]) ? $row["contact_email"] : '';
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
                                <li class="breadcrumb-item"><a href="./?mode=agent/list">เอเย่นต์</a></li>
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

                <?php if($ag_id > 0){ ?>
                <!-- ============================================================== -->
                <!-- Info box -->
                <!-- ============================================================== -->
                <div class="card-group">
                <?php
                    $query_ptype = "SELECT * FROM products_type WHERE id > '0' AND id != '2' AND id != '4'";
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
                        mysqli_stmt_bind_param($procedural_statement, 'ii', $row_ptype["id"], $ag_id);
                        mysqli_stmt_execute($procedural_statement);
                        $result = mysqli_stmt_get_result($procedural_statement);
                        // $numrow = mysqli_num_rows($result);
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
                                                    onclick="window.location.href='./?mode=agent/combine-agentxsupplier&ptype=<?php echo $row_ptype['id']; ?>&agent=<?php echo $ag_id; ?>'">
                                                <i class="icon-magnifier m-r-5"></i> รายการสินค้า</button></h3>
                                            <p class="text-muted font-bold"><?php echo $row_ptype["name_text_thai"]; ?></p>
                                        </div>
                                        <!-- <div class="ml-auto">
                                            <h2 class="counter text-cyan"><b><?php echo number_format($numrow); ?></b></h2>
                                        </div> -->
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
                <?php } /* if($ag_id > 0) */ ?>

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

                    <!-- Validation Form -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Detail</h4> -->
                                <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=agent/save" novalidate>
                                    <input type="hidden" id="ag_id" name="ag_id" value="<?php echo $ag_id; ?>">
                                    <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                                    <input type="hidden" id="ag_company_duplicate" name="ag_company_duplicate" value="false">
                                    <input type="hidden" id="ag_company_invoice_duplicate" name="ag_company_invoice_duplicate" value="false">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_status">สถานะ</label>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="ag_status" name="ag_status"
                                                <?php if($ag_status != 2 || !isset($ag_status)){ echo "checked"; } ?> value="1">
                                                <label class="custom-control-label" for="ag_status">ออนไลน์</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_transfer">แยกบิลสำหรับรถรับส่ง (<span style="color:#FF0000">เฉพาะสินค้าประเภททัวร์เท่านั้น</span>)</label>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="ag_transfer" name="ag_transfer"
                                                <?php if($ag_transfer != 2 || !isset($ag_transfer)){ echo "checked"; } ?> value="1">
                                                <label class="custom-control-label" for="ag_transfer">แยกบิล</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_payments_vat">Vat</label>
                                            <?php
                                                $query_vat = "SELECT * FROM payments_vat WHERE id > '0'";
                                                $query_vat .= " ORDER BY id ASC";
                                                $result_vat = mysqli_query($mysqli_p, $query_vat);
                                                while($row_vat = mysqli_fetch_array($result_vat, MYSQLI_ASSOC))
                                                {
                                            ?>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="paymentVat<?php echo $row_vat["id"]; ?>" name="ag_payments_vat" class="custom-control-input" 
                                                    <?php if($ag_payments_vat == $row_vat["id"]){ echo "checked"; } ?> value="<?php echo $row_vat["id"]; ?>" required>
                                                    <label class="custom-control-label" for="paymentVat<?php echo $row_vat["id"]; ?>"><?php echo $row_vat["name"]; ?></label>
                                                </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_company">ชื่อบริษัท</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputCompany"><i class="ti-home"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_company" name="ag_company" placeholder="" aria-describedby="inputCompany"
                                                    autocomplete="off" onkeyup="checkCompany();" value="<?php echo htmlspecialchars($ag_company); ?>" required
                                                    <?php echo ($ag_id > 0) ? 'readonly' : ''; ?>>
                                                <div class="invalid-feedback" id="ag_company_feedback">กรุณาระบุชื่อบริษัท</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_company_invoice">ชื่อบริษัท (ใบแจ้งหนี้)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputCompanyinvoice"><i class="ti-home"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_company_invoice" name="ag_company_invoice" placeholder="" aria-describedby="inputCompanyinvoice"
                                                    autocomplete="off" onkeyup="checkCompanyinvoice();" value="<?php echo htmlspecialchars($ag_company_invoice); ?>" required
                                                    <?php echo ($ag_id > 0) ? 'readonly' : ''; ?>>
                                                <div class="invalid-feedback" id="ag_company_invoice_feedback">กรุณาระบุชื่อบริษัท (ใบแจ้งหนี้)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_telephone">เบอร์โทรศัพท์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputTelephone"><i class="ti-mobile"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_telephone" name="ag_telephone" placeholder="" aria-describedby="inputTelephone"
                                                    value="<?php echo $ag_telephone; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_fax">แฟกซ์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputFax"><i class="ti-printer"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_fax" name="ag_fax" placeholder="" aria-describedby="inputFax"
                                                    value="<?php echo $ag_fax; ?>">
                                                <div class="invalid-feedback">กรุณาระบุแฟกซ์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_email">อีเมล์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputEmail"><i class="ti-email"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_email" name="ag_email" placeholder="" aria-describedby="inputEmail"
                                                    value="<?php echo $ag_email; ?>">
                                                <div class="invalid-feedback">กรุณาระบุอีเมล์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_website">เว็บไซต์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputWebsite"><i class="ti-world"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_website" name="ag_website" placeholder="" aria-describedby="inputWebsite"
                                                    value="<?php echo $ag_website; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเว็บไซต์</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_tax_no">เลขที่ผู้เสียภาษี</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputTax"><i class="ti-id-badge"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_tax_no" name="ag_tax_no" placeholder="" aria-describedby="inputTax"
                                                    value="<?php echo $ag_tax_no; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเลขที่ผู้เสียภาษี</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_headquarters">สำนักงานใหญ่ หรือสาขา</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputHeadquarters"><i class="ti-home"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_headquarters" name="ag_headquarters" placeholder="" aria-describedby="inputHeadquarters"
                                                    value="<?php echo $ag_headquarters; ?>">
                                                <div class="invalid-feedback">กรุณาระบุสำนักงานใหญ่ หรือสาขา</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="ag_address">ที่อยู่</label>
                                            <div class="input-group">
                                                <textarea class="form-control" id="ag_address" name="ag_address" aria-describedby="inputAddress" rows="3"><?php echo $ag_address; ?></textarea>
                                                <div class="invalid-feedback">กรุณาระบุที่อยู่</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <label for="ag_contact_person">ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactPerson"><i class="ti-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_contact_person" name="ag_contact_person" placeholder="" aria-describedby="inputContactPerson"
                                                    value="<?php echo $ag_contact_person; ?>">
                                                <div class="invalid-feedback">กรุณาระบุผู้ติดต่อ</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ag_contact_position">ตำแหน่ง</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactPosition"><i class="ti-briefcase"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_contact_position" name="ag_contact_position" placeholder="" aria-describedby="inputContactPosition"
                                                    value="<?php echo $ag_contact_position; ?>">
                                                <div class="invalid-feedback">กรุณาระบุตำแหน่ง</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ag_contact_telephone">เบอร์โทรศัพท์ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactTelephone"><i class="ti-mobile"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_contact_telephone" name="ag_contact_telephone" placeholder="" aria-describedby="inputContactTelephone"
                                                    value="<?php echo $ag_contact_telephone; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์ผู้ติดต่อ</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ag_contact_email">อีเมล์ผู้ติดต่อ</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputContactEmail"><i class="ti-email"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="ag_contact_email" name="ag_contact_email" placeholder="" aria-describedby="inputContactEmail"
                                                    value="<?php echo $ag_contact_email; ?>">
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
                                                    <small>Support "<b>JPG, PNG, PDF</b>" format only and file size <b>must not exceed 2MB per file</b>. Should be fix to <b>width</b> 600px, <b>height</b> 600px</small></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                    <?php 
                                        for($i=1;$i<=1;$i++)
                                        {
                                            $img_tmp = !empty($row["photo".$i]) ? $row["photo".$i] : '';
                                            $pathpicture = !empty($img_tmp) ? '../inc/photo/agent/'.$img_tmp : '';
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
                                                <input type="file" id="photo<?php echo $i; ?>" name="photo<?php echo $i; ?>" class="dropify" data-default-file="<?php echo $pathpicture; ?>" data-show-remove="false" data-max-file-size="2M" data-allowed-file-extensions="jpg png pdf" />
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
                                        var ag_company = document.getElementById('ag_company');
                                        // ag_company.value = ag_company.value.replace(/[^A-Za-z0-9]+/, '');
                                        var ag_company_duplicate = document.getElementById('ag_company_duplicate');

                                        jQuery.ajax({
                                            url: "../inc/ajax/agent/checkcompany.php",
                                            data: {ag_company: $("#ag_company").val(), ag_id: $("#ag_id").val()},
                                            type: "POST",
                                            success:function(response){
                                                // alert(response);

                                                if(response == "duplicate"){
                                                    ag_company_duplicate.value = false;
                                                    $("#ag_company_feedback").html("กรุณาระบุชื่อบริษัทใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว");

                                                    Swal.fire({
                                                        // position: 'top-end',
                                                        type: 'error',
                                                        // title: '',
                                                        text: 'กรุณาระบุชื่อบริษัทใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว',
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }else if(response == "true"){
                                                    ag_company_duplicate.value = true;
                                                    $("#ag_company_feedback").html("กรุณาระบุชื่อบริษัท");
                                                }else{
                                                    ag_company_duplicate.value = false;
                                                    $("#ag_company_feedback").html("กรุณาระบุชื่อบริษัทใหม่ เพราะยังไม่ได้ระบุชื่อบริษัทหรือชื่อบริษัทที่ระบุถูกใช้งานแล้ว");
                                                }
                                            },
                                            error:function(){}
                                        });
                                    }

                                    function checkCompanyinvoice() {
                                        var ag_company_invoice = document.getElementById('ag_company_invoice');
                                        // ag_company_invoice.value = ag_company_invoice.value.replace(/[^A-Za-z0-9]+/, '');
                                        var ag_company_invoice_duplicate = document.getElementById('ag_company_invoice_duplicate');

                                        jQuery.ajax({
                                            url: "../inc/ajax/agent/checkcompanyinvoice.php",
                                            data: {ag_company_invoice: $("#ag_company_invoice").val(), ag_id: $("#ag_id").val()},
                                            type: "POST",
                                            success:function(response){
                                                // alert(response);

                                                if(response == "duplicate"){
                                                    ag_company_invoice_duplicate.value = false;
                                                    $("#ag_company_invoice_feedback").html("กรุณาระบุชื่อบริษัท(ใบแจ้งหนี้)ใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว");

                                                    Swal.fire({
                                                        // position: 'top-end',
                                                        type: 'error',
                                                        // title: '',
                                                        text: 'กรุณาระบุชื่อบริษัท(ใบแจ้งหนี้)ใหม่ เพราะชื่อบริษัทที่ระบุถูกใช้งานแล้ว',
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }else if(response == "true"){
                                                    ag_company_invoice_duplicate.value = true;
                                                    $("#ag_company_invoice_feedback").html("กรุณาระบุชื่อบริษัท(ใบแจ้งหนี้)");
                                                }else{
                                                    ag_company_invoice_duplicate.value = false;
                                                    $("#ag_company_invoice_feedback").html("กรุณาระบุชื่อบริษัท(ใบแจ้งหนี้)ใหม่ เพราะยังไม่ได้ระบุชื่อบริษัท(ใบแจ้งหนี้)หรือชื่อบริษัท(ใบแจ้งหนี้)ที่ระบุถูกใช้งานแล้ว");
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
                                                    var ag_company = document.getElementById('ag_company').value;
                                                    var ag_company_invoice = document.getElementById('ag_company_invoice').value;
                                                    var ag_company_duplicate = document.getElementById('ag_company_duplicate').value;
                                                    var ag_company_invoice_duplicate = document.getElementById('ag_company_invoice_duplicate').value;

                                                    if (ag_company_duplicate == "false") { 
                                                        ag_company_duplicate = false;
                                                    } else { 
                                                        ag_company_duplicate = true;
                                                    }

                                                    if (ag_company_invoice_duplicate == "false") { 
                                                        ag_company_invoice_duplicate = false;
                                                    } else { 
                                                        ag_company_invoice_duplicate = true;
                                                    }
                                                    
                                                    if (form.checkValidity() === false || ag_company_duplicate === false || ag_company_invoice_duplicate === false) {
                                                        // $("#ag_company").next("div.invalid-feedback").text("Please enter a new username because the username is a duplicate or username has not been specified.");
                                                        if(ag_company_duplicate === false){
                                                            // ag_company.value = "";
                                                            // alert(ag_company);
                                                            document.getElementById('ag_company').value = "";
                                                        }
                                                        if(ag_company_invoice_duplicate === false){
                                                            // ag_company_invoice.value = "";
                                                            // alert(ag_company_invoice);
                                                            document.getElementById('ag_company_invoice').value = "";
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