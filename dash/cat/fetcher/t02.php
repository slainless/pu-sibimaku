<?php
    $param['table'] = $tbl_cat;
    $param['field'] = array(
        array('name' => 'title as title', 'result' => 'title'),
        array('name' => 'id as id', 'result' => 'id'),
    );
    $param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');

    $result = $exec->select($param);
?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Edit Kegiatan</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="">
                <label for="field-1" class="control-label">Nama Kegiatan</label>
                <input type="text" parsley-trigger="change" required class="form-control" name="title" id="field-1" placeholder="..." value="<?php echo $result['title']; ?>" data-default="<?php echo $result['title']; ?>">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit" 
                data-token="<?php echo $_SESSION['req_token']; ?>" 
                data-primary="<?php echo codeGen("a","f"); ?>" 
                data-mode="<?php echo codeGen("3","5"); ?>"
                data-id="<?php echo codeGen($result['id'], "", true); ?>">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
    $('#popup').parsley();

    $('#popup').on('submit', function(e){ 
        e.preventDefault();
        var that = $('#popup button[name="submit"]');
        var data = $(this).serialize();


        data += "&primary=" + that.attr('data-primary');
        data += "&token=" + that.attr('data-token');
        data += "&mode=" + that.attr('data-mode');
        data += "&data=" + that.attr('data-id');

        console.log(data);

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