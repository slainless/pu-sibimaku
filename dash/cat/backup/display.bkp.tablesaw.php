<?php
require $dir_html."hori/template.php";

$template = new template;

$bc = array(
    array("","Dashboard","1"),
    array("","Kegiatan","1")
);
$title = "Daftar Kegiatan";
$page_title = "SIBIMA-KU | Daftar Kegiatan";
$s_init['level'] = $s_level;
$s_init['name'] = $s_name;

$pagejs = "d1-display.init.js";

$template->init($bc, $title, $page_title, $s_init);
$template->pagejs($pagejs);

$template->includejs("tablesaw");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");

$table = 'cat';
$table0 = 'dash';
$exec = new dbExec($query);

$param['field'] = array(
    array('name' => 'id', 'result' => 'id_kegiatan'),
    array('name' => 'title', 'result' => 'nama_kegiatan')
);
$param['table'] = $table;

$kategori = $exec->select($param);
if(count($kategori['id_kegiatan']) == 1){
    $kategori['id_kegiatan'] = array($kategori['id_kegiatan']);
    $kategori['nama_kegiatan'] = array($kategori['nama_kegiatan']);
}
unset($param);

$param['field'] = array(
    array('name' => 'kategori', 'result' => 'id_kegiatan'),
    array('name' => 'SUM(n_pagu) AS n_pagu', 'result' => 'jumlah_pagu'),
    array('name' => 'COUNT(id) AS jumlah', 'result' => 'jumlah_proyek'),
);
$param['table'] = $table0;
$param['group'] = "kategori";

$pekerjaan = $exec->select($param);
unset($param);

$limit = count($kategori['id_kegiatan']);
$limity = count($pekerjaan['id_kegiatan']);

$t_pekerjaan = 0;
$t_pagu = 0;
for($x=0;$x<$limit;$x++):

    for($y=0;$y<$limity;$y++):
        if($pekerjaan['id_kegiatan'][$y] == $kategori['id_kegiatan'][$x]){

            $kategori['dana'][$x] = $pekerjaan['jumlah_pagu'][$y];
            $kategori['jumlah'][$x] = $pekerjaan['jumlah_proyek'][$y];

            settype($pekerjaan['jumlah_pagu'][$y], 'float');
            settype($pekerjaan['jumlah_proyek'][$y], 'float');

            $t_pekerjaan = $t_pekerjaan + $pekerjaan['jumlah_proyek'][$y];
            $t_pagu = $t_pagu + $pekerjaan['jumlah_pagu'][$y];
        }
    endfor;

endfor;

$template->call('include');
$template->call('topbar');
$template->call('navbar');
?>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <!-- <h4 class="m-t-0 header-title"><b>Swipe Table with Mini Map</b></h4>
                            <p class="text-muted font-13">
                                Your awesome text goes here.
                            </p> -->
                            
                            <div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" >
                                <div class="modal-dialog">
                                    <div class="modal-content p-0 b-0">
                                        <div class="panel panel-color panel-primary">
                                            <div class="panel-heading">
                                                <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
                                                <h3 class="panel-title">Tambah Kegiatan</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <form method="POST" action="" id="popup" data-parsley-validate="">
                                                        <label for="field-1" class="control-label">Nama Kegiatan</label>
                                                        <input type="text" parsley-trigger="change" required class="form-control" name="title" id="field-1" placeholder="Masukkan nama">
                                                        <input type="hidden" value="" name="status">
                                                        <input type="hidden" value="<?php echo codeGen("1","8"); ?>" name="device">
                                                        <input type="hidden" value="<?php echo codeGen("1","8"); ?>" name="device_statistic">
                                                        <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                                                        <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_0">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#panel-modal">Tambah Kegiatan</button>

                            <table id="data" class="tablesaw table" data-tablesaw-mode="swipe" data-tablesaw-minimap>
                                <thead>
                                    <tr>
                                        <th data-tablesaw-priority="persist">Nama Kegiatan</th>
                                        <th>Jumlah Pekerjaan</th>
                                        <th>Nilai Pagu Anggaran</th>
                                        <?php if($s_level > 3) echo "<th style='width: 95px'>Aksi</th>"; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($kategori)): ?>
                                    <tr>
                                    <?php
                                    $limit = count($kategori['id_kegiatan']);
                                    $prefix = "Pekerjaan";

                                    for($x=0;$x<$limit;$x++): 

                                        if(!isset($kategori['jumlah'][$x])){
                                            $kategori['jumlah'][$x] = "-";
                                            $prefix = "";
                                        }

                                        if(!isset($kategori['dana'][$x])){
                                            $kategori['dana'][$x] = "-";
                                        }
                                        $strcount = strlen($kategori['id_kegiatan'][$x]);

                                        ?>
                                        <td><a href="?d=catman&id=<?php echo $kategori['id_kegiatan'][$x]; ?>"><strong><?php echo $kategori['nama_kegiatan'][$x]; ?></strong></td>

                                        <td><?php echo $kategori['jumlah'][$x]." ".$prefix; ?></td>
                                        <td><?php echo $kategori['dana'][$x]; ?></td>
                                        <?php if($s_level > 3) echo '
                                        <td>
                                            <input type="hidden" value="'.codeGen($kategori['id_kegiatan'][$x], "", 1).'" name="status">
                                            <input type="hidden" value="'.codeGen("5","9").'" name="device">
                                            <input type="hidden" value="'.codeGen("9","4").'" name="mass_load">
                                            <input type="hidden" value="'.codeGen("2f7","2d6").'" name="id">
                                            <input type="hidden" value="'.codeGen("6","2").'" name="data_0">
                                            <button class="btn-sm btn-inverse waves-effect waves-light btn-load" data-toggle="modal" data-target="#panel-modal" ><i class="md md-edit"></i></button>
                                            <button class="btn-sm btn-danger waves-effect waves-light delete"><i class="md md-warning"></i></button>
                                        </td>'; ?>
                                    </tr>
                                    <?php
                                    endfor;

                                    if($t_pekerjaan == 0){
                                        $t_pekerjaan = "-";
                                    }
                                    if($t_pagu == 0){
                                        $t_pagu = "-";
                                    }

                                    $prefix = "Pekerjaan";
                                    if($t_pekerjaan == '' || $t_pekerjaan == 0){
                                        $prefix = "";
                                    }
                                    ?>
                                    <tfoot>
                                        <tr>
                                            <td><strong class="lead">Total</strong></td>
                                            <td><?php echo $t_pekerjaan." ".$prefix; ?></td>
                                            <td><?php echo $t_pagu; ?></td>
                                            <?php if($s_level > 3) echo '<td></td>'; ?>
                                        </tr>
                                    </tfoot>
                                <?php else: ?>
                                    <tr>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <?php if($s_level > 3) echo '<td>-</td>'; ?>
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
    $("#data").on('click','.btn-load', function (){ //change event for select

        var status = $(this).siblings("input[name='status']").attr("value");
        var statistic = $(this).siblings("input[name='device']").attr("value");
        var data = $(this).siblings("input[name='data_0']").attr("value");
        var id = $(this).siblings("input[name='id']").attr("value");

        $.ajax({ 
            type: "POST",
            url: "cat/fetcher.php",
            data: { status: status, statistic: statistic, data: data, id: id} 
        })
        .done(function( str ) { 

             var jsonData = JSON.parse(str);

             $('#panel-modal h3').text("Edit Kegiatan");
             $('#popup input[name="data_0"]').val(jsonData['data_0']);
             $('#popup input[name="id"]').val(jsonData['id']);
             $('#popup input[name="title"]').val(jsonData['data']);
             $('#popup input[name="status"]').val(jsonData['load']);
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