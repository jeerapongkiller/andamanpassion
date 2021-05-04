<?php
require "../inc/connection.php";

if (!empty($_GET["mode"]) && !empty($_SESSION["admin"]["id"])) {
?>

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
        <title><?php echo $default_name; ?> - Management System</title>
        <link rel="stylesheet" type="text/css" href="../assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
        <link rel="stylesheet" type="text/css" href="../assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="../assets/node_modules/dropify/dist/css/dropify.min.css">
        <!--alerts CSS -->
        <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <!-- Dropzone css -->
        <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />

        <link href="../assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
        <!-- Page plugins css -->
        <link href="../assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
        <!-- Color picker plugins css -->
        <link href="../assets/node_modules/jquery-asColorPicker-master/dist/css/asColorPicker.css" rel="stylesheet">
        <!-- Date picker plugins css -->
        <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker plugins css -->
        <link href="../assets/node_modules/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="../assets/node_modules/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="dist/css/style.min.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            a.disabled {
                pointer-events: none;
                color: #ccc;
            }
        </style>
    </head>

    <body class="horizontal-nav skin-megna fixed-layout">
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
        <div id="main-wrapper">

            <?php
            # Header ------------- #
            include "inc-header.php";
            ?>

            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">
                <?php
                # Mode --------------- #
                include "sections/" . $_GET["mode"] . ".php";
                ?>
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->

            <?php
            # Footer ------------- #
            include "inc-footer.php";
            ?>

        </div>
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
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <!--Wave Effects -->
        <script src="dist/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <!-- Dropzone Plugin JavaScript -->
        <script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>
        <!--Custom JavaScript -->
        <script src="dist/js/custom.min.js"></script>
        <!-- jQuery file upload -->
        <script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
        <!-- Sweet-Alert  -->
        <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
        <!-- This is data table -->
        <script src="../assets/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
        <!-- start - This is for export functionality only -->
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
        <!-- end - This is for export functionality only -->

        <!-- ============================================================== -->
        <!-- Plugins for this page -->
        <!-- ============================================================== -->
        <!-- Plugin JavaScript -->
        <script src="../assets/node_modules/moment/moment.js"></script>
        <script src="../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <!-- Clock Plugin JavaScript -->
        <script src="../assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
        <!-- Color Picker Plugin JavaScript -->
        <script src="../assets/node_modules/jquery-asColor/dist/jquery-asColor.js"></script>
        <script src="../assets/node_modules/jquery-asGradient/dist/jquery-asGradient.js"></script>
        <script src="../assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
        <!-- Date Picker Plugin JavaScript -->
        <script src="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
        <!-- Date range Plugin JavaScript -->
        <script src="../assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>

        <script>
            // MAterial Date picker
            $('#first_validity_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#first_validity_from').val()
            });
            $('#first_validity_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#first_validity_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('first_validity_to').value = $('#first_validity_from').val();
            });

            $('#third_periods_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#third_periods_from').val()
            });
            $('#third_periods_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#third_periods_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('third_periods_to').value = $('#third_periods_from').val();
            });

            $('#search_booking_date_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#search_booking_date_from').val()
            });
            $('#search_booking_date_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#search_booking_date_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('search_booking_date_to').value = $('#search_booking_date_from').val();
            });

            $('#search_travel_date_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#search_travel_date_from').val()
            });
            $('#search_travel_date_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#search_travel_date_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('search_travel_date_to').value = $('#search_travel_date_from').val();
            });
            $('#bp_travel_date').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            });

            // Workshop car
            $('#search_travel_date_car').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            });

            // Payment detail Date paid car
            $('#pm_date_paid').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            });

            // Date Form Bill Page
            $('#search_bi_date_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#search_bi_date_from').val()
            });
            $('#search_bi_date_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#search_bi_date_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('search_bi_date_to').value = $('#search_bi_date_from').val();
            });
            $('#search_due_date_to').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false,
                minDate: $('#search_due_date_from').val()
            });
            $('#search_due_date_from').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD',
                weekStart: 0,
                time: false
            }).on('change', function(e, date) {
                $('#search_due_date_to').bootstrapMaterialDatePicker('setMinDate', date);
                document.getElementById('search_due_date_to').value = $('#search_due_date_from').val();
            });

            var str_mode = "<?php echo $_GET["mode"] ?>";
            if (str_mode.indexOf("booking/product-detail") >= 0) {
                var bp_id = $('#bp_id').val();
                var bp_checkout_date = $('#bp_checkout_date').val();
                var checkinDate = new Date($('#bp_checkin_date').val());
                checkinDate = new Date(checkinDate.getFullYear(), checkinDate.getMonth(), checkinDate.getDate() + 1);
                var newDate = checkinDate.toLocaleDateString('fr-CA');
                if (bp_id > 0) {
                    document.getElementById('bp_checkout_date').value = bp_checkout_date;
                } else {
                    document.getElementById('bp_checkout_date').value = newDate;
                }

                $('#bp_checkout_date').bootstrapMaterialDatePicker({
                    format: 'YYYY-MM-DD',
                    weekStart: 0,
                    time: false,
                    minDate: newDate
                });
                $('#bp_checkin_date').bootstrapMaterialDatePicker({
                    format: 'YYYY-MM-DD',
                    weekStart: 0,
                    time: false
                }).on('change', function(e) {
                    var checkinDate = new Date($('#bp_checkin_date').val());
                    checkinDate = new Date(checkinDate.getFullYear(), checkinDate.getMonth(), checkinDate.getDate() + 1);
                    var newDate = checkinDate.toLocaleDateString('fr-CA');
                    $('#bp_checkout_date').bootstrapMaterialDatePicker('setMinDate', newDate);
                    document.getElementById('bp_checkout_date').value = newDate;
                });
            }

            // $('#bp_travel_date').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD', weekStart: 0, time: false, minDate: new Date() });

            $('#timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                time: true,
                date: false
            });
            $('#date-format').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY - HH:mm'
            });

            $('#min-date').bootstrapMaterialDatePicker({
                format: 'DD/MM/YYYY HH:mm',
                minDate: new Date()
            });
            // Clock pickers
            $('#single-input').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                'default': 'now'
            });
            $('.clockpicker').clockpicker({
                donetext: 'Done',
            }).find('input').change(function() {
                console.log(this.value);
            });
            $('#check-minutes').click(function(e) {
                // Have to stop propagation here
                e.stopPropagation();
                input.clockpicker('show').clockpicker('toggleView', 'minutes');
            });
            if (/mobile/i.test(navigator.userAgent)) {
                $('input').prop('readOnly', true);
            }
            // Colorpicker
            $(".colorpicker").asColorPicker();
            $(".complex-colorpicker").asColorPicker({
                mode: 'complex'
            });
            $(".gradient-colorpicker").asColorPicker({
                mode: 'gradient'
            });
            // Date Picker
            jQuery('.mydatepicker, #datepicker').datepicker();
            jQuery('#datepicker-autoclose').datepicker({
                autoclose: true,
                todayHighlight: true
            });
            jQuery('#date-range').datepicker({
                toggleActive: true
            });
            jQuery('#datepicker-inline').datepicker({
                todayHighlight: true
            });
            // -------------------------------
            // Start Date Range Picker
            // -------------------------------

            // Basic Date Range Picker
            $('.daterange').daterangepicker();

            // Date & Time
            $('.datetime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY h:mm A'
                }
            });

            //Calendars are not linked
            $('.timeseconds').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                timePicker24Hour: true,
                timePickerSeconds: true,
                locale: {
                    format: 'MM-DD-YYYY h:mm:ss'
                }
            });

            // Single Date Range Picker
            $('.singledate').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true
            });

            // Auto Apply Date Range
            $('.autoapply').daterangepicker({
                autoApply: true,
            });

            // Calendars are not linked
            $('.linkedCalendars').daterangepicker({
                linkedCalendars: false,
            });

            // Date Limit
            $('.dateLimit').daterangepicker({
                dateLimit: {
                    days: 7
                },
            });

            // Show Dropdowns
            $('.showdropdowns').daterangepicker({
                showDropdowns: true,
            });

            // Show Week Numbers
            $('.showweeknumbers').daterangepicker({
                showWeekNumbers: true,
            });

            $('.dateranges').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            // Always Show Calendar on Ranges
            $('.shawCalRanges').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                alwaysShowCalendars: true,
            });

            // Top of the form-control open alignment
            $('.drops').daterangepicker({
                drops: "up" // up/down
            });

            // Custom button options
            $('.buttonClass').daterangepicker({
                drops: "up",
                buttonClasses: "btn",
                applyClass: "btn-info",
                cancelClass: "btn-danger"
            });

            jQuery('#date-range').datepicker({
                toggleActive: true
            });
            jQuery('#datepicker-inline').datepicker({
                todayHighlight: true
            });

            // Daterange picker
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse'
            });
            $('.input-daterange-timepicker').daterangepicker({
                timePicker: true,
                format: 'MM/DD/YYYY h:mm A',
                timePickerIncrement: 30,
                timePicker12Hour: true,
                timePickerSeconds: false,
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse'
            });
            $('.input-limit-datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: '06/01/2015',
                maxDate: '06/30/2015',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                dateLimit: {
                    days: 6
                }
            });
        </script>

        <script type="text/javascript">
            var ad_permission = <?php echo $_SESSION["admin"]["permission"] ?>;
            if (ad_permission == 1) {
                var employee_tb = [3, 5, 7, 8, 9];
                var supplier_tb = [3, 5, 6, 7];
                var agent_tb = [3, 5, 6, 7];
                var combine_tb = [3, 5, 6, 7];
            } else {
                var employee_tb = [3, 5, 7, 8];
                var supplier_tb = [3, 5, 6];
                var agent_tb = [3, 5, 6];
                var combine_tb = [3, 5, 6];
            }

            $(function() {
                // employee list - responsive table
                $('#employee-table').DataTable({
                    responsive: true,
                    "order": [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        targets: employee_tb,
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false
                });

                // supplier list - responsive table
                $('#supplier-table').DataTable({
                    responsive: true,
                    "order": [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: supplier_tb,
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false
                });

                // agent list - responsive table
                $('#agent-table').DataTable({
                    responsive: true,
                    "order": [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: agent_tb,
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false
                });

                // combine list - responsive table
                $('#combine-table').DataTable({
                    responsive: true,
                    "order": [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: combine_tb,
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false
                });

                // booking list - responsive table
                $('#booking-table').DataTable({
                    "order": [
                        [0, 'desc']
                    ],
                    columnDefs: [{
                        targets: [5, 8, 9, 10],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // booking detail - responsive table
                $('#paid-table').DataTable({
                    "order": [
                        [2, 'asc']
                    ],
                    columnDefs: [{
                        targets: [5, 6],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Tours)
                $('#booking-table-1').DataTable({
                    "order": [
                        [3, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 5, 8, 9, 10],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Activity)
                $('#booking-table-2').DataTable({
                    "order": [
                        [3, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 5, 8, 9, 10],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Transfer)
                $('#booking-table-3').DataTable({
                    "order": [
                        [3, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 5, 8, 9, 10],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Hotel)
                $('#booking-table-4').DataTable({
                    "order": [
                        [6, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 7, 8],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Bill)
                $('#booking-table-5').DataTable({
                    "order": [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        targets: [0, 3, 4, 9],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // opertor list - responsive table (Bill)
                $('#bill-table').DataTable({
                    "order": [
                        [2, 'asc']
                    ],
                    columnDefs: [{
                        targets: [3, 4, 5, 6],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // dropoff list
                $('#booking-table-7').DataTable({
                    "order": [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        targets: [0, 2, 3, 4],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // booking detail - responsive table
                $('#history-table').DataTable({
                    "order": [
                        [4, 'desc']
                    ],
                    columnDefs: [{
                        targets: [3, 5],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // ar list - responsive table
                $('#ar-table').DataTable({
                    "order": [
                        [6, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 7, 8, 9],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });

                // invoice list - responsive table
                $('#invoice-table').DataTable({
                    "order": [
                        [2, 'desc']
                    ],
                    columnDefs: [{
                        targets: [0, 9],
                        orderable: false
                    }],
                    "displayLength": 10,
                    "searching": false,
                    "scrollX": true
                });
            });

            /* ============================================================== */
            /* javascript ckeck due date bill */
            /* ============================================================== */
            function CheckDuedate() {
                jQuery.ajax({
                    url: "../inc/ajax/bill/check_due_date.php",
                    data: {
                        status: 'ture'
                    },
                    type: "POST",
                    success: function(response) {
                        $(checkduedate).html(response)
                    }
                });
            }

            /* ============================================================== */
            /* javascript ready */
            /* ============================================================== */
            $(document).ready(function() {
                var str_mode = "<?php echo $_GET["mode"] ?>";
                if (str_mode.indexOf("employee/detail") >= 0) {
                    checkUsername();
                }
                if (str_mode.indexOf("supplier/detail") >= 0 || str_mode.indexOf("agent/detail") >= 0) {
                    checkCompany();
                }
                if (str_mode.indexOf("supplier/product-third-detail") >= 0) {
                    checkPeriods();
                }
                if (str_mode.indexOf("agent/detail") >= 0) {
                    checkCompanyinvoice();
                }
                if (str_mode.indexOf("booking/detail") >= 0) {
                    var str_print = "<?php echo (!empty($_GET['payment']) && $_GET['payment'] == 'print') ? 'true' : 'false' ; ?>"
                    checkCustomertype();
                    checkVoucher();
                    checkCancel();
                    if(str_print == 'true'){
                        printPayment();
                    }
                }
                if (str_mode.indexOf("booking/payment-detail") >= 0) {
                    // checkProducts();
                }
                if (str_mode.indexOf("booking/product-detail") >= 0) {
                    inputHide();
                    checkSupplier();
                    checkHours();
                    checkdropoff('chang');
                }
                if (str_mode.indexOf("operator/list") >= 0) {
                    selectsupplier();
                }
                if (str_mode.indexOf("invoice/list") >= 0) {
                    checkCustomertype();
                }
                if (str_mode.indexOf("bill/list") >= 0) {
                    checkCustomertype();
                    checkDatetype();
                }

                // ckeck due date bill
                CheckDuedate()

                // jQuery file upload
                $('.dropify').dropify();

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element) {
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element) {
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element) {
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e) {
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });

            function openQuestion(page){
                jQuery.ajax({
                    url: "../inc/ajax/question/page.php",
                    data: {
                        page: page,
                    },
                    type: "POST",
                    success: function(response) {
                        Swal.fire({
                            type: 'question',
                            title: 'วิธีใช้งาน',
                            // text: 'Something went wrong!',
                            html: response,
                            width: '80%',
                            showConfirmButton: false,
                            showCloseButton: true
                        })
                    },
                    error: function() {}
                });
            }
        </script>

        <!-- ============================================================== -->
        <!-- Error message -->
        <!-- ============================================================== -->
        <?php
        if (!empty($_GET["message"])) {
            switch ($_GET["message"]) {
                case "error-username":
                    $message_type = "error";
                    $message_title = "กรุณาระบุข้อมูลให้ถูกต้อง";
                    break;
                case "error-same":
                    $message_type = "error";
                    $message_title = "สินค้าที่เลือกซ้ำกับสินค้าในการจองนี้ กรุณาเลือกใหม่อีกครั้ง!";
                    break;
                case "error":
                    $message_type = "error";
                    $message_title = "ทำใหม่อีกครั้ง!";
                    break;
                case "success":
                    $message_type = "success";
                    $message_title = "ทำรายการสำเร็จแล้ว";
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
                    timer: 3600
                });
            </script>
        <?php
        } /* if(!empty($_GET["message"])){ */
        ?>

    </body>

    </html>

<?php
} else {
    if (!empty($_POST["mode"]) && $_POST["mode"] == "authentication/checklogin") {
        include "sections/authentication/checklogin.php"; # go to check login page
    } else {
        session_destroy();
        mysqli_close($mysqli_p);
        include "sections/authentication/login.php"; # go to login page
    }
}
?>