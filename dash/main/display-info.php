<?php
$limittable = 5;
date_default_timezone_set("Asia/Makassar");
require $dir_html."side/template.php";

$template = new template;

$exec = new dbExec($query);


if($s_level > 2){
	if(isset($_GET["id"])){
		$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$param['where'] = array('name' => $tbl_dash.'.id', 'value' => $get_id, 'type' => 'i'); 
	}
	else {
		echo "MASUKKAN ID";
		exit();
	}

}
else {

	$param['where'] = array('name' => $tbl_dash.'.rel_id', 'value' => $s_id, 'type' => 'i');
}

$param['field'] = array(
	array('name' => $tbl_dash.'.rel_id', 'result' => 'id_kontraktor'), 
	array('name' => $tbl_dash.'.kategori', 'result' => 'id_kegiatan'), 
	array('name' => $tbl_dash.'.dp_info', 'result' => 'info_proyek'), 
	array('name' => $tbl_dash.'.dp_kontrak', 'result' => 'info_kontrak'),
	array('name' => $tbl_dash.'.dp_surat', 'result' => 'info_surat'), 
	array('name' => $tbl_dash.'.pf_info', 'result' => 'info_fisik'), 
	array('name' => $tbl_dash.'.pf_total', 'result' => 'persen_fisik'), 
	array('name' => $tbl_dash.'.mp_info', 'result' => 'info_monitor'), 
	array('name' => $tbl_dash.'.rp_info_prg', 'result' => 'info_progress'), 
	array('name' => $tbl_dash.'.rp_info_waktu', 'result' => 'info_waktu'), 
	array('name' => $tbl_dash.'.rp_info_prg_c', 'result' => 'chart_progress'), 
	array('name' => $tbl_dash.'.rp_info_waktu_c', 'result' => 'chart_waktu'), 
	array('name' => $tbl_dash.'.ke_info', 'result' => 'penyerapan'), 
	array('name' => $tbl_dash.'.ke_c', 'result' => 'chart_uang'), 
	array('name' => $tbl_dash.'.gallery', 'result' => 'gallery'), 
	array('name' => $tbl_dash.'.mk_info', 'result' => 'info_monitor_k'), 
	array('name' => $tbl_dash.'.mk_c_xy', 'result' => 'chart_monitor_k_xy'), 
	array('name' => $tbl_dash.'.mk_c_info', 'result' => 'chart_monitor_k_info'), 
	array('name' => $tbl_dash.'.na_info', 'result' => 'info_nama'), 
	array('name' => $tbl_dash.'.lead_id', 'result' => 'id_pptk'), 
	array('name' => $tbl_dash.'.id', 'result' => 'id_proyek'), 
	array('name' => $tbl_dash.'.n_kontrak', 'result' => 'kontrak'), 
	array('name' => $tbl_dash.'.n_pagu', 'result' => 'pagu'),
	array('name' => $tbl_dash.'.dp_addendum', 'result' => 'info_addendum'),
	array('name' => $tbl_dash.'.konsultan', 'result' => 'konsultan'),
	array('name' => $tbl_cat.'.title', 'result' => 'nama_kegiatan'),
	array('name' => $tbl_cat.'.tahun', 'result' => 'tahun'),
);
$param['table'] = array($tbl_dash, $tbl_cat);
$param['join_op'] = array('left join');
$param['on'] = array(
		array('name' => $tbl_dash.'.kategori', 'target' => $tbl_cat.'.id'),
);

$result = $exec->select($param);
unset($param);

if(!$result || empty($result)){
	if($s_level > 2):
		errCode("404", "Page not Found");
	else:
		errCode("EC010", "Akun anda belum diaktifkan, silahkan kembali lagi nanti<br> atau hubungi pihak Operator.", true);
	endif;
}

if($s_level == 2){
	$get_id = $result['id_proyek'];
}


$stmtq = "SELECT (SELECT name FROM ".$tbl_mem." WHERE level = 5) as kadis, (SELECT name FROM ".$tbl_mem." WHERE level = 4) as ppk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as pptk, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as kontraktor, (SELECT name FROM ".$tbl_mem." WHERE id = ?) as konsultan";
$param['param']['type'] = 'iii';
$param['param']['value'] = array($result['id_pptk'], $result['id_kontraktor'], $result['konsultan']);

$param['result'] = array('kadis','ppk','pptk', 'kontraktor', 'konsultan');

$pelaksana = $exec->freeQuery($stmtq, $param);
unset($param);

$_SESSION['kegiatan'] = $result['id_kegiatan'];

$bc = array(
	array("","Dashboard","1"),
	array("/prokal/dash/?d=catman&id=".$result['id_kegiatan'],"Kegiatan","0"),
	array("","Pekerjaan","1"),
);
$title = "Rincian Pekerjaan";
$page_title = "SIBIMA-KU | Rincian Pekerjaan";

$pagejs = "d2-display.init.js";
$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);


$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("bootstraptable");
$template->includejs("datepicker");
$template->includejs("inputmask");
$template->includejs("dropzone");
$template->includejs("summernote");
$template->includejs("tagsinput");
$template->includejs("peity");
$template->includejs("xeditable");

$template->call('include');
?>
    <style>
        .parsley-except .parsley-errors-list {
            display: none;
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
		th[rowspan] {
			vertical-align: middle !important;
		}
        .ct-series-a .ct-point,  .ct-series-a .ct-line {
            /*stroke: #FFAA00;*/ stroke: #4C5667;
        }
        .ct-series-b .ct-point,  .ct-series-b .ct-line {
            stroke: #00B19D;
        }
        .ct-label {
        	font-size: 1.1rem;
        	fill: rgba(0,0,0,.6);
        	color: rgba(0,0,0,.6);
        }
/*		th[colspan] {
			text-align: center !important;
		}*/
    </style>

<?php
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>
                        <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="status">
                        <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="device">
                        <input type="hidden" value="<?php echo codeGen(random_int(1,20),random_int(1,15)); ?>" name="id">
                        <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="data_0">
						<div id="panel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" >
                            <div class="modal-dialog">
                                <div class="modal-content p-0 b-0">
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

						<div class="row">
							<div class="col-lg-9">
								<div class="row">
									<div class="col-sm-12">
										<ul class="nav nav-tabs tabs tabs-top">
											<li class="active tab">
												<a href="#home-12" data-toggle="tab" aria-expanded="false">
													<span class="visible-xs"><i class="fa fa-home"></i></span>
													<span class="hidden-xs">Info Proyek</span>
												</a>
											</li>
											<li class="tab">
												<a href="#profile-12" data-toggle="tab" aria-expanded="false">
													<span class="visible-xs"><i class="fa fa-user"></i></span>
													<span class="hidden-xs">Personalia</span>
												</a>
											</li>
											<li class="tab">
												<a href="#messages-12" data-toggle="tab" aria-expanded="true">
													<span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
													<span class="hidden-xs">Pelaksanaan</span>
												</a>
											</li>
											<li class="tab">
												<a href="#settings-12" data-toggle="tab" aria-expanded="false">
													<span class="visible-xs"><i class="fa fa-cog"></i></span>
													<span class="hidden-xs">Addendum</span>
												</a>
											</li>
											<li class="tab">
												<a href="#nilai-12" data-toggle="tab" aria-expanded="false">
													<span class="visible-xs"><i class="fa fa-cog"></i></span>
													<span class="hidden-xs">Nilai Proyek</span>
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="home-12">
												<ul class="list-group dl-horizontal">
												<?php
													$result['info_proyek'] = cta($result['info_proyek']);
													$result['info_kontrak'] = cta($result['info_kontrak']);
												?>
		                                            <li class="list-group-item">
		                                                <dt>Kegiatan</dt>
		                                                <dd><?php echoalt($result['nama_kegiatan'], '-'); ?></dd>
		                                            </li>
		                                            <li class="list-group-item">
		                                                <dt>Pekerjaan</dt>
		                                                <dd><?php echoalt($result['info_proyek'][0], '-'); ?></dd>
		                                            </li>
		                                            <li class="list-group-item">
		                                                <dt>No. Kontrak</dt>
		                                                <dd><?php echoalt($result['info_kontrak'][0], '-'); ?></dd>
		                                            </li>
		                                            <li class="list-group-item">
		                                                <dt>Tgl. Kontrak</dt>
		                                                <dd><?php echoalt(customDate($result['info_kontrak'][1]), '-'); ?></dd>
		                                            </li>
		                                            <li class="list-group-item">
		                                                <dt>Tahun</dt>
		                                                <dd><?php echoalt($result['tahun'], '-'); ?></dd>
		                                            </li>
		                                            <li class="list-group-item">
		                                                <dt>Lokasi Kegiatan</dt>
		                                                <dd><?php echoalt($result['info_proyek'][1], '-'); ?></dd>
		                                            </li>
		                                        </ul>
		                                        <?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger m-t-m-25 pull-right clearfix" data-toggle="modal" data-target="#panel-modal" value="1" type="button">Edit</button>'; ?>
											</div>
											<div class="tab-pane" id="profile-12">
												<ul class="list-group dl-horizontal">
													<li class="list-group-item">
		                                                <dt>Pengguna Anggaran</dt>
		                                                <dd><?php echoalt($pelaksana['kadis'], '-'); ?></dd>
		                                           	</li>
		                                           	<li class="list-group-item">
		                                                <dt>Pejabat Pembuat Komitmen</dt>
		                                                <dd><?php echoalt($pelaksana['ppk'], '-'); ?></dd>
		                                           	</li>
		                                           	<li class="list-group-item">
		                                                <dt>Pejabat Pelaksana Teknis Kegiatan</dt>
		                                                <dd><?php echoalt($pelaksana['pptk'], '-'); ?></dd>
		                                           	</li>
		                                           	<li class="list-group-item">
		                                                <dt>Kontraktor Pelaksana</dt>
		                                                <dd><?php echoalt($pelaksana['kontraktor'], '-'); ?></dd>
		                                           	</li>
		                                           	<li class="list-group-item">
		                                                <dt>Konsultan Supervisi</dt>
		                                                <dd><?php echoalt($pelaksana['konsultan'], '-'); ?></dd>
		                                           	</li>
		                                        </ul>
		                                        <?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right m-t-m-25 clearfix" data-toggle="modal" data-target="#panel-modal" value="2" type="button" >Edit</button>'; ?>
											</div>
											<div class="tab-pane" id="messages-12">
											<?php
												$result['info_surat'] = cta($result['info_surat']);
												if($result['info_surat']):
													$temp = $result['info_surat'][2] - 1;
													$result['info_akhir_waktu'] = strtotime("+".$temp." days", strtotime(reformDate($result['info_kontrak'][1])));
													$result['info_surat'][4] = customDate(date("d-m-Y", strtotime("+".$temp." days", strtotime(reformDate($result['info_kontrak'][1])))));
													// strtotime("+120 days", reformDate($result['info_kontrak'][1]));
													unset($temp);
												endif;
											?>
												<ul class="list-group dl-horizontal">
													<li class="list-group-item">
														<dt>Surat Penyerahan Lapangan</dt>
														<dd><?php echoalt($result['info_surat'][0], "-"); ?>
														</dd>
													</li>
													<li class="list-group-item">
														<dt>Surat Perintah Mulai Kerja</dt>
														<dd><?php echoalt($result['info_surat'][1], "-"); ?>
														</dd>
													</li>
													<li class="list-group-item">
														<dt>Masa Pelaksanaan</dt>
														<dd><?php echoalt($result['info_surat'][2], "-", " Hari"); ?>
														</dd>
													</li>
													<li class="list-group-item">
														<dt>Masa Pemeliharaan</dt>
														<dd><?php echoalt($result['info_surat'][3], "-", " Hari"); ?>
														</dd>
													</li>
													<li class="list-group-item">
														<dt>Akhir Masa Pelaksanaan</dt>
														<dd><?php echoalt($result['info_surat'][4], "-"); ?>
														</dd>
													</li>
												</ul>
												<?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right m-t-m-25 clearfix" data-toggle="modal" data-target="#panel-modal" value="3" type="button" >Edit</button>'; ?>
											</div>
											<div class="tab-pane" id="settings-12">
											<?php
												$result['info_addendum'] = cta($result['info_addendum'], true);
												if(!$result['info_addendum']) $result['info_addendum'] = array(array('','','0'));
											?>
												<ul class="list-group dl-horizontal">
												<?php foreach ($result['info_addendum'] as $key => $value): ?>
													<li class="list-group-item">
														<dt>No. Addendum Kontrak 0<?php echo $key + 1; ?></dt>
														<dd><?php echoalt($value[0], "-"); ?></dd>
													</li>

													<li class="list-group-item">
														<dt>Tanggal Addendum Kontrak 0<?php echo $key + 1; ?></dt>
														<dd><?php if(isset($value[1])) echoalt(customDate($value[1]), "-"); else echo "-"; ?></dd>
													</li>
												<?php endforeach; ?>
												</ul>
												<?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right m-t-m-25 clearfix" data-toggle="modal" data-target="#panel-modal" value="4" type="button" >Edit</button>'; ?>
											</div>

											<div class="tab-pane" id="nilai-12">
												<ul class="list-group dl-horizontal">
													<li class="list-group-item">
														<dt>Nilai Kontrak</dt>
														<dd><?php if(!$result['kontrak']) $result['kontrak'] = 0; echo "Rp. ".number_format($result['kontrak'], 2, ",", "."); ?></dd>
													</li>
												<?php foreach ($result['info_addendum'] as $key => $value): ?>
													<li class="list-group-item">
														<dt>Nilai Addendum Kontrak 0<?php echo $key + 1; ?></dt>
														<dd><?php echoalt("Rp. ".number_format($value[2], 2, ",", "."), "-"); ?></dd>
													</li>
												<?php endforeach; ?>
													<li class="list-group-item">
														<dt>Nilai Pagu</dt>
														<dd><?php echoalt("Rp. ".number_format($result['pagu'], 2, ",", "."), "-"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Penyerapan</dt>
														<dd><?php if(!$result['penyerapan']) $result['penyerapan'] = 0; echo "Rp. ".number_format($result['penyerapan'], 2, ",", "."); ?></dd>
													</li>
												</ul>
												<?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right m-t-m-25 clearfix" data-toggle="modal" data-target="#panel-modal" value="6" type="button" >Edit</button>'; ?>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="row">
											<?php
												$result['info_monitor'] = cta($result['info_monitor'], true);
												$temp = date('m');
												$now = date('Y');

												if($now == $result['tahun']):
													settype($temp, 'int');

													$result['progress'] = $result['info_monitor'][$temp-1];
													settype($result['progress'][1], 'float');
													settype($result['progress'][0], 'float');

													$result['progress'][2] = $result['progress'][1] - $result['progress'][0];
												else:
													$result['progress'][0] = $result['progress'][1] = $result['progress'][2] = 0;
												endif;

												for($x=12-1;$x>=0;$x--){
									    			if(!empty($result['info_monitor'][$x][1])){
									    				$result['persen_progress'] = round($result['info_monitor'][$x][1], 1);
									    				break;
									    			}
												}

												if(isset($result['persen_progress'])){
													$result['sisa_fisik'] = 100 - $result['persen_progress'];
												}
												else {
													$result['sisa_fisik'] = 100;
													$result['persen_progress'] = 0;
												}

											?>
											<div class="col-sm-12">
												<div class="panel panel-color panel-danger">
													<div class="panel-heading text-center">
														<h3 class="panel-title">Progress</h3>
													</div>

													<div class="panel-body">
														<span class="donut-red"><?php echo $result['sisa_fisik'].",".$result['persen_progress']; ?></span>
													</div>

												</div>
											</div>
											<div class="col-sm-12">
												<div class="panel panel-border panel-danger">
													<div class="panel-heading text-center">
														<h3 class="panel-title">Keterangan</h3>
													</div>

													<div class="panel-body text-right">
				                                		<i class="pull-left md md-label text-inverse"></i><span class="text-muted">Realisasi <strong class="text-inverse">(<?php echo $result['persen_progress']; ?> %)</strong></span><br>
														<i class="pull-left md md-label text-danger"></i><span class="text-muted">Belum Realisasi <strong class="text-danger">(<?php echo $result['sisa_fisik']; ?> %)</strong></span><br>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="row">
											<div class="col-sm-12">
												<div class="panel panel-color panel-warning">
													<div class="panel-heading text-center">
														<h3 class="panel-title">Waktu</h3>
													</div>

													<?php
														if($result['info_surat'] && !(empty($result['info_surat'][2]) && empty($result['info_kontrak'][1]))):
															$temp = $result['info_akhir_waktu'];
															$temp = ceil(($temp-time())/60/60/24);

															$f_waktu = 0;
	                                                        $result['waktu_sisa'] = $temp;
	                                                        $result['waktu_terpakai'] = $result['info_surat'][2]  - $result['waktu_sisa'];

	                                                        if($result['waktu_terpakai'] <= 0) { $result['waktu_terpakai'] = 0; $result['waktu_sisa'] = $result['info_surat'][2]; $f_waktu = 1; }
	                                                        elseif($result['waktu_terpakai'] > $result['info_surat'][2]) { $result['waktu_sisa'] = 0; $result['waktu_terpakai'] = $result['info_surat'][2];
	                                                    	};

	                                                    	if(empty($result['info_surat'][2])){
	                                                    		$result['persen_waktu_terpakai'] = $result['persen_waktu_sisa'] = 0;
	                                                    	}
	                                                    	else {
	                                                    		$result['persen_waktu_terpakai'] = round($result['waktu_terpakai']/($result['waktu_terpakai'] + $result['waktu_sisa']) * 100, 1);
	                                                    		$result['persen_waktu_sisa'] = 100 - $result['persen_waktu_terpakai'];
	                                                    	}
	                                                    else:
	                                                    	$result['waktu_sisa'] = $result['waktu_terpakai'] = $result['persen_waktu_terpakai'] = 0;
	                                                    	$result['persen_waktu_sisa'] = 100;
	                                                    endif;

													?>
													<div class="panel-body">
														<span class="donut-yellow"><?php echo $result['waktu_sisa'].",".$result['waktu_terpakai']; ?></span>
													</div>

												</div>
											</div>
											<div class="col-sm-12">
												<div class="panel panel-border panel-warning">
													<div class="panel-heading text-center">
														<h3 class="panel-title">Keterangan</h3>
													</div>

													<div class="panel-body text-right">
				                                		<i class="pull-left md md-label text-inverse"></i><span class="text-muted">Waktu Terpakai <strong class="text-inverse">(<?php echo $result['persen_waktu_terpakai']; ?> %)</strong></span><br>
				                                		<i class="pull-left md md-label text-warning"></i><span class="text-muted">Sisa Waktu <strong class="text-warning">(<?php echo $result['persen_waktu_sisa']; ?> %)</strong></span><br>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="panel panel-border panel-danger">
											<div class="panel-heading text-center">
												<h3 class="panel-title"></h3>
											</div>

											<div class="panel-body">
												<ul class="list-group">
													<li class="list-group-item">
														<dt>Kumulatif Rencana</dt>
														<dd><?php echoalt($result['progress'][0], "-", " %"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Kumulatif Realisasi</dt>
														<dd><?php echoalt($result['progress'][1], "-", " %"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Deviasi</dt>
														<dd><?php echoalt($result['progress'][2], "-", " %"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Progress Bulan ini</dt>
														<dd><?php echoalt($result['progress'][1], "-", " %"); ?></dd>
													</li>
												</ul>
											</div>

										</div>
									</div>

								</div>
							</div>
							<div class="col-lg-3">
								<div class="row">
									<div class="col-sm-12">
										<div class="panel panel-color panel-inverse">
											<div class="panel-heading text-center">
												<h3 class="panel-title">KEUANGAN</h3>
											</div>

											<div class="panel-body">
											<?php
												$x = count($result['info_addendum']);
												if($result['info_addendum'][$x-1][2]) 
													$result['sisa_kontrak'] = $result['info_addendum'][$x-1][2] - $result['penyerapan'];
												else
													$result['sisa_kontrak'] = $result['kontrak'] - $result['penyerapan'];
											?>
												<span class="donut"><?php echo $result['penyerapan'].','.$result['sisa_kontrak']; ?></span>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="panel panel-border panel-inverse">
											<div class="panel-heading text-center">
												<h3 class="panel-title">Keterangan</h3>
											</div>

											<div class="panel-body text-right">
											<?php
											if($result['penyerapan'] + $result['sisa_kontrak'] > 0):
												$result['persen_penyerapan'] = round($result['penyerapan']/($result['penyerapan'] + $result['sisa_kontrak'])*100, 1);
												$result['persen_sisa_kontrak'] = 100 - $result['persen_penyerapan'];
											else:
												$result['persen_penyerapan'] = $result['persen_sisa_kontrak'] = 0;
											endif;
												?>
		                                		<i class="pull-left md md-label text-inverse"></i><span class="text-muted">Realisasi <strong class="text-inverse">(<?php echo $result['persen_penyerapan']; ?> %)</strong></span><br>
												<i class="pull-left md md-label text-purple"></i><span class="text-muted">Sisa <strong class="text-purple">(<?php echo $result['persen_sisa_kontrak']; ?> %)</strong></span><br>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="panel panel-border panel-inverse">
											<div class="panel-heading text-center">
												<h3 class="panel-title">Total Progress</h3>
											</div>
											<div class="panel-body">

												<div class="progress progress-lg">
												<?php
			                                    $progress = $result['persen_fisik'];
			                                    if($progress) $progress_text = $progress."%";
			                                    unset($temp);
			                                    $temp[0] = "primary";
			                                    $temp[1] = "";
			                                    echo'<div class="progress-bar progress-bar-'.$temp[0].' progress-bar-striped active" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">'.$progress_text.'
			                                        </div>
			                                    </div>'; 
			                                    
			                                    if($s_level == 2) echo'<button class="btn btn-'.$temp[0].' waves-effect waves-light trigger pull-right" data-toggle="modal" '.$temp[1].' data-target="#panel-modal" value="5"><span>Top-Up</span></button>'; 
			                                    else echo '<button class="btn btn-'.$temp[0].' waves-effect waves-light trigger pull-right" data-toggle="modal" '.$temp[1].' data-target="#panel-modal" disabled value="5"><span>Top-Up</span></button>'; ?>
			                                </div>
										</div>
									</div>

									<div class="col-sm-12">
										<div class="panel panel-border panel-warning">
											<div class="panel-heading text-center">
												<h3 class="panel-title"></h3>
											</div>

											<div class="panel-body">
												<ul class="list-group">
													<li class="list-group-item">
														<dt>Masa Pelaksanaan</dt>
														<dd><?php echoalt($result['info_surat'][2], '-', " Hari"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Waktu Terpakai</dt>
														<dd><?php echoalt($result['waktu_terpakai'], '-', " Hari"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Sisa Waktu</dt>
														<dd><?php echoalt($result['waktu_sisa'], '-', " Hari"); ?></dd>
													</li>
												</ul>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-9">
								<div class="row">
									<div class="col-sm-12">
										<div class="panel panel-color panel-success">
											<div class="panel-heading">
												<h3 class="panel-title">Curva Monitoring Kegiatan</h3>
											</div>
											<div class="panel-body">
												<div id="chart-line" class="ct-major-tenth"></div>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="panel panel-color panel-purple">
											<div class="panel-heading">
												<h3 class="panel-title">Progress Fisik</h3>
											</div>
											<div class="panel-body">
											<?php 
											$result['info_fisik'] = cta($result['info_fisik'], true);
											$attach = '';
											$hidden = '';
											if(!$result['info_fisik']){

												$result['info_fisik'] = array(array('','','','',''));
												$attach = 'zero-row';
												$hidden = 'hidden';

											}
		                                    ?>
			                                    <form method="POST" id="table-fisik">
													<table class="table table-fisik table-hover <?php echo $hidden; ?>">
				                                        <thead>
				                                            <tr>
				                                                <th rowspan=2 width='10%'>#</th>
				                                                <th rowspan=2>Uraian</th>
				                                                <th colspan=4 class="side-table">Bobot</th>
				                                            </tr>
				                                            <tr>
				                                            	<th width='15%'>Kontrak</th>
				                                            	<th width='15%'>Bulan Lalu</th>
				                                            	<th width='15%'>Bulan Ini</th>
				                                            	<th class="side-table" width='15%'>S/d Bln Ini</th>
				                                            </tr>
				                                        </thead>
				                                        <tbody>
				                                        <?php
				                                        	$result['total_fisik'][0] = $result['total_fisik'][1] = $result['total_fisik'][2] = $result['total_fisik'][3] = 0;
				                                        	$counter = 0;
				                                        	foreach ($result['info_fisik'] as $key => $value): 
																$temp = $value[3] + $value[4];

				                                        		settype($value[2], 'float');
																settype($value[3], 'float');
																settype($value[4], 'float');
																settype($temp, 'float');

				                                        		$result['total_fisik'][0] = $result['total_fisik'][0] + $value[2];
				                                        		$result['total_fisik'][1] = $result['total_fisik'][1] + $value[3];
				                                        		$result['total_fisik'][2] = $result['total_fisik'][2] + $value[4];
				                                        		$result['total_fisik'][3] = $result['total_fisik'][3] + $temp;
				                                        		?>
				                                        		<tr class="initial-row group-<?php echo $counter + 1; ?>">
					                                        		<th><span class="editable" data-required='true' data-name="data_0[]"><?php echo $value[0]; ?></span></th>
					                                        		<td><span class="editable" data-required='true' data-name="data_1[]"><?php echo $value[1]; ?></span></td>
					                                        		<td><span class="editable <?php if(empty($value[2])) echo 'hidden'; ?>" data-mask-input='true' data-name="data_2[]"><?php echo $value[2]; ?></span></td>
					                                        		<td><span class="editable <?php if(empty($value[3])) echo 'hidden'; ?>" data-mask-input='true' data-name="data_3[]"><?php echo $value[3]; ?></span></td>
					                                        		<td><span class="editable <?php if(empty($value[4])) echo 'hidden'; ?>" data-mask-input='true' data-name="data_4[]"><?php echo $value[4]; ?></span></td>
					                                        		<td class="side-table"><span class="<?php if(empty($temp)) echo 'hidden'; ?>"><?php echo $temp; ?></span></td>
				                                        		</tr>
				                                        		<?php
				                                        		$counter++;
				                                        	endforeach;
				                                        ?>
				                                        </tbody>
				                                        <tfoot>
				                                        	<tr class="active">
				                                        		<th></th>
				                                        		<th>Total</th>
				                                        		<td><span class="<?php if(empty($result['total_fisik'][0])) echo 'hidden'; ?>"><?php echo $result['total_fisik'][0]; ?></span></td>
				                                        		<td><span class="<?php if(empty($result['total_fisik'][1])) echo 'hidden'; ?>"><?php echo $result['total_fisik'][1]; ?></span></td>
				                                        		<td><span class="<?php if(empty($result['total_fisik'][2])) echo 'hidden'; ?>"><?php echo $result['total_fisik'][2]; ?></span></td>
				                                        		<th><span class="<?php if(empty($result['total_fisik'][3])) echo 'hidden'; ?>"><?php echo $result['total_fisik'][3]; ?></span></th>
				                                        	</tr>
				                                        </tfoot>
				                                    </table>
		                                    <?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '
		                                    <button class="btn-sm hidden edit-button m-t-5 btn-inverse waves-effect waves-light btn-add" type="button" data-template="fisik"><i class="md md-add" ></i></button>
		                					<button class="btn-sm hidden edit-button m-t-5 btn-danger waves-effect waves-light btn-delete" type="button"><i class="md md-remove"></i></button>

		                                    <button class="btn btn-rounded btn-purple waves-effect waves-light edit-table pull-right m-t-5 clearfix '.$attach.'" type="button">Edit</button>
		                                    <button class="btn btn-rounded btn-danger waves-effect waves-light edit-button hidden pull-right m-t-5 clearfix '.$attach.' edit-cancel" type="button">Cancel</button>
		                                    <button class="btn btn-rounded btn-purple hidden edit-button waves-effect waves-light pull-right m-t-5 m-r-5 clearfix" name="submit">Submit</button>'; ?>
                                                <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="status">
												<input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
												<input type="hidden" value="<?php echo codeGen("f","a"); ?>" name="device_statistic">
												<input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
												<input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
		                                    	</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="row">
									<div class="col-sm-12">
										<div class="panel panel-border panel-success">
											<div class="panel-heading text-center">
												<h3 class="panel-title">Keterangan</h3>
											</div>

											<div class="panel-body text-right">
												<i class="pull-left md md-label text-success"></i><span class="text-muted">Realisasi</span><br>
		                                		<i class="pull-left md md-label text-inverse"></i><span class="text-muted">Rencana</span><br>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="panel panel-color panel-success">
											<div class="panel-heading">
												<h3 class="panel-title">Progress</h3>
											</div>
											<div class="panel-body">
												<ul class="list-group">
													<li class="list-group-item">
														<dt>Rencana bulan ini</dt>
														<dd><?php echoalt($result['progress'][0], "-", " %"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Realisasi bulan ini</dt>
														<dd><?php echoalt($result['progress'][1], "-", " %"); ?></dd>
													</li>
													<li class="list-group-item">
														<dt>Deviasi</dt>
														<dd><?php echoalt($result['progress'][2], "-", " %"); ?></dd>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="panel panel-color panel-success">
											<div class="panel-heading">
												<h3 class="panel-title">Lay-out Pekerjaan</h3>
											</div>
											<div class="panel-body">
												<div>
													<span> </span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-border panel-inverse">
									<div class="panel-heading">
										<h3 class="panel-title">Monitoring Progress</h3>
									</div>
									<div class="panel-body">
										<?php 
										$attach = '';
										$hidden = '';

										if(!$result['info_monitor']) {
											for($x=0;$x<12;$x++) $result['info_monitor'][$x] = array('','','');
											$attach = 'zero-row';
											$hidden = 'hidden';
										}
	                                    ?>
		                                    <form method="POST" id="table-monitor">
												<table class="table table-monitor table-hover <?php echo $hidden; ?>">
			                                        <thead>
			                                            <tr>
			                                                <th>Tahun</th>
			                                                <th colspan=12 class="side-table"><?php echo $result['tahun']; ?></th>
			                                            </tr>
			                                            <tr>
			                                            	<th>Bulan</th>
			                                            	<th width='7.5%'>Jan</th>
			                                            	<th width='7.5%'>Feb</th>
			                                            	<th width='7.5%'>Mar</th>
			                                            	<th width='7.5%'>Apr</th>
			                                            	<th width='7.5%'>Mei</th>
			                                            	<th width='7.5%'>Jun</th>
			                                            	<th width='7.5%'>Jul</th>
			                                            	<th width='7.5%'>Agu</th>
			                                            	<th width='7.5%'>Sep</th>
			                                            	<th width='7.5%'>Okt</th>
			                                            	<th width='7.5%'>Nov</th>
			                                            	<th width='7.5%'>Des</th>
			                                            </tr>
			                                        </thead>
			                                        <tbody>
			                                        	<tr>
			                                        		<th>(%) Kumulatif Rencana</th>
			                                        		<?php foreach($result['info_monitor'] as $key => $value): 
			                                        			settype($value[0], 'float');
			                                        			?>
			                                        			<td><span class="editable <?php if(empty($value[0])) echo 'hidden'; ?>" data-mask-input="true" data-name="data_0[]"><?php echo $value[0]; ?></span></td>
			                                        		<?php endforeach; ?>
			                                        	</tr>
			                                        	<tr>
				                                        	<th>(%) Kumulatif Realisasi</th>
				                                        	<?php foreach($result['info_monitor'] as $key => $value): 
				                                        		settype($value[1], 'float');
				                                        		?>
			                                        			<td><span class="editable <?php if(empty($value[1])) echo 'hidden'; ?>" data-mask-input="true" data-name="data_1[]"><?php echo $value[1]; ?></span></td>
			                                        		<?php endforeach; ?>
				                                        </tr>
				                                    </tbody>
				                                    <tfoot>
				                                        <tr class="active">
			                                        		<th> (%) Deviasi</th>	
			                                        		<?php foreach($result['info_monitor'] as $key => $value): 
			                                        			settype($value[0], 'float');
			                                        			settype($value[1], 'float');
			                                        			$temp = $value[1] - $value[0];
			                                        			$temp = round($temp, 1);
			                                        			?>
			                                        			<td><span class="<?php if(empty($temp) || empty($value[1])) echo 'hidden'; ?>"><?php echo $temp; ?></span></td>
			                                        		<?php endforeach; ?>
				                                        </tr>
			                                        </tfoot>
			                                    </table>
	                                    <?php if(($s_level == 3 && $result['id_pptk'] == $s_id)) echo '
	                                    <button class="btn btn-rounded btn-inverse waves-effect waves-light edit-table pull-right m-t-5 clearfix '.$attach.'" type="button">Edit</button>
	                                    <button class="btn btn-rounded btn-danger waves-effect waves-light edit-button hidden pull-right m-t-5 clearfix '.$attach.' edit-cancel" type="button">Cancel</button>
	                                    <button class="btn btn-rounded btn-inverse hidden edit-button waves-effect waves-light pull-right m-t-5 m-r-5 clearfix" name="submit">Submit</button>'; ?>
                                            <input type="hidden" value="<?php echo codeGen($get_id,"",1); ?>" name="status">
											<input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
											<input type="hidden" value="<?php echo codeGen("b","3"); ?>" name="device_statistic">
											<input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
											<input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
	                                    	</form>
									</div>
								</div>
							</div>
						</div>




<?php
$template->call('footer');
$template->call('includejs');
?>
<script>
	var limit = <?php echo $limittable; ?>;
	var counter = <?php echo $counter + 1; ?>;
	var initialCounter = <?php echo $counter + 1; ?>;

	$('.btn-add').on('click', function () {

		var template = $('<tr></tr>').addClass('added-row group-' + counter).append('<th></th>' + '<td></td>'.repeat(4));

		if(counter < (limit+1)){
			var that = $(this).parent().find('.group-' + counter);
			if(that.hasClass('initial-row')){
				that.removeClass('hidden');
				that.children().children('input').removeAttr('disabled');
				that.parent().parsley();

				counter++;
			}
			else {
				var that = $(this).parent().find('tbody');
				var x = 0;
				that.append(template);
				that.children('.group-' + counter).children().each(function(){
					$(this).append('<input></input>');
					$(this).children('input').attr('name', 'data_' + x + '[]').attr('data-mask','').attr("placeholder", '...').addClass('form-control').css("font-size", "14px");
					$(this).children('input[name="data_0[]"]').attr('required', 'required').removeAttr('data-mask');
					$(this).children('input[name="data_1[]"]').attr('required', 'required').removeAttr('data-mask');
					$(this).children('input[data-mask]').mask('999.00');
					x++;
				});
				that.parent().parent().parsley();
				counter++;
			}
		}
		else {
			alert("Jumlah maksimal tercapai (maks. 5)");
		}
	});

	$('.btn-delete').on('click', function () {
		if(counter > 2){
			var that = $(this).parent().find('.group-' + (counter-1));
			if(that.hasClass('initial-row')){
				that.addClass('hidden');
				that.children().children('input').attr('disabled', 'disabled');
			}
			else {
				that.remove();
			}
			counter--;
		}
		else {
			alert("Jumlah minimal tercapai (min. 1)");
		}
	});

	$('.edit-table').on('click', function () {

		var that = $(this).parent();
		var table = that.children('table');

		$(this).addClass('hidden');
		table.children('tfoot').addClass('hidden');
		table.find('.side-table').addClass('hidden');
		table.find('th.side-table[colspan]').attr('colspan', 3).removeClass('hidden');
		$(this).siblings('.edit-button').removeClass('hidden');
		table.removeClass('hidden');

		table.find('.editable').each(function(){
			var value = $(this).text();
			var name = $(this).attr('data-name');

			var data = $('<input></input>').addClass('form-control editable').attr("name", name).attr("data-initial", value).attr("placeholder", '...').css("font-size", "14px").val(value);

			if($(this).attr('data-required') == 'true'){
				data.attr('required', 'required');
			}

			if($(this).attr('data-mask-input') == 'true'){
				data.mask('999.00');
			}

			$(this).replaceWith(function(){
				return data;
			});

		});
		that.parsley();

	});

	$('.edit-cancel').on('click', function () {

		var that = $(this).parent();
		var table = $(this).siblings('table');

		$(this).addClass('hidden');
		$(this).siblings('.edit-button').addClass('hidden');
		$(this).siblings('.edit-table').removeClass('hidden');
		table.find('.side-table').removeClass('hidden');
		table.children('tfoot').removeClass('hidden');
		table.find('.added-row').remove();
		table.find('.initial-row').removeClass('hidden');
		table.find('th.side-table[colspan]').attr('colspan', 4).removeClass('hidden');
		
		counter = initialCounter;

		if($(this).hasClass('zero-row')){
			table.addClass('hidden');
		}

		table.find('.editable').each(function(){
			var name = $(this).attr('name');
			var initial = $(this).attr('data-initial');

			var data = $('<span></span>').addClass('editable').attr("data-name", name).text(initial);

			if($(this).attr('data-required') == 'true'){
				data.attr('data-required', 'true');
			}

			if(initial == '0'){
				data.addClass('hidden');
			}

			if($(this).attr('data-mask-input') == 'true'){
				data.attr('data-mask-input', 'true');
			}

			$(this).replaceWith(function(){
				return data;
			});
		});
		$('.editable').off();

	});


	$(document).ready(function(){
        $('.donut').peity('donut', {
            width: '100%',
            height: '100%',
            innerRadius: '60',
            fill: [
            <?php 
            	if($result['sisa_kontrak'] >= 0 && $result['penyerapan']) 
            		echo "'#4C5667', '#7266BA'"; 
            	else
            		echo "'#CCCCCC'";
            ?>
            ]
        });

        $('.donut-red').peity('donut', {
            width: '100%',
            height: '100%',
            innerRadius: '60',
            fill: [
            <?php
            	if($result['persen_progress'])
	            	echo "'#EF5350', '#4C5667'";
	            else 
	            	echo "'#CCCCCC'";
	        ?>
            ]
        });

        $('.donut-yellow').peity('donut', {
            width: '100%',
            height: '100%',
            innerRadius: '60',
            fill: [
	            <?php if(!$result['waktu_sisa'] && $result['waktu_terpakai']) echo "'#4C5667'"; elseif(!$result['waktu_terpakai'] && $result['waktu_sisa']) echo "'#FFAA00'"; elseif(!$result['waktu_sisa'] && !$result['waktu_terpakai']) echo "'#CCCCCC'"; else echo "'#FFAA00', '#4C5667'"; ?>
            ]
        });

        $('#popup input[name="data_0"]').mask('999.000.000.000.000', {reverse: true});

        <?php
        foreach($result['info_monitor'] as $key => $value) {
        	if(!empty(array_filter($value))){
        		$f = 1;
        		break;
        	}
        }
        if(!isset($f)) $result['info_monitor'] = 0;
        if($result['info_monitor']):

        	unset($temp);

			$month = array(
				"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
			);

    		for($x=0;$x<12;$x++){
    			if(!empty($result['info_monitor'][$x][0])){
    				$temp[0] = $x;
    				break;
    			}
    		}

	    	if(isset($temp[0])):
	       		for($x=0;$x<12;$x++){
	    			if(!empty($result['info_monitor'][$x][1])){
	    				$temp[1] = $x;
	    				break;
	    			}
	    		}
	    		unset($z);

	    		if(isset($temp[1])):
		    		if($temp[0] < $temp[1]) {
		    			$temp['start'] = $temp[0];
		    			$z = 0;
		    		}
		    		else {
		    			$temp['start'] = $temp[1];
		    			$z = 1;
		    		}
		    	else:
		    		$temp['start'] = $temp[0];
		    		$z = 0;
		    	endif;

	    		for($x=12-1;$x>=0;$x--){
	    			if(!empty($result['info_monitor'][$x][$z])){
	    				$temp['end'] = $x;
	    				break;
	    			}
				}


				for($x=$temp['start']; $x<$temp['end']+1; $x++){
					if(!empty($result['info_monitor'][$x][0])) $result['chart-data'][0][] = $result['info_monitor'][$x][0]; else $result['chart-data'][0][] = "";
					if(!empty($result['info_monitor'][$x][1])) $result['chart-data'][1][] = $result['info_monitor'][$x][1]; else $result['chart-data'][1][] = "";
				}

				for($x=$temp['start']; $x<$temp['end']+1; $x++){
					$result['chart-label'][] = $month[$x];
				}

				$result['chart-data'] = "[".joinString($result['chart-data'][0], '"')."],[".joinString($result['chart-data'][1], '"')."]";
				$result['chart-label'] = joinString($result['chart-label'], '"');
			else:
				$default = true;
			endif;
		else:
			$default = true;
		endif;

		if(isset($default)){
			$result['chart-label'] = '"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"';
			$result['chart-data'] = 0;
		}
		?>


		var data = {
		  labels: [<?php echo $result['chart-label']; ?>],
		  series: [<?php echo $result['chart-data']; ?>
		  ]
		};
		// In the global name space Chartist we call the Line function to initialize a line chart. As a first parameter we pass in a selector where we would like to get our chart created. Second parameter is the actual data object and as a third parameter we pass in our options
		new Chartist.Line('#chart-line', data, {
			chartPadding: {
                top: 10,
                right: 0,
                bottom: 0,
                left: 0
            }
		});

    });


    $('.trigger').on('click', function () { //change event for select

    	var status = $("input[name='status']").val();
        var statistic = $("input[name='device']").val();
        var data = $("input[name='data_0']").val();

        var type = $(this).val();

        if($(this).prop("disabled")){
        	return false;
        }

        $.ajax({ 
            type: "POST",
            url: "main/fetcher.php",
            data: { status: status, statistic: statistic, data: data, id: type} 
        })
        .done(function( str ) { 
        	$("#panel-modal .modal-content").html(str);
        });
    });

    $('#panel-modal').on('hidden.bs.modal', function (e) {
        $('#panel-modal .modal-content').empty();
    });

</script>
<?php
$template->call('endfile');