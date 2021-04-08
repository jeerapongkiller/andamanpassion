<?php
    if(!empty($_GET["id"])){
        if($_GET["id"] == '1'){ echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=employee/list'\" >"; }
        
        $query = "SELECT * FROM employee WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $page_title = stripslashes($row["name"]);
        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=employee/list'\" >";
        }
    }else{
        $page_title = "เพิ่มข้อมูล";
    }
    
    $em_id = !empty($row["id"]) ? $row["id"] : '0';
    $em_status = !empty($row["status"]) ? $row["status"] : '2';
    $em_username = !empty($row["em_username"]) ? $row["em_username"] : '';
    $em_password = !empty($row["em_password"]) ? $row["em_password"] : '';
    $em_permission = !empty($row["permission"]) ? $row["permission"] : '';
    $em_name = !empty($row["name"]) ? $row["name"] : '';
    $em_email = !empty($row["em_email"]) ? $row["em_email"] : '';
    $em_phone = !empty($row["em_phone"]) ? $row["em_phone"] : '';
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
                        <!-- <h4 class="text-themecolor">Admin</h4> -->
                        <div class="d-flex align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./?mode=booking/list">การจอง</a></li>
                                <li class="breadcrumb-item"><a href="./?mode=employee/list">ผู้ใช้งานระบบ</a></li>
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
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">

                    <!-- Validation Form -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <h4 class="card-title">Detail</h4> -->
                                <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=employee/save" novalidate>
                                    <input type="hidden" id="em_id" name="em_id" value="<?php echo $em_id; ?>">
                                    <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                                    <input type="hidden" id="em_username_duplicate" name="em_username_duplicate" value="false">
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="em_status">สถานะ</label>
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="em_status" name="em_status"
                                                <?php if($em_status != 2 || !isset($em_status)){ echo "checked"; } ?> value="1">
                                                <label class="custom-control-label" for="em_status">ออนไลน์</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="em_username">ชื่อผู้ใช้</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputUsername"><i class="ti-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="em_username" name="em_username" placeholder="" aria-describedby="inputUsername"
                                                    pattern="^[A-Za-z0-9]+$" autocomplete="off" onkeyup="checkUsername();" value="<?php echo $em_username; ?>" required
                                                    <?php echo ($em_id > 0) ? 'readonly' : ''; ?>>
                                                <div class="invalid-feedback" id="em_username_feedback">กรุณาระบุชื่อผู้ใช้</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="em_password">รหัสผ่าน</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputPassword"><i class="ti-lock"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="em_password" name="em_password" placeholder="" aria-describedby="inputPassword"
                                                    pattern="^[A-Za-z0-9]+$" autocomplete="off" onkeyup="checkPassword();" value="<?php echo $em_password; ?>" required>
                                                <div class="invalid-feedback">กรุณาระบุรหัสผ่าน</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="permission">ระดับการใช้งาน</label>
                                            <select class="custom-select" id="permission" name="permission" required>
                                            <?php
                                                $query = "SELECT * FROM employee_permission WHERE id > '0' ORDER BY id ASC";
                                                $procedural_statement = mysqli_query($mysqli_p, $query);
                                                while($rowperm = mysqli_fetch_array($procedural_statement, MYSQLI_ASSOC))
                                                {
                                                    $em_permission_command = ($page_title != "Create") ? (($rowperm["id"] == $em_permission) ? 'selected' : 'disabled') : '';
                                                    // $em_permission_command = ($rowperm["id"] == $em_permission) ? 'selected' : 'disabled';
                                                    echo "<option value=".$rowperm["id"]." ".$em_permission_command.">".$rowperm["name"]."</option>";
                                                }
                                            ?>
                                            </select>
                                            <div class="invalid-feedback">กรุณาเลือกระดับการใช้งาน</div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="name">ชื่อ-สกุล</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder=""
                                                value="<?php echo $em_name; ?>" required>
                                            <div class="invalid-feedback">กรุณาระบุชื่อ-สกุล</div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="em_email">อีเมล์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputEmail"><i class="ti-email"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="em_email" name="em_email" placeholder="" aria-describedby="inputEmail"
                                                    value="<?php echo $em_email; ?>">
                                                <div class="invalid-feedback">กรุณาระบุอีเมล์</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="em_phone">เบอร์โทรศัพท์</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputPhone"><i class="ti-mobile"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="em_phone" name="em_phone" placeholder="" aria-describedby="inputPhone"
                                                    value="<?php echo $em_phone; ?>">
                                                <div class="invalid-feedback">กรุณาระบุเบอร์โทรศัพท์</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <button class="btn btn-primary" type="submit">บันทึกข้อมูล</button>
                                </form>
                                <script>
                                    function checkUsername() {
                                        var em_username = document.getElementById('em_username');
                                        em_username.value = em_username.value.replace(/[^A-Za-z0-9]+/, '');
                                        var em_username_duplicate = document.getElementById('em_username_duplicate');

                                        jQuery.ajax({
                                            url: "../inc/ajax/employee/checkusername.php",
                                            data: {em_username: $("#em_username").val(), em_id: $("#em_id").val()},
                                            type: "POST",
                                            success:function(response){
                                                // alert(response);

                                                if(response == "duplicate"){
                                                    em_username_duplicate.value = false;
                                                    // document.getElementById("em_username_feedback").classList.add('invalid-feedback');
                                                    $("#em_username_feedback").html("กรุณาระบุชื่อผู้ใช้ใหม่ เพราะชื่อผู้ใช้ที่ระบุถูกใช้งานแล้ว");

                                                    Swal.fire({
                                                        // position: 'top-end',
                                                        type: 'error',
                                                        // title: '',
                                                        text: 'กรุณาระบุชื่อผู้ใช้ใหม่ เพราะชื่อผู้ใช้ที่ระบุถูกใช้งานแล้ว',
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }else if(response == "true"){
                                                    em_username_duplicate.value = true;
                                                    $("#em_username_feedback").html("กรุณาระบุชื่อผู้ใช้");
                                                }else{
                                                    em_username_duplicate.value = false;
                                                    $("#em_username_feedback").html("กรุณาระบุชื่อผู้ใช้ใหม่ เพราะยังไม่ได้ระบุชื่อผู้ใช้หรือชื่อผู้ใช้ที่ระบุถูกใช้งานแล้ว");
                                                }
                                            },
                                            error:function(){}
                                        });
                                    }

                                    function checkPassword() {
                                        var em_password = document.getElementById('em_password');
                                        em_password.value = em_password.value.replace(/[^A-Za-z0-9]+/, '');
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
                                                    var em_username = document.getElementById('em_username').value;
                                                    var em_username_duplicate = document.getElementById('em_username_duplicate').value;

                                                    if (em_username_duplicate == "false") { 
                                                        em_username_duplicate = false;
                                                    } else { 
                                                        em_username_duplicate = true;
                                                    }
                                                    
                                                    if (form.checkValidity() === false || em_username_duplicate === false) {
                                                        // $("#em_username").next("div.invalid-feedback").text("Please enter a new username because the username is a duplicate or username has not been specified.");
                                                        if(em_username_duplicate === false){
                                                            // em_username.value = "";
                                                            // alert(em_username);
                                                            document.getElementById('em_username').value = "";
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