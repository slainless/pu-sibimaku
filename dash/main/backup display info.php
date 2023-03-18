<?php

require $dir_html."side/template.php";

$template = new template;

$exec = new dbExec($query);

$table = 'dash';
$table1 = 'member';
$table2 = 'cat';

if($s_level > 2){
    if(isset($_GET["id"])){
        $get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $param['where'] = array('name' => $table.'.id', 'value' => $get_id, 'type' => 'i');      
    }
    else {
        echo "MASUKKAN ID";
        exit();
    }

}
else {
    $param['where'] = array('name' => $table.'.id', 'value' => $s_id, 'type' => 'i');
}

$param['field'] = array(
    array('name' => $table.'.rel_id', 'result' => 'id_kontraktor'), 
    array('name' => $table.'.kategori', 'result' => 'id_kegiatan'), 
    array('name' => $table.'.dp_info', 'result' => 'info_proyek'), 
    array('name' => $table.'.dp_kontrak', 'result' => 'info_kontrak'),
    array('name' => $table.'.dp_surat', 'result' => 'info_surat'), 
    array('name' => $table.'.pf_info', 'result' => 'info_fisik'), 
    array('name' => $table.'.mp_info', 'result' => 'info_monitor'), 
    array('name' => $table.'.rp_info_prg', 'result' => 'info_progress'), 
    array('name' => $table.'.rp_info_waktu', 'result' => 'info_waktu'), 
    array('name' => $table.'.rp_info_prg_c', 'result' => 'chart_progress'), 
    array('name' => $table.'.rp_info_waktu_c', 'result' => 'chart_waktu'), 
    array('name' => $table.'.ke_info', 'result' => 'info_uang'), 
    array('name' => $table.'.ke_c', 'result' => 'chart_uang'), 
    array('name' => $table.'.gallery', 'result' => 'gallery'), 
    array('name' => $table.'.mk_info', 'result' => 'info_monitor_k'), 
    array('name' => $table.'.mk_c_xy', 'result' => 'chart_monitor_k_xy'), 
    array('name' => $table.'.mk_c_info', 'result' => 'chart_monitor_k_info'), 
    array('name' => $table.'.na_info', 'result' => 'info_nama'), 
    array('name' => $table.'.lead_id', 'result' => 'id_pptk'), 
    array('name' => $table.'.id', 'result' => 'id_proyek'), 
    array('name' => $table.'.n_kontrak', 'result' => 'nilai_kontrak'), 
    array('name' => $table.'.n_pagu', 'result' => 'nilai_pagu'),
    array('name' => $table.'.dp_addendum', 'result' => 'info_addendum'),
    array('name' => $table1.'.name', 'result' => 'nama_kontraktor'),
    array('name' => $table2.'.title', 'result' => 'nama_kegiatan'),
);
$param['table'] = array($table, $table1, $table2);
$param['join_operator'] = array('left join', 'left join');
$param['on'] = array(
        array('name' => $table.'.rel_id', 'target' => $table1.'.id'),
        array('name' => $table.'.kategori', 'target' => $table2.'.id'),
);

$rincian = $exec->select($param);
unset($param);

if(!$rincian){
    echo "DATA EMPTY/ID NOT FOUND";
    exit();
}

$param['field'] = array(
    array('name' => 'id', 'result' => 'id'),
    array('name' => 'name', 'result' => 'nama')
);
$param['table'] = $table1;

$param['where_operator'] = array('or','or','or');
$param['where'] = array(
    array('name' => 'level', 'value' => 5, 'type' => 'i'),
    array('name' => 'level', 'value' => 4, 'type' => 'i'),
    array('name' => 'id', 'value' => $rincian['id_pptk'], 'type' => 'i'),
    array('name' => 'id', 'value' => $rincian['id_kontraktor'], 'type' => 'i'),
);
$param['order'] = array('name' => 'level', 'sort' => 'desc');

$pelaksana = $exec->select($param);
unset($param);

$rincian['nama_proyek'] = cta($rincian['info_proyek'])[0];
$rincian['info_proyek_lokasi'] = cta($rincian['info_proyek'])[1];
$rincian['info_kontrak_no'] = cta($rincian['info_kontrak'])[0];
$rincian['info_kontrak_tgl'] = cta($rincian['info_kontrak'])[1];
$rincian['info_addendum'] = cta($rincian['info_addendum'], true);
$rincian['info_pelaksana_kadis'] = $pelaksana['nama'][0];
$rincian['info_pelaksana_ppk'] = $pelaksana['nama'][1];
$rincian['info_pelaksana_pptk'] = $pelaksana['nama'][2];
$rincian['info_pelaksana_kontraktor'] = $pelaksana['nama'][3];
$rincian['info_surat_serah'] = cta($rincian['info_surat'])[0];
$rincian['info_surat_kerja'] = cta($rincian['info_surat'])[1];
$rincian['info_surat_masa_1'] = cta($rincian['info_surat'])[2];
$rincian['info_surat_masa_2'] = cta($rincian['info_surat'])[3];
$rincian['info_surat_masa_3'] = cta($rincian['info_surat'])[4];
$rincian['info_fisik'] = cta($rincian['info_fisik'], true);
$rincian['info_monitor'] = cta($rincian['info_monitor'], true);
$rincian['info_progress'] = cta($rincian['info_progress']);
$rincian['info_waktu'] = cta($rincian['info_waktu']);

$bc = array(
    array("","Dashboard","1"),
    array("/prokal/dash/","Kegiatan","0"),
    array("/prokal/dash/?d=1&id=","Pekerjaan","0"),
    array("","Detail","1"),
);
$title = "Detail Pekerjaan";
$page_title = "SIBIMA-KU | Detail Pekerjaan";

$pagejs = "d2-display.init.js";

$template->init($bc, $title, $page_title);
$template->pagejs($pagejs);

$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("bootstraptable");


$template->call('include');
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>


                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box">

                                    <form action="#" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kegiatan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['nama_kegiatan'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Pekerjaan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['nama_proyek'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">No. Kontrak</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_kontrak_no'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tanggal Kontrak</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_kontrak_tgl'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Lokasi Kegiatan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_proyek_lokasi'], "-"); ?>
                                            </div>
                                        </div>

                                    </form>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card-box">

                                    <form action="#" class="form-horizontal">
                                    <?php 
                                    $y = count($rincian['info_addendum']);
                                    for($x=0;$x<$y;$x++): ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">No. Addendum Kontrak 0<?php echo $x + 1; ?></label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_addendum'][$x][0], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Tanggal Addendum Kontrak 0<?php echo $x + 1; ?></label>
                                            <div class="col-sm-8 m-t-5"><?php if(isset($rincian['info_addendum'][$x][1])) echoalt($rincian['info_addendum'][$x][1], "-"); else echo "-"; ?>
                                            </div>
                                        </div>
                                    <?php
                                    endfor; ?>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box">

                                    <form action="#" class="form-horizontal">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Pengguna Anggaran</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_pelaksana_kadis'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Pejabat Pembuat Komitmen</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_pelaksana_ppk'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Pejabat Pelaksana Teknis Kegiatan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_pelaksana_pptk'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kontraktor Pelaksana</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_pelaksana_kontraktor'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Konsultan Supervisi</label>
                                            <div class="col-sm-8 m-t-5">-
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="card-box">

                                    <form action="#" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Surat Penyerahan Lapangan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_surat_serah'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Surat Perintah Mulai Kerja</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_surat_kerja'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Masa Pelaksanaan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_surat_masa_1'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Akhir Masa Pelaksanaan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_surat_masa_2'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Masa Pemeliharaan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_surat_masa_3'], "-"); ?>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-box">

                                    <form action="#" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nilai Kontrak</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['nilai_kontrak'], "-"); ?>
                                            </div>
                                        </div>
                                        <?php 
                                        $y = count($rincian['info_addendum']);
                                        for($x=0;$x<$y;$x++): ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nilai Addendum Kontrak 0<?php echo $x + 1; ?></label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_addendum'][$x][2], "-");?>
                                            </div>
                                        </div>
                                        <?php
                                        endfor; ?>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nilai Pagu</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['nilai_pagu'], "-"); ?>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">

                                    <table data-toggle="table" data-mobile-responsive="true" data-check-on-init="true" class="table">
                                        <thead>
                                            <tr>
                                                <th rowspan=2>Div.</th>
                                                <th rowspan=2>Uraian</th>
                                                <th colspan=4>Bobot</th>
                                            </tr>
                                            <tr>
                                                <th>Kontrak</th>
                                                <th>Bulan lalu</th>
                                                <th>Bulan ini</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $y = count($rincian['info_fisik']);
                                            for($x=0;$x<$y;$x++):
                                            ?>
                                            <tr>
                                                <td><?php echo $rincian['info_fisik'][$x][0]; ?></td>
                                                <td><?php echo $rincian['info_fisik'][$x][1]; ?></td>
                                                <td><?php echo $rincian['info_fisik'][$x][2]; ?></td>
                                                <td><?php echo $rincian['info_fisik'][$x][3]; ?></td>
                                                <td><?php echo $rincian['info_fisik'][$x][4]; ?></td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">

                                    <table data-toggle="table" data-mobile-responsive="true" data-check-on-init="true" class="table">
                                        <thead>
                                            <tr>
                                                <th rowspan=2>Monitoring</th>
                                                <th colspan=12>2017</th>
                                            </tr>
                                            <tr>
                                                <th>Januari</th>
                                                <th>Februari</th>
                                                <th>Maret</th>
                                                <th>April</th>
                                                <th>Mei</th>
                                                <th>Juni</th>
                                                <th>Juli</th>
                                                <th>Agustus</th>
                                                <th>September</th>
                                                <th>Oktober</th>
                                                <th>November</th>
                                                <th>Desember</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            echo "<tr>";
                                            $z = count($rincian['info_monitor']);
                                            for($x=1;$x<4;$x++){
                                                switch ($x) {
                                                    case 1:
                                                        echo "<td>Kumulatif Rencana (%)</td>";
                                                        break;
                                                    case 2:
                                                        echo "<td>Kumulatif Realisasi (%)</td>";
                                                        break;
                                                    case 3:
                                                        echo "<td>Deviasi (%)</td>";
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }

                                                for($y=0;$y<$z;$y++){
                                                    echo "<td>".$rincian['info_monitor'][$y][$x]."</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="card-box">

                                    <div id="keuangan" class="ct-chart ct-square simple-pie-chart-chartist"></div>
                                    <form action="#" class="form-horizontal m-t-30">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nilai Kontrak</label>
                                            <?php 
                                            if(isset($rincian['info_addendum'][0][2])){
                                                $y = count($rincian['info_addendum']);
                                                $rincian['info_sisa_dana'] = $rincian['info_addendum'][$y-1][2] - $rincian['info_uang'];
                                                ?>
                                                <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_addendum'][$y-1][2], "-");
                                            }
                                            else {
                                                $rincian['info_sisa_dana'] = $rincian['nilai_kontrak'] - $rincian['info_uang'];
                                                ?>
                                                <div class="col-sm-8 m-t-5"><?php echoalt($rincian['nilai_kontrak'], "-");
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Realisasi s/d Bulan ini</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_uang'], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Sisa Dana</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_sisa_dana'], "-"); ?>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card-box">

                                    <div id="ringkasan" class="ct-chart ct-square simple-pie-chart-chartist"></div>
                                    <form action="#" class="form-horizontal m-t-30">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kumulatif Rencana</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][0], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kumulatif Realisasi</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][1], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Deviasi</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][2], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Progress Bulan ini</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][3], "-"); ?>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card-box">

                                    <div id="waktu" class="ct-chart ct-square simple-pie-chart-chartist"></div>
                                    <form action="#" class="form-horizontal m-t-30">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Masa Pelaksanaan</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_waktu'][0], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Waktu Terpakai</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_waktu'][1], "-"); ?>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="col-sm-4 control-label">Sisa Waktu</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_waktu'][2], "-"); ?>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">

                                    GALLERY

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="card-box">

                                    CURVA

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card-box">
                                    <form action="#" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Rencana Bulan ini</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][0], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Realisasi Bulan ini</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][1], "-"); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Deviasi</label>
                                            <div class="col-sm-8 m-t-5"><?php echoalt($rincian['info_progress'][2], "-"); ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                            </div>
                            <div class="col-sm-4">
                                <div class="card-box">
                                    <form action="#" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Orang I</label>
                                            <div class="col-sm-8 m-t-5">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Orang II</label>
                                            <div class="col-sm-8 m-t-5">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Orang III</label>
                                            <div class="col-sm-8 m-t-5">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

<?php
$template->call('footer');
$template->call('includejs');
?>
<script>
$(document).ready(function() {

        var data_uang = {
            series: [
                <?php echoalt($rincian['info_uang'], ""); echo ", "; echoalt($rincian['info_sisa_dana'], ""); ?>
            ]
        };

        var data = {
            series: [
                3, 1, 2,
            ]
        };

        var data_waktu = {
            series: [
                <?php echoalt($rincian['info_waktu'][1], ""); echo ", "; echoalt($rincian['info_waktu'][2], ""); ?>
            ]
        };

        var sum = function(a, b) { return a + b };

        new Chartist.Pie('#keuangan', data_uang, {
            labelInterpolationFnc: function(value) {
                if(value != 0){
                    return Math.round(value / data_uang.series.reduce(sum) * 100) + '%';
                }
            }
        });

        new Chartist.Pie('#ringkasan', data, {
            labelInterpolationFnc: function(value) {
                if(value != 0){
                    return Math.round(value / data.series.reduce(sum) * 100) + '%';
                }
            }
        });

        new Chartist.Pie('#waktu', data_waktu, {
            labelInterpolationFnc: function(value) {
                if(value != 0){
                    return Math.round(value / data_waktu.series.reduce(sum) * 100) + '%';
                }
            }
        });
    } );
</script>
<?php
$template->call('endfile');