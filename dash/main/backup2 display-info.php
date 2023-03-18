<?php

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
	array('name' => $tbl_dash.'.mp_info', 'result' => 'info_monitor'), 
	array('name' => $tbl_dash.'.rp_info_prg', 'result' => 'info_progress'), 
	array('name' => $tbl_dash.'.rp_info_waktu', 'result' => 'info_waktu'), 
	array('name' => $tbl_dash.'.rp_info_prg_c', 'result' => 'chart_progress'), 
	array('name' => $tbl_dash.'.rp_info_waktu_c', 'result' => 'chart_waktu'), 
	array('name' => $tbl_dash.'.ke_info', 'result' => 'info_uang'), 
	array('name' => $tbl_dash.'.ke_c', 'result' => 'chart_uang'), 
	array('name' => $tbl_dash.'.gallery', 'result' => 'gallery'), 
	array('name' => $tbl_dash.'.mk_info', 'result' => 'info_monitor_k'), 
	array('name' => $tbl_dash.'.mk_c_xy', 'result' => 'chart_monitor_k_xy'), 
	array('name' => $tbl_dash.'.mk_c_info', 'result' => 'chart_monitor_k_info'), 
	array('name' => $tbl_dash.'.na_info', 'result' => 'info_nama'), 
	array('name' => $tbl_dash.'.lead_id', 'result' => 'id_pptk'), 
	array('name' => $tbl_dash.'.id', 'result' => 'id_proyek'), 
	array('name' => $tbl_dash.'.n_kontrak', 'result' => 'nilai_kontrak'), 
	array('name' => $tbl_dash.'.n_pagu', 'result' => 'nilai_pagu'),
	array('name' => $tbl_dash.'.dp_addendum', 'result' => 'info_addendum'),
	array('name' => $tbl_mem.'.name', 'result' => 'nama_kontraktor'),
	array('name' => $tbl_cat.'.title', 'result' => 'nama_kegiatan'),
);
$param['table'] = array($tbl_dash, $tbl_mem, $tbl_cat);
$param['join_op'] = array('left join', 'left join');
$param['on'] = array(
		array('name' => $tbl_dash.'.rel_id', 'target' => $tbl_mem.'.id'),
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


$param['field'] = array(
	array('name' => 'id', 'result' => 'id'),
	array('name' => 'name', 'result' => 'nama'),
	array('name' => 'level', 'result' => 'level')
);
$param['table'] = $tbl_mem;

$param['where_op'] = array('or','or','or');
$param['where'] = array(
	array('name' => 'level', 'value' => 5, 'type' => 'i'),
	array('name' => 'level', 'value' => 4, 'type' => 'i'),
	array('name' => 'id', 'value' => $result['id_pptk'], 'type' => 'i'),
	array('name' => 'id', 'value' => $result['id_kontraktor'], 'type' => 'i'),
);
$param['order'] = array('name' => 'level', 'sort' => 'desc');

$pelaksana = $exec->select($param);
unset($param);

$result['nama_proyek'] = cta($result['info_proyek'])[0];
$result['info_proyek_lokasi'] = cta($result['info_proyek'])[1];
$result['info_kontrak_no'] = cta($result['info_kontrak'])[0];
$result['info_addendum'] = cta($result['info_addendum'], true);

if(cta($result['info_kontrak'])[1] != ""):
$result['info_kontrak_tgl'] = explode("-", cta($result['info_kontrak'])[1]);

$result['info_kontrak_tgl'] = $result['info_kontrak_tgl'][0]." ".intoMonth($result['info_kontrak_tgl'][1])." ".$result['info_kontrak_tgl'][2];
endif;

$y = count($pelaksana['nama']);
for($x=0;$x<$y;$x++):
	switch ($pelaksana['level'][$x]) {
		case '5':
			$result['info_pelaksana_kadis'] = $pelaksana['nama'][$x];
			break;

		case '4':
			$result['info_pelaksana_ppk'] = $pelaksana['nama'][$x];
			break;

		case '3':
			$result['info_pelaksana_pptk'] = $pelaksana['nama'][$x];
			break;

		case '2':
			$result['info_pelaksana_kontraktor'] = $pelaksana['nama'][$x];
			break;
		
		default:
			# code...
			break;
	}

endfor;

if(!isset($result['info_pelaksana_kontraktor'])) $result['info_pelaksana_kontraktor'] = '';

$result['info_surat_serah'] = cta($result['info_surat'])[0];
$result['info_surat_kerja'] = cta($result['info_surat'])[1];
$result['info_surat_masa_1'] = cta($result['info_surat'])[2];
$result['info_surat_masa_2'] = cta($result['info_surat'])[3];
$result['info_surat_masa_3'] = cta($result['info_surat'])[4];

if(!(empty($info_surat_masa_2) && empty($result['info_surat_masa_3']))):
	$result['info_surat_masa_2'] = str_replace("-", "/", $result['info_surat_masa_2']);
	$result['info_surat_masa_2'] = explode("/", $result['info_surat_masa_2']);

	$result['info_waktu'] = $result['info_surat_masa_2'][1]."/".$result['info_surat_masa_2'][0]."/".$result['info_surat_masa_2'][2];

	$result['info_surat_masa_2'] = $result['info_surat_masa_2'][0]." ".intoMonth($result['info_surat_masa_2'][1])." ".$result['info_surat_masa_2'][2];


	$result['info_waktu'] = strtotime($result['info_waktu']);
	$result['info_waktu_sisa'] = ceil(($result['info_waktu']-time())/60/60/24);
	if($result['info_waktu_sisa'] <= 0) {
		$result['info_waktu_sisa'] = 0;
		$result['info_waktu_terpakai'] = $result['info_surat_masa_1'];
	}
	else {
		$result['info_waktu_terpakai'] = $result['info_surat_masa_1'] - $result['info_waktu_sisa'];

		if($result['info_waktu_terpakai'] <= 0){
			$result['info_waktu_terpakai'] = 0;
			$result['info_waktu_sisa'] = $result['info_surat_masa_1'];
		}
	}
else:
	$result['info_waktu_sisa'] = 0;
	$result['info_waktu_terpakai'] = 0;
endif;


$result['info_fisik'] = cta($result['info_fisik'], true);
$result['info_monitor'] = cta($result['info_monitor'], true);

$y = count($result['info_monitor']);
$temp = false;
for($x=0;$x<$y;$x++){
	$month = number_format(date('m'), 0);
	if($month == $result['info_monitor'][$x][0]){
		settype($result['info_monitor'][$x][2], 'float');
		settype($result['info_monitor'][$x][1], 'float');
		$result['info_monitor_rencana'] = $result['info_monitor'][$x][1];
		$result['info_monitor_realisasi'] = $result['info_monitor'][$x][2];
		$result['info_monitor_notrealisasi'] = 100 - $result['info_monitor_realisasi'];
		$result['info_monitor_deviasi'] = $result['info_monitor'][$x][3];
		$temp = true;
	}

	if($temp == false){
		$result['info_monitor_rencana'] =
		$result['info_monitor_realisasi'] = 
		$result['info_monitor_notrealisasi'] = 
		$result['info_monitor_deviasi'] = 0;
	}
}

$result['info_progress'] = cta($result['info_progress']);
if(empty($result['info_uang'])) $result['info_uang'] = 0;

if(isset($result['info_addendum'][0][2])){
	$y = count($result['info_addendum']);
	$result['info_sisa_dana'] = $result['info_addendum'][$y-1][2] - $result['info_uang'];
}
else {
	settype($result['nilai_kontrak'], 'float');
	$result['info_sisa_dana'] = $result['nilai_kontrak'] - $result['info_uang'];
}

$bc = array(
	array("","Dashboard","1"),
	array("/prokal/dash/?d=catman&id=".$result['id_kegiatan'],"Kegiatan","0"),
	array("","Pekerjaan","1"),
);
$title = "Detail Pekerjaan";
$page_title = "SIBIMA-KU | Detail Pekerjaan";

$pagejs = "d2-display.init.js";
$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);
$template->pagejs($pagejs);

$template->includejs("sweetalert");
$template->includejs("parsley");
$template->includejs("chartist");
$template->includejs("bootstraptable");
$template->includejs("datepicker");
$template->includejs("inputmask");
$template->includejs("dropzone");
$template->includejs("summernote");
$template->includejs("tagsinput");

$template->call('include');
?>
    <style>
        .parsley-except .parsley-errors-list {
            display: none;
        }
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
								<ul class="nav nav-tabs tabs">
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
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="home-12">
										<form action="#" class="form-horizontal">

											<div class="form-group">
												<label class="col-sm-3 control-label">Kegiatan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['nama_kegiatan'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Pekerjaan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['nama_proyek'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">No. Kontrak</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_kontrak_no'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Tanggal Kontrak</label>
												<div class="col-sm-9 m-t-5"><?php if(isset($result['info_kontrak_tgl'])) echoalt($result['info_kontrak_tgl'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Lokasi Kegiatan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_proyek_lokasi'], "-"); ?>
												</div>
											</div>
										</form>
										<?php if($s_level > 3 || ($s_level == 3 && $result['id_pptk'] == $s_id)) echo '<button class="btn btn-rounded btn-primary waves-effect waves-light trigger pull-right clearfix" data-toggle="modal" data-target="#panel-modal" value="1" type="button" style="margin-top: -25px"><span>Edit</span></button>'; ?>
									</div>
									<div class="tab-pane" id="profile-12">
										<form action="#" class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-3 control-label">Pengguna Anggaran</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_pelaksana_kadis'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Pejabat Pembuat Komitmen</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_pelaksana_ppk'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Pejabat Pelaksana Teknis Kegiatan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_pelaksana_pptk'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Kontraktor Pelaksana</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_pelaksana_kontraktor'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Konsultan Supervisi</label>
												<div class="col-sm-9 m-t-5">-
												</div>
											</div>
										</form>
										<?php if($s_level > 3 || ($s_level == 3 && $result['id_pptk'] == $s_id)) echo'<button class="btn btn-primary waves-effect waves-light trigger pull-right" data-toggle="modal" data-target="#panel-modal" value="2"><i class="md md-create m-r-5"></i><span>Edit</span></button>'; ?>
									</div>
									<div class="tab-pane" id="messages-12">
										<form action="#" class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-3 control-label">Surat Penyerahan Lapangan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_surat_serah'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Surat Perintah Mulai Kerja</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_surat_kerja'], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Masa Pelaksanaan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_surat_masa_1'], "-", " Hari"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Masa Pemeliharaan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_surat_masa_3'], "-", " Hari"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Akhir Masa Pelaksanaan</label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_surat_masa_2'], "-"); ?>
												</div>
											</div>
										</form>
										<?php if($s_level > 3 || ($s_level == 3 && $result['id_pptk'] == $s_id)) echo'<button class="btn btn-primary waves-effect waves-light trigger pull-right" data-toggle="modal" data-target="#panel-modal" value="3"><i class="md md-create m-r-5"></i><span>Edit</span></button>'; ?>
									</div>
									<div class="tab-pane" id="settings-12">
										<form action="#" class="form-horizontal">
										<?php 
										$y = count($result['info_addendum']);
										for($x=0;$x<$y;$x++): 
										$temp = explode("-", $result['info_addendum'][$x][1]);
										if(!empty($result['info_addendum'])) $result['info_addendum'][$x][1] = $temp[0]." ".intoMonth($temp[1])." ".$temp[2];
										?>
											<div class="form-group">
												<label class="col-sm-3 control-label">No. Addendum Kontrak 0<?php echo $x + 1; ?></label>
												<div class="col-sm-9 m-t-5"><?php echoalt($result['info_addendum'][$x][0], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Tanggal Addendum Kontrak 0<?php echo $x + 1; ?></label>
												<div class="col-sm-9 m-t-5"><?php if(isset($result['info_addendum'][$x][1])) echoalt($result['info_addendum'][$x][1], "-"); else echo "-"; ?>
												</div>
											</div>
										<?php
										endfor; ?>
										</form>
										<?php if($s_level > 3 || ($s_level == 3 && $result['id_pptk'] == $s_id)) echo'<button class="btn btn-primary waves-effect waves-light trigger pull-right" data-toggle="modal" data-target="#panel-modal" value="4"><i class="md md-create m-r-5"></i><span>Edit</span></button>'; ?>
									</div>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="panel panel-color panel-inverse">
									<div class="panel-heading">
										<h3 class="panel-title">Nilai Proyek</h3>
									</div>

									<div class="panel-body">
										<form action="#" class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-5 control-label">Nilai Kontrak</label>
												<div class="col-sm-7 m-t-5"><?php echoalt("Rp. ".number_format($result['nilai_kontrak'], 2, ",", "."), "-"); ?>
												</div>
											</div>
											<?php 
											$y = count($result['info_addendum']);
											for($x=0;$x<$y;$x++): ?>
											<div class="form-group">
												<label class="col-sm-5 control-label">Nilai Addendum Kontrak 0<?php echo $x + 1; ?></label>
												<div class="col-sm-7 m-t-5"><?php echoalt("Rp. ".number_format($result['info_addendum'][$x][2], 2, ",", "."), "-");?>
												</div>
											</div>
											<?php
											endfor; ?>
											<div class="form-group">
												<label class="col-sm-5 control-label">Nilai Pagu</label>
												<div class="col-sm-7 m-t-5"><?php echoalt("Rp. ".number_format($result['nilai_pagu'], 2, ",", "."), "-"); ?>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="panel panel-color panel-inverse">
									<div class="panel-heading">
										<h3 class="panel-title">TOTAL PROGRESS</h3>
									</div>
									<div class="panel-body">

										<div class="progress progress-lg">
										<?php 
	                                    $progress = 82;
	                                    unset($temp);
	                                    $temp[0] = "inverse";
	                                    $temp[1] = "disabled";
	                  					if($progress > 80){
	                  						$temp[0] = "primary";
	                  						$temp[1] = "";
	                  					}
	                                    echo'<div class="progress-bar progress-bar-'.$temp[0].' progress-bar-striped active" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">'.$progress.'%
	                                        </div>
	                                    </div>'; 
	                                    
	                                    if($s_level == 2) echo'<button class="btn btn-'.$temp[0].' waves-effect waves-light trigger pull-right" data-toggle="modal" '.$temp[1].' data-target="#panel-modal" value="5"><span>Pembayaran</span></button>'; 
	                                    else echo '<button class="btn btn-'.$temp[0].' waves-effect waves-light trigger pull-right" data-toggle="modal" '.$temp[1].' data-target="#panel-modal" disabled value="5"><span>Pembayaran</span></button>'; ?>
	                                </div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-8">
								<div class="panel panel-color panel-success">
									<div class="panel-heading">
										<h3 class="panel-title">Ringkasan Progress</h3>
									</div>
									<?php if($result['info_monitor_realisasi'] != 0 || $result['info_waktu_sisa'] != 0 || $result['info_waktu_terpakai'] != 0): ?>
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-6">
											<?php if($result['info_monitor_realisasi'] != 0): ?>
												<div class="row">
													<div class="col-sm-8">
														
														<div id="ringkasan" class="ct-chart ct-square simple-pie-chart-chartist"></div>

													</div>
													<div class="col-sm-4">
														<h5><i class="fa fa-square text-primary"></i> Realisasi</h5>
														<span class="text-muted"><?php 
														$temp = $result['info_monitor_realisasi'] + $result['info_monitor_notrealisasi'];
														$temp = $result['info_monitor_realisasi'] / $temp * 100;
														echo round($temp); ?>%</span>
														<h5><i class="fa fa-square text-pink"></i> Belum Terealisasi</h5>
														<span class="text-muted"><?php 
														$temp = $result['info_monitor_realisasi'] + $result['info_monitor_notrealisasi'];
														$temp = $result['info_monitor_notrealisasi'] / $temp * 100;
														echo round($temp); ?>%</span>
													</div>
												</div>
											<?php endif; ?>
												<form action="#" class="form-horizontal m-t-15">
													<div class="form-group">
														<label class="col-sm-5 control-label">Kumulatif Rencana</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_monitor_rencana'], "-"); ?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Kumulatif Realisasi</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_monitor_realisasi'], "-"); ?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Deviasi</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_monitor_deviasi'], "-"); ?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Progress Bulan ini</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_monitor_realisasi'], "-"); ?>
														</div>
													</div>
												</form>
											</div>
											<div class="col-sm-6">
												<div class="row">
													<div class="col-sm-8">
													<?php if($result['info_waktu_sisa'] != 0 || $result['info_waktu_terpakai'] != 0): ?>
														<div id="waktu" class="ct-chart ct-square simple-pie-chart-chartist"></div>

													</div>
													<div class="col-sm-4">
														<h5><i class="fa fa-square text-primary"></i> Waktu Terpakai</h5>
														<span class="text-muted"><?php 
														$temp = $result['info_waktu_sisa'] + $result['info_waktu_terpakai'];
														$temp = $result['info_waktu_terpakai'] / $temp * 100;
														echo round($temp); ?>%</span>
														<h5><i class="fa fa-square text-pink"></i> Sisa Waktu</h5>
														<span class="text-muted"><?php 
														$temp = $result['info_waktu_sisa'] + $result['info_waktu_terpakai'];
														$temp = $result['info_waktu_sisa'] / $temp * 100;
														echo round($temp); ?>%</span>
													</div>
												</div>
												<?php endif; ?>
												<form action="#" class="form-horizontal m-t-15">
													<div class="form-group">
														<label class="col-sm-5 control-label">Masa Pelaksanaan</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_surat_masa_1'], "-", " Hari"); ?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Waktu Terpakai</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_waktu_terpakai'], "-", " Hari"); ?>
														</div>
													</div>
													 <div class="form-group">
														<label class="col-sm-5 control-label">Sisa Waktu</label>
														<div class="col-sm-7 m-t-5"><?php echoalt($result['info_waktu_sisa'], "-", " Hari"); ?>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="panel panel-color panel-inverse">
									<div class="panel-heading">
										<h3 class="panel-title">Keuangan</h3>
									</div>
									<?php if($result['info_uang'] != 0 && $result['info_sisa_dana'] != 0): ?>
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-8">
												
												<div id="keuangan" class="ct-chart ct-square simple-pie-chart-chartist"></div>

											</div>
											<div class="col-sm-4">
												<h5><i class="fa fa-square text-primary"></i> Realisasi</h5>
												<span class="text-muted"><?php 
												$temp = $result['info_uang'] + $result['info_sisa_dana'];
												$temp = $result['info_uang'] / $temp * 100;
												echo round($temp); ?>%</span>
												<h5><i class="fa fa-square text-pink"></i> Sisa Dana</h5>
												<span class="text-muted"><?php 
												$temp = $result['info_uang'] + $result['info_sisa_dana'];
												$temp = $result['info_sisa_dana'] / $temp * 100;
												echo round($temp); ?>%</span>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<form action="#" class="form-horizontal m-t-15">
													<div class="form-group hidden">
														<label class="col-sm-5 control-label">Nilai Kontrak</label>
														<?php 
														if(isset($result['info_addendum'][0][2])){
															$y = count($result['info_addendum']);
															?>
															<div class="col-sm-7 m-t-5">Rp. <?php echoalt(number_format($result['info_addendum'][$y-1][2], 2, ",", "."), "-");
														}
														else {
															?>
															<div class="col-sm-7 m-t-5">Rp. <?php echoalt(number_format($result['nilai_kontrak'], 2, ",", "."), "-");
														}
														?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Realisasi s/d Bulan ini</label>
														<div class="col-sm-7 m-t-5">Rp. <?php echoalt(number_format($result['info_uang'], 2, ",", "."), "-"); ?>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-5 control-label">Sisa Dana</label>
														<div class="col-sm-7 m-t-5">Rp. <?php echoalt(number_format($result['info_sisa_dana'], 2, ",", "."), "-"); ?>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-8">
								 <div class="panel panel-color panel-purple">
									<div class="panel-heading">
										<h3 class="panel-title">Curva Monitoring Kegiatan</h3>
									</div>
<!-- 									<div class="panel-body">

									</div> -->
								</div>
							</div>
							<div class="col-sm-4">
								<div class="panel panel-color panel-purple">
									<div class="panel-heading"><h3 class="panel-title">Progress</h3>
									</div>
									<?php if(!empty($result['info_progress'][0]) || !empty($result['info_progress'][1]) || !empty($result['info_progress'][2])): ?>
									<div class="panel-body">
										<form action="#" class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-4 control-label">Rencana Bulan ini</label>
												<div class="col-sm-8 m-t-5"><?php echoalt($result['info_progress'][0], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Realisasi Bulan ini</label>
												<div class="col-sm-8 m-t-5"><?php echoalt($result['info_progress'][1], "-"); ?>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Deviasi</label>
												<div class="col-sm-8 m-t-5"><?php echoalt($result['info_progress'][2], "-"); ?>
												</div>
											</div>
										</form>
									</div>
								<?php endif; ?>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-8">
								<div class="panel panel-border panel-success">
									<div class="panel-heading">
										<h3 class="panel-title">Progress Fisik</h3>
									</div>
									<div class="panel-body">
									<?php if(!empty($result['info_fisik'])): ?>
										<table data-toggle="table" data-mobile-responsive="true" data-check-on-init="true" class="table">
											<thead>
												<tr>
													<th rowspan=2>Div.</th>
													<th rowspan=2>Uraian</th>
													<th colspan=4>Bobot</th>
												</tr>
												<tr>
													<th>Kontrak</th>
													<th>Bulan lalu</th>
													<th>Bulan ini</th>
												</tr>
											</thead>
											<tbody>
												<?php $y = count($result['info_fisik']);
												for($x=0;$x<$y;$x++):
												?>
												<tr>
													<td><?php echo $result['info_fisik'][$x][0]; ?></td>
													<td><?php echo $result['info_fisik'][$x][1]; ?></td>
													<td><?php echo $result['info_fisik'][$x][2]; ?></td>
													<td><?php echo $result['info_fisik'][$x][3]; ?></td>
													<td><?php echo $result['info_fisik'][$x][4]; ?></td>
												</tr>
												<?php endfor; ?>
											</tbody>
										</table>
									<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="panel panel-border panel-purple">
									<div class="panel-heading">
										<h3 class="panel-title">Lay-out Pekerjaan</h3>
									</div>
									<div class="panel-body">
									
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
									<?php if(!empty($result['info_monitor'])): ?>
										<table data-toggle="table" data-mobile-responsive="true" data-check-on-init="true" class="table">
											<thead>
												<tr>
													<th rowspan=2>Monitoring</th>
													<th colspan=12>2017</th>
												</tr>
												<tr>
													<th>Januari</th>
													<th>Februari</th>
													<th>Maret</th>
													<th>April</th>
													<th>Mei</th>
													<th>Juni</th>
													<th>Juli</th>
													<th>Agustus</th>
													<th>September</th>
													<th>Oktober</th>
													<th>November</th>
													<th>Desember</th>
												</tr>
											</thead>
											<tbody>
											<?php
											if($result['info_monitor']):
												echo "<tr>";
												$z = count($result['info_monitor']);
												for($x=1;$x<4;$x++){
													switch ($x) {
														case 1:
															echo "<td>Kumulatif Rencana (%)</td>";
															break;
														case 2:
															echo "<td>Kumulatif Realisasi (%)</td>";
															break;
														case 3:
															echo "<td>Deviasi (%)</td>";
															break;
														default:
															# code...
															break;
													}

													for($y=0;$y<$z;$y++){
														echo "<td>".$result['info_monitor'][$y][$x]."</td>";
													}
													echo "</tr>";
												}
											else:
												echo "<tr>";
												$z = count($result['info_monitor']);
												for($x=1;$x<4;$x++){
													switch ($x) {
														case 1:
															echo "<td>Kumulatif Rencana (%)</td>";
															break;
														case 2:
															echo "<td>Kumulatif Realisasi (%)</td>";
															break;
														case 3:
															echo "<td>Deviasi (%)</td>";
															break;
														default:
															# code...
															break;
													}

													for($y=0;$y<12;$y++){
														echo "<td></td>";
													}
													echo "</tr>";
												}
											endif;
											?>
											</tbody>
										</table>
									<?php endif; ?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-border panel-danger">
									<div class="panel-heading">
										<h3 class="panel-title">Dokumentasi Kegiatan</h3>
									</div>
									<div class="panel-body">
									
									</div>
								</div>
							</div>
						</div>



<?php
$template->call('footer');
$template->call('includejs');
?>
<script>
$(document).ready(function() {

		var data_uang = {
			series: [
				<?php if($result['info_uang'] != 0 && $result['info_sisa_dana'] != 0):
					echoalt($result['info_uang'], "0"); echo ", "; echoalt($result['info_sisa_dana'], "0");
				else:
					echo "0";
				endif;
				?>
			]
		};

		var data = {
			series: [
				<?php if($result['info_monitor_realisasi'] != 0):
					echoalt($result['info_monitor_realisasi'], "0"); echo ", "; echoalt($result['info_monitor_notrealisasi'], "0");
				else:
					echo "0";
				endif;
				?>
			]
		};

		var data_waktu = {
			series: [
			<?php if($result['info_waktu_sisa'] != 0 || $result['info_waktu_terpakai'] != 0): 
				echoalt($result['info_waktu_terpakai'], "0"); echo ", "; echoalt($result['info_waktu_sisa'], "0");
			else:
				echo "0";
			endif; ?> 
			]
		};

		var sum = function(a, b) { return a + b };

		new Chartist.Pie('#keuangan', data_uang, {
			donut: true,
			donutWidth: 20,
			showLabel: false,
		});

		new Chartist.Pie('#ringkasan', data, {
			donut: true,
			donutWidth: 20,
			showLabel: false,
		});

		new Chartist.Pie('#waktu', data_waktu, {
			donut: true,
			donutWidth: 20,
			showLabel: false,
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