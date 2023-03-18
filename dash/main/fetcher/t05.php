<?php
$limit = 5;
$postlimit = return_bytes(ini_get('post_max_size'));

$param['table'] = $dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');
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


$param['table'] = $mail;
$param['where'] = array('name' => 'rel_id', 'value' => $post[0], 'type' => 'i');
$param['field'] = array(
	array('name' => 'status', 'result' => 'status'),
	array('name' => 'target_list', 'result' => 'target_list'),
	array('name' => 'attach', 'result' => 'attach'),
	array('name' => 'tagihan', 'result' => 'tagihan'),
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'target', 'result' => 'target'),
	array('name' => 'time', 'result' => 'time'),
	array('name' => 'comment', 'result' => 'comment'),
	array('name' => 'c_time', 'result' => 'c_time')
);

$check = $exec->select($param);
unset($param);

$truelimit = $limit - count(cta($check['attach']));
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
	    		if($check['status'] == 2): ?>

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

			<div class="dropzone-wrapper <?php if($check && $truelimit === 0) echo 'hidden'; ?>">
				<label class="control-label">Nilai Tagihan</label>
				<div class="m-t-5">
					<div class="input-group parsley-except">
                    	<span class="input-group-addon"><span>RP</span></span>
                    	<input type="text" parsley-trigger="change" class="form-control" name="tagihan" placeholder="...">
                    	<span class="input-group-addon">00</span>
           			</div>
				</div>
	        </div>

        	<?php 
        	endif;

        	// UPLOADED FILE
        	// if mail NOT EMPTY
        	// 
        	if($check): ?>

        		<ul class="list-group dl-horizontal">
					<li class="list-group-item">
	                    <dt>Nilai Tagihan</dt>
	                    <dd><?php echo "Rp. ".number_format($check['tagihan'], 2, ",", "."); ?></dd>
                	</li>
                </ul>

            <?php 
            endif;

            if($check['status'] === 2): ?>
        		<label class="control-label">Keterangan</label>
        		<div class="inline-editor text-muted">

        		<?php echoalt($check['comment'], "-"); ?>

                </div>

            <?php endif;

            // HIDDEN INPUT/SUBMIT BUTTON
            // IF DITOLAK/MAIL EMPTY/BARU

            if($check['status'] == 2 || !$check): 
            ?>

            	<div class="clearfix pull-right m-t-15">
                	<button type="submit" name="submit" class="btn btn-inverse waves-effect waves-light">Submit</button>
                </div>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php if($check && $check['status'] == 2) echo codeGen("4","7"); elseif(!$check) echo codeGen("6","1"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
            
            <?php endif; ?>

	        </form>
	    </div>
	</div>
	<script>
		$('#popup input[name="tagihan"]').mask('999.000.000.000.000', {reverse: true});
    </script>
</div>