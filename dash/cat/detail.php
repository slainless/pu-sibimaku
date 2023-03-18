<?php
require $dir_html."hori/template.php";


$template = new template;

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$param['table'] = $tbl_cat;
$param['field'] = array(
    array('name' => 'title', 'result' => 'title'),
    array('name' => 'tahun', 'result' => 'tahun')
);
$param['where'] = array('name' => 'id', 'value' => $get_id, 'type' => 'i');

$exec = new dbExec($query);
$result = $exec->select($param);
unset($param);

$param['table'] = $tbl_mem;
$param['field'] = array(
    array('name' => 'id', 'result' => 'id'),
    array('name' => 'name', 'result' => 'name'),
);
$param['where'] = array('name' => 'level', 'value' => 3, 'type' => 'i');

$exec = new dbExec($query);
$member = $exec->select($param);
unset($param);

if(!$result){
    errCode('404', 'Page not Found');
}

$bc = array(
    array("","Dashboard","1"),
    array("/prokal/dash/?d=catman","Kegiatan","0"),
    array("","Detail","1"),
);
$title = "[ ".$result['tahun']." ] ".$result['title'];
$page_title = "SIBIMA-KU | Rincian Kegiatan";

$s_init['level'] = $s_level;
$s_init['name'] = $s_name;

$pagejs = "d1-detail.init.js";

$template->init($bc, $title, $page_title, $s_init);
$template->pagejs($pagejs);

$template->includejs("bootstraptable");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("inputmask");
$template->includejs("peity");
$template->includejs("counterup");

$template->call('include');
?>
    <style>
        .parsley-except .parsley-errors-list {
            display: none;
        }
        .ct-series-a .ct-bar {
            /*stroke: #FFAA00;*/ stroke: #4C5667;
        }
        .ct-series-b .ct-bar {
            stroke: #7266BA;
        }
        .ct-label.ct-vertical {
            -webkit-transform:rotate(90deg);
    -moz-transform:rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform:rotate(90deg);
    transform: rotate(90deg);
    font-size: 1.1rem;
        }
    </style>
<?php
$template->call('topbar');
$template->call('navbar');
?>

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
                                                        for($x=0;$x<$limitx;$x++){
                                                            echo "<option value=".$member['id'][$x].">".$member['name'][$x]."</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <input type="hidden" value="<?php echo $backup_status = codeGen(random_int(1,9),random_int(1,9)); ?>" name="status">
                                            <input type="hidden" value="<?php echo $backup_device = codeGen("c","f"); ?>" name="device">
                                            <input type="hidden" value="<?php echo $backup_statistic = codeGen("0","b"); ?>" name="device_statistic">
                                            <input type="hidden" value="<?php echo $backup_modal = codeGen(random_int(1,9),random_int(1,9)); ?>" name="modal_signature">
                                            <input type="hidden" value="<?php echo $backup_data = codeGen($get_id, "", true); ?>" name="data_signature">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <div class="row">
                    <div class="col-lg-2">
                            <div class="widget-chart-1">
                                <div class="panel panel-warning panel-color">
                                    <div class="panel-heading text-center">
                                        <h3 class="panel-title">Status Fisik</h3>
                                    </div>
                                    <div class="panel-body widget-simple text-right">
                                        <span class="donut">1</span>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="panel panel-warning panel-border">
                                    <div class="panel-heading text-center">
                                        <h3 class="panel-title">Keterangan</h3>
                                    </div>
                                    <div class="panel-body widget-simple text-right">
                                        <i class="pull-left md md-label text-danger"></i><span class="text-muted">Pekerjaan Selesai</span><br>
                                        <i class="pull-left md md-label text-warning"></i><span class="text-muted">Diatas 50%</span><br>
                                        <i class="pull-left md md-label text-success"></i><span class="text-muted">Dibawah 50%</span><br>
                                        <i class="pull-left md md-label text-muted"></i><span class="text-muted">Belum Berjalan</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="panel panel-warning panel-border">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title"></h3>
                            </div>
                            <div class="panel-body widget-simple text-right">
                                <h2 class="text-danger total_100">0</h2>
                                <p class="text-muted">Pekerjaan Selesai</p>
                                <h2 class="text-warning total_50up">0</h2>
                                <p class="text-muted">Diatas 50%</p>
                                <h2 class="text-success total_50down">0</h2>
                                <p class="text-muted">Dibawah 50%</p>
                                <h2 class="text-muted total_0">0</h2>
                                <p class="text-muted">Belum Berjalan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-lg-offset-2 total_pekerjaan">
                        <div class="panel panel-inverse panel-color">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Total Pekerjaan</h3>
                            </div>
                            <div class="panel-body widget-simple text-right">
                                <h2 class="">0</h2>
                                <p class="text-muted">Pekerjaan</p>
                            </div>
                        </div>
                        <div class="panel panel-inverse panel-border">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Keterangan</h3>
                            </div>
                            <div class="panel-body widget-simple text-right">
                                <i class="pull-left md md-label text-purple"></i><span class="text-muted">Sisa</span><br>
                                <i class="pull-left md md-label text-inverse"></i><span class="text-muted">Penyerapan</span><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="">
                            <div class="panel panel-inverse panel-color">
                                <div class="panel-heading text-center">
                                    <h3 class="panel-title">Status Keuangan</h3>
                                </div>
                                <div class="panel-body widget-simple text-right">
                                    <div id="stacked-bar-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="panel panel-inverse panel-border">
                                <div class="panel-heading text-center">
                                    <h3 class="panel-title"></h3>
                                </div>
                                <div class="panel-body widget-simple text-right" style="padding-top: 0">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5 class="total_pagu">Rp. 0,00</h5>
                                            <p class="text-muted">Pagu Anggaran</p>
                                            <h5 class="total_sisa_pagu">Rp. 0,00</h5>
                                            <p class="text-muted">Sisa Pagu</p>
                                        </div>
                                        <div class="col-lg-6">
                                            <h5 class="total_kontrak">Rp. 0,00</h5>
                                            <p class="text-muted">Nilai Terkontrak</p>
                                            <h5 class="total_sisa_kontrak">Rp. 0,00</h5>
                                            <p class="text-muted">Sisa Kontrak</p>
                                            <h5 class="total_penyerapan">Rp. 0,00</h5>
                                            <p class="text-muted">Penyerapan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card-box">

                        <?php if($s_level > 3) echo "<button class='btn btn-primary waves-effect waves-light pull-left' data-toggle='modal' data-target='#panel-modal'>Tambah Pekerjaan</button>"; ?>
                            <table id="data">
                                <thead>
                                    <tr>
                                        <th data-field="title" data-sortable="true" data-footer-formatter="totalTitle">Nama Pekerjaan</th>
                                        <th data-field="pptk" data-sortable="true" data-footer-formatter="totalPPTK">PPTK</th>
                                        <th data-field="pagu" data-sortable="true" data-footer-formatter="totalPagu">Nilai Pagu Dana</th>
                                        <th data-field="kontrak" data-sortable="true" data-footer-formatter="totalKontrak">Nilai Kontrak</th>
                                        <th data-field="addendum" data-sortable="true" data-footer-formatter="totalAddendum">Nilai Addendum</th>
                                        <th data-field="fisik" data-sortable="true" data-footer-formatter="totalFisik">Status Fisik</th>
                                        <th data-field="keuangan" data-sortable="true" data-footer-formatter="totalKeterangan">Status Keuangan</th>
                                        <th data-field="penyerapan" data-sortable="true" data-visible="false">Penyerapan</th>
                                        <th data-field="sisa_pagu" data-sortable="true" data-visible="false">Sisa Anggaran</th>
                                        <th data-field="sisa_kontrak" data-sortable="true" data-visible="false">Sisa Kontrak</th>
                                        <?php if($s_level > 3) echo '<th data-field="aksi" data-formatter="aksiFormatter" data-width="95px">Aksi</th>'; ?>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>

<?php 
$template->call('footer');
$template->call('basicjs');
?>

<script>

    var backup_data = '<?php echo $backup_data; ?>';
    var backup_modal = '<?php echo $backup_modal; ?>';
    var backup_statistic = '<?php echo $backup_statistic; ?>';
    var backup_device = '<?php echo $backup_device; ?>';
    var backup_status = '<?php echo $backup_status; ?>';

    chart = new Chartist.Bar('#stacked-bar-chart', {
            labels: ['Kontrak', 'Pagu'],
            series: [
                [0,0],[0,0]
            ]
        }, {
            stackBars: true,
            chartPadding: {
                top: 0,
                right: 15,
                bottom: 0,
                left: 0
            },
            horizontalBars: true,
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 30px'
                });
            }
        });

    $(document).ready(function(){
        $('.donut').peity('donut', {
            width: '100%',
            height: '200%',
            innerRadius: '65',
            fill: ['#CCCCCC', '#009886', '#FFAA00', '#EF5350']
        });

        $('#popup input[name="data_0"]').mask('999.000.000.000.000', {reverse: true});

    });

    var total_pekerjaan = total_addendum = total_pagu = total_kontrak = total_penyerapan = total_sisa_pagu = total_sisa_kontrak = 0;

    function dataHandler(data) {
        total_addendum = data.addendum;
        $('.total_pagu').text(data.pagu);
        $('.total_kontrak').text(data.kontrak);
        $('.total_penyerapan').text(data.penyerapan);
        $('.total_sisa_pagu').text(data.sisa_pagu);
        $('.total_sisa_kontrak').text(data.sisa_kontrak);
        $('.total_pekerjaan h2').text(data.total);
        $('.total_0').text(data.total_['0']);
        $('.total_50up').text(data.total_['50up']);
        $('.total_50down').text(data.total_['50down']);
        $('.total_100').text(data.total_['100']);
        chart_value = [data.total_['0'], data.total_['50down'], data.total_['50up'], data.total_['100']].join();

        
        var update = {
            labels: ['Kontrak', 'Pagu'],
            series: data.chart
        }; 
        console.log(data.chart);
        console.log(data);
        chart.update(update);

        $('.donut').text(chart_value).change();
        return data;
    }

    function detailFormatter(index, row, element) {
        var that = $('<dl class="dl-horizontal"></dl>');

        that.append('<dt>Sisa Anggaran</dt>').append('<dd>' + row.sisa_pagu + '</dd>');
        that.append('<dt>Sisa Kontrak</dt>').append('<dd>' + row.sisa_kontrak + '</dd>');
        that.append('<dt>Penyerapan</dt>').append('<dd>' + row.penyerapan + '</dd>');

        console.log(that.children());

        return that[0].outerHTML
    }


    function aksiFormatter(data, row, i) {

            return [
            '<input type="hidden" value="' + data[0] + '" name="data_signature">',
            '<input type="hidden" value="' + data[1] + '" name="id">',
            '<input type="hidden" value="' + data[2] + '" name="status">',
            '<input type="hidden" value="' + data[3] + '" name="bTable_signature">',
            '<input type="hidden" value="' + data[4] + '" name="modal_signature">',
            '<button class="btn-sm btn-inverse waves-effect waves-light btn-load " data-toggle="modal" data-target="#panel-modal" ><i class="md md-edit"></i></button> ',
            '<button class="btn-sm btn-danger waves-effect waves-light delete"><i class="md md-warning"></i></button>'
        ].join('');
    }

    $('#data').bootstrapTable({
        detailView: true,
        detailFormatter: 'detailFormatter',
        pagination: true,
        showRefresh: true,
        pageSize: 10,
        sidePagination: 'server',
        showFooter: false,
        search: true,
        url: 'cat/ajaxfetcher.php',
        method: 'post',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function(params) {
            params.id = '<?php echo codeGen("b","6"); ?>';
            params.status = '<?php echo codeGen("5","0"); ?>';
            params.data_signature = '<?php if($s_level > 3) $temp = 1; else $temp = 0; echo codeGen($temp,$temp); ?>';
            params.modal_signature = '<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>';
            params.bTable_signature = '<?php echo codeGen($get_id,"",true); ?>';
            return params;
        },
        sortName: 'title',
        responseHandler: 'dataHandler'
    });

    $('#data').on('load-error.bs.table', function(status, res) {
        $(this).bootstrapTable('removeAll');
    });


    $("#data").on('click','.btn-load', function (e){ //change event for select

        var id = $(this).siblings("input[name='id']").attr("value");
        var status = $(this).siblings("input[name='status']").attr("value");
        var data_sign = $(this).siblings("input[name='data_signature']").attr("value");
        var modal_sign = $(this).siblings("input[name='modal_signature']").attr("value");
        var table_sign = $(this).siblings("input[name='bTable_signature']").attr("value");

        $.ajax({ 
            type: "POST",
            url: "cat/ajaxfetcher.php",
            data: { id: id, status: status, data_signature: data_sign, modal_signature: modal_sign, bTable_signature: table_sign } 
        })
        .done(function( str ) { 

            if(str == 'false'){
                swal({
                    title: "Error!",
                    text: "Permintaan tidak dapat diproses, terjadi kesalahan. Silahkan laporkan ke Operator/Administrator",
                    type: "error",
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            else {
                var jsonData = JSON.parse(str);

                console.log(jsonData);

                $('#panel-modal h3').text("Edit Pekerjaan");
                $('#popup input[name="title"]').val(jsonData[0]);
                $('#popup input[name="data_0"]').val(jsonData[1]).trigger('input');
                $('#popup select').val(jsonData[2]);
                $('#popup input[name="status"]').val(jsonData[3]);
                $('#popup input[name="device"]').val(jsonData[4]);
                $('#popup input[name="device_statistic"]').val(jsonData[5]);
                $('#popup input[name="data_signature"]').val(jsonData[6]);
                $('#popup input[name="modal_signature"]').val(jsonData[7]);
            }
        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal h3').text("Tambah Kegiatan");
        $('#popup input[name="title"]').val("");
        $('#popup input[name="data_0"]').val("");
        $('#popup select').val("");
        $('#popup input[name="device_statistic"]').val(backup_statistic);
        $('#popup input[name="device"]').val(backup_device);
        $('#popup input[name="modal_signature"]').val(backup_modal);
        $('#popup input[name="data_signature"]').val(backup_data);
        $('#popup input[name="status"]').val(backup_status);
    });

    $('#popup').on('submit', function(e){
        var form = $(this).serialize();

        $.ajax({
            type: "POST",
            url: 'cat/ajaxprocess.php',
            data: form, // serializes the form's elements.
         })
        .done(function( str ) {
            var jsonData = JSON.parse(str);
            if(jsonData['status'] == 'true'){
                swal({
                    title: jsonData['message'],
                    type: "success",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            else {
                swal({
                    title: "Error!",
                    text: "Permintaan tidak dapat diproses, terjadi kesalahan. Silahkan laporkan ke Operator/Administrator",
                    type: "error",
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            $('#panel-modal').modal('hide');
            $('#data').bootstrapTable('refresh');
        });

        e.preventDefault();

    });


    $('#data').on('click', '.delete', function () {

        var id = $(this).siblings("input[name='id']").attr("value");
        var status = $(this).siblings("input[name='status']").attr("value");
        var data_sign = $(this).siblings("input[name='data_signature']").attr("value");
        var modal_sign = $(this).siblings("input[name='modal_signature']").attr("value");
        var table_sign = $(this).siblings("input[name='bTable_signature']").attr("value");

        swal({
            title: "Hapus pekerjaan ini?",
            text: "Seluruh data dalam pekerjaan ini akan terhapus.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-warning",
            confirmButtonText: "Ya, saya yakin!",
            cancelButtonText: "Tidak, batalkan",
            closeOnConfirm: false,
            closeOnCancel: false,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                $.ajax({ 
                    type: "POST",
                    url: "cat/ajaxfetcher.php",
                    data: { id: id, status: status, data_signature: data_sign, modal_signature: modal_sign, bTable_signature: table_sign, delete: '' } 
                })
                .done(function( str ) { 
                    var jsonData = JSON.parse(str);
                    if(jsonData['status'] == 'true'){
                        swal({
                            title: jsonData['message'],
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                    else{
                        swal({
                            title: "Error!",
                            text: "Permintaan tidak dapat diproses, terjadi kesalahan. Silahkan laporkan ke Operator/Administrator",
                            type: "error",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                    $('#data').bootstrapTable('refresh');
                });
                

            } else {
                swal({
                    title: "Aksi Dibatalkan.",
                    type: "error",
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        });
    });
</script>

<?php
$template->call('endfile');
?>