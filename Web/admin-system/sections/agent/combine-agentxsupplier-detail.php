<?php
    $ptype = !empty($_GET["ptype"]) ? $_GET["ptype"] : '';
    $agent = !empty($_GET["agent"]) ? $_GET["agent"] : '';

    if(!empty($agent)){
        $query = "SELECT * FROM agent WHERE id > '0'";
        $query .= " AND id = ?";
        $query .= " LIMIT 1";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'i', $agent);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result);
        if($numrow > 0){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $agent_name = stripslashes($row["company"]);

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
        }else{
            echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
        }
    }else{
        echo "<meta http-equiv=\"refresh\" content=\"0; url = './?mode=agent/list'\" >";
    }

    $page_title = "เลือกซัพพลายเออร์ (".$ptype_name.")";
    
    $ag_id = !empty($row["id"]) ? $row["id"] : '0';
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
                                <li class="breadcrumb-item"><a href="./?mode=agent/detail&id=<?php echo $agent; ?>"><?php echo $agent_name; ?></a></li>
                                <li class="breadcrumb-item"><a href="./?mode=agent/combine-agentxsupplier&ptype=<?php echo $ptype; ?>&agent=<?php echo $agent; ?>">เอเย่นต์ x ซัพพลายเออร์ (<?php echo $ptype_name; ?>)</a></li>
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
                                <form class="needs-validation" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" action="./?mode=agent/save-combine-agentxsupplier" novalidate>
                                    <input type="hidden" id="ag_id" name="ag_id" value="<?php echo $ag_id; ?>">
                                    <input type="hidden" id="ptype" name="ptype" value="<?php echo $ptype; ?>">
                                    <input type="hidden" id="page_title" name="page_title" value="<?php echo $page_title; ?>">
                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <label for="sp_company">เลือกซัพพลายเออร์ (ทัวร์)</label>
                                            <select class="custom-select" id="supplier" name="supplier" required>
                                                <option value="">กรุณาเลือกซัพพลายเออร์ (ทัวร์)</option>
                                            <?php
                                                $sup_combine = "";
                                                $query_combine = "SELECT * FROM combine_agentxsupplier WHERE agent = ? AND products_type = ?";
                                                $query_combine .= " ORDER BY agent ASC, supplier ASC";
                                                $procedural_statement = mysqli_prepare($mysqli_p, $query_combine);
                                                mysqli_stmt_bind_param($procedural_statement, 'ii', $ag_id, $ptype);
                                                mysqli_stmt_execute($procedural_statement);
                                                $result_combine = mysqli_stmt_get_result($procedural_statement);
                                                while($row_combine = mysqli_fetch_array($result_combine, MYSQLI_ASSOC))
                                                {
                                                    $sup_combine .= " AND id != '".$row_combine["supplier"]."'";
                                                }

                                                $query_sup = "SELECT * FROM supplier WHERE id > '0'";
                                                if($sup_combine != ""){ $query_sup .= $sup_combine; }
                                                $query_sup .= " ORDER BY company ASC";
                                                $result_sup = mysqli_query($mysqli_p, $query_sup);
                                                while($row_sup = mysqli_fetch_array($result_sup, MYSQLI_ASSOC))
                                                {
                                            ?>
                                                <option value="<?php echo $row_sup["id"]; ?>"><?php echo $row_sup["company"]; ?></option>
                                            <?php
                                                }
                                            ?>
                                            </select>
                                            <!-- <div class="input-group">
                                                <input type="text" class="form-control" id="sp_company" name="sp_company" placeholder="" aria-describedby="inputCompany"
                                                    autocomplete="off" onkeyup="checkCompany();" value="<?php echo htmlspecialchars($sp_company); ?>" required
                                                    <?php echo ($sp_id > 0) ? 'readonly' : ''; ?>>
                                                <div class="invalid-feedback" id="sp_company_feedback">กรุณาระบุชื่อบริษัท</div>
                                            </div> -->
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