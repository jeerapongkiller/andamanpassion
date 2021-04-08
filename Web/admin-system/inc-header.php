        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="./?mode=booking/list">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="../assets/images/logo-icon.png" alt="<?php echo $default_name; ?> - Management System" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="../assets/images/logo-light-icon.png" alt="<?php echo $default_name; ?> - Management System" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="hidden-sm-down">
                            <!-- text -->
                            <b><?php echo $default_name; ?></b>
                            <!-- dark Logo text -->
                            <!-- <img src="../assets/images/logo-text.png" alt="<?php echo $default_name; ?> - Management System" class="dark-logo" /> -->
                            <!-- Light Logo text -->
                            <!-- <img src="../assets/images/logo-light-text.png" alt="<?php echo $default_name; ?> - Management System" class="light-logo" /> -->
                        </span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                                href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none waves-effect waves-dark"
                                href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control" placeholder="Search & enter">
                            </form>
                        </li> -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- User Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown u-pro">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="../assets/images/users/1.jpg" alt="user" class=""> <span
                                    class="hidden-md-down"><?php echo $_SESSION["admin"]["name"]; ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <a href="./?mode=employee/detail&id=<?php echo $_SESSION["admin"]["id"]; ?>" class="dropdown-item"><i class="ti-user"></i> ข้อมูลผู้ใช้</a>
                                <div class="dropdown-divider"></div>
                                <a href="./" class="dropdown-item"><i class="fa fa-power-off"></i> ออกจากระบบ</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End User Profile -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="user-pro"> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user-img"
                                    class="img-circle"><span class="hide-menu"><?php echo $_SESSION["admin"]["name"]; ?></span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="./?mode=employee/detail&id=<?php echo $_SESSION["admin"]["id"]; ?>"><i class="ti-user"></i> ข้อมูลผู้ใช้</a></li>
                                <li><a href="./"><i class="fa fa-power-off"></i> ออกจากระบบ</a></li>
                            </ul>
                        </li>
                        <li class="nav-small-cap">--- เมนู</li>
                        <li <?php if(strstr($_GET["mode"], "booking/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=booking/list"
                                aria-expanded="false"><i class="ti-shopping-cart"></i><span class="hide-menu">การจอง</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "supplier/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=supplier/list"
                                aria-expanded="false"><i class="ti-truck"></i><span class="hide-menu">ซัพพลายเออร์</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "agent/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=agent/list"
                                aria-expanded="false"><i class="ti-briefcase"></i><span class="hide-menu">เอเย่นต์</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "operator/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=operator/list"
                                aria-expanded="false"><i class="ti-clipboard"></i><span class="hide-menu">โอเปอเรเตอร์</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "ar/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=ar/list"
                                aria-expanded="false"><i class="ti-clipboard"></i><span class="hide-menu">AR (สร้างใบแจ้งหนี้)</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "invoice/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=invoice/list"
                                aria-expanded="false"><i class="ti-clipboard"></i><span class="hide-menu">ใบแจ้งหนี้</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "bill/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=bill/list"
                                aria-expanded="false"><i class="ti-clipboard"></i><span class="hide-menu">ใบวางบิล</span></a>
                                <div class="notify" id="checkduedate" style="z-index: 99999;">  </div>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "place/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=place/list"
                                aria-expanded="false"><i class="ti-flag"></i><span class="hide-menu">สถานที่รับ-ส่ง</span></a>
                        </li>
                        <li <?php if(strstr($_GET["mode"], "employee/")){ echo "class=\"active\""; } ?>> <a class="waves-effect waves-dark" href="./?mode=employee/list"
                                aria-expanded="false"><i class="ti-user"></i><span class="hide-menu">ผู้ใช้งานระบบ</span></a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->