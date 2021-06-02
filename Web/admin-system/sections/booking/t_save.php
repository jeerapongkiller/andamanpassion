<!-- <div class="col-12">
    <form class="needs-validation" method="post" id="frmsearch" name="frmsearch" action="./?mode=booking/t_save">
        <div class="form-row">
            <?php for ($i = 1; $i <= 4; $i++) { ?>
                <div class="col-md-1 mb-1">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="type_e<?php echo $i; ?>" name="type_e[]" value="<?php echo $i; ?>" onclick="check_type(<?php echo $i; ?>)">
                        <label class="custom-control-label" for="type_e<?php echo $i; ?>"> E <?php echo $i; ?></label>
                    </div>
                </div>
            <?php } ?>
            <input type="hidden" id="de_count" name="de_count" value="4">
        </div>

        <?php for ($i = 1; $i <= 4; $i++) { ?>
            <div class="form-row" id="div_t<?php echo $i; ?>">
                <?php for ($a = 1; $a <= 4; $a++) { ?>
                    <div class="col-md-2 mb-1">
                        <label for="type_t<?php echo $a; ?>"> Test <?php echo $a; ?> - (E <?php echo $i; ?>) </label>
                        <input type="text" class="form-control" id="type_t<?php echo $a; ?>" name="type_t<?php echo $i; ?>[]" placeholder="" value="">
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <hr>

        <button class="btn btn-primary mb-3" type="submit">ค้นหา</button>
    </form>
</div>

<script>
    for (var index = 1; index <= 4; index++) {
        var div_t = document.getElementById('div_t' + index)
        div_t.hidden = true;
    }

    function check_type(id) {
        var type_e = document.getElementById('type_e' + id);
        var div_t = document.getElementById('div_t' + id);
        if (type_e.checked == true) {
            div_t.hidden = false;
        } else {
            div_t.hidden = true;
        }
    }
</script> -->
<?php
    // $count_e = count($_POST['type_e']);
    // echo 'E </br>';
    // print_r($_POST['type_e']);
    // echo '<hr>';
    // for ($i=1; $i <= $_POST['de_count']; $i++) { 
    //     if (!empty(in_array($i,$_POST['type_e']))) {
    //         echo 'E-'.$i.'</br>';
    //         print_r($_POST['type_t'.$i]);
    //         echo '</br>';
    //         for ($a=0; $a < sizeof($_POST['type_t'.$i]); $a++) { 
    //             if(!empty($_POST['type_t'.$i][$a])) {
    //                 echo 'T - '.$_POST['type_t'.$i][$a].', ';
    //             }
    //         }
    //         echo '<hr>';
    //     }
    // }
