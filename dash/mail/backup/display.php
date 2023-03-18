<?php

require $dir_html."hori/template.php";

$template = new template;

$dash = 'dash';
$mem = 'member';
$cat = 'cat';
$mail = 'mail';

$exec = new dbExec($query);

$bc = array(
    array("/prokal/dash/?d=catman","Dashboard","0"),
	array("","Permintaan","1"),
);
$title = "Permintaan Pembayaran";
$page_title = "SIBIMA-KU | Permintaan";

$pagejs = "d2-display.init.js";
$template->init($bc, $title, $page_title);
$template->pagejs($pagejs);

$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("summernote");

$template->call('include');
$template->call('topbar');
$template->call('navbar');

$param['table'] = array($mail, $dash, $mem);
$param['field'] = array(
    array('name' => $mail.'.rel_id', 'result' => 'mail_rel_id'),
    array('name' => $mail.'.f_pptk', 'result' => 'f_pptk'),
    array('name' => $mail.'.f_ppk', 'result' => 'f_ppk'),
    array('name' => $mail.'.f_bendahara', 'result' => 'f_bendahara'),
    array('name' => $mail.'.status', 'result' => 'status'),
    array('name' => $mail.'.target', 'result' => 'target'),
    array('name' => $mail.'.target_list', 'result' => 'target_list'),
    array('name' => $mail.'.time', 'result' => 'time'),
    array('name' => $mail.'.c_time', 'result' => 'c_time'),
    array('name' => $dash.'.dp_info', 'result' => 'dp_info'),
    array('name' => $dash.'.rel_id', 'result' => 'dash_rel_id'),
    array('name' => $mem.'.name', 'result' => 'name'),
);
$param['join_op'] = array('left join', 'left join');
$param['on'] = array(
        array('name' => $mail.'.rel_id', 'target' => $dash.'.id'),
        array('name' => $dash.'.rel_id', 'target' => $mem.'.id'),
);

if($s_level == 3):
    $param['where'] = array('name' => $mail.'.f_pptk', 'type' => 'i', 'value' => 1);
    $param['where_in'] = array('operator' => 'or', 'name' => $mail.'.rel_id', 'type' => 'i', 'value' => $s_id, 
    'query' => 'SELECT id FROM '.$dash.' WHERE lead_id = ?');
    $param['field'][] = array("name" => "CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(target_list, '/', 4), '/', -2), '\"', -2), '\"', '') AS DATETIME) as utime", "result" => "utime");
    $param['order'] = array('name' => 'utime', 'sort' => 'desc');
elseif($s_level == 4):
    $param['where'] = array(
        array('name' => $mail.'.f_ppk', 'type' => 'i', 'value' => 1),
        array('name' => $mail.'.status', 'type' => 'i', 'value' => 1)
    );
    $param['where_op'] = array('or');
    $param['field'][] = array("name" => "CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(target_list, '/', 6), '/', -2), '\"', -2), '\"', '') AS DATETIME) as utime", "result" => "utime");
    $param['order'] = array('name' => 'utime', 'sort' => 'desc');
endif;

$result = $exec->select($param);

?>
                        <input type="hidden" value="<?php echo codeGen('4','7'); ?>" name="status">
                        <input type="hidden" value="<?php echo codeGen('4','7'); ?>" name="device">
                        <input type="hidden" value="<?php echo codeGen(random_int(1,20),random_int(1,15)); ?>" name="id">
                        <input type="hidden" value="<?php echo codeGen('4','7'); ?>" name="data_0">

                        <div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" >
                            <div class="modal-dialog">
                                <div class="modal-content p-0 b-0">
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <div class="row">

                            <!-- Right Sidebar -->
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="btn-toolbar" role="toolbar">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-inbox"></i></button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-exclamation-circle"></i></button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light "><i class="fa fa-trash-o"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- End row -->

                                <div class="panel panel-default m-t-20">
                                    <div class="panel-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mails m-0" id="mail">
                                                <tbody>
                                                <?php
                                                if($result):
                                                    $y = count(fa($result['mail_rel_id']));
                                                    for($x=0;$x<$y;$x++):

                                                        $display['title'] = cta(fa($result['dp_info'])[$x])[0];
                                                        $temp = cta(fa($result['target_list'])[$x], true);

                                                        switch ($s_level) {
                                                            case '2':
                                                                $display['read'] = $temp[0][1];
                                                                $display['time'] = $temp[0][2];
                                                                break;

                                                            case '3':
                                                                $display['read'] = $temp[1][1];
                                                                $display['time'] = $temp[1][2];
                                                                break;

                                                            case '4':
                                                                $display['read'] = $temp[2][1];
                                                                $display['time'] = $temp[2][2];
                                                                break;
                                                            
                                                            default:
                                                                # code...
                                                                break;
                                                        }
                                                                
                                                        date_default_timezone_set("Asia/Makassar");
                                                        $temp[0] = new DateTime(date("Y-m-d H:i:s"));
                                                        $temp[1] = new DateTime($display['time']);
                                                        $interval = $temp[0]->diff($temp[1]);
                                                        
                                                        if($interval->y == 0){
                                                            if($interval->m == 0){
                                                                if($interval->d == 0){
                                                                    if($interval->h == 0){
                                                                        if($interval->i == 0){
                                                                            if($interval->s == 0){       
                                                                                $display['time'] = "1 Detik";
                                                                            }
                                                                            else {
                                                                                $display['time'] = $interval->s." Detik";
                                                                            }

                                                                        }
                                                                        else {
                                                                            $display['time'] = $interval->i." Mnt";
                                                                        }
                                                                    }
                                                                    else {
                                                                        $display['time'] = $interval->h." Jam";
                                                                    }
                                                                }
                                                                else {
                                                                    $display['time'] = $interval->d." Hari";
                                                                }
                                                            }
                                                            else {
                                                                $display['time'] = $interval->m." Bln";
                                                            }
                                                        }
                                                        else {
                                                            $display['time'] = $interval->y." Thn";
                                                        }

                                                        if($s_level == 3):
                                                            switch (fa($result['status'])[$x]) {
                                                                case 0:
                                                                    if(fa($result['f_pptk'])[$x] === 1){
                                                                        $display['status'] = '<span class="label label-warning">Revisi</span>';
                                                                    }
                                                                    else {
                                                                        $display['status'] = '<span class="label label-purple">Baru</span>';
                                                                    }
                                                                    break;

                                                                case 1:
                                                                    $display['status'] = '
                                                                        <span class="label label-success">Diterima</span>
                                                                        <span class="label label-inverse">Sedang Diproses</span>';
                                                                    break;   

                                                                case 2:
                                                                    $display['status'] = '
                                                                        <span class="label label-danger">Ditolak</span>
                                                                        <span class="label label-inverse">Menunggu Revisi</span>';
                                                                    break;   


                                                                case 4:
                                                                    $display['status'] = '
                                                                        <span class="label label-danger">Ditolak</span>';
                                                                    break;

                                                                case 5:
                                                                    $display['status'] = '
                                                                        <span class="label label-warning">Revisi</span>
                                                                        <span class="label label-inverse">Sedang Diproses</span>';
                                                                    break;   
                                                                
                                                                default:
                                                                    # code...
                                                                    break;
                                                                }

                                                            elseif($s_level == 4):
                                                                switch (fa($result['status'])[$x]) {
                                                                    case 1:
                                                                        $display['status'] = '
                                                                            <span class="label label-purple">Baru</span>';
                                                                        break;

                                                                    case 4:
                                                                        $display['status'] = '
                                                                            <span class="label label-danger">Ditolak</span>
                                                                            <span class="label label-inverse">Menunggu Revisi</span>';
                                                                        break;   

                                                                    case 5:
                                                                        $display['status'] = '
                                                                            <span class="label label-warning">Revisi</span>';
                                                                        break;   

                                                                    
                                                                    default:
                                                                        # code...
                                                                        break;
                                                                }
                                                            endif;

                                                    ?>


                                                    <tr class="<?php if($display['read'][$x] == '0') echo 'unread'; ?>">
                                                        <td class="mail-select">
                                                            <?php echo $display['status']; ?>
                                                        </td>

                                                        <td>
                                                            <a href="#" class="email-name open-mail" data-toggle="modal" data-target="#panel-modal" data-value="<?php echo $display['code'] = codeGen(fa($result['mail_rel_id'])[$x],"",1); ?>"><?php echo fa($result['name'])[$x]; ?></a>
                                                        </td>

                                                        <td>
                                                            <a href="#" class="email-msg open-mail" data-toggle="modal" data-target="#panel-modal" data-value="<?php echo $display['code']; ?>"><?php echo $display['title']; ?></a>
                                                        </td>
                                                        <td style="width: 20px;">
                                                            <i class="fa fa-paperclip"></i>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php echo $display['time']; ?>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                    endfor;
                                                endif;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div> <!-- panel body -->
                                </div> <!-- panel -->

                                <div class="row">
                                    <div class="col-xs-7">
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="btn-group pull-right">
                                          <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-left"></i></button>
                                          <button type="button" class="btn btn-default waves-effect"><i class="fa fa-chevron-right"></i></button>
                                        </div>
                                    </div>
                                </div>



                            </div> <!-- end Col-9 -->

                        </div><!-- End row -->


<?php
$template->call('footer');
$template->call('basicjs');
?>
<script>

    $(".open-mail").on('click', function(e) {
        e.preventDefault();
        var status = $("input[name='status']").val();
        var statistic = $("input[name='device']").val();
        var data = $("input[name='data_0']").val();
        var id = $("input[name='id']").val();

        var send = $(this).attr("data-value");
        var tag = $(this);

        $.ajax({ 
            type: "POST",
            url: "mail/fetcher.php",
            data: { status: status, statistic: statistic, data: data, id: send, id_0: id} 
        })
        .done(function( str ) { 
            $("#panel-modal .modal-content").html(str);
        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('.inline-editor').summernote('destroy');
        $('.note-popover').remove();
        $('#panel-modal .modal-content').empty();
    });

</script>
<?php
$template->call('endfile');