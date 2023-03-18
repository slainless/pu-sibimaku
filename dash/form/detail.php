<?php
$exec = new dbExec($query);

$code = explode("-",$_GET['id']);

if(
    count($code) !== 2 ||
    codeCrypt($code[0], true) === false
)
    errCode("404", "Page not found");

if($_SESSION['req_token'] !== $code[1])
    errCode("EC005", "Token Expire, Silahkan akses melalui halaman Permintaan Pembayaran");

$code['id'] = codeCrypt($code[0], true);

$stmtq = "
    SELECT 
        ppk.name as ppk_name, 
        ppk.nip as ppk_nip, 
        kontraktor.direktur as kon_name, 
        kontraktor.alamat as kon_alamat,
        kontraktor.perusahaan as kon_perusahaan,
        kontraktor.bank as kon_bank,
        kontraktor.rekening as kon_rekening,
        kontraktor.npwp as kon_npwp,
        replace(substring_index(".$tbl_dash.".dp_info, ',', 1), '\"', '') as proyek_name, 
        replace(substring_index(".$tbl_dash.".dp_info, ',', -1), '\"', '') as proyek_lokasi,
        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', 1), '\"', '') as proyek_kontrak, 
        replace(substring_index(".$tbl_dash.".dp_kontrak, ',', -1), '\"', '') as proyek_tanggal,
        ".$tbl_dash.".n_kontrak as proyek_nilai,
        (select tahun from cat where id = ".$tbl_dash.".kategori) as proyek_tahun,
        ".$tbl_mail.".form as form
    FROM 
        (select name, nip from ".$tbl_mem." where level = 4 and mask_level = 4 and f_assign = 1) as ppk 
        join 
        (select direktur, alamat, perusahaan, bank, npwp, rekening from ".$tbl_mem." where id = 
            (select rel_id from ".$tbl_dash." where id = (select rel_id from ".$tbl_mail." where id = ?))
        ) as kontraktor
        join
        ".$tbl_dash." 
        join ".$tbl_mail." 
    on ".$tbl_mail.".rel_id = ".$tbl_dash.".id
    where ".$tbl_mail.".id = ?";

$param['param']['type'] = 'ii';
$param['param']['value'] = array($code['id'], $code['id']);
$param['result'] = array('ppk_name','ppk_nip','kon_name','kon_alamat','kon_perusahaan','kon_bank','kon_rekening','kon_npwp','proyek_name','proyek_lokasi','proyek_kontrak','proyek_tanggal','proyek_nilai', 'proyek_tahun', 'form');

$result = $exec->freeQuery($stmtq, $param);
unset($param);

if(!$result)
    errCode("EC000", "Document Expire");

require $dir_html."hori/template.php";
date_default_timezone_set("Asia/Makassar");

$template = new template;

$bc = array(
    array("","Dashboard","1"),
    array("/dash/catman","Kegiatan","0"),
    array("","Detail","1"),
);
$title = "Berita Acara Pembayaran";
$page_title = "SIBIMA-KU | Pengisian Form";

$pagejs = "catman/02.js";

$template->init($bc, $title, $page_title);
// $template->pagejs($pagejs);

$template->includejs("bootstraptable");
$template->includejs("sweetalert");
$template->includejs("datepicker");
$template->includejs("parsley");
$template->includejs("inputmask");
$template->includejs("peity");
$template->includejs("counterup");
$template->includejs("bootstrapwizard");

$template->call('include');
?>
    <style>

        .str-custom.btn-custom {

        }
        .wrapper {

            margin-top: 30px;

        }
        .container {
            width: 95%;
        }
        .list-group-item {
            background-color: rgba(0,0,0,0);
        }

        .dl-horizontal dt {
            text-overflow: initial !important;
            white-space: initial !important;
        }
        .list-group-item {
            border: none !important;
        }

        .separator {
            list-style: none;
            margin: 25px 0;
        }

        .bold {
            font-weight: bold;
        }

        .nav.nav-tabs > .active + li:not(.active) ~ li > a {
            cursor: not-allowed;
        }
    </style>

            <div class="wrapper">
                <div class="container">

                    <div class="row">
                        <div class="col-md-offset-2 col-md-8">                       
                            <div class="card-box p-b-0">
                                <select class="form-control pull-right" style="width: 180px; font-size: 13px" name="level" disabled>
                                    <option value="1">Uang Muka/UM</option>
                                </select>
                                <h4 class="text-dark  header-title m-t-0">Berita Acara Pembayaran</h4>
                                <p class="text-muted m-b-25 font-13">
                                    Form Pengisian BAP
                                </p>

                                <form id="commentForm" method="get" action="" class="form-horizontal" 
                                data-token="<?php echo $_SESSION['req_token']; ?>" 
                                data-primary="<?php echo codeGen("4","0"); ?>" >
                                    <div id="rootwizard" class="pull-in">
                                        <ul>
                                            <li><a href="#first" data-toggle="tab">Pihak dan Kontrak</a></li>
                                            <li><a href="#second" data-toggle="tab">Informasi Pekerjaan</a></li>
                                            <li><a href="#third" data-toggle="tab">Pembayaran</a></li>
                                        </ul>
                                        <div class="tab-content m-b-0 bx-s-0">
                                            <div class="tab-pane fade" id="first">
                                                <ul class="list-group dl-horizontal">
                                                    <li class="list-group-item">
                                                        <dd>
                                                            <span class="text-danger">Beberapa telah diisi oleh sistem dan beberapa bisa diralat jika ada kesalahan <br>( <b>Contoh</b> : kesalahan penggunaan huruf besar/kecil pada nama).</span>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>No. Kontrak</dt>
                                                        <dd>
                                                            <div class="input-group">
                                                                <input type="text" parsley-trigger="change" required  class="form-control" name="kontrak" placeholder="..." data-parsley-pattern="/^\d+\/\d{2}\.\d{2}\/[A-Z]+\/[A-Z.-]+\/[IXV]+\/\d{4}$/" value="620/25.08/BAP/PU-BM/IV/2017">
                                                                <span class="input-group-addon"><i class="md md-assignment"></i></span>
                                                            </div>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Tanggal</dt>
                                                        <dd>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <input class="form-state" type="checkbox" data-parsley-excluded>
                                                                </span>
                                                                <input parsley-trigger="change" class="form-control" name="tanggal_bap" placeholder="..." required disabled value="<?php echo date('d/m/Y'); ?>">
                                                                <span class="input-group-addon"><i class="md md-event-note"></i></span>
                                                            </div>
                                                        </dd>
                                                    </li>
                                                    <li class="separator">
                                                        <dt></dt>
                                                        <dd>
                                                            <label>Pihak Pertama</label>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Nama</dt>
                                                        <dd>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <input class="form-state" type="checkbox" data-parsley-excluded>
                                                                </span>
                                                                <input parsley-trigger="change" class="form-control bold" name="nama_1" placeholder="..." required disabled value="<?php echo $result['ppk_name']; ?>">
                                                                <span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
                                                            </div>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>NIP</dt>
                                                        <dd>
                                                            <input class="form-control" disabled placeholder="..." value="<?php echo $result['ppk_nip']; ?>">
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Jabatan</dt>
                                                        <dd>
                                                            <textarea class="form-control" disabled>Pejabat Pembuat Komitmen Dinas Pekerjaan Umum dan Tata Ruang Provinsi Kalimantan Utara</textarea>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Alamat</dt>
                                                        <dd>
                                                            <textarea class="form-control" disabled>Jalan Agatis, Tanjung Selor</textarea>
                                                        </dd>
                                                    </li>
                                                    <li class="separator">
                                                        <dt></dt>
                                                        <dd>
                                                            <label>Pihak Kedua</label>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Nama</dt>
                                                        <dd>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <input class="form-state" type="checkbox" data-parsley-excluded>
                                                                </span>
                                                                <input parsley-trigger="change" class="form-control bold" name="nama_2" placeholder="..." required disabled value="<?php echo $result['kon_name']; ?>">
                                                                <span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
                                                            </div>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Jabatan</dt>
                                                        <dd>
                                                            <textarea class="form-control" placeholder="..." disabled>Direktur <?php echo $result['kon_perusahaan']; ?></textarea>
                                                        </dd>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <dt>Alamat</dt>
                                                        <dd>
                                                            <textarea class="form-control" placeholder="..." disabled=""><?php echo $result['kon_alamat']; ?></textarea>
                                                        </dd>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                            <div class="tab-pane fade" id="second">
                                                <div class="control-group">
                                                    <ul class="list-group dl-horizontal">
                                                        <li class="list-group-item">
                                                            <dt>No. DPA</dt>
                                                            <dd>
                                                                <input type="text" parsley-trigger="change" required  class="form-control" name="dpa" placeholder="..." value="1.03.1.03.01.15.03">
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Tanggal DPA</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <input parsley-trigger="change" class="form-control" name="tanggal_dpa" placeholder="..." required value="13/01/2017">
                                                                    <span class="input-group-addon"><i class="md md-event-note"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="separator">
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Nama Pekerjaan</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <textarea class="form-control" placeholder="..." disabled><?php echo $result['proyek_name']; ?></textarea>
                                                                    <span class="input-group-addon"><i class="md md-dashboard"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Kode Pekerjaan</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input class="form-state" type="checkbox" checked data-parsley-excluded>
                                                                    </span>
                                                                    <input parsley-trigger="change" class="form-control" name="kode" placeholder="..." required value="1.03.1.03.01.15.03.5.2.3.59.02">
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Lokasi</dt>
                                                            <dd>
                                                                    <input class="form-control" placeholder="..." disabled value="<?php echo $result['proyek_lokasi']; ?>">
                                                            </dd>
                                                        </li>
                                                        <li class="separator">
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>No. Kontrak</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <input class="form-control" placeholder="..." disabled value="<?php echo $result['proyek_kontrak']; ?>">
                                                                    <span class="input-group-addon"><i class="md md-assignment"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Tanggal Kontrak</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <input class="form-control" placeholder="..." disabled value="<?php echo str_replace('-', '/', $result['proyek_tanggal']); ?>">
                                                                    <span class="input-group-addon"><i class="md md-event-note"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Nilai Kontrak</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon bold">RP</span>
                                                                    <input type="text" class="form-control" placeholder="..." disabled value="<?php echo number_format($result['proyek_nilai'], 0, '.', '.'); ?>">
                                                                    <span class="input-group-addon bold">00</span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Sumber Dana</dt>
                                                            <dd>
                                                                <input class="form-state" type="checkbox" data-parsley-excluded>
                                                                <select class="form-control pull-right" style="width: 95%" name="sumber" disabled>
                                                                    <option value="1">APBD Provinsi Kalimantan Utara</option>
                                                                    <option value="2">APBN Provinsi Kalimantan Utara</option>
                                                                </select>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Tahun Anggaran</dt>
                                                            <dd>
                                                                    <input type="text" class="form-control" placeholder="..." disabled value="<?php echo $result['proyek_tahun']; ?>">
                                                            </dd>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="third">
                                                <ul class="list-group dl-horizontal">
                                                        <li class="list-group-item">
                                                            <dt>Penerima</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <input class="form-control"placeholder="..." disabled value="<?php echo $result['kon_perusahaan']; ?>">
                                                                    <span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Bank</dt>
                                                            <dd>
                                                                <select class="form-control pull-right" disabled>
                                                                    <option value="1" <?php if($result['kon_bank'] === 1) echo 'selected'; ?>>Bank BPD Kaltim Cabang Utama Samarinda</option>
                                                                </select>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>No. Rekening</dt>
                                                            <dd>
                                                                <div class="input-group">
                                                                    <input class="form-control" placeholder="..." disabled="" value="<?php echo $result['kon_rekening']; ?>">
                                                                    <span class="input-group-addon"><i class="md md-credit-card"></i></span>
                                                                </div>
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>No. NPWP</dt>
                                                            <dd>
                                                                    <input class="form-control" placeholder="..." disabled="" value="<?php echo $result['kon_npwp']; ?>">
                                                            </dd>
                                                        </li>
                                                        <li class="separator">
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>No. Surat Jaminan Bank</dt>
                                                            <dd>
                                                                    <input parsley-trigger="change" class="form-control" name="bayar" placeholder="..." required value="">
                                                            </dd>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <dt>Tanggal Surat Jaminan Bank</dt>
                                                            <dd>
                                                                    <input parsley-trigger="change" class="form-control" name="tanggal_bayar" placeholder="..." required value="">
                                                            </dd>
                                                        </li>
                                                    </ul>
                                            </div>
                                            <ul class="pager m-b-0 wizard">
                                                <li class="previous"><a href="#" class="btn btn-primary waves-effect waves-light">Previous</a></li>
                                                <li class="next"><a href="#" class="btn btn-primary waves-effect waves-light">Next</a>
                                                <button class="btn btn-inverse waves-effect waves-light hidden pull-right" type="submit"
                                                data-mode="<?php echo codeGen("7","b"); ?>"
                                                data-id="<?php echo codeGen($code['id'], "", true); ?>">Kirim</button>
                                                <button class="btn btn-info waves-effect waves-light hidden pull-right m-r-5" type="submit"
                                                data-mode="<?php echo codeGen("b","8"); ?>"
                                                data-id="<?php echo codeGen($code['id'], "", true); ?>">Tinjau</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

<?php
$template->call('basicjs');
?>
<script>

    $('#commentForm').parsley({
        requiredMessage: 'Input tidak boleh kosong.',
        errorsContainer: function(el) {
            return el.$element.closest('dd');
        },
        excluded: "input[data-parsley-excluded], input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"
    });
    $('input[name^="kontrak"]').parsley({
        patternMessage: 'Input harus mengikuti format [0-9]/00.00/[A-Z]/[A-Z.-]/[IXV]/0000. <br>(* []: Karakter yang diizinkan, 0: Angka)',
    });
    $('input[name^="nip"]').parsley({
        patternMessage: 'Input hanya boleh terdiri dari [0-9.-] dan spasi',
    });
    $('input[name^="nilai"]').mask('999.000.000.000.000', {reverse: true});
    $('input[name^="tanggal"]').mask('00/00/2000');
    $('input[name^="tahun"]').mask('2000');
    $('input[name^="tanggal"]').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    });
    $('#rootwizard').bootstrapWizard({
        'tabClass': 'nav nav-tabs navtab-custom nav-justified bg-muted',
        'onNext': function (tab, navigation, index, target) {
           /* var $valid = $("#commentForm").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }*/
            var valid = $('#commentForm').parsley().validate();
            if (!valid) {
                return false;
            }
        },
        onTabClick: function(tab, navigation, index, target) {

            if(index < target){
                var valid = $('#commentForm').parsley().validate();
                if (!valid) {
                    return false;
                }

                if(target - index > 1){
                    return false;
                }
            }
        },
        onTabShow: function(tab, navigation, index) {

            var count = navigation.children().length;
            var that = $('.wizard li.next');
            if(index === count-1){
                that.removeClass('disabled');
                that.children('a').addClass('hidden');
                that.children('button').removeClass('hidden');
            }
            else {
                that.children('a').removeClass('hidden');
                that.children('button').addClass('hidden');
            }
        }
    });

    $('button[type="submit"]').on('click', function(e) {
        var that = $(this);
        subdata = that.attr('data-id');
        submode = that.attr('data-mode');

    });

    $('#commentForm').on('submit', function(e){

        var valid = $(this).parsley().validate();
        var that = $(this);

        if (!valid) {
            return false;
        }

        e.preventDefault();

        data = $(this).serialize();
        data += "&primary=" + that.attr('data-primary');
        data += "&token=" + that.attr('data-token');
        data += "&mode=" + submode;
        data += "&data=" + subdata;

        $.ajax({

            type: "POST",
            url: '/dash/form/processor',
            data: data, // serializes the form's elements.

        }).done(function( str ) {
            var data = JSONParser(str);
            if(data){

                if(data.alert === undefined) {

                    var win = window.open(data.preview, '_blank');
                    if (win) {
                        //Browser has allowed it to be opened
                        win.focus();
                    } else {
                        //Browser has blocked it
                        alert('Please allow popups for this website');
                    }
                    
                }
                else {

                    swal({
                        title: data.alert.title,
                        text: data.alert.text,
                        type: data.alert.type,
                        showConfirmButton: data.alert.confirm,
                        timer: data.alert.timer
                    }, function (isConfirm) {
                        if(data.alert.type === 'success') {
                            window.top.close();
                        }
                    });
                }

                

                /*
                $('#home-tc').empty().append(data.data_0);
                $('#waktu-keterangan').empty().append(data.data_1);
                $('#waktu-info').empty().append(data.data_2);
                $('.donut-yellow').text(data.chart).change();*/

            }
            else {
            }
        });
    });

    $('.form-state').on('change', function(){
        var input = $(this).parent().siblings("input");
        var select = $(this).siblings("select");

        if(this.checked){
            input.removeAttr('disabled');
            select.removeAttr('disabled');
        }
        else {
            input.attr('disabled', '');
            select.attr('disabled', '');
        }
    });


    function JSONParser (str){
        try {
            var o = JSON.parse(str);

            if (o && typeof o === "object") {
                return o;
            }
        }
        catch (e) { }

        return false;
    };
</script>
<?php
$template->call('endfile');
?>