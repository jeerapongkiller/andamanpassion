<?php
require("../../../inc/connection.php");

if (!empty($_POST['type']) && !empty($_POST['name'])) {

    $pickup = $_POST['type'] == 'pickup' ? '1' : '2' ;
    $dropoff = $_POST['type'] == 'dropoff' ? '1' : '2' ;

    # ---- Insert to place ---- #
    $query_place = "INSERT INTO place (status, name, pickup, dropoff, trash_deleted, last_edit_time)";
    $query_place .= "VALUES (0, '', 0, 0, 0, now())";
    $result = mysqli_query($mysqli_p, $query_place);
    $place_id = mysqli_insert_id($mysqli_p);

    if(!empty($place_id)){
        $bind_types = "";
        $params = array();

        $query_place = "UPDATE place SET";

        $query_place .= " status = ?,";
        $bind_types .= "i";
        array_push($params, '1');

        $query_place .= " name = ?,";
        $bind_types .= "s";
        array_push($params, $_POST["name"]);

        $query_place .= " pickup = ?,";
        $bind_types .= "i";
        array_push($params, '1');

        $query_place .= " dropoff = ?,";
        $bind_types .= "i";
        array_push($params, '1');

        $query_place .= " trash_deleted = ?,";
        $bind_types .= "i";
        array_push($params, '2');

        $query_place .= " last_edit_time = now()";
        $query_place .= " WHERE id = '$place_id'";
        $procedural_statement = mysqli_prepare($mysqli_p, $query_place);
        if ($bind_types != "") {
            mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
        }
        mysqli_stmt_execute($procedural_statement);
        $result = mysqli_stmt_get_result($procedural_statement);
    }
    
?>

    <select class="custom-select" id="bp_pickup" name="bp_pickup" onchange="checkdropoff('chang', 'checkdropoff');">
        <option value="0">กรุณาเลือกสถานที่ส่ง</option>
        <?php
        $query_pickup = "SELECT * FROM place WHERE id > '0' AND status = '1' AND pickup = '1' ORDER BY name ASC";
        $result_pickup = mysqli_query($mysqli_p, $query_pickup);
        while ($row_pickup = mysqli_fetch_array($result_pickup, MYSQLI_ASSOC)) {
        ?>
            <option value="<?php echo $row_pickup["id"]; ?>"><?php echo $row_pickup["name"]; ?></option>
        <?php
        }
        ?>
    </select>

<?php
}
?>