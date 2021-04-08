<?php
require("../../../inc/connection.php");

if (!empty($_POST["ptype"]) && !empty($_POST["booking"])) {
    $bind_types = "";
    $params = array();
    $yesterday = date('Y-m-d', strtotime($_POST["bp_checkout_date"] . "-1 days"));
    if(!empty($_POST['products_category_first'])){ $supplier_category_first = get_value("products_category_first", "id", "supplier", $_POST["products_category_first"], $mysqli_p); }else{ $supplier_category_first = '0'; }
?>
    <label for="catesupplier">ซัพพลายเออร์</label>
    <select class="custom-select" id="catesupplier" name="catesupplier" onchange="checkCatesecond()" <?php echo ($_POST["bp_status_confirm_op"] == 1) ? 'disabled' : 'required'; ?>>
        <option value="">กรุณาเลือกซัพพลายเออร์</option>
        <?php
        if (!empty($_POST["agent"]) && $_POST["agent"] > 0 && ($_POST["ptype"] == 1 || $_POST["ptype"] == 3)) {
            $noagent = 0;
            $combine_no = "";
            $query_combine = "SELECT * FROM combine_agentxsupplier WHERE agent = ? AND products_type = ?";
            $query_combine .= $_POST["bp_id"] == 0 ? " AND status = '1'" : '';
            $query_combine .= " ORDER BY agent ASC, supplier ASC";
            $procedural_statement = mysqli_prepare($mysqli_p, $query_combine);
            mysqli_stmt_bind_param($procedural_statement, 'ii', $_POST["agent"], $_POST["ptype"]);
            mysqli_stmt_execute($procedural_statement);
            $result_combine = mysqli_stmt_get_result($procedural_statement);
            while ($row_combine = mysqli_fetch_array($result_combine, MYSQLI_ASSOC)) {
                ++$noagent;
                $combine_no .= $noagent > 1 ? " OR id = '" . $row_combine["supplier"] . "'" : " AND (id = '" . $row_combine["supplier"] . "'";
            }
            $combine_no .= ") ";

            $query_cate = "SELECT * FROM supplier WHERE id > '0' AND status = '1' ";
            if (!empty($combine_no)) {
                $query_cate .= $combine_no;
            }
            $query_cate .= " ORDER BY company ASC";
            $result_cate = mysqli_query($mysqli_p, $query_cate);
            while ($row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC)) { ?>
                <option value="<?php echo $row_cate["id"]; ?>" <?php echo ($supplier_category_first == $row_cate["id"]) ? 'selected' : ''; ?> ><?php echo $row_cate["company"]; ?></option>
            <?php 
            }
        } else {
            $query_cate = "SELECT * FROM supplier WHERE id > '0' AND status = '1' ";
            $query_cate .= " ORDER BY company ASC";
            $result_cate = mysqli_query($mysqli_p, $query_cate);
            while ($row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC)) {
            ?>
                <option value="<?php echo $row_cate["id"]; ?>" <?php echo ($supplier_category_first == $row_cate["id"]) ? 'selected' : ''; ?> ><?php echo $row_cate["company"]; ?></option>
        <?php
            }
        }
        ?>
    </select>

    <!-- <?php echo "<br /><br />" . $query_cate; ?> -->

<?php
} else {
?>
    <label for="catesupplier">ซัพพลายเออร์</label>
    <select class="custom-select" id="catesupplier" name="catesupplier" required>
        <option value="">ไม่พบรายการซัพพลายเออร์</option>
    </select>
<?php
}
?>