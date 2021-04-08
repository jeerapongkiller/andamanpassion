<?php
require("../../../inc/connection.php");
if (!empty($_POST['supplier'])) {
    $ptype = array();
    $query_first = "SELECT DISTINCT(products_type) FROM products_category_first WHERE supplier = '" . $_POST['supplier'] . "' ORDER BY products_type ASC ";
    $result_first = mysqli_query($mysqli_p, $query_first);
    while ($row_first = mysqli_fetch_array($result_first, MYSQLI_ASSOC)) {
        array_push($ptype, $row_first['products_type']);
    }
?>
    <label for="search_ptype_val">ประเภทสินค้า</label>
    <select class="custom-select" id="search_ptype_val" name="search_ptype_val" onchange="selectcatesecond()">
        <option value="0">ทั้งหมด</option>
        <?php foreach ($ptype as $val) { ?>
            <option value="<?php echo $val; ?>" <?php if ($_POST['ptype'] == $val) {
                                                    echo "selected";
                                                } ?>><?php echo get_value('products_type', 'id', 'name_text_thai', $val, $mysqli_p) ?></option>
        <?php } ?>
    </select>
<?php
}else{
?>
    <label for="search_ptype_val">ประเภทสินค้า</label>
    <select class="custom-select" id="search_ptype_val" name="search_ptype_val" >
        <option value="0">ทั้งหมด</option>
    </select>
<?php } ?>