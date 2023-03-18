<?php
$postlimit = return_bytes(ini_get('post_max_size'));

$param['table'] = $tbl_docs;
$param['where'] = array('name' => 'id', 'value' => $code['id'], 'type' => 'i');
$param['field'] = array(
	array('name' => 'id', 'result' => 'id'),
	array('name' => 'rel_id', 'result' => 'rel_id'),
	array('name' => 'name', 'result' => 'name'), 
	array('name' => 'link', 'result' => 'link'), 
	array('name' => 'tag', 'result' => 'tag'),
	array('name' => 'status', 'result' => 'status'), 
	array('name' => 'time', 'result' => 'time'),
	array('name' => 'substring_index(link, ".", -1)', 'result' => 'format'),
);
$param['table'] = $tbl_docs;

$result = $exec->select($param);
unset($param);

var_dump($result);
?>
<div class="panel panel-color panel-primary">
	<style>
		.parsley-except .parsley-errors-list {
            position: absolute;
            bottom: -1.3em;
        }
		.dropzone {

			min-height: 0;
		}
		.dropzone .dz-message {
		    font-size: inherit;
		}
		.dropzone.dz-started .dz-message {
		    display: none;
		}
		.dz-details {
			max-width: 80%;
			text-overflow: ellipsis;
		}

		.dz-preview.dz-error .dz-details.label-info {
			background-color: #EF5350 !important;
		}

		.dz-preview.dz-error .dz-size {
			color: #EF5350;
		}

		.dz-preview {
			cursor: initial;
		}

		.dz-progress {
			margin-bottom: 0;
		}
		.dz-error-message {
			position: absolute;
			bottom: -2.5rem;
			font-size: 1rem;
			color: #EF5350;
		}
		.dz-default {
			text-align: center;
			padding: 10px 0;
			cursor: pointer;
		}
		.dz-default span {
			font-size: 2rem;
		}
	</style>
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Upload Berkas</h3>
    </div>
	<div class="panel-body">
	    <div class="form-group">
	        <form method="POST" id="popup" data-parsley-validate="" enctype="multipart/form-data">
	        	<div class="checkbox checkbox-primary">
                    <input id="upload" type="checkbox" checked>
                    <label for="upload" style="font-weight: 700">
                        Timpa Berkas
                    </label>
                </div>
				<div class="dropzone-wrapper hidden">
					<div class="dropzone m-b-10 bootstrap-tagsinput col-sm-12" id="dropzone">
		            	<div class="fallback">
		                	<input name="file" type="file"/>
		              	</div>
		            </div>
		        </div>
		        <label for="field-2" class="control-label">Judul Berkas</label>
                <div class="input-group m-t-5 parsley-except">
                    <span class="input-group-addon"><span>Max. 40</span></span>
                    <input type="text" required class="form-control" name="title" placeholder="..." data-parsley-whitespace="trim" data-parsley-maxlength="40" value="<?php echo $result['name']; ?>">
                </div>
                <label for="field-2" class="control-label m-t-10">Tag</label>
                <select class="form-control" style="width: 180px" selected="4" name="tag">
                	<option value="">...</option>
                    <option value="1" <?php if($result['tag'] === 1) echo "selected"; ?>>Harian</option>
                    <option value="2" <?php if($result['tag'] === 2) echo "selected"; ?>>Pembayaran</option>
                    <option value="3" <?php if($result['tag'] === 3) echo "selected"; ?>>3</option>
                    <option value="4" <?php if($result['tag'] === 4) echo "selected"; ?>>4</option>
                    <option value="5" <?php if($result['tag'] === 5) echo "selected"; ?>>5</option>
                </select>
		        <button type="submit" class="btn btn-inverse btn-rounded pull-right waves-effect waves-light" data-primary="<?php echo codeGen("c","d"); ?>" data-mode="<?php echo codeGen("b","6"); ?>" data-id="<?php echo codeGen($code['id'],"", true); ?>" data-token="<?php echo $_SESSION['req_token']; ?>" data-action="<?php echo codeGen("0","9"); ?>">Submit</button>
	        </form>
	    </div>
	</div>
	<script>

	$('#popup').parsley();

	$('#upload').on('change', function(){
		if($(this).prop('checked')){
			$('.dropzone-wrapper').removeClass('hidden');
			$("#popup button").off();
			initDropzone();
		}
		else {

			disableDz();

		}
	});

	Dropzone.autoDiscover = false;

/*	setTimeout(function() { /* re init dropzone to mod max file upload
		limit++;
        alert("habis!" + limit);
        Dropzone.forElement("#dropzone").destroy();
        test(limit);
    }, 10000);*/

    var counter = 0;

	function initDropzone() { 
		$("#dropzone").dropzone({ 
		  	autoProcessQueue: false,
		  	addRemoveLinks: false,
		  	url: 'docs/fetcher.php',
		  	maxFilesize: 20,
		  	maxFiles: 1,
		  	dictDefaultMessage: '<i class="md md-backup m-r-10" style="font-size: 3rem"></i><span>Drop/Upload berkas</span>',
		  	acceptedFiles: '.pdf, .docx, .xlsx, .doc, .xls',
		  	dictFileTooBig: 'Ukuran file terlalu besar. (Maks: {{maxFilesize}}MiB)',
		  	dictInvalidFileType: 'Format file tidak diizinkan',
		  	dictRemoveFile: 'Hapus file',
		  	sending: function(file, xhr, formData) {
		  		that = $("#popup button");

		  		formData.append("mime", file.name.split('.').pop());
		  		formData.append("primary", that.attr("data-primary"));
		  		formData.append("mode", that.attr("data-mode"));
		  		formData.append("token", that.attr("data-token"));
		  		formData.append("id", that.attr("data-id"));
		  		formData.append("action", that.attr("data-action"));
		  		formData.append("title", $("#popup input[name='title']").val());
		  		formData.append("tag", $("#popup select").val());

		    },
		  	init: function() {
			    var myDropzone = this;


			    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
			    // of the sending event because uploadMultiple is set to true.
			    // 
			    $("#popup button").on("click", function(e) {
			      // Make sure that the form isn't actually being sent.
				    e.preventDefault();
				    var valid = $("#popup").parsley().validate();
				    if(valid){
				    	myDropzone.processQueue();
				    }
				    
			    });
			    
			    this.on("addedfile", function(file) {

			    	counter++;

					if(counter > 1){
			    		myDropzone.removeFile(file);

			    		swal({
                            title: "Batas Upload",
                            text: "Maksimal 1 file dalam sekali upload!",
                            type: "error",
                            showConfirmButton: true,
                            confirmButtonClass: 'btn-danger'
                        });
			    	}
			    	

			    });

			    this.on("success", function(files, response) {
			    	var data = JSON.parse(response);

			    	swal({
                        title: data.title,
                        text: data.desc,
                        type: data.type,
                        showConfirmButton: true,
                        confirmButtonClass: data.btn
                    }, function (isConfirm) {
            			if (isConfirm) {
            				$('#data').attr('data-token', data.token);
            				$('#data').bootstrapTable('refresh');
            				$('#panel-modal').modal('hide');
            			}
            		});
			    });

			    this.on("removedfile", function(files, response) {
			    	counter--;
			    });
			},
			previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-details tag label label-info"><span class="dz-filename"><span data-dz-name></span></span><img data-dz-thumbnail /><span class="dz-remove" data-dz-remove data-role="remove"></span></div><span class="dz-size pull-right" data-dz-size></span><div class="dz-progress progress m-t-5 "><span class="dz-upload progress-bar progress-bar-primary progress-bar-striped active" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></span></div><div class="dz-success-mark hidden"><span>✔</span></div><div class="dz-error-mark hidden"><span>✘</span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div>'
		});
	}

	function disableDz() {

		$('.dropzone-wrapper').addClass('hidden');
		Dropzone.forElement("#dropzone").destroy();
		$("#popup button").off();

		$("#popup button").on("click", function(e) {
		      // Make sure that the form isn't actually being sent.
		    e.preventDefault();
		    var valid = $("#popup").parsley().validate();
		    if(valid){
		    	var form = $("#popup").serialize();
		    	alert(form);
		    }
			    
		});

	}

	initDropzone();
	disableDz();

    </script>
</div>