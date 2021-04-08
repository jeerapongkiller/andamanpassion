<?php
require("../../../inc/connection.php");
if (!empty($_POST['catesecond'])) {
    $bp = array();
    $query_bp = "SELECT DISTINCT(dropoff) FROM booking_products WHERE products_category_second = '" . $_POST['catesecond'] . "' AND invoice = '0' ORDER BY id ASC ";
    $result_bp = mysqli_query($mysqli_p, $query_bp);
    while ($row_bp = mysqli_fetch_array($result_bp, MYSQLI_ASSOC)) {
        if(!empty($row_bp['dropoff'])){ array_push($bp, $row_bp['dropoff']); }
    }
?>
    <label for="search_dropoff_val">สถานที่ส่ง</label>
    <select class="custom-select" id="search_dropoff_val" name="search_dropoff_val">
        <option value="0">ทั้งหมด</option>
        <?php foreach ($bp as $val) { ?>
            <option value="<?php echo $val; ?>" <?php if ($_POST['dropoff'] == $val) {
                                                    echo "selected";
                                                } ?>><?php echo get_value('place', 'id', 'name', $val, $mysqli_p); ?></option>
        <?php } ?>
    </select>
<?php
} else {
?>
    <label for="search_dropoff_val">สถานที่ส่ง</label>
    <select class="custom-select" id="search_dropoff_val" name="search_dropoff_val">
        <option value="0">ทั้งหมด</option>
    </select>
<?php } ?>