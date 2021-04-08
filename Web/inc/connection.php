<?php
ob_start();
session_start();

date_default_timezone_set("Asia/Bangkok");
# -------------------- #

# DB Connect --------- #
if (strpos($_SERVER['DOCUMENT_ROOT'], ":")) # for server-ps
{
	$host = "localhost";
	$username = "root";
	$password = "password";
	$database = "system_andamanpassion_v2";

	$url_explode = explode("/", $_SERVER['REQUEST_URI']);
	$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/" . $url_explode[1] . "/" . $url_explode[2];
} else # for demo / online
{
	$host = "localhost";
	$username = "andama13_ams";
	$password = "YmBRWuyFy";
	$database = "andama13_ams";

	$URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
}

$mysqli_p = mysqli_connect($host, $username, $password, $database);
if (!$mysqli_p) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($mysqli_p, "utf8");
# -------------------- #

# Set Default -------- #
$default_name = "Andaman Passion"; # Set company name
$confirm_code = "#ADMINPASSION";
$today = date("Y-m-d");
$times = date("H:i:s");
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; # Set full URL
# -------------------- #

# Set Function ------- #
function get_value($table, $field_id, $field_name, $val, $mysqli_p)
{
	$sqlgv = "SELECT $field_id, $field_name FROM $table WHERE $field_id = '$val'";
	$resultgv = mysqli_query($mysqli_p, $sqlgv);
	$numrowgv = mysqli_num_rows($resultgv);

	if (!empty($numrowgv)) {
		$rows = mysqli_fetch_array($resultgv);
		$value = $rows[1];
		// $value = preg_replace("~'~","",$value);
	} else {
		$value = "";
	}

	return $value;
}

function img_upload($photo, $photo_name, $tmp_photo, $uploaddir, $photo_time, $paramiter)
{
	if (empty($photo)) {
		$photo = $tmp_photo;
	} else {
		$ext = explode(".", $photo_name);
		$ext_n = count($ext) - 1;
		$ext_filetype = strtolower($ext[$ext_n]);
		$final_filename = $photo_time . $paramiter . "." . $ext_filetype;
		$newfile = $uploaddir . $final_filename;
		if (move_uploaded_file($photo, $newfile)) {
			$photo = $final_filename;
			!empty($tmp_photo) ? unlink($uploaddir . $tmp_photo) : '';
		} else {
			$photo = "false";
		}
	}

	return $photo;

	// if(!$photo){
	// 	$photo = $tmp_photo;
	// }else{
	// 	$ext = explode(".",$photo_name);
	// 	$ext_n = count($ext) - 1;
	// 	if(strtoupper($ext[$ext_n]) == "JPG" || strtoupper($ext[$ext_n]) == "JPEG"){
	// 		$final_filename = $photo_id . $paramiter.".jpg";
	// 		$newfile = $uploaddir."$final_filename";
	// 		if(move_uploaded_file($photo, $newfile)){ }
	// 		$photo = $final_filename; 
	// 		if($tmp_photo){ unlink($uploaddir . $tmp_photo); }
	// 	}else if(strtoupper($ext[$ext_n]) == "PNG"){
	// 		$final_filename = $photo_id . $paramiter.".png";
	// 		$newfile = $uploaddir."$final_filename";
	// 		if(move_uploaded_file($photo, $newfile)){ }
	// 		$photo = $final_filename; 
	// 		if($tmp_photo){ unlink($uploaddir . $tmp_photo); }
	// 	}else{
	// 		// echo "<meta http-equiv=\"refresh\" content=\"0; url='".$redirect_url."'\" >";
	// 		// exit;
	// 	}
	// }
}

function list_number($select, $start, $loop)
{
	$select = $select * 1;
	$i = 0;
	while ($i != $loop) {
		$selected = ($start == $select) ? ' selected' : '';
		echo "<option value=\"$start\"" . $selected . ">$start</option>";
		$start++;
		$i++;
	}
}

function DateDiff($strDate1, $strDate2)
{
	return (strtotime($strDate2) - strtotime($strDate1)) / (60 * 60 * 24);  // 1 day = 60*60*24
}
# -------------------- #
