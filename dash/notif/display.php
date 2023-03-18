<?php
require $dir_html."hori/template.php";

$template = new template;

$bc = array(
    array("","Dashboard","1"),
    array("","Permintaan","1")
);
$title = "Notifikasi";
$page_title = "SIBIMA-KU | Notifikasi";

$pagejs = 'mail/01.js';

$template->init($bc, $title, $page_title);
// $template->pagejs($pagejs);

$template->includejs("bootstraptable");
$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("summernote");

$template->call('include');
?>
    <style>

        .mails .mail-select {
            min-width: 80px;
        }
        .list-group-item {
            background-color: rgba(0,0,0,0);
        }
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
        #data .p-l-20 {
            padding-left: 20px !important;
        }
        #data .p-r-20 {
            padding-right: 20px !important;
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
        .label-stardusk {
            width: 100%; 
            display: block; 
            padding: 6px;
        }
        #data td {
            padding: 8px;
        }
        .dl-horizontal dt {
            text-overflow: initial !important;
            white-space: initial !important;
        }
        .list-group-item {
            border: none !important;
        }
        .m-t-m-25 {
            margin-top: -25px;
        }
        .separator {
            list-style: none;
            margin: 25px 0;
        }
        .unread .text {
            font-weight: bold;
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

<div class="row" id='mail'>
    <div class="col-xs-12">
        <div class="btn-group pull-left m-r-5" id="sorter">
            <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" aria-expanded="false">Urutkan <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
                <li class="active sort">
                    <input class="hidden" name="sorter" value="user" checked="" type="radio" id="radio1">
                    <a class="p-0"><label for="radio1">Kontraktor</label></a>
                </li>
                <li class="sort">
                    <input class="hidden" name="sorter" value="title" type="radio" id="radio2">
                    <a class="p-0"><label for="radio2">Proyek</label>
                    </a>
                </li>
                <li class="sort">
                    <input class="hidden" name="sorter" value="time" type="radio" id="radio3">
                    <a class="p-0"><label for="radio3">Waktu</label></a>
                </li>
                <li class="sort">
                    <input class="hidden" name="sorter" value="status" type="radio" id="radio4">
                    <a class="p-0"><label for="radio4">Status</label>
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
                    <div class="checkbox checkbox-inverse">
                        <input id="checkbox-all" type="checkbox" checked="" name="all" value="all">
                        <label for="checkbox-all">
                            Semua
                        </label>
                    </div>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="checkbox checkbox-warning">
                        <input id="checkbox0" type="checkbox" name="status" value="1">
                        <label for="checkbox0">
                            Baru
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox checkbox-warning">
                        <input id="checkbox1" type="checkbox" name="status" value="2">
                        <label for="checkbox1">
                            Revisi
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox checkbox-warning">
                        <input id="checkbox2" type="checkbox" name="status" value="3">
                        <label for="checkbox2">
                            Ditolak/Menunggu Revisi
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox checkbox-warning">
                        <input id="checkbox3" type="checkbox" name="status" value="4">
                        <label for="checkbox3">
                            <?php echo $s_level === 3 ? 'Diterima/Menunggu PPK' : 'Diterima'; ?>
                        </label>
                    </div>
                </li>
                <?php if($s_level === 3): ?>
                <li>
                    <div class="checkbox checkbox-warning">
                        <input id="checkbox4" type="checkbox" name="status" value="5">
                        <label for="checkbox4">
                            Ditolak PPK
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox checkbox-inverse">
                        <input id="checkbox5" type="checkbox" name="status" value="6">
                        <label for="checkbox5">
                            Diterima PPK
                        </label>
                    </div>
                </li>
            <?php endif; ?>
                <li class="divider"></li>
                <li>
                    <div class="checkbox checkbox-danger">
                        <input id="checkbox6" type="checkbox" name="read" value="aktif">
                        <label for="checkbox6">
                            Read
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox checkbox-success">
                        <input id="checkbox8" type="checkbox" name="read" value="inaktif">
                        <label for="checkbox8">
                            Unread
                        </label>
                    </div>
                </li>
            </ul>
        </div>
        <table class="table table-hover mails m-0" id="data" data-token="<?php echo $s_token; ?>" data-primary="<?php echo codeGen("f","1"); ?>" data-mode="<?php echo codeGen("c","d"); ?>" data-sort="time" data-order="desc">
        <thead>
            <tr>
                <th data-field="status" data-sortable="true" class="p-l-20" data-width="120px"></th>
                <th data-field="read" data-sortable="true" class="p-r-20"></th>
                <th data-field="kontraktor" data-sortable="true"></th>
                <th data-field="name" data-sortable="true"></th>
                <th data-field="aksi" data-sortable="true" data-width="100px"></th>
                <th data-field="time" data-width="100px" class="mail-select text-right p-r-20"></th>
            </tr>
        </thead>
        </table>

    </div>
</div>
<?php 
$template->call('footer');
$template->call('basicjs');
$template->call('endfile');
?>