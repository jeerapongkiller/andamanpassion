<?php
require("../../../inc/connection.php");
if (!empty($_POST['bill'])) {
    $bo_bi_name = get_value("bill", "id", "bi_name", $_POST['bill'],$mysqli_p);
    $bo_bi_address = get_value("bill", "id", "bi_address", $_POST['bill'],$mysqli_p);
    $bo_bi_date = get_value("bill", "id", "bi_date", $_POST['bill'],$mysqli_p);
    $bo_due_date = get_value("bill", "id", "due_date", $_POST['bill'],$mysqli_p);
    $bo_bi_condition = get_value("bill", "id", "bi_condition", $_POST['bill'],$mysqli_p);
    $bo_bi_con_detail = get_value("bill", "id", "bi_con_detail", $_POST['bill'],$mysqli_p);
    $bo_bi_agent = get_value("bill", "id", "agent", $_POST['bill'],$mysqli_p);

    // echo "Bill : ".$_POST['bill']."</br>"; ./?mode=bill/save
?>
    <form action="#" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" onsubmit="return Checkfrom_bill();">
        <input type="hidden" name="bo_bi_bill" id="bo_bi_bill" value="<?php echo $_POST['bill']; ?>">
        <input type="hidden" name="bo_bi_agent" id="bo_bi_agent" value="<?php echo $bo_bi_agent; ?>">
        <input type="hidden" name="search_bill_no" id="search_bill_no" value="<?php echo $_POST['search_bill_no']; ?>">
        <input type="hidden" name="search_invoice_no" id="search_invoice_no" value="<?php echo $_POST['search_invoice_no']; ?>">
        <input type="hidden" name="search_bi_date_from" id="search_bi_date_from" value="<?php echo $_POST['search_bi_date_from']; ?>">
        <input type="hidden" name="search_bi_date_to" id="search_bi_date_to" value="<?php echo $_POST['search_bi_date_to']; ?>">
        <input type="hidden" name="search_due_date_from" id="search_due_date_from" value="<?php echo $_POST['search_due_date_from']; ?>">
        <input type="hidden" name="search_due_date_to" id="search_due_date_to" value="<?php echo $_POST['search_due_date_to']; ?>">
        <input type="hidden" name="search_customer_type" id="search_customer_type" value="<?php echo $_POST['search_customer_type']; ?>">
        <input type="hidden" name="search_name_val" id="search_name_val" value="<?php echo $_POST['search_name_val']; ?>">
        <input type="hidden" name="search_agent_val" id="search_agent_val" value="<?php echo $_POST['search_agent_val']; ?>">
        <table width="900" border="0" cellspacing="0" cellpadding="1" align="center" style="font-size:14px;">
            <tr>
                <td colspan="4" style="text-align: left; border:1px solid #cdcdcd; margin-top:10px; padding:10px 10px;">
                    <div class="" id="check_bi_name">
                        <label for="bo_bi_name">ชื่อ</label>
                        <input type="text" class="form-control" name="bo_bi_name" id="bo_bi_name" value="<?php echo $bo_bi_name; ?>"><br />
                    </div>
                    <div class="" id="check_bi_address">
                        <label for="bo_bi_address">ที่อยู่</label>
                        <textarea class="form-control" name="bo_bi_address" id="bo_bi_address" cols="40" rows="4"><?php echo $bo_bi_address; ?></textarea>
                    </div>
                </td>
                <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; margin-top:10px; padding:10px 10px;">
                    <div class="" id="check_bi_date">
                        <label for="bo_bi_date"> วันที่เอกสาร </label>
                        <input type="date" class="form-control" name="bo_bi_date" id="bo_bi_date" value="<?php echo $bo_bi_date; ?>"><br />
                    </div>
                    <div class="" id="check_due_date">    
                        <label for="bo_due_date"> วันทีครบกำหนด </label>
                        <input type="date" class="form-control" name="bo_due_date" id="bo_due_date" value="<?php echo $bo_due_date; ?>"><br />
                    </div>
                    <div class="" id="check_bi_condition">    
                        <label for="bo_bi_condition"> เงื่อนใขการชำระเงิน </label>
                        <input type="text" class="form-control" name="bo_bi_condition" id="bo_bi_condition" value="<?php echo $bo_bi_condition; ?>" >
                    </div>
                </td>
            </tr>
            <tr>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">เลขที่ใบแจ้งหนี้</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">วันที่จอง</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">วันที่เดินทาง</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">จำนวนเงิน</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">ชำระแล้ว</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">เงินคงค้าง</th>
            </tr>
            <?php 
                $query_bi = "SELECT bill_invoice.*, invoice.inv_full as inv_full, booking.voucher_no as b_vn, booking.booking_date as b_bate, 
                invoice_products.total_invoice as inv_tt, booking.receipt_name as b_rename, booking.agent_voucher as b_agenyv, booking_products.travel_date as bptd,
                booking_products.checkout_date as bpcd, booking_products.products_type as ptypeid, booking_products.create_date as bpcred
                FROM bill_invoice 
                LEFT JOIN invoice
                    ON bill_invoice.invoice = invoice.id
                LEFT JOIN invoice_products
                    ON bill_invoice.invoice = invoice_products.id
                LEFT JOIN booking
                    ON bill_invoice.booking = booking.id
                LEFT JOIN booking_products
                    ON bill_invoice.booking_products = booking_products.id   
                WHERE bill_invoice.bill = '".$_POST['bill']."' 
                ";

                $procedural_statement_bi = mysqli_prepare($mysqli_p, $query_bi);
                mysqli_stmt_execute($procedural_statement_bi);
                $result_bi = mysqli_stmt_get_result($procedural_statement_bi);
                while($row_bi = mysqli_fetch_array($result_bi, MYSQLI_ASSOC)){
                    if ($row_bi["ptypeid"] != '4') {
                        $travel_date_pro = $row_bi["bptd"] != "0000-00-00" ? date("Y-m-d", strtotime($row_bi["bptd"])) : 'ไม่มีสินค้า';
                    } else {
                        $travel_date_pro = $row_bi["bpcd"] != "0000-00-00" ? date("Y-m-d", strtotime($row_bi["bpcd"])) : 'ไม่มีสินค้า';
                    }
                    $total_balance = $row_bi["inv_tt"] - $row_bi["pay_bill"];
            ?>
            <input type="hidden" id="invoice_id" name="invoice_id[]" value="<?php echo $id_invo; ?>">
            <tr>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo $row_bi["inv_full"]; ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo date("Y-m-d", strtotime($row_bi["bpcred"])); ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo $travel_date_pro; ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo number_format($row_bi["inv_tt"], 2) ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo number_format($row_bi["pay_bill"], 2) ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">
                        <div id="text_balance<?php echo $id_invo; ?>"><?php echo number_format($total_balance, 2) ?></div>
                    </td>
                </tr>
                <?php } /* while($row_bi = mysqli_fetch_array($result_bi, MYSQLI_ASSOC)){ */ ?>
            <tr>
                <td colspan="7" style="text-align: left; border:1px solid #cdcdcd; padding:5px 5px;">
                    <div class="" id="check_bi_con_detail"> 
                        <label for="bo_bi_con_detail">เงื่อนใขการชำระเงิน</label><br />
                        <textarea class="form-control" name="bo_bi_con_detail" id="bo_bi_con_detail" cols="80" rows="6"><?php echo $bo_bi_con_detail; ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
        </br>
        <button class="btn btn-lg btn-primary" type="submit">บันทึก</button>
    </form>
<?php } ?>