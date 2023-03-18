<?php
$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
$param['field'] = array(
	array('name' => 'rel_id', 'result' => 'id_kontraktor'), 
	array('name' => 'lead_id', 'result' => 'id_pptk'), 
	array('name' => 'konsultan', 'result' => 'konsultan')
);
$param['table'] = $tbl_dash;
$result = $exec->select($param);
unset($param);

$stmtq = "SELECT (SELECT name FROM ".$tbl_mem." WHERE level = 5) as kadis, (SELECT name FROM ".$tbl_mem." WHERE level = 4) as ppk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as pptk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as kontraktor, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as konsultan";
$param['param']['type'] = 'iii';
$param['param']['value'] = array($result['id_pptk'], $result['id_kontraktor'], $result['konsultan']);

$param['result'] = array('kadis','ppk','pptk', 'kontraktor', 'konsultan');

$pelaksana = $exec->freeQuery($stmtq, $param);
unset($param);

ob_start();
?>

<ul class="list-group dl-horizontal">
	<li class="list-group-item">
        <dt>Pengguna Anggaran</dt>
        <dd><?php echoalt($pelaksana['kadis'], '-'); ?></dd>
   	</li>
   	<li class="list-group-item">
        <dt>Pejabat Pembuat Komitmen</dt>
        <dd><?php echoalt($pelaksana['ppk'], '-'); ?></dd>
   	</li>
   	<li class="list-group-item">
        <dt>Pejabat Pelaksana Teknis Kegiatan</dt>
        <dd><?php echoalt($pelaksana['pptk'], '-'); ?></dd>
   	</li>
   	<li class="list-group-item">
        <dt>Kontraktor Pelaksana</dt>
        <dd><?php echoalt($pelaksana['kontraktor'], '-'); ?></dd>
   	</li>
   	<li class="list-group-item">
        <dt>Konsultan Supervisi</dt>
        <dd><?php echoalt($pelaksana['konsultan'], '-'); ?></dd>
   	</li>
</ul>
<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right m-t-m-25 clearfix" data-toggle="modal" data-target="#panel-modal" value="2" type="button" data-primary="<?php echo codeGen("b","3"); ?>" data-mode="<?php echo codeGen("b","4"); ?>">Edit</button>

<?php
$outsource['data_0'] = ob_get_contents();
ob_end_clean();