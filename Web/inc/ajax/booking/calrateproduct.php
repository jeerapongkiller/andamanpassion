<?php
require("../../../inc/connection.php");

if (!empty($_POST["ptype"]) && !empty($_POST["catethird"])) {
    $price_sum = 0;
    $bp_price_default = 0;
    $bp_price_latest = 0;
    $rate_2 = 0;
    $rate_4 = 0;
    $charter_2 = 0;
    $group_2 = 0;
    $transfer_2 = 0;
    $extra_hour_2 = 0;
    $extrabeds_2 = 0;
    $sharingbed_2 = 0;

    if (!empty($_POST["agent"]) && $_POST["agent"] > 0 && ($_POST["ptype"] == 1 || $_POST["ptype"] == 3)) {
        $query_cate = "SELECT C.id as combineID, T.id as thirdID, T.periods_from as thirdFrom, T.periods_to as thirdTo, 
                                T.pax as pax, C.rate_2 as rate_2, C.rate_4 as rate_4, C.transfer_2 as transfer_2, 
                                C.charter_2 as charter_2, C.group_2 as group_2, C.extra_hour_2 as extra_hour_2, T.hours_no as hours_no, 
                                C.extrabeds_2 as extrabeds_2, C.sharingbed_2 as sharingbed_2
                        FROM products_category_third_combine C 
                        LEFT JOIN products_category_third T ON C.products_category_third = T.id
                        WHERE C.id = ? 
                            ORDER BY T.periods_from ASC
        ";
    } else {
        $query_cate = "SELECT * 
                        FROM products_category_third 
                        WHERE id = ?
        ";
        $query_cate .= " ORDER BY periods_from ASC";
    }

    $procedural_statement = mysqli_prepare($mysqli_p, $query_cate);
    mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["catethird"]);
    mysqli_stmt_execute($procedural_statement);
    $result_cate = mysqli_stmt_get_result($procedural_statement);
    $numrow = mysqli_num_rows($result_cate);

    // echo $query_cate;
    // echo $_POST["catethird"];

    if ($numrow > 0) {
        $row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC);

        $pax_mix = $_POST["bp_adults"] + $_POST["bp_children"];
        $check_foc = $_POST["bp_adults"] == '0' && $_POST["bp_children"] == '0' && $_POST["bp_foc"] > '0' ? 'True' : 'False' ;
        if ($_POST["ptype"] == 1 || $_POST["ptype"] == 2) {
            if ($row_cate["pax"] > 0) {
                if ($_POST["ptype"] == 1) {
                    $rateforpax = $row_cate["charter_2"];
                    $charter_2 = $row_cate["charter_2"];
                } else {
                    $rateforpax = $row_cate["group_2"];
                    $group_2 = $row_cate["group_2"];
                }
                if ($pax_mix > $row_cate["pax"]) {
                    $pax_profuse = $pax_mix - $row_cate["pax"];
                    if ($_POST["bp_transfer"] == "true") {
                        $price_sum = (($row_cate["rate_2"] * $pax_profuse) + ($row_cate["transfer_2"] * $pax_mix)) + $rateforpax;
                        $transfer_2 = $row_cate["transfer_2"];
                        $rate_2 = $row_cate["rate_2"];
                    } else {
                        $price_sum = ($row_cate["rate_2"] * $pax_profuse) + $rateforpax;
                        $rate_2 = $row_cate["rate_2"];
                    }
                } else {
                    if ($_POST["bp_transfer"] == "true") {
                        $price_sum = ($row_cate["transfer_2"] * $pax_mix) + $rateforpax;
                        $transfer_2 = $row_cate["transfer_2"];
                    } else {
                        $price_sum = $rateforpax;
                    }
                }
            } else {
                $price_adults = $row_cate["rate_2"] * $_POST["bp_adults"];
                $price_children = $row_cate["rate_4"] * $_POST["bp_children"];
                $rate_2 = $row_cate["rate_2"];
                $rate_4 = $_POST["bp_children"] != 0 ? $row_cate["rate_4"] : 0;
                if ($_POST["bp_transfer"] == "true") {
                    $price_sum = ($price_adults + $price_children) + ($row_cate["transfer_2"] * $pax_mix);
                    $transfer_2 = $row_cate["transfer_2"];
                } else {
                    $price_sum = $price_adults + $price_children;
                }
            }
            $price_sum = $pax_mix == '0' ? '0' : $price_sum ;
            $price_sum = ($_POST["bp_foreigner"] == "true") ? $price_sum = $price_sum + $_POST["bp_foreigner_price"] : $price_sum;
        } elseif ($_POST["ptype"] == 3) {
            if ($row_cate["hours_no"] > 0) {
                if ($_POST["bp_no_hours"] > $row_cate["hours_no"]) {
                    $hours_profuse = $_POST["bp_no_hours"] - $row_cate["hours_no"];
                    if ($hours_profuse > 0) {
                        $price_sum = (($row_cate["extra_hour_2"] * $hours_profuse) + $row_cate["rate_2"]) * $_POST["bp_no_cars"];
                        $rate_2 = $row_cate["rate_2"];
                        $extra_hour_2 = $row_cate["extra_hour_2"];
                    } else {
                        $price_sum = $row_cate["rate_2"] * $_POST["bp_no_cars"];
                        $rate_2 = $row_cate["rate_2"];
                    }
                } else {
                    $price_sum = $row_cate["rate_2"] * $_POST["bp_no_cars"];
                    $rate_2 = $row_cate["rate_2"];
                }
                $hours_no = $row_cate["hours_no"] != 0 ? $row_cate["hours_no"] : 0 ;
            } else {
                $rate_2 = $row_cate["rate_2"];
                $price_sum = $_POST["bp_no_cars"] * $row_cate["rate_2"];
            }
        } elseif ($_POST["ptype"] == 4) {
            $nodate = 0;
            $nodate = ceil(DateDiff($_POST["bp_checkin_date"], $_POST["bp_checkout_date"]));
            $price_sum = (($_POST["bp_no_rooms"] * $row_cate["rate_2"]) + (($_POST["bp_extra_beds"] * $row_cate["extrabeds_2"]) + ($_POST["bp_share_bed"] * $row_cate["sharingbed_2"]))) * $nodate;
            $rate_2 = $row_cate["rate_2"];
            $extrabeds_2 = $_POST["bp_extra_beds"] != 0 ? $row_cate["extrabeds_2"] : 0 ;
            $sharingbed_2 = $_POST["bp_share_bed"] != 0 ? $row_cate["sharingbed_2"] : 0 ;
        }

        $pax = $row_cate["pax"] != 0 ? $row_cate["pax"] : 0 ;
        $bp_price_default = $price_sum ;
        $bp_price_latest = ($_POST["bp_id"] > 0) ? $_POST["price_latest_before"] : $price_sum;
        $hours_no = !empty($hours_no) ? $hours_no : '0' ;

        #--- Check FOC ----#
        if($check_foc == 'True'){
            $rate_2 = '0';
            $rate_4 = '0';
            $charter_2 = '0';
            $group_2 = '0';
            $transfer_2 = '0';
            $extra_hour_2 = '0';
            $extrabeds_2 = '0';
            $sharingbed_2 = '0';
            $pax = '0';
            $bp_price_latest = '0';
            $bp_price_default = '0';
            $hours_no = '0';
        }
    }
?>
    <input type="hidden" name="bp_rate_2" value="<?php echo $rate_2; ?>">
    <input type="hidden" name="bp_rate_4" value="<?php echo $rate_4; ?>">
    <input type="hidden" name="bp_charter_2" value="<?php echo $charter_2; ?>">
    <input type="hidden" name="bp_group_2" value="<?php echo $group_2; ?>">
    <input type="hidden" name="bp_transfer_2" value="<?php echo $transfer_2; ?>">
    <input type="hidden" name="bp_extra_hour_2" value="<?php echo $extra_hour_2; ?>">
    <input type="hidden" name="bp_extrabeds_2" value="<?php echo $extrabeds_2; ?>">
    <input type="hidden" name="bp_sharingbed_2" value="<?php echo $sharingbed_2; ?>">
    <input type="hidden" name="bp_pax" value="<?php echo $pax; ?>">
    <input type="hidden" name="bp_hours_no" value="<?php echo $hours_no; ?>">

    <div class="col-md-2 mb-3" style="text-align:center">
        <label for="bp_price_default">ราคาตั้งต้น (<span style="color:#FF0000">บาท</span>)</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputPricedefault"><i class="ti-wallet"></i></span>
            </div>
            <input type="text" class="form-control" id="bp_price_default" name="bp_price_default" placeholder="" aria-describedby="inputPricedefault" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($bp_price_default); ?>" oninput="priceformat('bp_price_default');" readonly>
            <div class="invalid-feedback">กรุณาระบุราคาตั้งต้น</div>
        </div>
    </div>
    <div class="col-md-2 mb-3" style="text-align:center">
        <label for="bp_price_latest">ราคาสำหรับแก้ไข (<span style="color:#FF0000">บาท</span>)</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputPricelatest"><i class="ti-wallet"></i></span>
            </div>
            <input type="text" class="form-control" id="bp_price_latest" name="bp_price_latest" placeholder="" aria-describedby="inputPricelatest" pattern="^[0-9,]+$" autocomplete="off" value="<?php echo number_format($bp_price_latest); ?>" oninput="priceformat('bp_price_latest');" required>
            <div class="invalid-feedback">กรุณาระบุราคาสำหรับแก้ไข</div>
        </div>
    </div>

<?php
} else {
?>
    <div class="col-md-12 mb-3" style="text-align:center">
        <span style="font-size:16px; font-weight:bold; color:#FF0000; margin-left: auto; margin-right: auto">กรุณาเลือกสินค้าก่อน</span>
    </div>
<?php
}
?>