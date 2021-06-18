<?php
require("../../../inc/connection.php");

if (!empty($_POST["type"])) {
    if ($_POST["type"] == 'pickup') {
        if ($_POST["pickup"] > 0) {
            $query = "SELECT * FROM place WHERE id = ? ";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["pickup"]);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
            $numrow = mysqli_num_rows($result);
            if ($numrow > 0) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>
                <label for="bp_zones_pickup">โซน(สถานที่รับ)</label>
                <div class="input-group">
                    <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto"><?php echo stripslashes(get_value('zones', 'id', 'name', $row["zones"], $mysqli_p)); ?></span>
                </div>
            <?php
            }
        } else {
            ?>
            <label for="bp_zones_pickup">โซน(สถานที่รับ)</label>
            <div class="input-group">
                <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto">เลือกสถานที่รับ</span>
            </div>
            <?php
        }
    }

    if ($_POST["type"] == 'dropoff') {
        if ($_POST["dropoff"] > 0) {
            $query = "SELECT * FROM place WHERE id = ? ";
            $procedural_statement = mysqli_prepare($mysqli_p, $query);
            mysqli_stmt_bind_param($procedural_statement, 'i', $_POST["dropoff"]);
            mysqli_stmt_execute($procedural_statement);
            $result = mysqli_stmt_get_result($procedural_statement);
            $numrow = mysqli_num_rows($result);
            if ($numrow > 0) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            ?>
                <label for="bp_zones_dropoff">โซน(สถานที่ส่ง)</label>
                <div class="input-group">
                    <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto"><?php echo stripslashes(get_value('zones', 'id', 'name', $row["zones"], $mysqli_p)); ?></span>
                </div>
            <?php
            }
        } else {
            ?>
            <label for="bp_zones_dropoff">โซน(สถานที่ส่ง)</label>
            <div class="input-group">
                <span style="font-size:14px; font-weight:bold; color:#0D84DE; margin-left: auto; margin-right: auto">เลือกสถานที่ส่ง</span>
            </div>
<?php
        }
    }
}
