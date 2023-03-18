<?php
$limittable = 5;
date_default_timezone_set("Asia/Makassar");
require $dir_html."side/template.php";

$template = new template;

$exec = new dbExec($query);

if($s_level > 2){
    if(isset($_GET["id"])){
        $get_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);     
    }
    else {
        echo "MASUKKAN ID";
        exit();
    }

}
else {
    $param['where'] = array('name' => $tbl_dash.'.rel_id', 'value' => $s_id, 'type' => 'i');
    $param['table'] = $tbl_dash;
    $param['field'] = array('name' => 'id', 'result' => 'id');

    $check =$exec->select($param);
    $get_id = $check['id'];
}


$bc = array(
    array("/prokal/dash/?d=catman","Dashboard","0"),
    array("#","Pekerjaan","0"),
    array("","Dokumen","1"),
);
$title = "Detail Pekerjaan";
$page_title = "SIBIMA-KU | Detail Pekerjaan";

$pagejs = "docs/01.js";
$template->init($bc, $title, $page_title);
$template->pagejs($pagejs);

$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("bootstraptable", false);
$template->includejs("datepicker");
$template->includejs("inputmask");
$template->includejs("dropzone", false);
$template->includejs("summernote");
$template->includejs("tagsinput");
$template->includejs("peity");
$template->includejs("xeditable");

$template->call('include');
?>
    <style>
        .fixed-table-body {

            -moz-box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.1);
            -webkit-box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.1);
            border-radius: 0px;
            border: none;
            box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;



        }
        #data button {
            font-size: 75%; font-weight: 700; line-height: 1; white-space: nowrap; vertical-align: baseline; 
            border: none !important;
        }
        .p-l-20 {
            padding-left: 20px !important;
        }
        .p-l-8 {
            padding-left: 8px !important;
        }

        .str-custom.btn-custom {
            padding: 5px 6px !important;
            border-bottom-width: 0px !important;
        }
        label {
            display: block;
            width: 100%;
            height: 100%;
            padding: 6px 20px;
            cursor: pointer;
            font-weight: normal;
        }
        .checkbox label {
            padding: 0;
            padding-left: 5px;
        }
        #filter li {
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
    <link href="/assets/css/stardusk-bt.css" rel="stylesheet" type="text/css">

<?php
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>
                        <div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" >
                            <div class="modal-dialog">
                                <div class="modal-content p-0 b-0">
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <div class="row" id='docs'>
                            <div class="col-xs-12">
                        <?php if(checkId($get_id, $_SESSION, $tbl_dash, $query))
                            echo '<button class="btn btn-primary waves-effect waves-light pull-left new-modal m-r-5" data-primary="'.codeGen("c","d").'" data-mode="'.codeGen("5","2").'" type="button">Upload Dokumen</button>';
                            ?>
                                <div class="btn-group pull-left m-r-5" id="sorter">
                                    <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" aria-expanded="false">Urutkan <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="active sort">
                                            <input class="hidden" name="sorter" value="title" checked="" type="radio" id="radio1">
                                            <a class="p-0"><label for="radio1">Nama</label></a>
                                        </li>
                                        <li class="sort">
                                            <input class="hidden" name="sorter" value="format" type="radio" id="radio2">
                                            <a class="p-0"><label for="radio2">Jenis</label>
                                            </a>
                                        </li>
                                        <li class="sort">
                                            <input class="hidden" name="sorter" value="tag" type="radio" id="radio3">
                                            <a class="p-0"><label for="radio3">Tag</label></a>
                                        </li>
                                        <li class="sort">
                                            <input class="hidden" name="sorter" value="status" type="radio" id="radio4">
                                            <a class="p-0"><label for="radio4">Status</label>
                                            </a>
                                        </li>
                                        <li class="sort">
                                            <input class="hidden" name="sorter" value="time" type="radio" id="radio5">
                                            <a class="p-0"><label for="radio5">Waktu</label>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li class="order">
                                            <input class="hidden" name="order" value="asc" type="radio" id="radio6">
                                            <a class="p-0"><label for="radio6">A-Z / 0-9 <i class="md md-expand-less"></i></label>
                                            </a>
                                        </li>
                                        <li class="order active">
                                            <input class="hidden" name="order" value="desc" type="radio" id="radio7">
                                            <a class="p-0"><label for="radio7">Z-A / 9-0 <i class="md md-expand-more"></i></label>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="btn-group pull-left" id="filter">
                                    <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" aria-expanded="false">Filter <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <div class="checkbox checkbox-warning">
                                                <input id="checkbox-all" type="checkbox" checked="" name="all" value="all">
                                                <label for="checkbox-all">
                                                    Semua
                                                </label>
                                            </div>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <div class="checkbox checkbox-info">
                                                <input id="checkbox0" type="checkbox" name="format" value="docx">
                                                <label for="checkbox0">
                                                    DOCX
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-info">
                                                <input id="checkbox1" type="checkbox" name="format" value="doc">
                                                <label for="checkbox1">
                                                    DOC
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox2" type="checkbox" name="format" value="xlsx">
                                                <label for="checkbox2">
                                                    XLSX
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox3" type="checkbox" name="format" value="xls">
                                                <label for="checkbox3">
                                                    XLS
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-danger">
                                                <input id="checkbox4" type="checkbox" name="format" value="pdf">
                                                <label for="checkbox4">
                                                    PDF
                                                </label>
                                            </div>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <div class="checkbox checkbox-danger">
                                                <input id="checkbox5" type="checkbox" name="status" value="lock">
                                                <label for="checkbox5">
                                                    Terkunci
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-success">
                                                <input id="checkbox6" type="checkbox" name="status" value="open">
                                                <label for="checkbox6">
                                                    Terbuka
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkbox checkbox-primary">
                                                <input id="checkbox7" type="checkbox" name="status" value="wait">
                                                <label for="checkbox7">
                                                    Menunggu
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <table class="table table-hover mails m-0" id="data" data-token="<?php echo $s_token; ?>" data-primary="<?php echo codeGen("c","d"); ?>" data-mode="<?php echo codeGen("e","7"); ?>" data-id="<?php echo codeGen($get_id,"", true); ?>" data-sort="title" data-order="desc">
                                <thead>
                                    <tr>
                                        <th data-field="format" data-width="5%" data-sortable="true" class="p-l-20"></th>
                                        <th data-field="tag" data-sortable="true" data-width="5%" class="mail-select p-l-8"></th>
                                        <th data-field="title" data-sortable="true"></th>
                                        <th data-field="rev" data-sortable="true" data-width='20px'></th>
                                        <th data-field="status" data-sortable="true" data-width="130px"></th>
                                        <th data-field="time" data-sortable="true" data-width='8%' data-align="right" class="mail-select"></th>
                                    </tr>
                                </thead>
                                </table>

                            </div>
                        </div>





<?php
$template->call('footer');
$template->call('includejs');
$template->call('endfile');