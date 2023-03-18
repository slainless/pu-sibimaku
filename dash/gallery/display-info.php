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
    array("","Gallery","1"),
);
$title = "Gallery";
$page_title = "SIBIMA-KU | Gallery";

$session = array(
	'name' => $s_name,
	'level' => $s_level,
);
$template->init($bc, $title, $page_title, $session);

$template->includejs("gallery");

$template->call('include');
$template->call('topbar');
$template->call('navbar');
$template->call('leftbar');
?>
                        <!-- SECTION FILTER
                        ================================================== -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <div class="portfolioFilter">
                                    <a data-filter="*" class="current" style="cursor: pointer">All</a>
                                    <a data-filter=".seminar1" style="cursor: pointer">Seminar Awal</a>
                                    <a data-filter=".termin1" style="cursor: pointer">Progress Termin #1</a>
                                    <a data-filter=".seminar2" style="cursor: pointer">Seminar Tengah</a>
                                    <a data-filter=".termin2" style="cursor: pointer">Progress Termin #2</a>
                                    <a data-filter=".seminar3" style="cursor: pointer">Seminar Akhir</a>
                                    <a data-filter=".termin3" style="cursor: pointer">Progress Termin #3</a>
                                </div>
                            </div>
                        </div>

                        <div class="row port">
                            <div class="portfolioContainer">
                                <div class="col-sm-6 col-lg-3 col-md-4 seminar1">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/1.jpg" class="image-popup" title="Screenshot-1">
                                            <img src="../assets/images/gallery/1.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Awal</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin1">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/2.jpg" class="image-popup" title="Screenshot-2">
                                            <img src="../assets/images/gallery/2.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #1</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 seminar2">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/3.jpg" class="image-popup" title="Screenshot-3">
                                            <img src="../assets/images/gallery/3.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Tengah</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin2">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/4.jpg" class="image-popup" title="Screenshot-4">
                                            <img src="../assets/images/gallery/4.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #2</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 seminar3">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/5.jpg" class="image-popup" title="Screenshot-5">
                                            <img src="../assets/images/gallery/5.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Akhir</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin3">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/6.jpg" class="image-popup" title="Screenshot-6">
                                            <img src="../assets/images/gallery/6.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #3</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 seminar1">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/7.jpg" class="image-popup" title="Screenshot-7">
                                            <img src="../assets/images/gallery/7.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Awal</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin1">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/8.jpg" class="image-popup" title="Screenshot-8">
                                            <img src="../assets/images/gallery/8.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #1</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 seminar2">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/9.jpg" class="image-popup" title="Screenshot-9">
                                            <img src="../assets/images/gallery/9.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Tengah</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin2">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/10.jpg" class="image-popup" title="Screenshot-10">
                                            <img src="../assets/images/gallery/10.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #2</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 seminar3">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/11.jpg" class="image-popup" title="Screenshot-11">
                                            <img src="../assets/images/gallery/11.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Seminar Akhir</small></p>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3 col-md-4 termin3">
                                    <div class="gal-detail thumb">
                                        <a href="../assets/images/gallery/12.jpg" class="image-popup" title="Screenshot-12">
                                            <img src="../assets/images/gallery/12.jpg" class="thumb-img" alt="work-thumbnail">
                                        </a>
                                        <h4 class="text-center">Gallery Image</h4>
                                        <div class="ga-border"></div>
                                        <p class="text-muted text-center"><small>Progress Termin #3</small></p>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- End row -->





<?php
$template->call('footer');
$template->call('includejs');
?>
<script type="text/javascript">
    $(window).load(function(){
        var $container = $('.portfolioContainer');
        $container.isotope({
            filter: '*',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });

        $('.portfolioFilter a').click(function(){
            $('.portfolioFilter .current').removeClass('current');
            $(this).addClass('current');

            var selector = $(this).attr('data-filter');
            $container.isotope({
                filter: selector,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            return false;
        });
    });
    $(document).ready(function() {
        $('.image-popup').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-fade',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            }
        });
    });
</script>
<?php
$template->call('endfile');