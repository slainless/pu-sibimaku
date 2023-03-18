<?php

$dir_html2 = $dir_html.'dash-cat/edit/';
require $dir_html2."header.html";
$get_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$process = new catProcess($query);
$array = $process->fetch(1, $get_id);

if($array):
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
                                        <li><a href="#">Kegiatan</a></li>
                                        <li class="active">Edit</li>
                                    </ol>
                                    <h4 class="page-title">Edit Kegiatan</h4>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box">

                                    <form class="form-horizontal" role="form" method="POST">
                                        <div class="form-group">
                                            <label for="inputTitle" class="col-sm-3 control-label">Kategori</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" id="inputTitle" placeholder="Kategori" name="title" value='<?php echo $array[0][1]; ?>'>
                                            </div>
                                        </div>
                                        <div class="form-group m-b-0">
                                            <div class="col-sm-offset-3 col-sm-9">
                                              <button type="submit" class="btn btn-info waves-effect waves-light">Sign in</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
<?php
require $dir_html2."footer.html";
else:
    echo "NO DATA";
endif;