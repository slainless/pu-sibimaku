<?php
require $dir_html."hori/template.php";

$template = new template;

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$table0 = 'cat';
$table1 = 'dash';
$table2 = 'member';
$exec = new dbExec($query);

$param['field'] = array(
    array('name' => 'id', 'result' => 'id_kegiatan'),
    array('name' => 'title', 'result' => 'nama_kegiatan')
);
$param['table'] = $table0;

$param['where'] = array('name' => 'id', 'type' => 'i', 'value' => $get_id);

$kegiatan = $exec->select($param);
unset($param);

$param['field'] = array(
    array('name' => $table1.'.dp_info', 'result' => 'info_proyek'),
    array('name' => $table1.'.lead_id', 'result' => 'id_pptk'),
    array('name' => $table1.'.n_pagu', 'result' => 'nilai_pagu'),
    array('name' => $table1.'.n_kontrak', 'result' => 'nilai_kontrak'),
    array('name' => $table1.'.dp_addendum', 'result' => 'info_addendum'),
    array('name' => $table1.'.pf_info', 'result' => 'info_fisik'),
    array('name' => $table1.'.ke_info', 'result' => 'info_uang'),
    array('name' => $table2.'.name', 'result' => 'nama_pptk'),
    array('name' => $table1.'.id', 'result' => 'id_proyek'),
);
$param['table'] = array($table1, $table2);
$param['join_op'] = 'left join';
$param['on'] = array('name' => $table1.'.lead_id', 'target' => $table2.'.id');

$param['where'] = array('name' => $table1.'.kategori', 'type' => 'i', 'value' => $get_id);

$pekerjaan = $exec->select($param);
unset($param);

$param['field'] = array(
    array('name' => 'name', 'result' => 'nama'),
    array('name' => 'id', 'result' => 'id'),
);
$param['table'] = $table2;
$param['where'] = array('name' => 'level', 'type' => 'i', 'value' => 3);

$member = $exec->select($param);
unset($param);

$info['total_pagu'] = $info['total_kontrak'] = $info['total_add'] = $info['total_penyerapan'] = $info['total_sisa_pagu'] = $info['total_sisa_kontrak'] = $info['total_sf_0'] = $info['total_sf_d50'] = $info['total_sf_u50'] = $info['total_sf_100'] = 0;
if($pekerjaan):
    $limit = count($pekerjaan['id_proyek']);
    for($x=0;$x<$limit;$x++){

        $add_flag = false;

        $temp = cta($pekerjaan['info_fisik'][$x], true);
        if($temp){

            $info['status_fisik'][$x] = 0;

            // LOOP THROUGH temp
            $limity = count($temp);
            for($y=0;$y<$limity;$y++){
                if($temp[$y][4] == ""){
                    settype($temp[$y][3], 'float');
                    $info['status_fisik'][$x] = $info['status_fisik'][$x] + $temp[$y][3];
                }
                else {
                    settype($temp[$y][4], 'float');
                    $info['status_fisik'][$x] = $info['status_fisik'][$x] + $temp[$y][4];
                }
            }

        }
        else {
            $info['status_fisik'][$x] = 0;
        }

        if($info['status_fisik'][$x] == 0){
            $info['total_sf_0']++;
        }
        elseif ($info['status_fisik'][$x] < 50 && $info['status_fisik'][$x] > 0) {
            $info['total_sf_d50']++;
        }
        elseif ($info['status_fisik'][$x] >= 50 && $info['status_fisik'][$x] < 100) {
            $info['total_sf_u50']++;
        }
        elseif ($info['status_fisik'][$x] == 100) {
            $info['total_sf_100']++;
        }

        $info['title'][$x] = cta($pekerjaan['info_proyek'][$x])[0];
        $temp = cta($pekerjaan['info_addendum'][$x], true);
        if($temp){
            $limity = count($temp);
            $limity--;
            $info['nilai_add'][$x] = $temp[$limity][2];
            $add_flag = true;
        }
        else {
            $info['nilai_add'][$x] = 0;
        }

        settype($pekerjaan['nilai_kontrak'][$x], 'float');
        settype($pekerjaan['info_uang'][$x], 'float');
        settype($info['nilai_add'][$x], 'float');
        if($add_flag){
            $info['sisa_kontrak'][$x] = $info['nilai_add'][$x] - $pekerjaan['info_uang'][$x];
        }
        else {
            $info['sisa_kontrak'][$x] = $pekerjaan['nilai_kontrak'][$x] - $pekerjaan['info_uang'][$x];
        }

        $info['sisa_pagu'][$x] = $pekerjaan['nilai_pagu'][$x] - $pekerjaan['info_uang'][$x];

        $info['total_pagu'] = $info['total_pagu'] + $pekerjaan['nilai_pagu'][$x];
        $info['total_kontrak'] = $info['total_kontrak'] + $pekerjaan['nilai_kontrak'][$x];
        $info['total_add'] = $info['total_add'] + $info['nilai_add'][$x];
        $info['total_penyerapan'] = $info['total_penyerapan'] + $pekerjaan['info_uang'][$x];
        $info['total_sisa_pagu'] = $info['total_sisa_pagu'] + $info['sisa_pagu'][$x];
        $info['total_sisa_kontrak'] = $info['total_sisa_kontrak'] + $info['sisa_kontrak'][$x];

        if($add_flag){
            if($info['nilai_add'][$x] != 0):
                $temp = $info['status_fisik'][$x] - ($pekerjaan['info_uang'][$x] / $info['nilai_add'][$x] * 100);
            endif;
        }
        else {
            if($pekerjaan['nilai_kontrak'][$x] != 0):
                $temp = $info['status_fisik'][$x] - ($pekerjaan['info_uang'][$x] / $pekerjaan['nilai_kontrak'][$x] * 100);
            endif;
        }

/*        $temp = $info['status_fisik'][$x] - ($pekerjaan['info_uang'] / $pekerjaan['nilai_pagu'] * 100);*/

        if($temp < 20){
            $info['status_uang'][$x] = "Aman";
        }
        elseif($temp >= 20){
            $info['status_uang'][$x] = "Kritis";
        }


    }
endif;

$bc = array(
    array("","Dashboard","1"),
    array("/prokal/dash/?d=catman","Kegiatan","0"),
    array("","Detail","1"),
);
$title = $kegiatan['nama_kegiatan'];
$page_title = "SIBIMA-KU | Detail Kegiatan";

$s_init['level'] = $s_level;
$s_init['name'] = $s_name;

$pagejs = "d1-detail.init.js";

$template->init($bc, $title, $page_title, $s_init);
$template->pagejs($pagejs);

$template->includejs("tablesaw");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("inputmask");

$template->call('include');
?>
    <style>
        .parsley-except .parsley-errors-list {
            display: none;
        }
    </style>
<?php
$template->call('topbar');
$template->call('navbar');
?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-box">
                            <div id="stat-pekerjaan" class="ct-chart ct-square simple-pie-chart-chartist"></div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="card-box">
                            <ul class="list-inline">
                                <li>
                                    <h5 class="text-muted">Pekerjaan Selesai</h5>
                                    <h4 class="m-b-0"><?php echo $info['total_sf_100']; ?></h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Progress Diatas 50%</h5>
                                    <h4 class="m-b-0"><?php echo $info['total_sf_u50']; ?></h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Progress Dibawah 50%</h5>
                                    <h4 class="m-b-0"><?php echo $info['total_sf_d50']; ?></h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Belum Terlaksana</h5>
                                    <h4 class="m-b-0"><?php echo $info['total_sf_0']; ?></h4>
                                </li>
                                <li>
                                    <h5 class="text-success">Total Pekerjaan</h5>
                                    <h4 class="m-b-0"><?php echo count($pekerjaan['id_proyek']); ?></h4>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <div id="stacked-bar-chart" class="ct-chart"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card-box">
                                    <ul class="list-inline">
                                    <li>
                                        <h6 class="text-muted">Pagu Anggaran</h6>
                                        <h5 class="m-b-0"><?php echo "Rp. ".number_format($info['total_pagu'], 2, ",", "."); ?></h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Nilai terkontrak</h6>
                                        <h5 class="m-b-0"><?php echo "Rp. ".number_format($info['total_kontrak'], 2, ",", "."); ?></h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Penyerapan</h6>
                                        <h5 class="m-b-0"><?php echo "Rp. ".number_format($info['total_penyerapan'], 2, ",", "."); ?></h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Sisa Anggaran</h6>
                                        <h5 class="m-b-0"><?php echo "Rp. ".number_format($info['total_sisa_pagu'], 2, ",", "."); ?></h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Sisa kontrak</h6>
                                        <h5 class="m-b-0"><?php echo "Rp. ".number_format($info['total_sisa_kontrak'], 2, ",", "."); ?></h5>
                                    </li>
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" >
                                <div class="modal-dialog">
                                    <div class="modal-content p-0 b-0">
                                        <div class="panel panel-color panel-primary">
                                            <div class="panel-heading">
                                                <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
                                                <h3 class="panel-title">Tambah Pekerjaan</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <form method="POST" action="" id="popup" data-parsley-validate="">
                                                        <div class="form-group">
                                                            <label for="field-1" class="control-label">Nama Pekerjaan</label>
                                                            <input type="text" parsley-trigger="change" required class="form-control" name="title" id="field-1" placeholder="Masukkan nama">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="field-2" class="control-label">Nilai Pagu Dana</label>
                                                            <div class="input-group m-t-10 parsley-except">
                                                                <span class="input-group-addon"><span>RP</span></span>
                                                                <input type="text" parsley-trigger="change" required class="form-control" name="data_0" id="field-2" placeholder="Masukkan nilai">
                                                                <span class="input-group-addon">,00</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="field-2" class="control-label">PPTK</label>
                                                            <select class="form-control select2" id="field-3" required parsley-trigger="change" name="data_1">
                                                                <option value="">Pilih PPTK</option>
                                                                <?php
                                                                    $limitx = count($member['id']);
                                                                    $selected = "";
                                                                    for($x=0;$x<$limitx;$x++){
                                                                        if($member['id'][$x] == $r_rel_id){
                                                                            $selected = "selected";
                                                                        }
                                                                        echo "<option value=".$member['id'][$x]." ".$selected.">".$member['nama'][$x]."</option>";
                                                                        $selected = "";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                                                        <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_x">
                                                        <input type="hidden" value="" name="status">
                                                        <input type="hidden" value="<?php echo codeGen("2","4"); ?>" name="device">
                                                        <input type="hidden" value="<?php echo codeGen("2","4"); ?>" name="device_statistic">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#panel-modal">Tambah Pekerjaan</button>

                            <table id="data" class="tablesaw table" data-tablesaw-mode="swipe" data-tablesaw-minimap>
                                <thead>
                                    <tr>
                                        <th data-tablesaw-priority="persist">Nama Pekerjaan</th>
                                        <th>PPTK</th>
                                        <th>Nilai Pagu Dana</th>
                                        <th>Nilai Kontrak</th>
                                        <th>Nilai Addendum</th>
                                        <th>Status Fisik</th>
                                        <th>Status Keuangan</th>
                                        <th>Penyerapan</th>
                                        <th>Sisa Anggaran</th>
                                        <th>Sisa Kontrak</th>
                                        <?php if($s_level > 3) echo '<th data-tablesaw-priority="persist" style="width: 95px">Aksi</th>'; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if(!empty($pekerjaan)): 


                                    for($x=0;$x<$limit;$x++): ?>
                                    <tr>
                                        <td><a href="?d=dashboard&id=<?php echo $pekerjaan['id_proyek'][$x]; ?>"><?php echo $info['title'][$x]; ?></a></td>
                                        <td><?php echo $pekerjaan['nama_pptk'][$x]; ?></td>
                                        <td><?php echo $pekerjaan['nilai_pagu'][$x]; ?></td>
                                        <td><?php echo $pekerjaan['nilai_kontrak'][$x]; ?></td>
                                        <td><?php echo $info['nilai_add'][$x]; ?></td>
                                        <td><?php echo $info['status_fisik'][$x]; ?></td>
                                        <td><?php echo $info['status_uang'][$x]; ?></td>
                                        <td><?php echo $pekerjaan['info_uang'][$x]; ?></td>
                                        <td><?php echo $pekerjaan['nilai_pagu'][$x] - $pekerjaan['info_uang'][$x]; ?></td>
                                        <td><?php echo $info['sisa_kontrak'][$x]; ?></td>
                                        <?php if($s_level > 3) echo '<td>
                                            <input type="hidden" value="'.codeGen($pekerjaan['id_proyek'][$x], "", 1).'" name="status">
                                            <input type="hidden" value="'.codeGen("6","7").'" name="device">
                                            <input type="hidden" value="'.codeGen("3","0").'" name="mass_load">
                                            <input type="hidden" value="'.codeGen(random_int(1,9),random_int(1,9)).'" name="id">
                                            <input type="hidden" value="'.codeGen(random_int(1,9),random_int(1,9)).'" name="data_x">
                                            <button class="btn-sm btn-inverse waves-effect waves-light btn-load" data-toggle="modal" data-target="#panel-modal" ><i class="md md-edit"></i></button>
                                            <button class="btn-sm btn-danger waves-effect waves-light delete"><i class="md md-warning"></i></button>
                                        </td>'; ?>
                                    </tr>
                                <?php endfor; ?>
                                    <tr>
                                        <td><strong class="lead">Total</strong></td>
                                        <td></td>
                                        <td><?php echo $info['total_pagu']; ?></td>
                                        <td><?php echo $info['total_kontrak']; ?></td>
                                        <td><?php echo $info['total_add']; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $info['total_penyerapan']; ?></td>
                                        <td><?php echo $info['total_sisa_pagu']; ?></td>
                                        <td><?php echo $info['total_sisa_kontrak']; ?></td>
                                        <?php if($s_level > 3) echo '<td></td>' ?>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <?php if($s_level > 3) echo '<td>-</td>'?>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

<?php 
$template->call('footer');
$template->call('basicjs');
?>

<script>

    $(document).ready(function() {
        $('#popup input[name="data_0"]').mask('999.000.000.000.000', {reverse: true});

        new Chartist.Bar('#stacked-bar-chart', {
            labels: ['Kontrak', 'Pagu'],
            series: [
                [<?php if(isset($info['total_penyerapan'], $info['total_kontrak']) && $info['total_penyerapan'] != 0 && $info['total_kontrak']) echo $info['total_penyerapan'].", ".$info['total_sisa_kontrak']; else echo "0"; ?>],
                [<?php if(isset($info['total_penyerapan'], $info['total_pagu']) && $info['total_penyerapan'] != 0 && $info['total_pagu']) echo $info['total_penyerapan'].", ".$info['total_sisa_pagu']; else echo "0"; ?>]
            ]
        }, {
            stackBars: true,
            horizontalBars: true,
            high: 1000000000,
            axisX: {
                labelInterpolationFnc: function(value) {
                    if(value != 0){
                        return (value / 1000000) + 'Jt';
                    }
                    else {
                        return 0;
                    }
                }
            }
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 30px'
                });
            }
        });

        var data = {
            series: [
            <?php 
                echo $info['total_sf_0'].", ".$info['total_sf_d50'].", ".$info['total_sf_u50'].", ".$info['total_sf_100'];
            ?>
            ]
        };

        var sum = function(a, b) { return a + b };

        new Chartist.Pie('#stat-pekerjaan', data, {
            labelInterpolationFnc: function(value) {
                if(value != 0){
                    return Math.round(value / data.series.reduce(sum) * 100) + '%';
                }
            }
        });
    } );

    $("#data").on('click','.btn-load', function (){ //change event for select

        var status = $(this).siblings("input[name='status']").attr("value");
        var statistic = $(this).siblings("input[name='device']").attr("value");
        var data = $(this).siblings("input[name='data_x']").attr("value");
        var id = $(this).siblings("input[name='id']").attr("value");

        $.ajax({ 
            type: "POST",
            url: "cat/fetcher.php",
            data: { status: status, statistic: statistic, id: id, data: data} 
        })
        .done(function( str ) { 

            var jsonData = JSON.parse(str);

            $('#panel-modal h3').text("Edit Pekerjaan");
            $('#popup input[name="status"]').val(jsonData['load']);
            $('#popup input[name="title"]').val(jsonData['data_2']);
            $('#popup input[name="data_0"]').val(jsonData['data_0']).trigger('input');
            $('#popup input[name="data_x"]').val(jsonData['data_x']);
            $('#popup input[name="id"]').val(jsonData['id']);
            $('#popup select').val(jsonData['data_1']);
            $('#popup input[name="device"]').val(jsonData['access_mode']);
        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal h3').text("Tambah Pekerjaan");
        $('#popup input[name="title"]').val("");
        $('#popup input[name="data_0"]').val("");
        $('#popup select').val("");
        var backup = $('#popup input[name="device_statistic"]').val();
        $('#popup input[name="device"]').val(backup);
    })
</script>

<?php
$template->call('endfile');
?>