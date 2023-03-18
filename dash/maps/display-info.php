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
    array("","Peta Lokasi","1"),
);
$title = "Peta Lokasi";
$page_title = "SIBIMA-KU | Peta Lokasi";

$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);

$template->includejs("maps");

$template->call('include');
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>
                        <!-- SECTION FILTER
                        ================================================== -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h4 class="m-t-0 m-b-20 header-title"><b>Lokasi Pekerjaan</b></h4>

                                    <div id="gmaps-overlay" class="gmaps"></div>
                                </div>
                            </div>
                        </div>

<?php
$template->call('footer');
$template->call('includejs');
$template->call('endfile');