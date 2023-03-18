<?php
$dir_html2 = $dir_html.'dash-cat/edit-pekerjaan/';
require $dir_html2."header.html";

$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$process = new dashbProcess($query);
$arrayMember = $process->fetchMember(3);
$array = $process->fetch($get_id, 9);

$r_rel_id = $array[0][0];
$r_kategori = $array[0][1];
$dp_info = cta($array[0][2]);
$kategori = $array[0][3];
$pagu = $array[0][4];
?>
<div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">

                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <ol class="breadcrumb pull-right">
                                        <li><a href="#">Dashboard</a></li>
                                        <li><a href="#">Pekerjaan</a></li>
                                        <li class="active">Edit</li>
                                    </ol>
                                    <h4 class="page-title">Edit Pekerjaan</h4>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <a class="btn btn-icon waves-effect waves-light btn-danger m-b-5 pull-right"><i class="fa fa-warning m-r-5"></i> <span>Hapus</span> </a>
                                    <h4 class="m-t-0 header-title"><b><?php echo $kategori; ?></b></h4>
                                    <p class="text-danger font-13 m-b-30">
                                        (* Semua input wajib diisi)
                                    </p>
                                    

                                    <form method="POST" data-parsley-validate novalidate>
                                        <div class="form-group">
                                            <label for="Pekerjaan">Nama Pekerjaan</label>
                                            <input type="text" name="title" parsley-trigger="change" required placeholder="Masukkan Pekerjaan" class="form-control" id="Pekerjaan" value="<?php echo $dp_info[0]; ?>">
                                        </div>

                                        <h5><b>Pilih PPTK</b></h5>
                                        <select class="form-control select2" required parsley-trigger="change" name="pptk">
                                            <option value="">Select</option>
                                            <?php
                                                $limit = count($arrayMember);
                                                $selected = "";
                                                for($x=0;$x<$limit;$x++){
                                                    if($arrayMember[$x][0] == $r_rel_id){
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value=".$arrayMember[$x][0]." ".$selected.">".$arrayMember[$x][1]."</option>";
                                                    $selected = "";
                                                }
                                            ?>
                                        </select><br>

                                        <div class="form-group">
                                            <label for="pagudana">Nilai Pagu Dana</label>
                                            <input type="text" name="pagu" parsley-trigger="change" required placeholder="Masukkan Nilai Pagu Dana" class="form-control" id="pagudana" value="<?php echo $pagu; ?>">
                                        </div>

                                        <div class="form-group text-right m-b-0">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit" name="submit">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                                                Cancel
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>                                   
                        </div>


                        <!-- end row -->


                    </div>
                    <!-- end container -->
                </div>
                <!-- end content -->

<?php
require $dir_html2."footer.html";

?>