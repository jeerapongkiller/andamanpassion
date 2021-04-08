<?php
require("../../../inc/connection.php");
if (!empty($_POST['supplier']) && !empty($_POST['ptype'])) {
    $first = array();
    $query_first = "SELECT * FROM products_category_first WHERE supplier = '" . $_POST['supplier'] . "' AND products_type = '" . $_POST['ptype'] . "' ORDER BY id ASC ";
    $result_first = mysqli_query($mysqli_p, $query_first);
    while ($row_first = mysqli_fetch_array($result_first, MYSQLI_ASSOC)) {
        array_push($first, $row_first['id']);
    }
?>
    <label for="search_catesecond_val">สินค้า</label>
    <select class="custom-select" id="search_catesecond_val" name="search_catesecond_val" onchange="selectdropoff()">
        <option value="0">ทั้งหมด</option>
        <?php foreach ($first as $val) {
            $query_second = "SELECT * FROM products_category_second WHERE products_category_first = '" . $val . "' ORDER BY id ASC ";
            $result_second = mysqli_query($mysqli_p, $query_second);
            while ($row_second = mysqli_fetch_array($result_second, MYSQLI_ASSOC)) { ?>
                <option value="<?php echo $row_second['id']; ?>" <?php if ($_POST['catesecond'] == $row_second['id']) {
                                                                echo "selected";
                                                            } ?>><?php echo $row_second['name']; ?></option>
        <?php }
        } ?>
    </select>
<?php
} else {
?>
    <label for="search_catesecond_val">สินค้า</label>
    <select class="custom-select" id="search_catesecond_val" name="search_catesecond_val">
        <option value="0">ทั้งหมด</option>
    </select>
<?php } ?>