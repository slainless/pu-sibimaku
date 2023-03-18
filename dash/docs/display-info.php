<?php
$limittable = 5;
date_default_timezone_set("Asia/Makassar");
require $dir_html."side/template.php";

$template = new template;

$exec = new dbExec($query);

if($s_level > 2){
	if(isset($_GET["id"])){
		$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);     
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

$pagejs = "bootstrap-table-mods.js";
$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);
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
    </style>
    <link href="/prokal/assets/css/stardusk-bt.css" rel="stylesheet" type="text/css">

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
                            echo '<button class="btn btn-primary waves-effect waves-light pull-left new-modal" data-primary="'.codeGen("c","d").'" data-mode="'.codeGen("5","2").'" data-id="'.codeGen($get_id,"", true).'" type="button">Upload Dokumen</button>';
							?>
                                <table class="table table-hover mails m-0" id="data" data-token="<?php echo $s_token; ?>" data-primary="<?php echo codeGen("c","d"); ?>" data-mode="<?php echo codeGen("e","7"); ?>" data-id="<?php echo codeGen($get_id,"", true); ?>" data-action="<?php if($s_level > 2) echo codeGen('1','1'); else echo codeGen('0','0'); ?>">
                                <thead>
                                    <tr>
                                        <th data-field="format" data-width="5%" data-sortable="true" class="p-l-20"></th>
                                        <th data-field="tag" data-sortable="true" data-width="5%" class="mail-select p-l-8"></th>
                                        <th data-field="title" data-sortable="true"></th>
                                        <th data-field="rev" data-sortable="true" data-width='20px'></th>
                                        <th data-field="status" data-sortable="true" data-width="90px"></th>
                                        <th data-field="time" data-sortable="true" data-width='8%' data-align="right" class="mail-select"></th>
                                    </tr>
                                </thead>
                            	</table>

							</div>
						</div>





<?php
$template->call('footer');
$template->call('includejs');
?>
<script>

    $('#data').bootstrapTable({
        pagination: true,
        showRefresh: true,
        pageSize: 50,
        showHeader: false,
        sidePagination: 'server',
        showFooter: false,
        search: true,
        sortOrder: 'desc',
        sortName: 'time',
        url: 'docs/fetcher.php',
        method: 'post',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function(params) {
        	that = $('#data');

            params.id = that.attr("data-id");
            params.mode = that.attr("data-mode");
            params.primary = that.attr("data-primary");
            params.token = that.attr("data-token");
            params.action = that.attr("data-action");
            return params;
        },
        rowStyle: 'rowFormatter',
        responseHandler: 'dataHandler',

    });

    function dataHandler(data) {
    	that = $('[data-token]');

    	that.attr("data-token", data.token);
        if(typeof data.rows === 'undefined'){
            $('#data').bootstrapTable('removeAll');
        }
    	return data;
    }

    function rowFormatter(row, index) {
        
        if(row.unlock === 1){
            return {
                css: '',
                classes: 'unread'
            }
        }
        else {
            return {
                css: '',
                classes: ''
            }
        }
    }

    $('#data').on('load-error.bs.table', function(status, res) {
        $(this).bootstrapTable('removeAll');
    });

    $("#docs").on('click', '.new-modal', function(e){
    	that = $(this);

    	$("#panel-modal .modal-content").load('docs/fetcher.php', {
    		'id' : that.attr("data-id"),
            'mode' : that.attr("data-mode"),
            'primary' : that.attr("data-primary"),
    	}, function(data, status, xhr){
    		  
            if(status == 'success'){
                $('#panel-modal').modal('show');
            }

    	});

    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal .modal-content').empty();
        $('.dz-hidden-input').remove();
    });

</script>
<?php
$template->call('endfile');