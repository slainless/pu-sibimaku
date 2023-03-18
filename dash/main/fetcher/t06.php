<?php

$param['field'] = array(
    array('name' => 'ke_info', 'result' => 'penyerapan'),
    array('name' => 'dp_addendum', 'result' => 'info_addendum'),
    array('name' => 'n_kontrak', 'result' => 'kontrak'),
    array('name' => 'n_pagu', 'result' => 'pagu'),
);
$param['table'] = $tbl_dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');


$result = $exec->select($param);

$result['info_addendum'] = cta($result['info_addendum'], true);
?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Info Proyek</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="" class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nilai Pagu</label>
					<div class="col-sm-9 m-t-5"><?php echoalt("Rp. ".number_format($result['pagu'], 2, ",", "."), "-"); ?></div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nilai Kontrak</label>
					<div class="col-sm-9 m-t-5">
					<div class="input-group parsley-except">
                    <span class="input-group-addon"><span>RP</span></span>
                    <input type="text" parsley-trigger="change" class="form-control" name="data_t060" placeholder="..." value="<?php echoalt($result['kontrak'], ""); ?>">
                    <span class="input-group-addon">00</span>
                    </div>
                    </div>
                </div>
				<?php if($result['info_addendum'])
					foreach ($result['info_addendum'] as $key => $value): ?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Nilai Addendum Kontrak 0<?php echo $key + 1; ?></label>
					<div class="col-sm-9 m-t-5"><?php echoalt("Rp. ".number_format($value[2], 2, ",", "."), "-"); ?></div>
				</div>
				<?php endforeach; ?>
				<div class="form-group">
					<label class="col-sm-3 control-label">Penyerapan</label>
					<div class="col-sm-9 m-t-5">
					<div class="input-group parsley-except">
                    <span class="input-group-addon"><span>RP</span></span>
					<input type="text" parsley-trigger="change" class="form-control" name="data_t061" placeholder="..." value="<?php echoalt($result['penyerapan'], ""); ?>">
                    <span class="input-group-addon">00</span>
                    </div>
                    </div>
                </div>
				<script>
					$('#popup').parsley();
					$('#popup input[name="data_t060"]').mask('999.000.000.000.000', {reverse: true});
					$('#popup input[name="data_t061"]').mask('999.000.000.000.000', {reverse: true});
				</script>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php echo codeGen("5","f"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>