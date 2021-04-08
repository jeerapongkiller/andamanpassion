<?php
include "inc-wating.php";

$booking = !empty($_POST["booking"]) ? $_POST["booking"] : '';
$bp_id = !empty($_POST["bp_id"]) ? $_POST["bp_id"] : '';
$bp_id_before = !empty($_POST["bp_id"]) ? $_POST["bp_id"] : '';
$ptype = !empty($_POST["ptype"]) ? $_POST["ptype"] : '';
$agent = !empty($_POST["agent"]) ? $_POST["agent"] : '0';
$bp_date_not_specified = !empty($_POST["bp_date_not_specified"]) ? $_POST["bp_date_not_specified"] : '2';
$bp_travel_date = !empty($_POST["bp_travel_date"]) ? $_POST["bp_travel_date"] : $today;
$bp_checkin_date = !empty($_POST["bp_checkin_date"]) ? $_POST["bp_checkin_date"] : $today;
$bp_checkout_date = !empty($_POST["bp_checkout_date"]) ? $_POST["bp_checkout_date"] : $today;

# -- Set Date 2000-01-01 (Date Min in Booking TB)
$bp_travel_date = $ptype == 4 ? $bp_travel_date = '2000-01-01' : $bp_travel_date = $bp_travel_date;
$bp_checkin_date = $ptype != 4 ? $bp_checkin_date = '2000-01-01' : $bp_checkin_date = $bp_checkin_date;
$bp_checkout_date = $ptype != 4 ? $bp_checkout_date = '2000-01-01' : $bp_checkout_date = $bp_checkout_date;

$catefirst = !empty($_POST["catefirst"]) ? $_POST["catefirst"] : '';
$catethird = !empty($_POST["catethird"]) ? $_POST["catethird"] : '';
if ($agent > 0 && ($ptype == 1 || $ptype == 3)) {
    $catesecond = get_value("products_category_third_combine", "id", "products_category_second", $catethird, $mysqli_p);
} else {
    $catesecond = get_value("products_category_third", "id", "products_category_second", $catethird, $mysqli_p);
}

// if ($bp_id > 0) {
//     $catefirst = !empty($_POST["products_category_first"]) ? $_POST["products_category_first"] : '';
//     $catesecond = !empty($_POST["products_category_second"]) ? $_POST["products_category_second"] : '';
//     if ($agent > 0 && ($ptype == 1 || $ptype == 3)) {
//         $catethird = !empty($_POST["products_category_third_combine"]) ? $_POST["products_category_third_combine"] : '';
//     } else {
//         $catethird = !empty($_POST["products_category_third"]) ? $_POST["products_category_third"] : '';
//     }
// } else {
//     $catefirst = !empty($_POST["catefirst"]) ? $_POST["catefirst"] : '';
//     $catethird = !empty($_POST["catethird"]) ? $_POST["catethird"] : '';
//     if ($agent > 0 && ($ptype == 1 || $ptype == 3)) {
//         $catesecond = get_value("products_category_third_combine", "id", "products_category_second", $catethird, $mysqli_p);
//     } else {
//         $catesecond = get_value("products_category_third", "id", "products_category_second", $catethird, $mysqli_p);
//     }
// }

$bp_adults = !empty($_POST["bp_adults"]) ? $_POST["bp_adults"] : '0';
$bp_children = !empty($_POST["bp_children"]) ? $_POST["bp_children"] : '0';
$bp_infant = !empty($_POST["bp_infant"]) ? $_POST["bp_infant"] : '0';
$bp_foc = !empty($_POST["bp_foc"]) ? $_POST["bp_foc"] : '0';
$bp_transfer = !empty($_POST["bp_transfer"]) ? $_POST["bp_transfer"] : '2';
$bp_pickup = !empty($_POST["bp_pickup"]) ? $_POST["bp_pickup"] : '0';
$bp_dropoff = !empty($_POST["bp_dropoff"]) ? $_POST["bp_dropoff"] : '0';
$bp_zones = !empty($_POST["bp_zones"]) ? $_POST["bp_zones"] : '0';
$bp_roomno = !empty($_POST["bp_roomno"]) ? $_POST["bp_roomno"] : '';
$bp_no_cars = !empty($_POST["bp_no_cars"]) ? $_POST["bp_no_cars"] : '0';
$bp_no_hours = !empty($_POST["bp_no_hours"]) ? $_POST["bp_no_hours"] : '0';
$bp_pickup_time = !empty($_POST["bp_pickup_time"]) ? $_POST["bp_pickup_time"] : $times;
$bp_dropoff_time = !empty($_POST["bp_dropoff_time"]) ? $_POST["bp_dropoff_time"] : $times;
$bp_no_rooms = !empty($_POST["bp_no_rooms"]) ? $_POST["bp_no_rooms"] : '0';
$bp_notes = !empty($_POST["bp_notes"]) ? $_POST["bp_notes"] : '';
$bp_extra_beds = !empty($_POST["bp_extra_beds"]) ? $_POST["bp_extra_beds"] : '0';
$bp_share_bed = !empty($_POST["bp_share_bed"]) ? $_POST["bp_share_bed"] : '0';
$bp_foreigner = !empty($_POST["bp_foreigner"]) ? $_POST["bp_foreigner"] : '2';
$bp_foreigner_no = !empty($_POST["bp_foreigner_no"]) ? $_POST["bp_foreigner_no"] : '0';
$bp_foreigner_price = !empty($_POST["bp_foreigner_price"]) ? preg_replace('(,)', '', $_POST["bp_foreigner_price"]) : '0';
$bp_price_default = !empty($_POST["bp_price_default"]) ? preg_replace('(,)', '', $_POST["bp_price_default"]) : '0';
$bp_price_latest = !empty($_POST["bp_price_latest"]) ? preg_replace('(,)', '', $_POST["bp_price_latest"]) : '0';
$bp_status_email = !empty($_POST["bp_status_email"]) ? $_POST["bp_status_email"] : '2';
$bp_status_confirm = !empty($_POST["bp_status_confirm"]) ? $_POST["bp_status_confirm"] : '2';
$bp_status_confirm_op = !empty($_POST["bp_status_confirm_op"]) ? $_POST["bp_status_confirm_op"] : '2';
$bp_rate_2 = !empty($_POST["bp_rate_2"]) ? $_POST["bp_rate_2"] : '0';
$bp_rate_4 = !empty($_POST["bp_rate_4"]) ? $_POST["bp_rate_4"] : '0';
$bp_charter_2 = !empty($_POST["bp_charter_2"]) ? $_POST["bp_charter_2"] : '0';
$bp_group_2 = !empty($_POST["bp_group_2"]) ? $_POST["bp_group_2"] : '0';
$bp_transfer_2 = !empty($_POST["bp_transfer_2"]) ? $_POST["bp_transfer_2"] : '0';
$bp_extra_hour_2 = !empty($_POST["bp_extra_hour_2"]) ? $_POST["bp_extra_hour_2"] : '0';
$bp_extrabeds_2 = !empty($_POST["bp_extrabeds_2"]) ? $_POST["bp_extrabeds_2"] : '0';
$bp_sharingbed_2 = !empty($_POST["bp_sharingbed_2"]) ? $_POST["bp_sharingbed_2"] : '0';
$bp_pax = !empty($_POST["bp_pax"]) ? $_POST["bp_pax"] : '0';
$bp_hours_no = !empty($_POST["bp_hours_no"]) ? $_POST["bp_hours_no"] : '0';

$page_title = !empty($_POST["page_title"]) ? $_POST["page_title"] : '';
$trash = !empty($_GET["trash"]) ? $_GET["trash"] : '';
$return_url = !empty($bp_id) ? '&ptype=' . $ptype . '&booking=' . $booking : '';
$message_alert = "error";

# ---- Check same ---- #
if ($bp_id > 0) {
    $querysame = "SELECT * FROM booking_products WHERE booking = ? AND products_category_second = ? AND id = ?";
    $procedural_statement = mysqli_prepare($mysqli_p, $querysame);
    mysqli_stmt_bind_param($procedural_statement, 'iii', $booking, $catesecond, $bp_id);
} else {
    $querysame = "SELECT * FROM booking_products WHERE booking = ? AND products_category_second = ?";
    $procedural_statement = mysqli_prepare($mysqli_p, $querysame);
    mysqli_stmt_bind_param($procedural_statement, 'ii', $booking, $catesecond);
}
mysqli_stmt_execute($procedural_statement);
$resultsame = mysqli_stmt_get_result($procedural_statement);
$rowsame = mysqli_fetch_array($resultsame, MYSQLI_ASSOC);
$numrowsame = mysqli_num_rows($resultsame);
#--- Check Edit Date Tarvel ---#
if ($bp_id > 0) { 
    if ($ptype != 4) {
       $edit_date = $rowsame["travel_date"] != $bp_travel_date ? '1' : $rowsame["edit_date"] ;
    }else{
        $edit_date = $rowsame["checkin_date"] != $bp_checkin_date || $rowsame["checkout_date"] != $bp_checkout_date  ? '1' : $rowsame["edit_date"] ;
    }
}else{
    $edit_date = '2';
 }
// if ($numrowsame > 0) {
//     if($rowsame["trash_deleted"] != 1){
//         $return_url = '&ptype=' . $ptype . '&booking=' . $booking;
//         $message_alert = "error-same";
//     }
// }

if (!empty($catefirst) && !empty($catesecond) && !empty($catethird) && $ptype > 0 && $message_alert != "error-same") {
    if (empty($bp_id)) {
        # ---- Insert to database ---- #
        $query = "INSERT INTO booking_products (products_type, booking, date_not_specified, travel_date, checkin_date, checkout_date, products_category_first, products_category_second, products_category_third, products_category_third_combine, adults, children, infant, foc, transfer, no_cars, no_hours, no_rooms, extra_beds, share_bed, pickup, pickup_time, dropoff, dropoff_time, vans, zones, roomno, foreigner, foreigner_no, foreigner_price, rate_2, rate_4, charter_2, group_2, transfer_2, extra_hour_2, extrabeds_2, sharingbed_2, price_default, price_latest, pax, hours_no, notes, payments_invoice, status_email, status_confirm, status_confirm_by, status_confirm_op, invoice, trash_deleted, create_date, last_edit_time)";
        $query .= "VALUES (0, 0, 0, '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, 0, '', 0, 0, 0, now(), now())";
        $result = mysqli_query($mysqli_p, $query);
        $bp_id = mysqli_insert_id($mysqli_p);

        # --- booking confirm and email ---#
        $num_status = '2';
        $query = "UPDATE booking SET status_confirm = ? , status_email = ? WHERE id = ?";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        mysqli_stmt_bind_param($procedural_statement, 'iii', $num_status, $num_status, $booking);
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
    }

    if (!empty($bp_id)) {
        function get_client_ip()
        {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        $ip = get_client_ip();
        $description_field = "";

        $bp_date_not_specified_text = ($bp_date_not_specified != 2) ? 'ใช่' : 'ไม่ใช่';

        $bp_adults_text = ($bp_adults > 0) ? $bp_adults : '-';
        $bp_children_text = ($bp_children > 0) ? $bp_children : '-';
        $bp_infant_text = ($bp_infant > 0) ? $bp_infant : '-';
        $bp_foc_text = ($bp_foc > 0) ? $bp_foc : '-';
        $bp_transfer_text = ($bp_transfer != 2) ? 'ใช่' : 'ไม่ใช่';
        $bp_pickup_text = !empty($bp_pickup) ? get_value("place", "id", "name", $bp_pickup, $mysqli_p) : 'N/A';
        $bp_dropoff_text = ($bp_dropoff > 0) ? get_value("place", "id", "name", $bp_dropoff, $mysqli_p) : 'N/A';
        $bp_zones_text = ($bp_zones > 0) ? get_value("zones", "id", "name", $bp_zones, $mysqli_p) : '-';
        $bp_roomno_text = !empty($bp_roomno) ? $bp_roomno : '-';
        $bp_no_cars_text = ($bp_no_cars > 0) ? $bp_no_cars : '-';
        $bp_no_hours_text = ($bp_no_hours > 0) ? $bp_no_hours : '-';
        $bp_pickup_time_text = !empty($bp_pickup_time) ? $bp_pickup_time : '-';
        $bp_dropoff_time_text = !empty($bp_dropoff_time) ? $bp_dropoff_time : '-';
        $bp_no_rooms_text = ($bp_no_rooms > 0) ? $bp_no_rooms : '-';
        $bp_extra_beds_text = ($bp_extra_beds > 0) ? $bp_extra_beds : '-';
        $bp_share_bed_text = ($bp_share_bed > 0) ? $bp_share_bed : '-';
        $bp_foreigner_text = ($bp_foreigner != 2) ? 'มี' : 'ไม่มี';
        $bp_foreigner_no_text = ($bp_foreigner_no > 0) ? $bp_foreigner_no : '-';
        $bp_foreigner_price_text = ($bp_foreigner_price > 0) ? $bp_foreigner_price : 0;
        $bp_price_latest_text = ($bp_price_latest > 0) ? $bp_price_latest : 0;
        $bp_notes_text = !empty($bp_notes) ? $bp_notes : '';

        # --- Check history
        if (empty($bp_id_before)) {
            $description_field .= "ไม่ระบุวันที่ : " . $bp_date_not_specified_text . "\n";
            if ($bp_date_not_specified == 2) {
                if ($ptype != 4) {
                    $description_field .= "วันที่เที่ยว / วันที่เดินทาง : " . date("d F Y", strtotime($bp_travel_date)) . "\n";
                } else {
                    $description_field .= "วันที่เช็คอิน : " . date("d F Y", strtotime($bp_checkin_date)) . "\n";
                    $description_field .= "วันที่เช็คเอาท์ : " . date("d F Y", strtotime($bp_checkout_date)) . "\n";
                }
            }
            $description_field .= "ผู้ใหญ่ : " . $bp_adults_text . " คน\n";
            $description_field .= "เด็ก : " . $bp_children_text . " คน\n";
            $description_field .= "ทารก : " . $bp_infant_text . " คน\n";
            $description_field .= "FOC : " . $bp_foc_text . " คน\n";
            if ($ptype != 3 && $ptype != 4) {
                $description_field .= "เพิ่มรถรับส่ง : " . $bp_transfer_text . "\n";
            }
            $description_field .= "สถานที่รับ : " . $bp_pickup_text . "\n";
            $description_field .= "สถานที่ส่ง : " . $bp_dropoff_text . "\n";
            $description_field .= "โซน : " . $bp_zones_text . "\n";
            $description_field .= "ห้องพัก : " . $bp_roomno_text . "\n";
            if ($ptype == 3) {
                $description_field .= "จำนวนคัน : " . $bp_no_cars_text . " คัน\n";
                $description_field .= "จำนวนชั่วโมง : " . $bp_no_hours_text . " ชั่วโมง\n";
                $description_field .= "เวลารับ : " . $bp_pickup_time_text . "\n";
                $description_field .= "เวลาส่ง : " . $bp_dropoff_time_text . "\n";
            }
            if ($ptype == 4) {
                $description_field .= "จำนวนห้อง : " . $bp_no_rooms_text . " ห้อง\n";
                $description_field .= "จำนวนเตียงเสริม : " . $bp_extra_beds_text . " เตียง\n";
                $description_field .= "จำนวนแชร์เตียง : " . $bp_share_bed_text . " เตียง\n";
            }
            if ($ptype != 3 && $ptype != 4) {
                $description_field .= "ชาวต่างชาติ : " . $bp_foreigner_text . "\n";
                $description_field .= "จำนวนชาวต่างชาติ : " . $bp_foreigner_no_text . " คน\n";
                $description_field .= "ราคาเพิ่มเติมสำหรับชาวต่างชาติ : " . number_format($bp_foreigner_price_text) . " บาท\n";
            }
            $description_field .= "ราคาสำหรับแก้ไข : " . number_format($bp_price_latest_text) . " บาท\n";
            $description_field .= "หมายเหตุ : \n" . $bp_notes_text . "\n";
        } else {
            $query_booking_prod = "SELECT * FROM booking_products WHERE id > '0'";
            $query_booking_prod .= " AND id = ?";
            $query_booking_prod .= " LIMIT 1";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_booking_prod);
            mysqli_stmt_bind_param($procedural_statement, 'i', $bp_id);
            mysqli_stmt_execute($procedural_statement);
            $result_prod = mysqli_stmt_get_result($procedural_statement);
            $row_booking_prod = mysqli_fetch_array($result_prod, MYSQLI_ASSOC);

            $description_field .= ($bp_date_not_specified != $row_booking_prod["date_not_specified"]) ? "ไม่ระบุวันที่ : " . $bp_date_not_specified_text . "\n" : "";
            if ($bp_date_not_specified == 2) {
                if ($ptype != 4) {
                    $description_field .= ($bp_travel_date != $row_booking_prod["travel_date"]) ? "วันที่เที่ยว / วันที่เดินทาง : " . date("d F Y", strtotime($bp_travel_date)) . "\n" : "";
                } else {
                    $description_field .= ($bp_checkin_date != $row_booking_prod["bp_checkin_date"]) ? "วันที่เช็คอิน : " . date("d F Y", strtotime($bp_checkin_date)) . "\n" : "";
                    $description_field .= ($bp_checkout_date != $row_booking_prod["bp_checkout_date"]) ? "วันที่เช็คเอาท์ : " . date("d F Y", strtotime($bp_checkout_date)) . "\n" : "";
                }
            }
            $description_field .= ($bp_adults_text != $row_booking_prod["adults"]) ? "ผู้ใหญ่ : " . $bp_adults_text . " คน\n" : "";
            $description_field .= ($bp_children_text != $row_booking_prod["children"]) ? "เด็ก : " . $bp_children_text . " คน\n" : "";
            $description_field .= ($bp_infant_text != $row_booking_prod["infant"]) ? "ทารก : " . $bp_infant_text . " คน\n" : "";
            $description_field .= ($bp_foc_text != $row_booking_prod["foc"]) ? "FOC : " . $bp_foc_text . " คน\n" : "";
            if ($ptype != 3 && $ptype != 4) {
                $description_field .= ($bp_transfer != $row_booking_prod["transfer"]) ? "เพิ่มรถรับส่ง : " . $bp_transfer_text . "\n" : "";
            }
            $description_field .= ($bp_pickup != $row_booking_prod["pickup"]) ? "สถานที่รับ : " . $bp_pickup_text . "\n" : "";
            $description_field .= ($bp_dropoff != $row_booking_prod["dropoff"]) ? "สถานที่ส่ง : " . $bp_dropoff_text . "\n" : "";
            $description_field .= ($bp_zones != $row_booking_prod["zones"]) ? "โซน : " . $bp_zones_text . "\n" : "";
            $description_field .= ($bp_roomno != $row_booking_prod["roomno"]) ? "ห้องพัก : " . $bp_roomno_text . "\n" : "";
            if ($ptype == 3) {
                $description_field .= ($bp_no_cars_text != $row_booking_prod["no_cars"]) ? "จำนวนคัน : " . $bp_no_cars_text . " คัน\n" : "";
                $description_field .= ($bp_no_hours_text != $row_booking_prod["no_hours"]) ? "จำนวนชั่วโมง : " . $bp_no_hours_text . " ชั่วโมง\n" : "";
                $description_field .= ($bp_pickup_time != $row_booking_prod["pickup_time"]) ? "เวลารับ : " . $bp_pickup_time_text . "\n" : "";
                $description_field .= ($bp_dropoff_time != $row_booking_prod["dropoff_time"]) ? "เวลาส่ง : " . $bp_dropoff_time_text . "\n" : "";
            }
            if ($ptype == 4) {
                $description_field .= ($bp_no_rooms_text != $row_booking_prod["no_rooms"]) ? "จำนวนห้อง : " . $bp_no_rooms_text . " ห้อง\n" : "";
                $description_field .= ($bp_extra_beds_text != $row_booking_prod["extra_beds"]) ? "จำนวนเตียงเสริม : " . $bp_extra_beds_text . " เตียง\n" : "";
                $description_field .= ($bp_share_bed_text != $row_booking_prod["share_bed"]) ? "จำนวนแชร์เตียง : " . $bp_share_bed_text . " เตียง\n" : "";
            }
            if ($ptype != 3 && $ptype != 4) {
                $description_field .= ($bp_foreigner != $row_booking_prod["foreigner"]) ? "ชาวต่างชาติ : " . $bp_foreigner_text . "\n" : "";
                $description_field .= ($bp_foreigner_no != $row_booking_prod["foreigner_no"]) ? "จำนวนชาวต่างชาติ : " . $bp_foreigner_no_text . " คน\n" : "";
                $description_field .= ($bp_foreigner_price != $row_booking_prod["foreigner_price"]) ? "ราคาเพิ่มเติมสำหรับชาวต่างชาติ : " . number_format($bp_foreigner_price_text) . " บาท\n" : "";
            }
            $description_field .= ($bp_price_latest != $row_booking_prod["price_latest"]) ? "ราคาสำหรับแก้ไข : " . number_format($bp_price_latest_text) . " บาท\n" : "";
            $description_field .= "หมายเหตุ : \n" . $bp_notes_text . "\n";
        }

        # ---- Update to database ---- #
        $bind_types = "";
        $params = array();

        $query = "UPDATE booking_products SET";

        $query .= " products_type = ?,";
        $bind_types .= "i";
        array_push($params, $ptype);

        $query .= " booking = ?,";
        $bind_types .= "i";
        array_push($params, $booking);

        $query .= " date_not_specified = ?,";
        $bind_types .= "i";
        array_push($params, $bp_date_not_specified);

        $query .= " travel_date = ?,";
        $bind_types .= "s";
        array_push($params, $bp_travel_date);

        $query .= " checkin_date = ?,";
        $bind_types .= "s";
        array_push($params, $bp_checkin_date);

        $query .= " checkout_date = ?,";
        $bind_types .= "s";
        array_push($params, $bp_checkout_date);

        $query .= " products_category_first = ?,";
        $bind_types .= "i";
        array_push($params, $catefirst);

        $query .= " products_category_second = ?,";
        $bind_types .= "i";
        array_push($params, $catesecond);

        // type.1 = 'tours' type.3 = 'transfer'
        if ($agent > 0 && ($ptype == 1 || $ptype == 3)) {
            $query .= " products_category_third_combine = ?,";
            $bind_types .= "i";
            array_push($params, $catethird);
        } else {
            $query .= " products_category_third = ?,";
            $bind_types .= "i";
            array_push($params, $catethird);
        }

        $query .= " adults = ?,";
        $bind_types .= "i";
        array_push($params, $bp_adults);

        $query .= " children = ?,";
        $bind_types .= "i";
        array_push($params, $bp_children);

        $query .= " infant = ?,";
        $bind_types .= "i";
        array_push($params, $bp_infant);

        $query .= " foc = ?,";
        $bind_types .= "i";
        array_push($params, $bp_foc);

        $query .= " transfer = ?,";
        $bind_types .= "i";
        array_push($params, $bp_transfer);

        $query .= " no_cars = ?,";
        $bind_types .= "i";
        array_push($params, $bp_no_cars);

        $query .= " no_hours = ?,";
        $bind_types .= "i";
        array_push($params, $bp_no_hours);

        $query .= " no_rooms = ?,";
        $bind_types .= "i";
        array_push($params, $bp_no_rooms);

        $query .= " extra_beds = ?,";
        $bind_types .= "i";
        array_push($params, $bp_extra_beds);

        $query .= " share_bed = ?,";
        $bind_types .= "i";
        array_push($params, $bp_share_bed);

        $query .= " pickup = ?,";
        $bind_types .= "i";
        array_push($params, $bp_pickup);

        $query .= " pickup_time = ?,";
        $bind_types .= "s";
        array_push($params, $bp_pickup_time);

        $query .= " dropoff = ?,";
        $bind_types .= "i";
        array_push($params, $bp_dropoff);

        $query .= " dropoff_time = ?,";
        $bind_types .= "s";
        array_push($params, $bp_dropoff_time);

        $query .= " zones = ?,";
        $bind_types .= "i";
        array_push($params, $bp_zones);

        $query .= " roomno = ?,";
        $bind_types .= "s";
        array_push($params, $bp_roomno);

        $query .= " notes = ?,";
        $bind_types .= "s";
        array_push($params, $bp_notes);

        $query .= " edit_date = ?,";
        $bind_types .= "i";
        array_push($params, $edit_date);

        $query .= " foreigner = ?,";
        $bind_types .= "i";
        array_push($params, $bp_foreigner);

        $query .= " foreigner_no = ?,";
        $bind_types .= "i";
        array_push($params, $bp_foreigner_no);

        $query .= " foreigner_price = ?,";
        $bind_types .= "d";
        array_push($params, $bp_foreigner_price);

        $query .= " rate_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_rate_2);

        $query .= " rate_4 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_rate_4);

        $query .= " charter_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_charter_2);

        $query .= " group_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_group_2);

        $query .= " transfer_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_transfer_2);

        $query .= " extra_hour_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_extra_hour_2);

        $query .= " extrabeds_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_extrabeds_2);

        $query .= " sharingbed_2 = ?,";
        $bind_types .= "d";
        array_push($params, $bp_sharingbed_2);

        $query .= " price_default = ?,";
        $bind_types .= "d";
        array_push($params, $bp_price_default);

        $query .= " price_latest = ?,";
        $bind_types .= "d";
        array_push($params, $bp_price_latest);

        $query .= " pax = ?,";
        $bind_types .= "i";
        array_push($params, $bp_pax);

        $query .= " hours_no = ?,";
        $bind_types .= "i";
        array_push($params, $bp_hours_no);

        $query .= " status_email = ?,";
        $bind_types .= "i";
        array_push($params, $bp_status_email);

        $query .= " status_confirm = ?,";
        $bind_types .= "i";
        array_push($params, $bp_status_confirm);

        $query .= " status_confirm_op = ?,";
        $bind_types .= "i";
        array_push($params, $bp_status_confirm_op);

        if ($bp_id_before == "") {
            $query .= " create_date = now(),";
        }

        $query .= " last_edit_time = now()";
        $query .= " WHERE id = '$bp_id'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);

        # --- Check Min Date Travel
        if ($bp_date_not_specified != '1' && $bp_id_before == "") {
            $query_booking = "SELECT travel_date_min FROM booking WHERE id = ? ";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_booking);
            mysqli_stmt_bind_param($procedural_statement, 'i', $booking);
            mysqli_stmt_execute($procedural_statement);
            $result_prod = mysqli_stmt_get_result($procedural_statement);
            $row_booking = mysqli_fetch_array($result_prod, MYSQLI_ASSOC);

            $travel_date = "UPDATE booking SET travel_date_min = ? WHERE id = ?";
            $procedural_statement = mysqli_prepare($mysqli_p, $travel_date);
            if ($ptype != '4') {
                if ($bp_travel_date < $row_booking['travel_date_min'] || $row_booking['travel_date_min'] == "0000-00-00") {
                    mysqli_stmt_bind_param($procedural_statement, 'si', $bp_travel_date, $booking);
                }
            } else {
                if ($bp_checkin_date < $row_booking['travel_date_min'] || $row_booking['travel_date_min'] == "0000-00-00") {
                    mysqli_stmt_bind_param($procedural_statement, 'si', $bp_checkin_date, $booking);
                }
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
        }

        # --- booking_history
        if (!empty($bp_id) && !empty($booking) && $description_field != "") {
            # ---- Insert to booking_history ---- #
            $query_history = "INSERT INTO booking_history (booking, history_type, booking_products, description_field, employee, ip_address, create_date)";
            $query_history .= "VALUES (0, 0, 0, '', 0, '', now())";
            $result = mysqli_query($mysqli_p, $query_history);
            $history_id = mysqli_insert_id($mysqli_p);

            $bind_types = "";
            $params = array();

            $query_history = "UPDATE booking_history SET";

            $query_history .= " booking = ?,";
            $bind_types .= "i";
            array_push($params, $booking);

            if (empty($bp_id_before)) {
                $query_history .= " history_type = ?,";
                $bind_types .= "i";
                array_push($params, '1');
            } else {
                $query_history .= " history_type = ?,";
                $bind_types .= "i";
                array_push($params, '2');
            }

            $query_history .= " booking_products = ?,";
            $bind_types .= "i";
            array_push($params, $bp_id);

            $query_history .= " description_field = ?,";
            $bind_types .= "s";
            array_push($params, $description_field);

            $query_history .= " employee = ?,";
            $bind_types .= "i";
            array_push($params, $_SESSION["admin"]["id"]);

            $query_history .= " ip_address = ?,";
            $bind_types .= "s";
            array_push($params, $ip);

            $query_history .= " create_date = now()";
            $query_history .= " WHERE id = '$history_id'";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_history);
            if ($bind_types != "") {
                mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
            }
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
        }

        mysqli_close($mysqli_p);

        $return_url = "&ptype=" . $ptype . "&booking=" . $booking . "&id=" . $bp_id;
        $message_alert = "success";
        echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/product-detail" . $return_url . "&message=" . $message_alert . "'\" >";
    }
} else {
    echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/product-detail" . $return_url . "&message=" . $message_alert . "'\" >";
}

// echo "<meta http-equiv=\"refresh\" content=\"0; url='./?mode=booking/product-detail&ptype=1&booking=2&id=1'\" >";
