<?php
require $dir_html."hori/template.php";

$template = new template;

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$process = new catProcess($query);
$kategori = $process->fetch(1, $get_id);

$process = new dashbProcess($query);
$pekerjaan = $process->fetch($get_id, 11);
$member = $process->fetchMember(3);

$info['total_pagu'] = $info['total_kontrak'] = $info['total_add'] = $info['total_penyerapan'] = $info['total_sisa_pagu'] = $info['total_sisa_kontrak'] = 0;
if(!empty($pekerjaan)):
    $limit = count($pekerjaan);
    for($x=0;$x<$limit;$x++){

        $add_flag = false;

        $temp = cta($pekerjaan[$x]['info_fisik'], true);
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

        $info['title'][$x] = cta($pekerjaan[$x]['info'])[0];
        $temp = cta($pekerjaan[$x]['info_add'], true);
        if($temp){
            $limity = count($temp);
            $limity--;
            $info['nilai_add'][$x] = $temp[$limity][2];
            $add_flag = true;
        }
        else {
            $info['nilai_add'][$x] = 0;
        }

        settype($pekerjaan[$x]['nilai_kontrak'], 'float');
        settype($pekerjaan[$x]['info_uang'], 'float');
        settype($info['nilai_add'][$x], 'float');
        if($add_flag){
            $info['sisa_kontrak'][$x] = $info['nilai_add'][$x] - $pekerjaan[$x]['info_uang'];
        }
        else {
            $info['sisa_kontrak'][$x] = $pekerjaan[$x]['nilai_kontrak'] - $pekerjaan[$x]['info_uang'];
        }

        $info['sisa_pagu'][$x] = $pekerjaan[$x]['nilai_pagu'] - $pekerjaan[$x]['info_uang'];

        $info['total_pagu'] = $info['total_pagu'] + $pekerjaan[$x]['nilai_pagu'];
        $info['total_kontrak'] = $info['total_kontrak'] + $pekerjaan[$x]['nilai_kontrak'];
        $info['total_add'] = $info['total_add'] + $info['nilai_add'][$x];
        $info['total_penyerapan'] = $info['total_penyerapan'] + $pekerjaan[$x]['info_uang'];
        $info['total_sisa_pagu'] = $info['total_sisa_pagu'] + $info['sisa_pagu'][$x];
        $info['total_sisa_kontrak'] = $info['total_sisa_kontrak'] + $info['sisa_kontrak'][$x];

        if($add_flag){
            if($info['nilai_add'][$x] != 0):
                $temp = $info['status_fisik'][$x] - ($pekerjaan[$x]['info_uang'] / $info['nilai_add'][$x] * 100);
            endif;
        }
        else {
            if($pekerjaan[$x]['nilai_kontrak'] != 0):
                $temp = $info['status_fisik'][$x] - ($pekerjaan[$x]['info_uang'] / $pekerjaan[$x]['nilai_kontrak'] * 100);
            endif;
        }

/*        $temp = $info['status_fisik'][$x] - ($pekerjaan[$x]['info_uang'] / $pekerjaan[$x]['nilai_pagu'] * 100);*/

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
    array("/prokal/dash/","Kegiatan","0"),
    array("","Detail","1"),
);
$title = $kategori[0]['title'];
$page_title = "SIBIMA-KU | Detail Kegiatan";

$pagejs = "d1-detail.init.js";

$template->init($bc, $title, $page_title);
$template->pagejs($pagejs);

$template->includejs("datatables");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");

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
                                    <h4 class="m-b-0">10</h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Progress Diatas 50%</h5>
                                    <h4 class="m-b-0">8</h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Progress Dibawah 50%</h5>
                                    <h4 class="m-b-0">8</h4>
                                </li>
                                <li>
                                    <h5 class="text-muted">Belum Terlaksana</h5>
                                    <h4 class="m-b-0">8</h4>
                                </li>
                                <li>
                                    <h5 class="text-success">Total Pekerjaan</h5>
                                    <h4 class="m-b-0">8</h4>
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
                                        <h5 class="m-b-0">Rp. 154.969.430.000,00</h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Nilai terkontrak</h6>
                                        <h5 class="m-b-0">Rp. 140.000.000.000,00</h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Penyerapan</h6>
                                        <h5 class="m-b-0">Rp. 100.000.000.000,00</h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Sisa Anggaran</h6>
                                        <h5 class="m-b-0">Rp.   54. 969.430.000,00</h5>
                                    </li>
                                    <li>
                                        <h6 class="text-muted">Sisa kontrak</h6>
                                        <h5 class="m-b-0">Rp.   40.000.000.000,00</h5>
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
                                                                    $limitx = count($member);
                                                                    $selected = "";
                                                                    for($x=0;$x<$limitx;$x++){
                                                                        if($member[$x][0] == $r_rel_id){
                                                                            $selected = "selected";
                                                                        }
                                                                        echo "<option value=".$member[$x][0]." ".$selected.">".$member[$x][1]."</option>";
                                                                        $selected = "";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
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
                            <button class="btn btn-primary waves-effect waves-light m-b-20" data-toggle="modal" data-target="#panel-modal">Tambah Pekerjaan</button>

                            <table id="data" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nama Pekerjaan</th>
                                        <th>PPTK</th>
                                        <th>Nilai Pagu Dana</th>
                                        <th>Nilai Kontrak</th>
                                        <th>Nilai Addendum</th>
                                        <th>Status Fisik</th>
                                        <th>Status Keuangan</th>
                                        <th>Penyerapan</th>
                                        <th>Sisa Anggaran</th>
                                        <th>Sisa Kontrak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                if(!empty($pekerjaan)): 


                                    for($x=0;$x<$limit;$x++): ?>
                                    <tr>
                                        <td><?php echo $info['title'][$x]; ?></td>
                                        <td><?php echo $pekerjaan[$x]['nama_pptk']; ?></td>
                                        <td><?php echo $pekerjaan[$x]['nilai_pagu']; ?></td>
                                        <td><?php echo $pekerjaan[$x]['nilai_kontrak']; ?></td>
                                        <td><?php echo $info['nilai_add'][$x]; ?></td>
                                        <td><?php echo $info['status_fisik'][$x]; ?></td>
                                        <td><?php echo $info['status_uang'][$x]; ?></td>
                                        <td><?php echo $pekerjaan[$x]['info_uang']; ?></td>
                                        <td><?php echo $pekerjaan[$x]['nilai_pagu'] - $pekerjaan[$x]['info_uang']; ?></td>
                                        <td><?php echo $info['sisa_kontrak'][$x]; ?></td>
                                        <td>
                                            <input type="hidden" value="<?php echo codeGen($kategori[$x]['id'], "", 1); ?>" name="status">
                                            <input type="hidden" value="<?php echo codeGen("6","7"); ?>" name="device">
                                            <input type="hidden" value="<?php echo codeGen("3","0"); ?>" name="mass_load">
                                            <div class="btn-group">
                                                <button class="btn btn-inverse waves-effect waves-light btn-load" data-toggle="modal" data-target="#panel-modal" ><i class="md md-edit"></i></button>
                                                <button class="btn btn-danger waves-effect waves-light delete"><i class="md md-warning"></i></button>
                                            </div>
                                        </td>
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
                                        <td></td>
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
                                        <td>-</td>
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
        $('#data').DataTable( {
            "aoColumnDefs": [ 
              { "sWidth": "20%", "aTargets": [ 0 ] },
            ]
        } );
    } );

    $("#data").on('click','.btn-load', function (){ //change event for select

        var status = $(this).siblings("input[name='status']").attr("value");
        var statistic = $(this).siblings("input[name='device']").attr("value");

        $.ajax({ 
            type: "POST",
            url: "cat/fetcher.php",
            data: { status: status, statistic: statistic} 
        })
        .done(function( str ) { 

             var jsonData = JSON.parse(str);

             $('#panel-modal h3').text("Edit Pekerjaan");
             $('#popup input[name="status"]').val(jsonData['load']);
             $('#popup input[name="title"]').val(jsonData['data']);
             $('#popup input[name="device"]').val(jsonData['access_mode']);
        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal h3').text("Tambah Kegiatan");
        $('#popup input[name="title"]').val("");
        var backup = $('#popup input[name="device_statistic"]').val();
        $('#popup input[name="device"]').val(backup);
    })
</script>

<?php
$template->call('endfile');
?>