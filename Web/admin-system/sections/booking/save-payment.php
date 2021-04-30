<?php
$pm_id = !empty($_POST["pm_id"]) ? $_POST["pm_id"] : '';
$page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
$pm_booking = !empty($_POST["pm_booking"]) ? $_POST["pm_booking"] : '';
$pm_status = !empty($_POST["pm_status"]) ? $_POST["pm_status"] : '1';
$pm_receip_no = !empty($_POST["pm_receip_no"]) ? $_POST["pm_receip_no"] : '';
$pm_date_paid = !empty($_POST["pm_date_paid"]) ? $_POST["pm_date_paid"] : $today;
$pm_accounts = !empty($_POST["pm_accounts"]) ? $_POST["pm_accounts"] : '';
$pm_booking_products = !empty($_POST["pm_booking_products"]) ? $_POST["pm_booking_products"] : '';
$pm_amount_paid = !empty($_POST["pm_amount_paid"]) ? preg_replace('(,)', '', $_POST["pm_amount_paid"]) : 0;
$pm_receiver_name = !empty($_POST["pm_receiver_name"]) ? $_POST["pm_receiver_name"] : '';

$return_url = !empty($pm_id) ? '&booking=' . $pm_booking : '';
$message_alert = "error";

if (!empty($pm_booking)) {
    if (empty($pm_id)) {
        # ---- Insert to database ---- #
        $query = "INSERT INTO payments_booking (status, booking, booking_products, accounts, bank, date_paid, receip_no, amount_paid, receiver_name, bank_name, bank_no, photo1, trash_deleted, last_edit_time)";
        $query .= "VALUES (1, 0, 0, 0, 0, '', '', 0, '', '', '', '', 2, now())";
        $result = mysqli_query($mysqli_p, $query);
        $pm_id = mysqli_insert_id($mysqli_p);
    }

    if (!empty($pm_id)) {
        # ---- Upload Photo ---- #
        $photo_count = !empty($_POST["photo_count"]) ? $_POST["photo_count"] : 0;
        $uploaddir = "../inc/photo/booking/";
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

        $query = "UPDATE payments_booking SET";

        $query .= " status = ?,";
        $bind_types .= "i";
        array_push($params, $pm_status);

        $query .= " booking = ?,";
        $bind_types .= "i";
        array_push($params, $pm_booking);

        $query .= " booking_products = ?,";
        $bind_types .= "i";
        array_push($params, $pm_booking_products);

        $query .= " accounts = ?,";
        $bind_types .= "s";
        array_push($params, $pm_accounts);

        $query .= " date_paid = ?,";
        $bind_types .= "s";
        array_push($params, $pm_date_paid);

        $query .= " receip_no = ?,";
        $bind_types .= "s";
        array_push($params, $pm_receip_no);

        $query .= " amount_paid = ?,";
        $bind_types .= "s";
        array_push($params, $pm_amount_paid);

        $query .= " receiver_name = ?,";
        $bind_types .= "s";
        array_push($params, $pm_receiver_name);

        foreach ($photo as $i => $item) {
            if ($item != "false") {
                $photo_field = "photo" . $i;
                $query .= " " . $photo_field . " = ?,";
                $bind_types .= "s";
                array_push($params, $item);
            }
        }

        $query .= " last_edit_time = now()";
        $query .= " WHERE id = '$pm_id'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        mysqli_close($mysqli_p);

        $return_url = "&booking=" . $pm_booking . "&id=" . $pm_id;
        $message_alert = "success";
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/payment-detail" . $return_url . "&message=" . $message_alert . "'\" >";
    }
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/payment-detail" . $return_url . "&message=" . $message_alert . "'\" >";
}
