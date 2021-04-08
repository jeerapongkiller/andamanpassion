<?php
require("../../../inc/connection.php");

if (!empty($_POST["ptype"]) && !empty($_POST["booking"]) && !empty($_POST["catesupplier"])) {
    $bind_types = "";
    $params = array();
    $yesterday = date('Y-m-d', strtotime($_POST["bp_checkout_date"] . "-1 days"));
    $nocateF = 0;
    $cateF_no = "";
    $query_cateF = "SELECT * 
                    FROM products_category_first 
                    WHERE id > '0' 
                        AND products_type = ? AND supplier = ?
    ";
    $query_cateF .= $_POST["ptype"] == 4 ? " AND validity_from <= '" . $_POST["bp_checkin_date"] . "' AND validity_to >= '" . $yesterday . "'" : " AND validity_from <= '" . $_POST["bp_travel_date"] . "' AND validity_to >= '" . $_POST["bp_travel_date"] . "'";
    $procedural_statement = mysqli_prepare($mysqli_p, $query_cateF);
    mysqli_stmt_bind_param($procedural_statement, 'ii', $_POST["ptype"], $_POST["catesupplier"]);
    mysqli_stmt_execute($procedural_statement);
    $result_cateF = mysqli_stmt_get_result($procedural_statement);
    while ($row_cateF = mysqli_fetch_array($result_cateF, MYSQLI_ASSOC)) {
        ++$nocateF;
        if (!empty($_POST["agent"]) && $_POST["agent"] > 0 && ($_POST["ptype"] == 1 || $_POST["ptype"] == 3)) {
            $cateF_no .= $nocateF > 1 ? " OR F.id = '" . $row_cateF['id'] . "'" : " AND (F.id = '" . $row_cateF['id'] . "'";
        }else{
            $cateF_no .= $nocateF > 1 ? " OR T.products_category_first = '" . $row_cateF['id'] . "'" : " AND (T.products_category_first = '" . $row_cateF['id'] . "'";
        }
    }
    $cateF_no .= ") ";
?>

    <label for="catethird">สินค้า</label>
    <select class="custom-select" id="catethird" name="catethird" onchange="checkCatethird()" <?php echo ($_POST["bp_status_confirm_op"] == 1) ? 'disabled' : 'required'; ?>>
        <?php if (empty($_POST["products_category_second"])) { ?><option value="">กรุณาเลือกสินค้า</option><?php } ?>
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
                $combine_no .= $noagent > 1 ? " OR C.combine_agentxsupplier = '" . $row_combine["id"] . "'" : " AND (C.combine_agentxsupplier = '" . $row_combine["id"] . "'";
            }
            $combine_no .= ") ";

            $query_cate = "SELECT C.id as combineID, C.products_category_first as combineFirst, 
                                F.status as firstStatus, F.id as firstID, F.name as firstName, 
                                F.validity_from as validFrom, F.validity_to as validTo, 
                                F.procode as procode, S.id as secondID, S.name as secondName, 
                                T.id as thirdID, T.periods_from as thirdFrom, T.periods_to as thirdTo 
                        FROM products_category_third_combine C 
                        LEFT JOIN products_category_first F ON C.products_category_first = F.id
                        LEFT JOIN products_category_second S ON C.products_category_second = S.id
                        LEFT JOIN products_category_third T ON C.products_category_third = T.id
                        WHERE C.id > '0' 
                            AND F.validity_from <= '" . $_POST["bp_travel_date"] . "' 
                            AND F.validity_to >= '" . $_POST["bp_travel_date"] . "' 
            ";
            if (!empty($combine_no)) {
                $query_cate .= $combine_no;
            }
            if (!empty($_POST["products_category_second"])) {
                $query_cate .= " AND S.id = '" . $_POST["products_category_second"] . "'";
            }
            $query_cate .= " AND T.periods_from <= '" . $_POST["bp_travel_date"] . "' AND T.periods_to >= '" . $_POST["bp_travel_date"] . "'";
            $query_cate .= $_POST["bp_id"] == 0 ? " AND F.status = '1'" : '';
            $query_cate .= !empty($cateF_no) ? $cateF_no : "";
            $query_cate .= "AND S.status = '1' ";
            $query_cate .= " ORDER BY S.name ASC";
            $result_cate = mysqli_query($mysqli_p, $query_cate);
            while ($row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC)) {
        ?>
                <option value="<?php echo $row_cate["combineID"]; ?>"><?php echo $row_cate["secondName"]; ?></option>
            <?php
            }
        } else {
            $query_cate = "SELECT T.*, S.status as secondStatus, S.id as secondID, S.name as secondName
                        FROM products_category_third T 
                        LEFT JOIN products_category_second S ON T.products_category_second = S.id
                        WHERE T.id > '0' 
            ";
            $query_cate .= $_POST["ptype"] == 4 ? " AND T.periods_from <= '" . $_POST["bp_checkin_date"] . "' AND T.periods_to >= '" . $yesterday . "'" : " AND T.periods_from <= '" . $_POST["bp_travel_date"] . "' AND T.periods_to >= '" . $_POST["bp_travel_date"] . "'";
            $query_cate .= $_POST["bp_id"] == 0 ? " AND T.status = '1'" : '';
            $query_cate .= !empty($cateF_no) ? $cateF_no : "";
            $query_cate .= "AND S.status = '1' ";
            $query_cate .= " ORDER BY S.name ASC";
            $result_cate = mysqli_query($mysqli_p, $query_cate);
            while ($row_cate = mysqli_fetch_array($result_cate, MYSQLI_ASSOC)) {
            ?>
                <option value="<?php echo $row_cate["id"]; ?>" <?php echo ($_POST['products_category_second'] == $row_cate["secondID"]) ? 'selected' : '' ; ?> ><?php echo $row_cate["secondName"]; ?></option>
        <?php
            }
        }
        ?>
    </select>


    <?php // echo "<br /><br />" . $query_cate; ?>

<?php
} else {
?>
    <label for="catethird">สินค้า</label>
    <select class="custom-select" id="catethird" name="catethird" required>
        <option value="">ไม่พบรายการสินค้า</option>
    </select>
<?php
}
?>