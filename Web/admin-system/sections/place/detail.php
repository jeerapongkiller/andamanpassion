<?php
if (!empty($_GET["id"])) {
    $query = "SELECT * FROM place WHERE id > '0'";
    $query .= " AND id = ?";
    $query .= " LIMIT 1";
    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_GET["id"]);
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result);
    if ($numrow > 0) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $page_title = stripslashes($row["name"]);
    } else {
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=place/list'\" >";
    }
} else {
    $page_title = "เพิ่มข้อมูล";
}

$dp_id = !empty($row["id"]) ? $row["id"] : '0';
$dp_status = !empty($row["status"]) ? $row["status"] : '2';
$dp_name = !empty($row["name"]) ? $row["name"] : '';
// $dp_pickup = !empty($row["pickup"]) ? $row["pickup"] : '2';
// $dp_dropoff = !empty($row["dropoff"]) ? $row["dropoff"] : '2';
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
                        <li class="breadcrumb-item"><a href="./?mode=place/list">สถานที่ส่ง</a></li>
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
                        <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=place/save" novalidate>
                            <input type="hidden" id="dp_id" name="dp_id" value="<?php echo $dp_id; ?>">
                            <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="dp_status">สถานะ</label>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="dp_status" name="dp_status" <?php if ($dp_status != 2 || !isset($dp_status)) {
                                                                                                                                echo "checked";
                                                                                                                            } ?> value="1">
                                        <label class="custom-control-label" for="dp_status">ออนไลน์</label>
                                    </div>
                                </div>
                                <!-- <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="dp_type">ประเภท</label>
                                        <div class="controls">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="1" name="dp_pickup" id="dp_pickup" class="custom-control-input" <?php // echo $dp_pickup == '1' ? 'checked' : '' ; ?>>
                                                    <label class="custom-control-label" for="dp_pickup">รับ</label>
                                                </div>
                                            </fieldset>
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="1" name="dp_dropoff" id="dp_dropoff" class="custom-control-input" <?php // echo $dp_dropoff == '1' ? 'checked' : '' ; ?>>
                                                    <label class="custom-control-label" for="dp_dropoff">ส่ง</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div> -->
                            </div>

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="dp_name">สถานที่</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputCompany"><i class="ti-flag"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="dp_name" name="dp_name" placeholder="" aria-describedby="inputCompany" autocomplete="off" value="<?php echo htmlspecialchars($dp_name); ?>" required>
                                        <div class="invalid-feedback" id="dp_name_feedback"></div>
                                    </div>
                                </div>
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
                                            var dp_name = document.getElementById('dp_name').value;
                                            var dp_pickup = document.getElementById('dp_pickup');
                                            var dp_dropoff = document.getElementById('dp_dropoff');

                                            if (form.checkValidity() === false) {

                                                // if(dp_pickup.checked == false && dp_dropoff.checked == false){
                                                //     dp_pickup.required = true;
                                                //     dp_dropoff.required = true;
                                                // }else{
                                                //     dp_pickup.required = false;
                                                //     dp_dropoff.required = false;
                                                // }

                                                $("#dp_name").next("div.invalid-feedback").text("กรุณาระบุสถานที่ส่ง");

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