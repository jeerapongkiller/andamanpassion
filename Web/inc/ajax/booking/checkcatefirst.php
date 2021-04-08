<?php
require("../../../inc/connection.php");
if(!empty($_POST["catethird"])){
    if(!empty($_POST["agent"]) && $_POST["agent"] > 0 && ($_POST["ptype"] == 1 || $_POST["ptype"] == 3)){
        $catefirst = get_value("products_category_third_combine", "id", "products_category_first", $_POST["catethird"], $mysqli_p);
    }else{
        $catefirst = get_value("products_category_third", "id", "products_category_first", $_POST["catethird"], $mysqli_p);
    }
?>
    <input type="hidden" id="catefirst" name="catefirst" value="<?php echo $catefirst; ?>">
<?php
}
?>