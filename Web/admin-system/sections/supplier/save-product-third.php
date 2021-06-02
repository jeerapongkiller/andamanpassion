<?php
$sp_id = !empty($_POST["sp_id"]) ? $_POST["sp_id"] : '';
$ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
$first_id = !empty($_POST["first_id"]) ? $_POST["first_id"] : '';
$second_id = !empty($_POST["second_id"]) ? $_POST["second_id"] : '';
$third_id = !empty($_POST["third_id"]) ? $_POST["third_id"] : '';
$third_status = !empty($_POST["third_status"]) ? $_POST["third_status"] : '2';
$third_periods_from = !empty($_POST["third_periods_from"]) ? $_POST["third_periods_from"] : $today;
$third_periods_to = !empty($_POST["third_periods_to"]) ? $_POST["third_periods_to"] : $today;
$third_rate_1 = !empty($_POST["third_rate_1"]) ? preg_replace('(,)', '', $_POST["third_rate_1"]) : '0';
$third_rate_2 = !empty($_POST["third_rate_2"]) ? preg_replace('(,)', '', $_POST["third_rate_2"]) : '0';
$third_rate_3 = !empty($_POST["third_rate_3"]) ? preg_replace('(,)', '', $_POST["third_rate_3"]) : '0';
$third_rate_4 = !empty($_POST["third_rate_4"]) ? preg_replace('(,)', '', $_POST["third_rate_4"]) : '0';
$third_charter_1 = !empty($_POST["third_charter_1"]) ? preg_replace('(,)', '', $_POST["third_charter_1"]) : '0';
$third_charter_2 = !empty($_POST["third_charter_2"]) ? preg_replace('(,)', '', $_POST["third_charter_2"]) : '0';
$third_group_1 = !empty($_POST["third_group_1"]) ? preg_replace('(,)', '', $_POST["third_group_1"]) : '0';
$third_group_2 = !empty($_POST["third_group_2"]) ? preg_replace('(,)', '', $_POST["third_group_2"]) : '0';
$third_transfer_1 = !empty($_POST["third_transfer_1"]) ? preg_replace('(,)', '', $_POST["third_transfer_1"]) : '0';
$third_transfer_2 = !empty($_POST["third_transfer_2"]) ? preg_replace('(,)', '', $_POST["third_transfer_2"]) : '0';
$third_pax = !empty($_POST["third_pax"]) ? preg_replace('(,)', '', $_POST["third_pax"]) : '0';
$third_hours_no = !empty($_POST["third_hours_no"]) ? preg_replace('(,)', '', $_POST["third_hours_no"]) : '0';
$third_extra_hour_1 = !empty($_POST["third_extra_hour_1"]) ? preg_replace('(,)', '', $_POST["third_extra_hour_1"]) : '0';
$third_extra_hour_2 = !empty($_POST["third_extra_hour_2"]) ? preg_replace('(,)', '', $_POST["third_extra_hour_2"]) : '0';
$third_extrabeds_1 = !empty($_POST["third_extrabeds_1"]) ? preg_replace('(,)', '', $_POST["third_extrabeds_1"]) : '0';
$third_extrabeds_2 = !empty($_POST["third_extrabeds_2"]) ? preg_replace('(,)', '', $_POST["third_extrabeds_2"]) : '0';
$third_extrabeds_3 = !empty($_POST["third_extrabeds_3"]) ? preg_replace('(,)', '', $_POST["third_extrabeds_3"]) : '0';
$third_extrabeds_4 = !empty($_POST["third_extrabeds_4"]) ? preg_replace('(,)', '', $_POST["third_extrabeds_4"]) : '0';
$third_sharingbed_1 = !empty($_POST["third_sharingbed_1"]) ? preg_replace('(,)', '', $_POST["third_sharingbed_1"]) : '0';
$third_sharingbed_2 = !empty($_POST["third_sharingbed_2"]) ? preg_replace('(,)', '', $_POST["third_sharingbed_2"]) : '0';
$third_season = !empty($_POST["third_season"]) ? preg_replace('(,)', '', $_POST["third_season"]) : '0';

$page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
$trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
// $return_url = !empty($first_id) ? '&id='.$first_id : '';
$message_alert = "error";

if (!empty($third_periods_from) && !empty($third_periods_to) && !empty($sp_id) && !empty($ptype) && !empty($first_id) && !empty($second_id)) {
    if (empty($third_id)) {
        # ---- Insert to database ---- #
        $query = "INSERT INTO products_category_third (status, products_category_first, products_category_second, periods_from, periods_to, rate_1, rate_2, rate_3, rate_4, charter_1, charter_2, group_1, group_2, transfer_1, transfer_2, pax, extra_hour_1, extra_hour_2, hours_no, extrabeds_1, extrabeds_2, extrabeds_3, extrabeds_4, sharingbed_1, sharingbed_2, season, trash_deleted, last_edit_time) ";
        $query .= "VALUES (0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, now())";
        $result = mysqli_query($mysqli_p, $query);
        $third_id = mysqli_insert_id($mysqli_p);
    }

    if (!empty($third_id)) {
        # ---- Upload Photo ---- #
        $photo_count = !empty($_POST["photo_count"]) ? $_POST["photo_count"] : 0;
        $uploaddir = "../inc/photo/supplier/";
        $photo_time = time();
        $photo = array();
        $photo_name = array();
        $tmp_photo = array();
        $del_photo = array();
        $params = array();

        for ($i = 1; $i <= $photo_count; $i++) {
            $paramiter = "_" . $i;
            $photo[$i] = !empty($_FILES["photo" . $i]["tmp_name"]) ? $_FILES["photo" . $i]["tmp_name"] : '';
            $photo_name[$i] = !empty($_FILES["photo" . $i]["name"]) ? $_FILES["photo" . $i]["name"] : '';
            $tmp_photo[$i] = !empty($_POST["tmp_photo" . $i]) ? $_POST["tmp_photo" . $i] : '';
            $del_photo[$i] = !empty($_POST["del_photo" . $i]) ? $_POST["del_photo" . $i] : '';
            // echo $photo[$i]." - ".$photo_name[$i]." - ".$tmp_photo[$i]." - ".$del_photo[$i]."<br />";

            if (!empty($del_photo[$i])) {
                unlink($uploaddir . $tmp_photo[$i]);
                $photo[$i] = "";
            } else {
                $photo[$i] = !empty($photo[$i]) ? img_upload($photo[$i], $photo_name[$i], $tmp_photo[$i], $uploaddir, $photo_time, $paramiter) : $tmp_photo[$i];
            }

            // echo $photo[$i]."<br />";
        }

        # ---- Update to database ---- #
        $bind_types = "";
        $params = array();

        $query = "UPDATE products_category_third SET";

        $query .= " status = ?,";
        $bind_types .= "i";
        array_push($params, $third_status);

        $query .= " products_category_first = ?,";
        $bind_types .= "i";
        array_push($params, $first_id);

        $query .= " products_category_second = ?,";
        $bind_types .= "i";
        array_push($params, $second_id);

        $query .= " periods_from = ?,";
        $bind_types .= "s";
        array_push($params, $third_periods_from);

        $query .= " periods_to = ?,";
        $bind_types .= "s";
        array_push($params, $third_periods_to);

        $query .= " rate_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_rate_1);

        $query .= " rate_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_rate_2);

        $query .= " rate_3 = ?,";
        $bind_types .= "d";
        array_push($params, $third_rate_3);

        $query .= " rate_4 = ?,";
        $bind_types .= "d";
        array_push($params, $third_rate_4);

        $query .= " charter_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_charter_1);

        $query .= " charter_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_charter_2);

        $query .= " group_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_group_1);

        $query .= " group_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_group_2);

        $query .= " transfer_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_transfer_1);

        $query .= " transfer_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_transfer_2);

        $query .= " pax = ?,";
        $bind_types .= "i";
        array_push($params, $third_pax);

        $query .= " hours_no = ?,";
        $bind_types .= "i";
        array_push($params, $third_hours_no);

        $query .= " extra_hour_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extra_hour_1);

        $query .= " extra_hour_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extra_hour_2);

        $query .= " extrabeds_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extrabeds_1);

        $query .= " extrabeds_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extrabeds_2);

        $query .= " extrabeds_3 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extrabeds_3);

        $query .= " extrabeds_4 = ?,";
        $bind_types .= "d";
        array_push($params, $third_extrabeds_4);

        $query .= " sharingbed_1 = ?,";
        $bind_types .= "d";
        array_push($params, $third_sharingbed_1);

        $query .= " sharingbed_2 = ?,";
        $bind_types .= "d";
        array_push($params, $third_sharingbed_2);

        $query .= " season = ?,";
        $bind_types .= "d";
        array_push($params, $third_season);

        foreach ($photo as $i => $item) {
            if ($item != "false") {
                $photo_field = "photo" . $i;
                $query .= " " . $photo_field . " = ?,";
                $bind_types .= "s";
                array_push($params, $item);
            }
        }

        // $query .= ($page_title == "เพิ่มข้อมูล") ? ' create_date = now(),' : '';

        $query .= " last_edit_time = now()";
        $query .= " WHERE id = '$third_id'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        mysqli_close($mysqli_p);

        $return_url = "&id=" . $third_id . "&ptype=" . $ptype . "&supplier=" . $sp_id . "&catefirst=" . $first_id . "&catesecond=" . $second_id;
        $message_alert = "success";
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/product-third-detail" . $return_url . "&message=" . $message_alert . "'\" >";
    }
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=supplier/list&message=" . $message_alert . "'\" >";
}
