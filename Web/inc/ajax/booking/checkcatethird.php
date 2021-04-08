<?php
require("../../../inc/connection.php");

// if (!empty($_POST["ptype"]) && !empty($_POST["catefirst"]) && !empty($_POST["catethird"])) {
if (!empty($_POST["ptype"]) && !empty($_POST["catethird"])) {
    $cate_periods = 0;
    $cate_periods_txt = "ไม่พบระยะเวลาที่เกี่ยวข้อง";
    if (!empty($_POST["agent"]) && $_POST["agent"] > 0 && ($_POST["ptype"] == 1 || $_POST["ptype"] == 3)) {
        $query_cate = "SELECT C.id as combineID, T.id as thirdID, T.periods_from as thirdFrom, T.periods_to as thirdTo 
                        FROM products_category_third_combine C 
                        LEFT JOIN products_category_third T ON C.products_category_third = T.id
                        WHERE C.id = ? 
                            ORDER BY T.periods_from ASC
        ";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_cate);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["catethird"]);
        mysqli_stmt_execute($procedural_statement);
        $result_cate = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result_cate);
        if ($numrow > 0) {
            $row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC);
            $cate_periods = 1;
            $cate_periods_txt = date("d F Y", strtotime($row_cate["thirdFrom"])) . " - " . date("d F Y", strtotime($row_cate["thirdTo"]));
        }
    } else {
        $query_cate = "SELECT * 
                        FROM products_category_third 
                        WHERE id = ?
        ";
        $query_cate .= " ORDER BY periods_from ASC";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_cate);
        mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["catethird"]);
        mysqli_stmt_execute($procedural_statement);
        $result_cate = mysqli_stmt_get_result($procedural_statement);
        $numrow = mysqli_num_rows($result_cate);
        if ($numrow > 0) {
            $row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC);
            $cate_periods = 1;
            $cate_periods_txt = date("d F Y", strtotime($row_cate["periods_from"])) . " - " . date("d F Y", strtotime($row_cate["periods_to"]));
        }
    }
?>

    <label for="catethird_period">ระยะเวลา</label>
    <div class="input-group">
        <input type="hidden" class="form-control" id="catethird_period" name="catethird_period" placeholder="" aria-describedby="catethird_period" value="<?php echo $cate_periods; ?>" readonly>
        <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto"><?php echo $cate_periods_txt; ?></span>
    </div>

<?php
} else {
?>
    <label for="catethird_period">ระยะเวลา</label>
    <div class="input-group">
        <input type="hidden" class="form-control" id="catethird_period" name="catethird_period" placeholder="" aria-describedby="catethird_period" value="0" readonly>
        <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto">ไม่พบระยะเวลาที่เกี่ยวข้อง</span>
    </div>
<?php
}
?>