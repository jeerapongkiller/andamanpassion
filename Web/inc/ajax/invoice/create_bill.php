<?php
require("../../../inc/connection.php");
if (!empty($_POST['invoice_id'])) {

    // foreach($_POST['invoice_id'] as $index => $invoice_id)
    // {
    //         echo $invoice_id . '<br/>';
    //         echo $_POST['total_balance'][$index] . '<br/>';
    //         echo '<br/><br/>';
    // }
    $a = '1';
    $period_text = "";
    $period = array_unique($_POST['period']);
    sort($period);
    foreach ($period as $val){
        $period_text .= $a == '1' ? "งวดที่ ".$val : ", งวดที่ ".$val ;
        $a++;
    }
?>
    <form action="./?mode=invoice/save" method="post" id="frmsave" name="frmsave" enctype="multipart/form-data" onsubmit="return Checkfrom_bill();">
        <input type="hidden" name="bo_bi_agent" id="bo_bi_agent" value="<?php echo $_POST["agent"]; ?>">
        <input type="hidden" name="search_voucher_no" id="search_voucher_no" value="<?php echo $_POST['search_voucher_no']; ?>">
        <input type="hidden" name="search_invoice_no" id="search_invoice_no" value="<?php echo $_POST['search_invoice_no']; ?>">
        <input type="hidden" name="search_travel_date_from" id="search_travel_date_from" value="<?php echo $_POST['search_travel_date_from']; ?>">
        <input type="hidden" name="search_travel_date_to" id="search_travel_date_to" value="<?php echo $_POST['search_travel_date_to']; ?>">
        <input type="hidden" name="search_customer_type" id="search_customer_type" value="<?php echo $_POST['search_customer_type']; ?>">
        <input type="hidden" name="search_name_val" id="search_name_val" value="<?php echo $_POST['search_name_val']; ?>">
        <input type="hidden" name="search_agent_val" id="search_agent_val" value="<?php echo $_POST['search_agent_val']; ?>">
        <input type="hidden" name="search_company_val" id="search_company_val" value="<?php echo $_POST['search_company_val']; ?>">
        <table width="900" border="0" cellspacing="0" cellpadding="1" align="center" style="font-size:14px;">
            <tr>
                <td colspan="4" style="text-align: left; border:1px solid #cdcdcd; margin-top:10px; padding:10px 10px;">
                    <div class="" id="check_bi_name">
                        <label for="bo_bi_name">ชื่อ</label>
                        <input type="text" class="form-control" name="bo_bi_name" id="bo_bi_name" value="<?php echo $_POST["agent"] > '0' ? get_value("agent", "id", "company_invoice", $_POST["agent"], $mysqli_p) : ""; ?>"><br />
                    </div>
                    <div class="" id="check_bi_address">
                        <label for="bo_bi_address">ที่อยู่</label>
                        <textarea class="form-control" name="bo_bi_address" id="bo_bi_address" cols="40" rows="3"><?php echo $_POST["agent"] > '0' ? get_value("agent", "id", "address", $_POST["agent"], $mysqli_p) : ""; ?></textarea>
                    </div>
                    <div class="" id="check_bi_period">
                        <label for="bo_bi_detail">หมายเหตุ</label>
                        <input type="text" class="form-control" name="bo_bi_detail" id="bo_bi_detail" value="<?php echo $period_text; ?>"><br />
                    </div>
                </td>
                <td colspan="3" style="text-align: left; border:1px solid #cdcdcd; margin-top:10px; padding:10px 10px;">
                    <div id="check_bi_date">
                        <label for="bo_bi_date"> วันที่เอกสาร </label>
                        <input type="date" class="form-control" id="bo_bi_date" name="bo_bi_date" value="<?php echo $today ?>"><br />
                    </div>
                    <div id="check_due_date">    
                        <label for="bo_due_date"> วันทีครบกำหนด </label>
                        <input type="date" class="form-control" id="bo_due_date" name="bo_due_date" value="<?php echo $today ?>"><br />
                    </div>
                    <div id="check_bi_condition">    
                        <label for="bo_bi_condition"> เงื่อนใขการชำระเงิน </label>
                        <input type="text" class="form-control" name="bo_bi_condition" id="bo_bi_condition">
                    </div>
                </td>
            </tr>
            <tr>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">เลขที่</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">เลขที่ใบแจ้งหนี้</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">วันที่จอง</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">วันที่เดินทาง</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">จำนวนเงิน</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">ชำระแล้ว</th>
                <th style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">เงินคงค้าง</th>
            </tr>
            <?php
            $i = '1';
            $invoice_arr = "";
            foreach ($_POST['invoice_id'] as $index => $id_invo) {
                $total_balance = $_POST['total_balance'][$index];
                $invoice_arr .= $invoice_arr == "" ? $id_invo : "," . $id_invo;
                $query_in = "SELECT invoice.*, invoice_products.products_type as inv_pt, invoice_products.booking as inv_b, invoice_products.booking_products as inv_bp,
                        invoice_products.transfer as inv_t, booking.voucher_no as b_vn, booking.booking_date as b_bate, booking.receipt_name as b_rename, booking.agent_voucher as b_agenyv, booking_products.travel_date as bptd,
                        booking_products.checkout_date as bpcd, booking_products.products_type as ptypeid, booking_products.create_date as bpcred
                    FROM invoice
                    LEFT JOIN invoice_products
                        ON invoice.id = invoice_products.invoice
                    LEFT JOIN booking
                        ON invoice_products.booking = booking.id
                    LEFT JOIN booking_products
                        ON invoice_products.booking_products = booking_products.id
                    WHERE invoice.id = '" . $id_invo . "'
            ";
                $procedural_statement_in = mysqli_prepare($mysqli_p, $query_in);
                mysqli_stmt_execute($procedural_statement_in);
                $result_in = mysqli_stmt_get_result($procedural_statement_in);
                $row_in = mysqli_fetch_array($result_in, MYSQLI_ASSOC);
                if ($row_in["ptypeid"] != '4') {
                    $travel_date_pro = $row_in["bptd"] != "0000-00-00" ? date("Y-m-d", strtotime($row_in["bptd"])) : 'ไม่มีสินค้า';
                } else {
                    $travel_date_pro = $row_in["bpcd"] != "0000-00-00" ? date("Y-m-d", strtotime($row_in["bpcd"])) : 'ไม่มีสินค้า';
                }
            ?>
                <tr>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo $i; ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo $row_in["inv_full"]; ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo date("Y-m-d", strtotime($row_in["bpcred"])); ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo $travel_date_pro; ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;"><?php echo number_format($total_balance, 2) ?></td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">
                            <input type="hidden" name="balance_<?php echo $id_invo; ?>" id="balance_<?php echo $id_invo; ?>" value="<?php echo $total_balance; ?>">
                        <div class="" id="check_bill_paid<?php echo $id_invo; ?>">
                            <input type="text" value="0" step="any" class="form-control" name="bill_paid<?php echo $id_invo; ?>" id="bill_paid<?php echo $id_invo; ?>" oninput="priceformat('bill_paid<?php echo $id_invo; ?>', 'balance_<?php echo $id_invo; ?>', 'text_balance<?php echo $id_invo; ?>');" disabled>
                        </div>
                            <input type="hidden" id="invoice_id" name="invoice_id[]" value="<?php echo $id_invo; ?>">
                    </td>
                    <td style="text-align: center; border:1px solid #cdcdcd; padding:5px 5px;">
                        <div id="text_balance<?php echo $id_invo; ?>"><?php echo number_format($total_balance, 2) ?></div>
                    </td>
                </tr>
            <?php $i++;
            }
            ?>
            <tr>
                <td colspan="7" style="text-align: left; border:1px solid #cdcdcd; padding:5px 5px;">
                    <div class="" id="check_bi_con_detail"> 
                        <label for="bo_bi_con_detail">เงื่อนใขการชำระเงิน</label><br />
                        <textarea class="form-control" name="bo_bi_con_detail" id="bo_bi_con_detail" cols="80" rows="6"></textarea>
                    </div>
                </td>
            </tr>
        </table>
        </br>
        <button class="btn btn-lg btn-primary" type="submit">บันทึก</button>
    </form>
<?php } ?>