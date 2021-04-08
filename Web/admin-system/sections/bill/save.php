<?php
$bo_bi_bill = !empty($_POST["bo_bi_bill"]) ? $_POST["bo_bi_bill"] : '';
$bo_bi_agent = !empty($_POST["bo_bi_agent"]) ? $_POST["bo_bi_agent"] : '';
$bo_bi_name = !empty($_POST["bo_bi_name"]) ? $_POST["bo_bi_name"] : '';
$bo_bi_address = !empty($_POST["bo_bi_address"]) ? $_POST["bo_bi_address"] : '';
$bo_bi_date = !empty($_POST["bo_bi_date"]) ? $_POST["bo_bi_date"] : $today;
$bo_due_date = !empty($_POST["bo_due_date"]) ? $_POST["bo_due_date"] : $today;
$bo_bi_condition = !empty($_POST["bo_bi_condition"]) ? $_POST["bo_bi_condition"] : '';
$bo_bi_con_detail = !empty($_POST["bo_bi_con_detail"]) ? $_POST["bo_bi_con_detail"] : '';

if (!empty($bo_bi_bill)) {
    # ---- Update to database ---- #
    $bind_types = "";
    $params = array();

    $query = "UPDATE bill SET";

    $query .= " bi_date = ?,";
    $bind_types .= "s";
    array_push($params, $bo_bi_date);

    $query .= " due_date = ?,";
    $bind_types .= "s";
    array_push($params, $bo_due_date);

    $query .= " bi_name = ?,";
    $bind_types .= "s";
    array_push($params, $bo_bi_name);

    $query .= " bi_address = ?,";
    $bind_types .= "s";
    array_push($params, $bo_bi_address);

    $query .= " bi_condition = ?,";
    $bind_types .= "s";
    array_push($params, $bo_bi_condition);

    $query .= " bi_con_detail = ?,";
    $bind_types .= "s";
    array_push($params, $bo_bi_con_detail);

    $query .= " bi_by = ?,";
    $bind_types .= "i";
    array_push($params, $_SESSION["admin"]["id"]);

    $query .= " agent = ?,";
    $bind_types .= "i";
    array_push($params, $bo_bi_agent);

    $query .= " create_date = now()";
    $query .= " WHERE id = '$bo_bi_bill'";

    $procedural_statement = mysqli_prepare($mysqli_p, $query);
    if ($bind_types != "") {
        mysqli_stmt_bind_param($procedural_statement, $bind_types, ...$params);
    }
    mysqli_stmt_execute($procedural_statement);
    $result = mysqli_stmt_get_result($procedural_statement);
?>
    <form action="./?mode=bill/list&message=success" method="POST" name="form_save_bill">
        <input type="hidden" name="search_bill_no" id="search_bill_no" value="<?php echo $_POST['search_bill_no']; ?>">
        <input type="hidden" name="search_invoice_no" id="search_invoice_no" value="<?php echo $_POST['search_invoice_no']; ?>">
        <input type="hidden" name="search_bi_date_from" id="search_bi_date_from" value="<?php echo $_POST['search_bi_date_from']; ?>">
        <input type="hidden" name="search_bi_date_to" id="search_bi_date_to" value="<?php echo $_POST['search_bi_date_to']; ?>">
        <input type="hidden" name="search_due_date_from" id="search_due_date_from" value="<?php echo $_POST['search_due_date_from']; ?>">
        <input type="hidden" name="search_due_date_to" id="search_due_date_to" value="<?php echo $_POST['search_due_date_to']; ?>">
        <input type="hidden" name="search_customer_type" id="search_customer_type" value="<?php echo $_POST['search_customer_type']; ?>">
        <input type="hidden" name="search_name_val" id="search_name_val" value="<?php echo $_POST['search_name_val']; ?>">
        <input type="hidden" name="search_agent_val" id="search_agent_val" value="<?php echo $_POST['search_agent_val']; ?>">
        <button type="submit" value="submit">save</button>
    </form>
    <script>
        window.onload = function() {
            document.forms['form_save_bill'].submit();
        }
    </script>
<?php
}
?>