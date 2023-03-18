<?php
$param['where'] = array('name' => $tbl_dash.'.id', 'value' => $code['id'], 'type' => 'i'); 

$param['field'] = array(
    array('name' => $tbl_dash.'.dp_info', 'result' => 'info_proyek'), 
    array('name' => $tbl_dash.'.dp_kontrak', 'result' => 'info_kontrak'),
    array('name' => $tbl_cat.'.title', 'result' => 'nama_kegiatan'),
    array('name' => $tbl_dash.'.dp_surat', 'result' => 'info_surat'), 
    array('name' => $tbl_cat.'.tahun', 'result' => 'tahun')
);
$param['table'] = array($tbl_dash, $tbl_cat);
$param['join_op'] = array('left join');
$param['on'] = array(
        array('name' => $tbl_dash.'.kategori', 'target' => $tbl_cat.'.id'),
);

$result = $exec->select($param);
unset($param);

$result['info_surat'] = cta($result['info_surat']);
$result['info_kontrak'] = cta($result['info_kontrak']);
$result['info_proyek'] = cta($result['info_proyek']);

if($result['info_surat']):
    $temp = $result['info_surat'][2] - 1;
    $result['info_akhir_waktu'] = strtotime("+".$temp." days", strtotime(reformDate($result['info_kontrak'][1])));
    unset($temp);
endif;

if($result['info_surat'] && !(empty($result['info_surat'][2]) && empty($result['info_kontrak'][1]))):
    $temp = $result['info_akhir_waktu'];
    $temp = ceil(($temp-time())/60/60/24);

    $f_waktu = 0;
    $result['waktu_sisa'] = $temp;
    $result['waktu_terpakai'] = $result['info_surat'][2]  - $result['waktu_sisa'];

    if($result['waktu_terpakai'] <= 0) { $result['waktu_terpakai'] = 0; $result['waktu_sisa'] = $result['info_surat'][2]; $f_waktu = 1; }
    elseif($result['waktu_terpakai'] > $result['info_surat'][2]) { $result['waktu_sisa'] = 0; $result['waktu_terpakai'] = $result['info_surat'][2];
    };

    if(empty($result['info_surat'][2])){
        $result['persen_waktu_terpakai'] = $result['persen_waktu_sisa'] = 0;
    }
    else {
        $result['persen_waktu_terpakai'] = round($result['waktu_terpakai']/($result['waktu_terpakai'] + $result['waktu_sisa']) * 100, 1);
        $result['persen_waktu_sisa'] = 100 - $result['persen_waktu_terpakai'];
    }
else:
    $result['waktu_sisa'] = $result['waktu_terpakai'] = $result['persen_waktu_terpakai'] = 0;
    $result['persen_waktu_sisa'] = 100;
endif;


$outsource['waktu_sisa'] = $result['waktu_sisa'];
$outsource['waktu_terpakai'] = $result['waktu_terpakai'];

ob_start();
?>
<ul class="list-group dl-horizontal">
    <li class="list-group-item">
        <dt>Kegiatan</dt>
        <dd><?php echoalt($result['nama_kegiatan'], '-'); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Pekerjaan</dt>
        <dd><?php echoalt($result['info_proyek'][0], '-'); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>No. Kontrak</dt>
        <dd><?php echoalt($result['info_kontrak'][0], '-'); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Tgl. Kontrak</dt>
        <dd><?php echoalt(customDate($result['info_kontrak'][1]), '-'); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Tahun</dt>
        <dd><?php echoalt($result['tahun'], '-'); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Lokasi Kegiatan</dt>
        <dd><?php echoalt($result['info_proyek'][1], '-'); ?></dd>
    </li>
</ul>
<button class="btn btn-rounded btn-primary waves-effect waves-light trigger m-t-m-25 pull-right clearfix" data-toggle="modal" data-target="#panel-modal" value="1" type="button" data-primary="<?php echo codeGen("b","3"); ?>" data-mode="<?php echo codeGen("8","1"); ?>">Edit</button>
<?php
$outsource['data_0'] = ob_get_contents();
ob_end_clean();

ob_start();
?>
<ul class="list-group">
    <li class="list-group-item">
        <dt>Masa Pelaksanaan</dt>
        <dd><?php echoalt($result['info_surat'][2], '-', " Hari"); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Waktu Terpakai</dt>
        <dd><?php echoalt($result['waktu_terpakai'], '-', " Hari"); ?></dd>
    </li>
    <li class="list-group-item">
        <dt>Sisa Waktu</dt>
        <dd><?php echoalt($result['waktu_sisa'], '-', " Hari"); ?></dd>
    </li>
</ul>
<?php
$outsource['data_1'] = ob_get_contents();
ob_end_clean();

ob_start();
?>
<i class="pull-left md md-label text-inverse"></i><span class="text-muted">Waktu Terpakai <strong class="text-inverse">(<?php echo $result['persen_waktu_terpakai']; ?> %)</strong></span><br>
<i class="pull-left md md-label text-warning"></i><span class="text-muted">Sisa Waktu <strong class="text-warning">(<?php echo $result['persen_waktu_sisa']; ?> %)</strong></span><br>
<?php
$outsource['data_2'] = ob_get_contents();
ob_end_clean();