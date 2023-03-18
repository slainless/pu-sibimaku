<?php

$param['field'] = array(
	array('name' => 'lead_id', 'result' => 'id_pptk'),
	array('name' => 'rel_id', 'result' => 'id_kontraktor'),
	array('name' => 'konsultan', 'result' => 'id_konsultan')
);
$param['table'] = $dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');

$value = $exec->select($param);
unset($param);

$stmtq = "SELECT (SELECT name FROM ".$tbl_mem." WHERE level = 5) as kadis, (SELECT name FROM ".$tbl_mem." WHERE level = 4) as ppk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as pptk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as kontraktor, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as konsultan";
$param['param']['type'] = 'iii';
$param['param']['value'] = array($value['id_pptk'], $value['id_kontraktor'], $value['id_konsultan']);

$param['result'] = array('kadis','ppk','pptk', 'kontraktor', 'konsultan');

$result = $exec->freeQuery($stmtq, $param);
unset($param);

$param['field'] = array(
    array('name' => 'name', 'result' => 'nama'),
    array('name' => 'id', 'result' => 'id'),
);
$param['table'] = $mem;
$param['where'] = array('name' => 'level', 'type' => 'i', 'value' => 2);

$member = $exec->select($param);

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
					<label class="col-sm-3 control-label">Pengguna Anggaran</label>
					<div class="col-sm-9 m-t-5"><?php echoalt($result['kadis'], "-"); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Pejabat Pembuat Komitmen</label>
					<div class="col-sm-9 m-t-5"><?php echoalt($result['ppk'], "-"); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Pejabat Pelaksana Teknis Kegiatan</label>
					<div class="col-sm-9 m-t-5"><?php echoalt($result['pptk'], "-"); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kontraktor Pelaksana</label>
	                <div class="col-sm-9 m-t-5">
	                    <select class="form-control select2" name="data_t020">
	                        <option value="">...</option>
	                        <?php
	                            $limitx = count($member['id']);
	                            $selected = "";
	                            for($x=0;$x<$limitx;$x++){
	                                if($member['id'][$x] == $value['id_kontraktor']){
	                                    $selected = "selected";
	                                }
	                                echo "<option value=".$member['id'][$x]." ".$selected.">".$member['nama'][$x]."</option>";
	                                $selected = "";
	                            }
	                        ?>
	                    </select>
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Konsultan Supervisi</label>
					<div class="col-sm-9 m-t-5">
	                    <select class="form-control select2" name="data_t021">
	                        <option value="">...</option>
	                        <?php
	                            $limitx = count($member['id']);
	                            $selected = "";
	                            for($x=0;$x<$limitx;$x++){
	                                if($member['id'][$x] == $value['id_konsultan']){
	                                    $selected = "selected";
	                                }
	                                echo "<option value=".$member['id'][$x]." ".$selected.">".$member['nama'][$x]."</option>";
	                                $selected = "";
	                            }
	                        ?>
	                    </select>
                    </div>
				</div>
				<script>
				</script>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php echo codeGen("3","0"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>