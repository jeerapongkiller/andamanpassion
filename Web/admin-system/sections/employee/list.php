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
                                <li class="breadcrumb-item active">ผู้ใช้งานระบบ</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-info m-l-15" onclick="window.location.href='./?mode=employee/detail'">
                            <i class="fa fa-plus-circle"></i> เพิ่มข้อมูล</button>
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

                        function deleteList(emid){
                            Swal.fire({
                                type: 'warning',
                                title: 'คุณแน่ใจไหม?',
                                text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่!",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่ ลบข้อมูล!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.value) {
                                    jQuery.ajax({
                                        url: "../inc/ajax/employee/deletelist.php",
                                        data: {em_id: emid},
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

                        function restoreList(emid){
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
                                        url: "../inc/ajax/employee/restorelist.php",
                                        data: {em_id: emid},
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
                    </script>

                    <?php
                        # check value from search
                        $search_name_val = !empty($_POST["search_name"]) ? $_POST["search_name"] : '';
                        $search_username_val = !empty($_POST["search_username"]) ? $_POST["search_username"] : '';
                        $search_status_val = !empty($_POST["search_status"]) ? $_POST["search_status"] : '';
                    ?>

                    <!-- Validation Form (Search) -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">ค้นหาข้อมูล</h4>
                                <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=<?php echo $_GET["mode"]; ?>" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-2 mb-3">
                                            <label for="search_name">ชื่อ-สกุล</label>
                                            <input type="text" class="form-control" id="search_name" name="search_name" placeholder="" value="<?php echo $search_name_val; ?>"> <!-- required -->
                                            <!-- <div class="invalid-feedback">Please provide a name.</div> -->
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_username">ชื่อผู้ใช้</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputUsername"><i class="ti-user"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="search_username" name="search_username" placeholder="" aria-describedby="inputUsername" 
                                                value="<?php echo $search_username_val; ?>"> <!-- required -->
                                                <!-- <div class="invalid-feedback">Please choose a username.</div> -->
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="search_status">สถานะ</label>
                                            <select class="custom-select" id="search_status" name="search_status"> <!-- required -->
                                                <option value="0" <?php if($search_status_val == 0){ echo "selected"; } ?>>ทั้งหมด</option>
                                                <option value="1" <?php if($search_status_val == 1){ echo "selected"; } ?>>ออนไลน์</option>
                                                <option value="2" <?php if($search_status_val == 2){ echo "selected"; } ?>>ออฟไลน์</option>
                                            </select>
                                            <!-- <div class="invalid-feedback">Please choose a status.</div> -->
                                        </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit"><i class="ti-search"></i>&nbsp;&nbsp;ค้นหา</button>
                                    <button class="btn btn-primary" type="button" onclick="window.location.href='./?mode=employee/list'"><i class="ti-reload"></i>&nbsp;&nbsp;ล้างค่าใหม่</button>
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
                                <h4 class="card-title">ผู้ใช้งานระบบ </h4>
                                <!-- <h6 class="card-subtitle">Description</h6> -->
                                <div class="table-responsive m-t-40">
                                    <table id="employee-table" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr align="center">
                                                <th>สถานะ</th>
                                                <th>ชื่อ-สกุล</th>
                                                <th>ชื่อผู้ใช้</th>
                                                <th>รหัสผ่าน</th>
                                                <th>อีเมล์</th>
                                                <th>เบอร์โทรศัพท์</th>
                                                <th>ระดับการใช้งาน</th>
                                                <th>แก้ไข</th>
                                                <th>ลบ</th>
                                                <?php if($_SESSION["admin"]["permission"] == 1){ echo "<th>คืน</th>"; } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            // Procedural mysqli
                                            $bind_types = "";
                                            $params = array();

                                            $query = "SELECT * FROM employee WHERE id != '1'";
                                            if($_SESSION["admin"]["permission"] != 1){ $query .= " AND trash_deleted != '1'"; }
                                            if(!empty($search_name_val))
                                            { 
                                                # search name
                                                $query .= " AND name = ?"; 
                                                $bind_types .= "s";
                                                array_push($params, $search_name_val);
                                            }
                                            if(!empty($search_username_val))
                                            { 
                                                # search username
                                                $query .= " AND em_username = ?"; 
                                                $bind_types .= "s";
                                                array_push($params, $search_username_val);
                                            }
                                            if(!empty($search_status_val))
                                            { 
                                                # search status
                                                $query .= " AND status = ?";
                                                $bind_types .= "i";
                                                array_push($params, $search_status_val);
                                            }
                                            $procedural_statement = mysqli_prepare($mysqli_p, $query);

                                            // Check error query
                                            if($procedural_statement == false) {
                                                die("<pre>".mysqli_error($mysqli_p).PHP_EOL.$query."</pre>");
                                            }

                                            // mysqli_stmt_bind_param($procedural_statement, 'ss', $search_name_val, $search_username_val);
                                            if($bind_types != "")
                                            { 
                                                // call_user_func_array('mysqli_stmt_bind_param', array_merge (array($procedural_statement, $bind_types), $params));
                                                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params); 
                                            }

                                            mysqli_stmt_execute($procedural_statement);
                                            $result = mysqli_stmt_get_result($procedural_statement);
                                            $numrow = mysqli_num_rows($result);
                                            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                                            {
                                                $status_class = $row["status"] == 1 ? 'success' : 'danger';
                                                $status_txt = $row["status"] == 1 ? 'ออนไลน์' : 'ออฟไลน์';
                                        ?>
                                            <tr>
                                                <td align="center"><span class="label label-<?php echo $status_class; ?>"><?php echo $status_txt; ?></span></td>
                                                <td><?php echo $row["name"]; ?></td>
                                                <td><?php echo $row["em_username"]; ?></td>
                                                <td><?php echo $row["em_password"]; ?></td>
                                                <td><?php echo $row["em_email"]; ?></td>
                                                <td><?php echo $row["em_phone"]; ?></td>
                                                <td align="center"><span class="label label-info">
                                                    <?php echo get_value("employee_permission","id","name",$row["permission"],$mysqli_p); ?></span></td>
                                                <td align="center"><a href="./?mode=employee/detail&id=<?php echo $row["id"]; ?>"><i class="fas fa-edit"></i></a></td>
                                                <td align="center"><a href="#deleted" onclick="deleteList(<?php echo $row['id']; ?>);"><i class="fas fa-trash-alt" style="color:#FF0000"></i></a></td>

                                                <?php if($_SESSION["admin"]["permission"] == 1){ ?>
                                                <td align="center">
                                                    <?php if($row["trash_deleted"] == 1){ ?>
                                                        <a href="#restore" onclick="restoreList(<?php echo $row['id']; ?>);"><i class="ti-reload" style="color:#0CDE66"></i></a><?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                        <?php
                                            } /* while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ */
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