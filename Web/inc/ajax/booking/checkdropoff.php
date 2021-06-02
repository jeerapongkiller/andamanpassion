<?php
require("../../../inc/connection.php");

if (!empty($_POST['type'])) {
    $query_dropoff = "SELECT * FROM place WHERE id > '0' ";
    $query_dropoff .= $_POST['type'] == 'chang' ? "AND id != '".$_POST['pickup']."' " : "" ;
    $query_dropoff .= "AND status = '1' AND dropoff = '1' ORDER BY name ASC";
    $result_dropoff = mysqli_query($mysqli_p, $query_dropoff);
?>
    <select class="select2 form-control custom-select" id="bp_dropoff" name="bp_dropoff">
        <option value="0">กรุณาเลือกสถานที่ส่ง</option>
        <?php while ($row_dropoff = mysqli_fetch_array($result_dropoff, MYSQLI_ASSOC)) { ?>
            <option value="<?php echo $row_dropoff["id"]; ?>" <?php echo !empty($_POST['dropoff']) && $row_dropoff["id"] == $_POST['dropoff'] ? 'selected' : '' ; ?>><?php echo $row_dropoff["name"]; ?></option>
        <?php
        }
        ?>
    </select>
<?php
}
