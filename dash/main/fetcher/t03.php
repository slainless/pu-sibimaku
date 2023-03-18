<?php

$param['field'] = array('name' => 'dp_surat', 'result' => 'info_surat');
$param['table'] = $dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');

$result = $exec->select($param);

if($result['info_surat'] != ""):
$result['info_surat_serah'] = cta($result['info_surat'])[0];
$result['info_surat_kerja'] = cta($result['info_surat'])[1];
$result['info_surat_masa_1'] = cta($result['info_surat'])[2];
$result['info_surat_masa_2'] = cta($result['info_surat'])[3];
endif;
?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Pelaksanaan</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="" class="form-horizontal">
                <div class="form-group">
					<label class="col-sm-3 control-label">Surat Penyerahan Lapangan</label>
					<div class="col-sm-9 m-t-5">
						<input type="text" parsley-trigger="change"  required class="form-control" name="data_t030" placeholder="..." value="<?php if(isset($result['info_surat_serah'])) echo $result['info_surat_serah']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Surat Perintah Mulai Kerja</label>
					<div class="col-sm-9 m-t-5">
						<input type="text" parsley-trigger="change" required class="form-control" name="data_t031" placeholder="..." value="<?php if(isset($result['info_surat_kerja'])) echo $result['info_surat_kerja']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Masa Pelaksanaan</label>
					<div class="col-sm-9 m-t-5">
						<div class="input-group parsley-except">
							<input type="text" parsley-trigger="change" required class="form-control" name="data_t032" placeholder="..." value="<?php if(isset($result['info_surat_masa_1'])) echo $result['info_surat_masa_1']; ?>">
							<span class="input-group-addon">Hari</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Masa Pemeliharaan</label>
					<div class="col-sm-9 m-t-5">
						<div class="input-group parsley-except">
							<input type="text" parsley-trigger="change" required class="form-control" name="data_t033" placeholder="..." value="<?php if(isset($result['info_surat_masa_2'])) echo $result['info_surat_masa_2']; ?>">
							<span class="input-group-addon">Hari</span>
						</div>
					</div>
				</div>
				<script>
					$('#popup').parsley();
					$('#popup input[name="data_t032"]').mask('999', {reverse: true});
					$('#popup input[name="data_t033"]').mask('999', {reverse: true});
				</script>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php echo codeGen("9","6"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>