<?php if(!empty($_POST['period']) && !empty($_POST['vat']) && !empty($_POST['products_id'])){  
    $period = $_POST['period'];
    $products_id = $_POST['products_id'];
    $vat = $_POST['vat'];
?>
<form class="needs-validation" method="post" id="frminvoice" name="frminvoice" action="./?mode=ar/create_invoice_normal" >
    <input type="hidden" name="period" id="period" value="<?php echo $period; ?>">
    <input type="hidden" name="vat" id="vat" value="<?php echo $vat; ?>">
    <input type="hidden" name="search_voucher_no" id="search_voucher_no" value="<?php echo $_POST['search_voucher_no']; ?>">
    <input type="hidden" name="search_customer_firstname" id="search_customer_firstname" value="<?php echo $_POST['search_customer_firstname']; ?>">
    <input type="hidden" name="search_customer_lastname" id="search_customer_lastname" value="<?php echo $_POST['search_customer_lastname']; ?>">
    <input type="hidden" name="search_travel_date_from" id="search_travel_date_from" value="<?php echo $_POST['search_travel_date_from']; ?>">
    <input type="hidden" name="search_travel_date_to" id="search_travel_date_to" value="<?php echo $_POST['search_travel_date_to']; ?>">
    <?php foreach ($_POST['products_id'] as $index => $products_id) { ?>
        <input type="hidden" name="products_id[]" id="products_id" value="<?php echo $products_id; ?>">
    <?php } ?>
    <div class="form-row mb-3">
        <?php for($i=1;$i<=$period;$i++){ ?>
            <div class="col-md-12 mb-3">
            <label for="period_<?php echo $i; ?>">งวดที่ <?php echo $i; ?></label>
                <select class="form-control" id="percent_<?php echo $i; ?>" name="percent_<?php echo $i; ?>">
                    <?php if($period != '1'){ ?>
                        <option value="">เลือกเปอร์เซ็น</option>
                        <?php for($p=10;$p<=100;$p=$p+5){ ?>
                            <option value="<?php echo $p; ?>"><?php echo $p."%"; ?></option>
                        <?php } ?>
                    <?php }else{ ?>
                        <option value="100">100%</option>
                    <?php } ?>
                </select>
            </div>
        <?php } ?>
    </div>
    <button class="btn btn-lg btn-primary" type="submit">บันทึก</button>
</form>
<?php } ?>