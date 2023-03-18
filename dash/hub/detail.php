<?php
require $dir_html."hori/template.php";

$template = new template;

$bc = array(
    array("","Dashboard","1"),
    array("","Daftar Pekerjaan","1")
);
$title = "Daftar Pekerjaan";
$page_title = "SIBIMA-KU | Rincian Kegiatan";

$pagejs = "hub/02.js";

$template->init($bc, $title, $page_title);
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

        .str-custom.btn-custom {
            padding: 5px 6px !important;
            border-bottom-width: 0px !important;
        }

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

                <div class="row">

                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id="data" 
                            data-token="<?php echo $s_token; ?>" 
                            data-primary="<?php echo codeGen("3","b"); ?>" 
                            data-id="<?php echo codeGen($s_id,"", true); ?>" 
                            data-mode="<?php echo codeGen("4","c"); ?>">
                                <thead>
                                    <tr>
                                        <th data-field="title" data-sortable="true">Nama Pekerjaan</th>
                                        <th data-field="pptk" data-sortable="true">PPTK</th>
                                        <th data-field="pagu" data-sortable="true">Nilai Pagu Dana</th>
                                        <th data-field="kontrak" data-sortable="true">Nilai Kontrak</th>
                                        <th data-field="addendum" data-sortable="true">Nilai Addendum</th>
                                        <th data-field="fisik" data-sortable="true">Status Fisik</th>
                                        <th data-field="keuangan" data-sortable="true">Status Keuangan</th>
                                        <th data-field="penyerapan" data-sortable="true" data-visible="false">Penyerapan</th>
                                        <th data-field="sisa_pagu" data-sortable="true" data-visible="false">Sisa Anggaran</th>
                                        <th data-field="sisa_kontrak" data-sortable="true" data-visible="false">Sisa Kontrak</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>

<?php 
$template->call('footer');
$template->call('basicjs');
$template->call('endfile');
?>