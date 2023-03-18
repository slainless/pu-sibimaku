<?php


$stmtq = 'select @relid:='.$tbl_dash.'.rel_id, '.$tbl_dash.'.lead_id, '.$tbl_dash.'.dp_info, '.$tbl_dash.'.ppk, (select perusahaan from '.$tbl_mem.' where id = @relid) as name from '.$tbl_dash.' left join '.$tbl_mail.' on '.$tbl_mail.'.rel_id = '.$tbl_dash.'.id where '.$tbl_mail.'.id = ? and '.$tbl_mail.'.rel_id = '.$tbl_dash.'.id';

$param['param']['value'] = $code['id'];
$param['param']['type'] = 'i';
$param['result'] = array('rel_id', 'lead_id', 'dp_info', 'ppk', 'name');

$checkDash = $exec->freeQuery($stmtq, $param);
unset($param);

if(
    ($s_level === 3 && $s_id !== $checkDash['lead_id']) ||
    ($s_level === 4 && $s_id !== $checkDash['ppk'])
)
    errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);

$param['table'] = $tbl_mail;
$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
$param['field'] = array(
    array('name' => 'status', 'result' => 'status'),
    array('name' => 'target_list', 'result' => 'target_list'),
    array('name' => 'attach', 'result' => 'attach'),
    array('name' => 'tagihan', 'result' => 'tagihan'),
    array('name' => 'rel_id', 'result' => 'rel_id'),
    array('name' => 'f_pptk', 'result' => 'f_pptk'),
    array('name' => 'f_ppk', 'result' => 'f_ppk'),
    array('name' => 'target', 'result' => 'target'),
    array('name' => 'time', 'result' => 'time'),
    array('name' => 'comment', 'result' => 'comment'),
    array('name' => 'time_mod', 'result' => 'time_mod'),
    array('name' => 'form_data', 'result' => 'form'),
    array('name' => 'jenis', 'result' => 'jenis')
);

$check = $exec->select($param);
unset($param);

if(!$check)
    errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);

?>
<div class="panel panel-color panel-primary">
    <style>
    .note-popover .popover .arrow {
        left: 20px !important; 
    }
    .note-popover .note-table, .note-popover .note-insert {
        display: none !important; 
    }
    
    </style>
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Permintaan Pembayaran</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="" data-token="<?php echo $_SESSION['req_token']; ?>" 
                    data-primary="<?php echo codeGen("b","4"); ?>" >
                <div class="m-b-10 text-right clearfix">
                    <?php 

                    if($_SESSION['level'] === 3):
                        switch (true):
                             
                            case ($check['target'] === 1 && $check['status'] === 0 && $check['f_pptk'] === 0): $status_per = 0; break;
                            case ($check['target'] === 1 && $check['status'] === 0 && $check['f_pptk'] === 1): $status_per = 1; break;
                            case ($check['target'] === 0 && $check['status'] === 2): $status_per = 2; break;
                            case ($check['target'] === 2 && $check['status'] === 1): $status_per = 3; break;
                            case ($check['target'] === 1 && $check['status'] === 2): $status_per = 4; break;
                            case ($check['target'] === 3): $status_per = 5; break;

                        endswitch;
                    else:
                        switch (true):
                         
                            case ($check['target'] === 2 && $check['status'] === 1 && $check['f_ppk'] === 0): $status_per = 0; break;
                            case ($check['target'] === 2 && $check['status'] === 1 && $check['f_ppk'] === 1): $status_per = 1; break;
                            case (($check['target'] === 1 || $check['target'] === 0) && $check['f_ppk'] === 1): $status_per = 2; break;
                            case ($check['target'] === 3): $status_per = 5; break;

                        endswitch;
                    endif;

                    switch (true):
                        case ($status_per === 0):
                            $temp[0] = 'default';
                            $temp[1] = 'Baru';
                        break;

                        case ($status_per === 1):
                            $temp[0] = 'inverse';
                            $temp[1] = 'Revisi';
                        break;

                        case ($status_per === 2):
                            $temp[0] = $_SESSION['level'] === 3 ? 'purple' : 'danger';
                            $temp[1] = 'Ditolak';
                            $temp[2] = $_SESSION['level'] === 3 ? 'purple' : 'danger';
                            $temp[3] = 'Menunggu Revisi';
                        break;

                        case ($status_per === 3):
                            $temp[0] = empty($check['form']) ? 'info' : 'success';
                            $temp[1] = 'Diterima';
                            $temp[2] = empty($check['form']) ? 'info' : 'success';
                            $temp[3] = empty($check['form']) ? 'Isi Form BAP' : 'Menunggu Keputusan PPK';
                        break;

                        case ($status_per === 4):
                            $temp[0] = 'danger';
                            $temp[1] = 'Ditolak PPK';
                            $temp[2] = 'danger';
                            $temp[3] = 'Menunggu Revisi';
                        break;

                        case ($status_per === 5):
                            $temp[0] = 'warning';
                            $temp[1] = 'Diterima';
                            $temp[2] = 'inverse';
                            $temp[3] = 'Diproses Bendahara';
                        break;
                        
                        default:
                            break;
                    endswitch;

                    ?>
                        <span class="label label-<?php echo $temp[0]; ?> pull-left m-r-5"><?php echo $temp[1]; ?></span>
                        <?php if(isset($temp[3])): ?> 
                        <span class="label label-inverse pull-left"><?php echo $temp[3]; ?></span>
                        <?php endif; ?>
                        <span class="text-<?php echo $temp[2]; ?>"><?php echo $checkDash['name']; ?></span>
                    </div>

                <ul class="list-group">
                    <li class="list-group-item">
                        <dt>Pekerjaan</dt>
                        <dd><?php echo cta($checkDash['dp_info'])[0]; ?></dd>
                    </li>
                    <li class="list-group-item">
                        <dt>Metode Pembayaran</dt>
                        <?php 
                            switch ($check['jenis']):
                                case 1: $temp = 'Uang Muka'; break;
                                case 2: $temp = 'Sertifikat Bulanan'; break;
                            endswitch;
                        ?>
                        <dd><?php echo $temp; ?></dd>
                    </li>
                    <?php if($check['jenis'] === 2): ?>
                    <li class="list-group-item">
                        <dt>Nilai Tagihan</dt>
                        <dd><?php echo "Rp. ".number_format($check['tagihan'], 2, ",", "."); ?></dd>
                    </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <dt>Keterangan</dt>
                        <dd>
                            <div class="inline-editor text-muted">

                            <?php if(empty($check['comment']) && $check['status'] === 0) echo "(*) Opsional"; else echoalt($check['comment'], "(*) Opsional"); ?>

                            </div>
                        </dd>
                    </li>
                </ul>

                <div class="pull-left">
                    <a class="btn btn-inverse waves-effect waves-light" data-toggle="tooltip" data-container="body" data-placement="left" title="" data-original-title="Menuju berkas proyek" href="/dash/docs/<?php echo $code['id']; ?>" target="_blank"><i class="md md-description" style="font-size: 1.4em; line-height: 1em"></i></a>
                    <a class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-container="body" data-placement="bottom" title="" data-original-title="Menuju dashboard proyek" href="/dash/dashboard/<?php echo $code['id']; ?>" target="_blank"><i class="md md-dashboard" style="font-size: 1.4em; line-height: 1em"></i></a>
                </div>

                <div class="pull-right">
                <?php if(
                    $status_per === 0 || $status_per === 1 || $status_per === 4
                ): ?>
                    <button type="submit" class="btn btn-danger waves-effect waves-light" value='0' data-swal-desc="Anda yakin ingin menolak permintaan pembayaran ini?" data-swal-type="warning" data-mode="<?php echo codeGen("b","7"); ?>"
                    data-id="<?php echo codeGen($code['id'], "", true); ?>"><?php echo $status_per === 4 ? 'Teruskan' : 'Tolak'; ?></button>
                <?php endif;

                if(
                    $status_per === 0 || $status_per === 1 || $status_per === 4
                ): ?>
                    <button type="submit" class="btn btn-success waves-effect waves-light" value='1' data-swal-desc="Anda yakin ingin menerima permintaan pembayaran ini?" data-swal-type="info" data-mode="<?php echo codeGen("b","7"); ?>"
                    data-id="<?php echo codeGen($code['id'], "", true); ?>"><?php echo $status_per === 4 ? 'Kirim' : 'Terima'; ?></button>
                <?php endif;

                if(
                    $_SESSION['level'] === 3 && ($status_per === 3 || !empty($check['form']))
                ): ?>
                    <button class="btn btn-inverse waves-effect waves-light" onclick="window.open('/dash/form/<?php echo codeGen($code['id'], '', true); echo '-'.$_SESSION['req_token']; ?>', '_blank'); return false;"><?php echo empty($check['form']) ? 'Isi Form' : 'Edit Form'; ?></button>
                <?php endif; ?>
                </div>

            </form>
        </div>
    </div>
    <script>

    $('[data-toggle="tooltip"]').tooltip();
    $('#popup').parsley();
    <?php if(
        $status_per === 0 || $status_per === 1 || $status_per === 4
    ): ?>
        $('.inline-editor').summernote({
            airMode: true,
            placeholder: 'Isi keterangan... (Opsional)'
        });
    <?php endif; ?>

    <?php if($status_per === 0 || $status_per === 1 || $status_per === 4): ?>
    $('button[type="submit"]').on('click', function(e) {
        var that = $(this);
        subvalue = this.value;
        subdata = that.attr('data-id');
        submode = that.attr('data-mode');
        swalDesc = that.attr('data-swal-desc');
        swalType = that.attr('data-swal-type');

    });

    $('#popup').on('submit', function(e){
        e.preventDefault();

        swal({
            title: "Anda yakin?",
            text: swalDesc,
            type: swalType,
            showCancelButton: true,
            confirmButtonClass: "btn-" + swalType,
            cancelButtonClass: "btn-danger",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: true,
            html: true,
        }, function (isConfirm) {
            if (isConfirm) {

                var that = $('#popup');
                var data = $(this).serialize();

                data += "&primary=" + that.attr('data-primary');
                data += "&token=" + that.attr('data-token');
                data += "&mode=" + submode;
                data += "&data=" + subdata;
                data += "&submit=" + subvalue;
                data += "&keterangan=" + $(".inline-editor").html().trim();

                $.ajax({

                    type: "POST",
                    url: '/dash/mail/processor',
                    data: data, // serializes the form's elements.

                }).done(function( str ) {
                    var data = JSONParser(str);
                    if(data){

                        swal({
                            title: data.alert.title,
                            text: data.alert.text,
                            type: data.alert.type,
                            showConfirmButton: data.alert.confirm,
                            timer: data.alert.timer
                        }, function (isConfirm) {
                            if (isConfirm) {
                                $('#panel-modal').modal('hide');
                                $('#data').attr("data-token", data.token).bootstrapTable('refresh');

                                <?php if($status_per === 0): ?>
                                if(data.link !== undefined){
                                    window.open(data.link, '_blank');
                                }
                                <?php endif; ?>
                            }
                        }); 

                    }
                    else {
                    }
                });

            }
        });

    });
    <?php endif; ?>
    </script>
</div>