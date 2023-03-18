<?php
$limit = 5;
$postlimit = return_bytes(ini_get('post_max_size'));

$param['table'] = $tbl_dash;
$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
$param['field'] = array(
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'lead_id', 'result' => 'lead_id'),
	array('name' => 'n_kontrak', 'result' => 'kontrak'),
); // TO ADD : CHECK PROGRESS

$checkDash = $exec->select($param);
unset($param);

if($s_level == 2 && $s_id == $checkDash['rel_id']){

}
else {
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
}


$param['table'] = $tbl_mail;
$param['where'] = array('name' => 'rel_id', 'value' => $code['id'], 'type' => 'i');
$param['field'] = array(
	array('name' => 'status', 'result' => 'status'),
	array('name' => 'target_list', 'result' => 'target_list'),
	array('name' => 'attach', 'result' => 'attach'),
	array('name' => 'tagihan', 'result' => 'tagihan'),
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'target', 'result' => 'target'),
	array('name' => 'time', 'result' => 'time'),
	array('name' => 'comment', 'result' => 'comment'),
	array('name' => 'time_mod', 'result' => 'time_mod')
);

$check = $exec->select($param);
unset($param);

if($check)
    if($check['status'] === 2 && $check['target'] === 0)
        $check['status_per'] = 1;
    else
        $check['status_per'] = 0;
?>
<div class="panel panel-color panel-primary">
	<style>
	</style>
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Permintaan Pembayaran</h3>
    </div>
	<div class="panel-body">
	    <div class="form-group">
	        <form method="POST" action="" id="popup" data-parsley-validate="">
        	<?php
    		// status 0 = baru
    		// status 2 = ditolak
    		// 
    		// STATUS
    		// if mail NOT empty
    		if($check):
    			// $check['status'] = 2; //debug
	    		if($check['status_per']): ?>

	    			<div class="m-b-10 text-right clearfix">
	    				<span class="label label-danger pull-left">Ditolak</span>
	    				<span class="text-danger">Berkas ditolak. Silahkan revisi</span>
	    			</div>

					<?php
	    		else: ?>

	    			<div class="m-b-10 text-right clearfix">
	    				<span class="label label-inverse pull-left">Diproses</span>
	    				<span class="text-muted">Pemintaan sedang diproses</span>
	    			</div>
	    		


	    	<?php endif;
	    	endif;
            	
    		// DROPZONE
    		// if mail empty

			if(!$check): ?>

			<ul class="list-group dl-horizontal">
                <li class="list-group-item">
                    <dt>Jenis Tagihan</dt>
                    <dd>
                        <select name="jenis" required="">
                            <option value="">...</option>
                            <option value="">Uang Muka</option>
                        </select>
                    </dd>
                </li>
                <!-- <li class="list-group-item">
                    <dt>Nilai Tagihan</dt>
                    <dd>
    					<div class="input-group parsley-except">
                        	<span class="input-group-addon"><span>RP</span></span>
                        	<input type="text" parsley-trigger="change" required class="form-control" name="tagihan" placeholder="...">
                        	<span class="input-group-addon">00</span>
               			</div>
                    </dd>
                </li> -->
            </ul>

        	<?php 
        	endif;

        	// UPLOADED FILE
        	// if mail NOT EMPTY
        	// 
        	if($check): ?>

        		<ul class="list-group <?php if(!$check['status_per']) echo 'dl-horizontal'; ?>">
					<li class="list-group-item">
	                    <dt>Nilai Tagihan</dt>
	                    <dd>
                            <?php if($check['status_per']): ?>
                            <div class="input-group parsley-except">
                                <span class="input-group-addon"><span>RP</span></span>
                                <input type="text" parsley-trigger="change" required class="form-control" name="tagihan" placeholder="..." value="<?php echo "Rp. ".number_format($check['tagihan'], 2, ",", "."); ?>">
                                <span class="input-group-addon">00</span>
                            </div>
                            <?php else: echo "Rp. ".number_format($check['tagihan'], 2, ",", "."); endif; ?>
                        </dd>
                	</li>

                    <?php if($check['status_per']): ?>
                    <li class="list-group-item">
                        <dt>Keterangan</dt>
                        <dd>
                            <div class="inline-editor text-muted">
                            <?php echoalt($check['comment'], "-"); ?>
                            </div>
                        </dd>
                    </li>

                    <?php endif; ?>
                </ul>

            <?php 
            endif;

            // HIDDEN INPUT/SUBMIT BUTTON
            // IF DITOLAK/MAIL EMPTY/BARU

            if($check['status_per'] || !$check): 
            ?>

            	<div class="clearfix pull-right m-t-15">
                	<button type="submit" name="submit" class="btn btn-inverse waves-effect waves-light"  
                    data-token="<?php echo $_SESSION['req_token']; ?>" 
                    data-primary="<?php echo codeGen("e","4"); ?>" 
                    data-mode="<?php echo !$check['status_per'] ? codeGen("e","4") : codeGen("4","2"); ?>"
                    data-id="<?php echo codeGen($code['id'], "", true); ?>">Submit</button>
                </div>
            
            <?php endif; ?>

	        </form>
	    </div>
	</div>
	<script>
		$('#popup input[name="tagihan"]').mask('999.000.000.000.000', {reverse: true});

        $('#popup').parsley();

        <?php if($check['status_per'] || !$check): ?>
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
                url: '/dash/dashboard/processor',
                data: data, // serializes the form's elements.

            }).done(function( str ) {
                var data = JSONParser(str);
                if(data){
                    $('#panel-modal').modal('hide');
                    $('#info').attr("data-token", data.token);

                    swal({
                        title: data.alert.title,
                        text: data.alert.text,
                        type: data.alert.type,
                        showConfirmButton: data.alert.confirm,
                        timer: data.alert.timer
                    }, function (isConfirm) {
                        if (isConfirm) {
                            location.reload();
                        }
                    });

                }
                else {
                }
            });

        });
        <?php endif; ?>

    </script>
</div>