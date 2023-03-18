<?php

$param['table'] = array($dash, $mem);
$param['where'] = array('name' => $dash.'.id', 'value' => $get_id, 'type' => 'i');
$param['join_op'] = 'left join';
$param['on'] = array('name' => $dash.'.rel_id', 'target' => $mem.'.id');
$param['field'] = array(
	array('name' => $dash.'.rel_id', 'result' => 'rel_id'),
	array('name' => $dash.'.lead_id', 'result' => 'lead_id'),
	array('name' => $dash.'.dp_info', 'result' => 'dp_info'),
	array('name' => $mem.'.name', 'result' => 'name'),
); // TO ADD : CHECK PROGRESS

$checkDash = $exec->select($param);
unset($param);

if($s_level == 3 && $s_id == $checkDash['lead_id']){

}
elseif($s_level > 3){

}
else {
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
}


$param['table'] = $mail;
$param['where'] = array('name' => 'rel_id', 'value' => $get_id, 'type' => 'i');
$param['field'] = array(
	array('name' => 'status', 'result' => 'status'),
	array('name' => 'target_list', 'result' => 'target_list'),
	array('name' => 'attach', 'result' => 'attach'),
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'target', 'result' => 'target'),
	array('name' => 'time', 'result' => 'time'),
	array('name' => 'comment', 'result' => 'comment'),
	array('name' => 'c_time', 'result' => 'c_time')
);

$check = $exec->select($param);
unset($param);

if(!$check){
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
}

?>
<div class="panel panel-color panel-primary">
	<style>
	.note-popover .popover .arrow {
	    left: 20px !important; 
	}
	.note-popover .note-table, .note-popover .note-insert {
	    display: none !important; 
	}
	
	</style>
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Permintaan Pembayaran</h3>
    </div>
	<div class="panel-body">
	    <div class="form-group">
	        <form method="POST" action="" id="popup" data-parsley-validate="">
                <div class="m-b-10 text-right clearfix">
                	<?php switch ($check['status']) {

                		case '1':
                			
                			if($s_level == 3): ?>
                			<span class="label label-success pull-left m-r-5">Diterima</span>
                			<span class="label label-inverse pull-left">Sedang Diproses</span>
	    					<span class="text-success"><?php echo $checkDash['name']; ?></span>
	    					<?php elseif($s_level == 4): ?>
                			<span class="label label-purple pull-left">Baru</span>
	    					<span class="text-purple"><?php echo $checkDash['name']; ?></span>
	    					<?php endif;
                			break;
                		
                		default:
                			# code...
                			break;
                	}
	    			?>
	    			</div>
	        	<label class="control-label">Subyek</label>
                <div class="m-b-10">
                	<span class="text-muted"><?php echo cta($checkDash['dp_info'])[0]; ?></span>
                </div>
                <label class="control-label hidden">Berkas</label>
                <div class="bootstrap-tagsinput m-b-10">

        	<?php
        	$check['attach'] = cta($check['attach']);
        	$y = count($check['attach']);

        	for($x=0;$x<$y;$x++):

	        	$z = explode("/", $check['attach'][$x]);
	        	$zx = count($z);

	        	$check['attach'][$x] = $abs_dir_upload.$z[$zx-1];
	        ?>

					<span class="tag label label-info">
						<a style="color: white;" href="<?php echo $check['attach'][$x]; ?>" target="_blank"><?php echo $z[$zx-1]; ?></a>
					</span>

        	<?php
        	endfor;
        	?>

        		</div>

        		<label class="control-label">Keterangan</label>
        		<div class="inline-editor text-muted">

        		<?php if(empty($check['comment']) && ($check['status'] === 1 || $check['status'] === 5)) echo "(*) Opsional"; else echoalt($check['comment'], "-"); ?>

                </div>

                <?php if($check['status'] === 1 || $check['status'] === 5): ?>
                <label class="control-label">Status</label>
                <div>

                    <div class="radio">
                        <input name="data_y" id="radio1" value="" checked="" required type="radio">
                        <label for="radio1">
                            Pilih Status
                        </label>
                    </div>
                    <div class="radio radio-success">
                        <input name="data_y" id="radio4" value=1 required type="radio">
                        <label for="radio4">
                            Diterima
                        </label>
                    </div><button type="submit" class="btn btn-inverse waves-effect waves-light pull-right" name="submit">Submit</button>
                    <div class="radio radio-danger">
                        <input name="data_y" id="radio6" value=2 required type="radio">
                        <label for="radio6">
                            Ditolak
                        </label>
                    </div>
					<input type="hidden" name="data_x">
	                <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="status">
	                <input type="hidden" value="<?php echo codeGen("9","0"); ?>" name="device">
	                <input type="hidden" value="<?php 
	                	if(($check['status'] === 0 || $check['status'] === 3) && $s_level == 3): echo codeGen("1","9"); 
	                	elseif($check['status'] === 1 || $check['status'] === 5): echo codeGen("5","5");
	                	endif; ?>" name="device_statistic">
	                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
	                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
	            	<?php endif; ?>
                </div>
	        </form>
	    </div>
	</div>
	<script>
	$('#popup').parsley();
	<?php if((($check['status'] === 0 || $check['status'] === 3) && $s_level == 3) || (($check['status'] === 1 || $check['status'] === 5) && $s_level == 4)): ?>
	$('.inline-editor').summernote({
        airMode: true,
        placeholder: 'Isi keterangan... (Opsional)'
    });

    $('button[name="submit"]').on('click', function(e) {
    	$("input[name='data_x']").val($(".inline-editor").html());
    });
    <?php endif; ?>
    </script>
</div>