<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title><?php echo $default_name; ?> - เข้าสู่ระบบ</title>
    
    <!-- page css -->
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!--alerts CSS -->
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php echo $default_name; ?></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register" style="background-image:url(../assets/images/background/bodybg.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" method="post" id="frmlogin" name="frmlogin" action="./index.php">
                        <input class="form-control" type="hidden" id="mode" name="mode" value="authentication/checklogin">
                        <h3 class="text-center m-b-20">ลงชื่อเข้าใช้</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" style="text-align:center" type="text" placeholder="ชื่อผู้ใช้" id="em_username" name="em_username" required> </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" style="text-align:center" type="password" placeholder="รหัสผ่าน" id="em_password" name="em_password" required> </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" style="text-align:center" type="text" placeholder="รหัสตรวจสอบความถูกต้อง" id="em_code" name="em_code" required> </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">เข้าสู่ระบบ</button>
                            </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                © <?php echo (date("Y") > "2019") ? '2019 - ' : ''; ?><?php echo date("Y"); ?> Develop by <a href="https://phuketsolution.com" target="_blank">Phuket Solution</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    
    <!-- ============================================================== -->
    <!-- Error message -->
    <!-- ============================================================== -->
    <?php
        if(!empty($_GET['message'])){
            switch($_GET['message'])
            {
                case "error-login" : 
                    $message_type = "error";
                    $message_title = "กรุณาระบุข้อมูลให้ถูกต้อง";
                    break;
                case "error" :
                    $message_type = "error";
                    $message_title = "ลองใหม่อีกครั้ง!";
                    break;
                case "success" :
                    $message_type = "success";
                    $message_title = "เสร็จสิ้น!";
                    break;
            }
    ?>
            <script type="text/javascript">
                Swal.fire({
                    // position: 'top-end',
                    type: '<?php echo $message_type; ?>',
                    // title: '<?php echo $message_title; ?>',
                    text: '<?php echo $message_title; ?>',
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
    <?php
        } /* if(!empty($_GET['message'])){ */
    ?>

    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
    
</body>

</html>