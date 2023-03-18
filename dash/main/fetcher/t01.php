<?php

$param['field'] = array(
    array('name' => $dash.'.kategori', 'result' => 'id_kegiatan'),
    array('name' => $dash.'.dp_info', 'result' => 'info_proyek'),
    array('name' => $cat.'.title', 'result' => 'nama_kegiatan'),
    array('name' => $dash.'.id', 'result' => 'id_proyek'),
    array('name' => $dash.'.dp_kontrak', 'result' => 'info_kontrak')
);
$param['table'] = array($dash, $cat);
$param['join_op'] = 'left join';
$param['where'] = array('name' => $dash.'.id', 'value' => $post[0], 'type' => 'i');

$param['on'] = array('name' => $dash.'.kategori', 'target' => $cat.'.id');

$result = $exec->select($param);

$result['nama_proyek'] = cta($result['info_proyek'])[0];
$result['no_kontrak'] = cta($result['info_kontrak'])[0];
$result['tanggal_kontrak'] = str_replace("-", "/", cta($result['info_kontrak'])[1]);
$result['lokasi_proyek'] = cta($result['info_proyek'])[1];

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
					<label class="col-sm-3 control-label">Kegiatan</label>
					<div class="col-sm-9 m-t-5"><?php echoalt($result['nama_kegiatan'], "-"); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Pekerjaan</label>
					<div class="col-sm-9 m-t-5"><?php echoalt($result['nama_proyek'], "-"); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">No. Kontrak</label>
					<div class="col-sm-9 m-t-5"><input type="text" parsley-trigger="change" class="form-control" name="data_t010" placeholder="..." value="<?php echoalt($result['no_kontrak'], ""); ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tanggal Kontrak</label>
					<div class="col-sm-9 m-t-5">
					<div class="input-group parsley-except">
					<input type="text" parsley-trigger="change" class="form-control" name="data_t011" placeholder="..." value="<?php echoalt($result['tanggal_kontrak'], ""); ?>">
					<span class="input-group-addon"><i class="md md-event-note"></i></span>
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Lokasi Kegiatan</label>
					<div class="col-sm-9 m-t-5"><input type="text" parsley-trigger="change" required class="form-control" name="data_t013" placeholder="..." value="<?php echoalt($result['lokasi_proyek'], ""); ?>">
					</div>
				</div>
				<script>
					$('#popup').parsley();
					$('#popup input[name="data_t011"]').datepicker({
                	autoclose: true,
                	todayHighlight: true,
                	format: 'dd/mm/yyyy'
                	});
				</script>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php echo codeGen("5","3"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>