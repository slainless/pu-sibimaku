
<?php
$param['table'] = $tbl_mem;
$param['field'] = array(
    array('name' => 'name', 'result' => 'name'),
    array('name' => 'id', 'result' => 'id')
);

$param['where'] = array('name' => 'level', 'type' => 'i', 'value' => 3);
$param['option']['force_array'] = true;
$param['option']['transpose'] = true;

$member = $exec->select($param);
?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Tambah Pekerjaan</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="">
                <div class="form-group">
                    <label for="field-1" class="control-label">Nama Pekerjaan</label>
                    <input type="text" parsley-trigger="change" required class="form-control" name="title" id="field-1" placeholder="Masukkan nama">
                </div>
                <div class="form-group">
                    <label for="field-2" class="control-label">Nilai Pagu Dana</label>
                    <div class="input-group m-t-10 parsley-except">
                        <span class="input-group-addon"><span>RP</span></span>
                        <input type="text" parsley-trigger="change" required class="form-control" name="pagu" id="field-2" placeholder="Masukkan nilai">
                        <span class="input-group-addon">,00</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-2" class="control-label">PPTK</label>
                    <select class="form-control select2" id="field-3" required parsley-trigger="change" name="pptk">
                        <option value="">Pilih PPTK</option>
                        <?php
                            $limitx = count($member);
                            for($x=0;$x<$limitx;$x++){
                                echo "<option value=".$member[$x]['id'].">".$member[$x]['name']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit" 
                data-token="<?php echo $_SESSION['req_token']; ?>" 
                data-primary="<?php echo codeGen("c","8"); ?>" 
                data-mode="<?php echo codeGen("9","0"); ?>"
                data-id="<?php echo codeGen($code['id'], "", true); ?>">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
    $('#popup').parsley();
    $('#popup input[name="pagu"]').mask('999.000.000.000.000', {reverse: true});

    $('#popup').on('submit', function(e){ 
        e.preventDefault();
        var that = $('#popup button[name="submit"]');
        var data = $(this).serialize();

        data += "&primary=" + that.attr('data-primary');
        data += "&token=" + that.attr('data-token');
        data += "&mode=" + that.attr('data-mode');
        data += "&data=" + that.attr('data-id');

        $.ajax({

            type: "POST",
            url: '/dash/catman/processor',
            data: data, // serializes the form's elements.

        }).done(function( str ) {
            var data = JSONParser(str);
            if(data){
                swal({
                    title: data.title,
                    text: data.text,
                    type: data.type,
                    showConfirmButton: false,
                    timer: data.timer
                });

                $('#panel-modal').modal('hide');
                $('#data').attr("data-token", data.token).bootstrapTable('refresh');

            }
            else {
            }
        });

    });
</script>