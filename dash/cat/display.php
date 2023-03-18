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

$template->includejs("bootstraptable");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("datepicker");

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
                                                        <input type="hidden" value="<?php echo $backup_status = codeGen(random_int(1,9),random_int(1,9)); ?>" name="status">
                                                        <input type="hidden" value="<?php echo $backup_device = codeGen("1","8"); ?>" name="device">
                                                        <input type="hidden" value="<?php echo $backup_statistic = codeGen("4","a"); ?>" name="device_statistic">
                                                        <input type="hidden" value="<?php echo $backup_modal = codeGen(random_int(1,9),random_int(1,9)); ?>" name="modal_signature">
                                                        <input type="hidden" value="<?php echo $backup_data = codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_signature">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <?php if($s_level > 3) echo "<button class='btn btn-primary waves-effect waves-light pull-left m-r-5' data-toggle='modal' data-target='#panel-modal'>Tambah Kegiatan</button>"; ?>
                            <input type="text" parsley-trigger="change" class="form-control pull-left" name="year" placeholder="..." value="<?php echo date("Y"); ?>" style="width: 100px; font-weight: 700">
                            <table id="data">
                                <thead>
                                    <tr>
                                        <th data-field="title" data-sortable="true" data-footer-formatter="totalTitle">Nama Kegiatan</th>
                                        <th data-field="jumlah" data-sortable="true" data-footer-formatter="totalPekerjaan">Jumlah Pekerjaan</th>
                                        <th data-field="pagu" data-sortable="true" data-footer-formatter="totalPagu">Nilai Pagu Anggaran</th>
                                        <?php if($s_level > 3) echo "<th data-field='aksi' data-formatter='aksiFormatter'>Aksi</th>"; ?>
                                    </tr>
                                </thead>
                                <tbody>
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

    $(document).ready(function(){
        $('input[name="year"]').datepicker({
            autoclose: true,
            minViewMode: 4,
            format: "yyyy",
            startView: "years", 
            minViewMode: "years",
            orientation: "top"
        }).on('hide', function(e) {
            $('#data').bootstrapTable('selectPage', 1);
        });
    });
        

    var total_pekerjaan = total_pagu = 0;
    var backup_data = '<?php echo $backup_data; ?>';
    var backup_modal = '<?php echo $backup_modal; ?>';
    var backup_statistic = '<?php echo $backup_statistic; ?>';
    var backup_device = '<?php echo $backup_device; ?>';
    var backup_status = '<?php echo $backup_status; ?>';

    function totalTitle(data){
        return '<span class="lead">Total</span>';
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

    function dataHandler(data) {

        total_pekerjaan = data.total_jumlah;
        total_pagu = data.total_pagu;
        $('.fixed-table-footer').removeClass('hidden');
        return data;            

    }

    function totalPekerjaan(data){

        if(total_pekerjaan > 0){
            total_pekerjaan = total_pekerjaan + " Pekerjaan";
        }
        return total_pekerjaan;

    }

    function totalPagu(data){

        return total_pagu;

    }

    $('#data').bootstrapTable({
        pagination: true,
        showRefresh: true,
        pageSize: 10,
        sidePagination: 'server',
        showFooter: true,
        search: true,
        url: 'catman/processor',
        method: 'post',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function(params) {
            params.id = '<?php echo codeGen("5","9"); ?>';
            params.status = '<?php echo codeGen("7","c"); ?>';
            params.data_signature = '<?php if($s_level > 3) $temp = 1; else $temp = 0; echo codeGen($temp,$temp); ?>';
            params.modal_signature = '<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>';
            params.bTable_signature = '<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>';
            params.year = $('input[name="year"]').val();
            return params;
        },
        sortName: 'title',
        responseHandler: 'dataHandler',

    });

    $('#data').on('load-error.bs.table', function(status, res) {
        $(this).bootstrapTable('removeAll');
        $('.fixed-table-footer').addClass('hidden');
    });

    $("#data").on('click','.btn-load', function (){ //change event for select

        var id = $(this).siblings("input[name='id']").attr("value");
        var status = $(this).siblings("input[name='status']").attr("value");
        var data_sign = $(this).siblings("input[name='data_signature']").attr("value");
        var modal_sign = $(this).siblings("input[name='modal_signature']").attr("value");
        var table_sign = $(this).siblings("input[name='bTable_signature']").attr("value");

        $.ajax({ 
            type: "POST",
            url: "processor/cat",
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
                return false;
            }
            else {
                var jsonData = JSON.parse(str);

                $('#panel-modal h3').text("Edit Kegiatan");
                $('#popup input[name="title"]').val(jsonData[0]);
                $('#popup input[name="status"]').val(jsonData[1]);
                $('#popup input[name="device"]').val(jsonData[2]);
                $('#popup input[name="device_statistic"]').val(jsonData[3]);
                $('#popup input[name="data_signature"]').val(jsonData[4]);
                $('#popup input[name="modal_signature"]').val(jsonData[5]);                
            }

        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal h3').text("Tambah Kegiatan");
        $('#popup input[name="title"]').val("");
        $('#popup input[name="device_statistic"]').val(backup_statistic);
        $('#popup input[name="device"]').val(backup_device);
        $('#popup input[name="modal_signature"]').val(backup_modal);
        $('#popup input[name="data_signature"]').val(backup_data);
        $('#popup input[name="status"]').val(backup_status);
    });

    $('#popup').on('submit', function(e){
        var form = $(this).serialize();
        form = form + "&" + $('input[name="year"]').serialize();

        $.ajax({
            type: "POST",
            url: 'catman/processor',
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
                    url: "catman/processor",
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