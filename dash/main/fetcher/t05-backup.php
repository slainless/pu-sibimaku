<?php
$limit = 5;
$postlimit = return_bytes(ini_get('post_max_size'));

$param['table'] = $dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');
$param['field'] = array(
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'lead_id', 'result' => 'lead_id'),
); // TO ADD : CHECK PROGRESS

$checkDash = $exec->select($param);
unset($param);

if($s_level == 2 && $s_id == $checkDash['rel_id']){

}
else {
	errCode("EC004", "Lagi ngapain disini? tersesat ya mas/mbak?", true);
}


$param['table'] = $mail;
$param['where'] = array('name' => 'rel_id', 'value' => $post[0], 'type' => 'i');
$param['field'] = array(
	array('name' => 'status', 'result' => 'status'),
	array('name' => 'target_list', 'result' => 'target_list'),
	array('name' => 'attach', 'result' => 'attach'),
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'target', 'result' => 'target'),
	array('name' => 'time', 'result' => 'time'),
	array('name' => 'comment', 'result' => 'comment'),
	array('name' => 'c_time', 'result' => 'c_time')
);

$check = $exec->select($param);
unset($param);

$truelimit = $limit - count(cta($check['attach']));
?>
<div class="panel panel-color panel-primary">
	<style>
	</style>
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Permintaan Pembayaran</h3>
    </div>
	<div class="panel-body">
	    <div class="form-group">
	        <form method="POST" action="" id="popup" data-parsley-validate="" enctype="multipart/form-data">
        	<?php
    		// status 0 = baru
    		// status 2 = ditolak
    		// 
    		// STATUS
    		// if mail NOT empty
    		if($check):
    			// $check['status'] = 2; //debug
	    		if($check['status'] == 2): ?>

	    			<div class="m-b-10 text-right clearfix">
	    				<span class="label label-danger pull-left">Ditolak</span>
	    				<span class="text-danger">Berkas ditolak. Silahkan revisi</span>
	    			</div>

					<?php
	    		else: ?>

	    			<div class="m-b-10 text-right clearfix">
	    				<span class="label label-inverse pull-left">Diproses</span>
	    				<span class="text-muted">Pemintaan sedang diproses</span>
	    			</div>
	    		


	    	<?php endif;
	    	endif;
            	
    		// DROPZONE
    		// if mail empty

			if($check['status'] == 2 || !$check): ?>

			<div class="dropzone-wrapper <?php if($check && $truelimit === 0) echo 'hidden'; ?>">
				<label class="control-label m-b-10">Upload Berkas</label>
				<div class="dropzone m-b-10" id="dropzone">
	            	<div class="fallback">
	                	<input name="file" type="file" multiple />
	                	<input name="data_0" type="hidden"/>
	              	</div>
	            </div>
	        </div>

        	<?php 
        	endif;

        	// UPLOADED FILE
        	// if mail NOT EMPTY

        	if($check && !empty($check['attach'])): ?>

        		<div class="bootstrap-tagsinput col-sm-12 m-b-10">

        	<?php
        	$check['attach'] = cta($check['attach']);
        	$y = count($check['attach']);

        	for($x=0;$x<$y;$x++):

	        	$z = explode("/", $check['attach'][$x]);
	        	$zx = count($z);

	        	$check['attach'][$x] = $abs_dir_upload.$z[$zx-1];
	        ?>

					<span class="tag label label-info">
						<a style="color: white;" href="<?php echo $check['attach'][$x]; ?>" target="_blank"><?php echo $z[$zx-1]; ?></a>
						<?php if($check['status'] == 2) echo '<span class="remove-file" data-role="remove" value="'.$x.'"></span>'; ?>
					</span>

        	<?php
        	endfor;
        	?>

        		</div>

            <?php endif; 

            if($check['status'] === 2): ?>
        		<label class="control-label">Keterangan</label>
        		<div class="inline-editor text-muted">

        		<?php echoalt($check['comment'], "-"); ?>

                </div>

            <?php endif;

            // HIDDEN INPUT/SUBMIT BUTTON
            // IF DITOLAK/MAIL EMPTY/BARU

            if($check['status'] == 2 || !$check): 
            ?>

            	<div class="clearfix pull-right m-t-15">
                	<button type="submit" class="btn btn-inverse waves-effect waves-light">Submit</button>
                </div>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php if($check && $check['status'] == 2) echo codeGen("4","7"); elseif(!$check) echo codeGen("6","1"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
            
            <?php endif; ?>

	        </form>
	    </div>
	</div>
	<script>
	Dropzone.autoDiscover = false;

/*	setTimeout(function() { /* re init dropzone to mod max file upload
		limit++;
        alert("habis!" + limit);
        Dropzone.forElement("#dropzone").destroy();
        test(limit);
    }, 10000);*/

	var initDropzone = function(max) { 
		var max_onadded = 0;
		$("#dropzone").dropzone({ 
		  	autoProcessQueue: false,
		  	addRemoveLinks: true,
		  	uploadMultiple: true,
		  	parallelUploads: 5,
		  	maxFiles: max,
		  	url: '#',
		  	maxFilesize: 20,
		  	dictDefaultMessage: 'Drop/Upload persyaratan berkas (*.pdf)',
		  	acceptedFiles: 'application/pdf',
		  	dictFileTooBig: 'Ukuran file terlalu besar. (Maks: {{maxFilesize}}MiB)',
		  	dictInvalidFileType: 'Format file harus dalam bentuk *.pdf',
		  	dictRemoveFile: 'Hapus file',
		  	dictMaxFilesExceeded: 'Mencapai batas upload maksimal. (Maks: {{maxFiles}})',
		  	sending: function(file, xhr, formData) {
		  		formData.append("filetype[]", file.type);
		  	},
		  	sendingmultiple: function(file, xhr, formData) {
		       	formData.append("status", $('#popup input[name="status"]').val());
		       	formData.append("device", $('#popup input[name="device"]').val());
		       	formData.append("device_statistic", $('#popup input[name="device_statistic"]').val());
		       	formData.append("id", $('#popup input[name="id"]').val());
		       	formData.append("data_mark", $('#popup input[name="data_mark"]').val());
		       	formData.append("submit", '');

		    	var x;
		       	var max = 0;
		       	for(x in file){
		       		max = max + file[x].size;
		       	}

		       	if(max > <?php echo $postlimit; ?>){
		       		this.removeAllFiles(true)

                	swal({
                        title: "Gagal",
                        text: "Gagal mengirim berkas. Total ukuran keselurahan mencapai batas upload POST maksimal (<?php echo ini_get('post_max_size')."B"; ?>)",
                        type: "error",
                        showConfirmButton: false,
                        timer: 5000,
                	});
		       	}
		    },
		  	init: function() {
			    var myDropzone = this;

			    // First change the button to actually tell Dropzone to process the queue.
			    $("#popup button").on("click", function(e) {
			      // Make sure that the form isn't actually being sent.
				    e.preventDefault();
				    e.stopPropagation();
				    var value = $("#summernote").val();
				    $("#popup input[name='data_0']").val(value);
				    myDropzone.processQueue();
			    });

			    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
			    // of the sending event because uploadMultiple is set to true.
			    


			    this.on("successmultiple", function(files, response) {
			    	if(response == 'true'){
				    	$('#panel-modal').modal('hide');

					    swal({
	                        title: "Terkirim!",
	                        text: "Permintaan anda berhasil dikirim.",
	                        type: "success",
	                        showConfirmButton: false,
	                        timer: 2000,
	                    });
	                }
	                else {
	                	$('#panel-modal').modal('hide');

	                	swal({
	                        title: "Gagal",
	                        text: "Gagal mengirim berkas. Terjadi kesalahan pada sistem, silahkan hubungi Operator",
	                        type: "error",
	                        showConfirmButton: false,
	                        timer: 5000,
                    	});
	                }
			    });
			    this.on("errormultiple", function(files, response) {
			      // Gets triggered when there was an error sending the files.
			      // Maybe show form again, and notify user of error
			      	swal({
                        title: "Gagal",
                        text: "Gagal menambah berkas. Berkas yang diupload tidak memenuhi syarat atau sudah mencapai batas upload. Silahkan tinjau kembali berkas yang anda upload",
                        type: "error",
                        showConfirmButton: false,
                        timer: 3000,
                    });
			    });
			}
		});
	}

	<?php if($check):
		echo 'var limit = '.$truelimit.';';
		echo 'initDropzone(limit);';

	else: ?>
		var limit = 5;
		initDropzone(limit);

	<?php endif; ?>

	$(".remove-file").on('click', function() {
    	var send = $(this).attr("value");
    	var tag = $(this);

        var status_count = $("#popup").children("input[name='status']").attr("value");
        var device = $("#popup").children("input[name='device']").attr("value");
        var device_statistic = $("#popup").children("input[name='device_statistic']").attr("value");
        var id = $("#popup").children("input[name='id']").attr("value");

    	swal({
                title: "Hapus berkas ini?",
                text: "Berkas akan dihapus dari server.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-warning",
                confirmButtonText: "Ya, saya yakin!",
                cancelButtonText: "Tidak, batalkan",
                closeOnConfirm: false,
                closeOnCancel: false,
                html: true,
            }, 
            function (isConfirm) {
                if (isConfirm) {

                    $.ajax({ 
                        type: "POST",
                        url: "main/fetcher.php",
                        data: { send: send, status: status_count, device: device, id: id, device_statistic: device_statistic} 
                    })
                    .done(function( str ) { 
                        if(str == 'true'){
                            swal({
                                title: "Terhapus!",
                                text: "Berkas telah dihapus.",
                                type: "success",
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            setTimeout(function() {
                            	limit++;
						        Dropzone.forElement("#dropzone").destroy();
						        $(".dropzone-wrapper").removeClass("hidden");
						        initDropzone(limit);
						        tag.parent().remove();

						        var counter = 0;
						        $('.remove-file').each(function () {
								    $(this).attr("value", counter);
								    counter++;
								});

						        if($(".bootstrap-tagsinput").children().length == 0){
						        	$(".bootstrap-tagsinput").remove();
						        }
                            }, 2300);
                        }
                        else{
                            swal({
                                title: "Error!",
                                text: "Permintaan tidak dapat diproses, terjadi kesalahan. Silahkan hubungi Operator/Administrator",
                                type: "error",
                                showConfirmButton: false,
                                timer: 2000,
                            });

                            setTimeout(function() {
                                
                            }, 2300);
                        }
                    });
                    

                } 
                else {
                    swal({
                            title: "Batal!",
                            text: "Kegiatan ini batal dihapus.",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500,
                    });
                }
            });
    });

/*        $('#summernote').summernote({
            height: 200,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            focus: false,                 // set focus to editable area after initializing summernote
            toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['fontsize', ['fontsize']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['height', ['height']]
			  ]
        });*/

    </script>
</div>