<?php


?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Tambah User</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="" class="form-horizontal">
	          	<ul class="list-group dl-horizontal">
					<li class="list-group-item">
						<dt>Username</dt>
						<dd>
						<div class="input-group">
						<input type="text" parsley-trigger="change" required  class="form-control" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [@#_.] dan spasi." data-parsley-pattern="/^[A-Za-z0-9@#_.]*$/" data-parsley-length="[5, 15]" name="user" placeholder="..." value="">
						<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
						</div>
						</dd>
					</li>
					<li class="list-group-item">
						<dt>Name</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control" required  name="name" data-parsley-pattern="/^[a-zA-Z0-9.,{}()\[\]#@_\-<>/ ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [.,{}()[]#@_\-<>/] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="separator">
					</li>
					<li class="list-group-item internal field-control required hidden">
						<dt>NIP</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control" required name="nip" data-parsley-pattern="/^[0-9.,#@\- ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari angka, [.,#@-] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="list-group-item external field-control required hidden">
						<dt>Perusahaan</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control" required  name="perusahaan" data-parsley-pattern="/^[a-zA-Z0-9.,#@\- ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [.,#@-] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="list-group-item external field-control required hidden">
						<dt>Direktur</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control" required  name="direktur" data-parsley-pattern="/^[a-zA-Z0-9.,\- ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [.,-] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="list-group-item external field-control required hidden">
						<dt>Alamat</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control" required  name="alamat" data-parsley-pattern="/^[a-zA-Z0-9.,#@\- ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [.,#@-] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="separator external hidden">
					</li>
					<li class="list-group-item external field-control hidden">
						<dt>Bank</dt>
						<dd>
							<select class="form-control" name="bank">
				                	<option value="">...</option>
				                	<option value="1">Bank BPD Kaltim Cabang Utama Samarinda</option>
							</select>
						</dd>
					</li>
					<li class="list-group-item external field-control hidden">
						<dt>No. Rekening</dt>
						<dd>
							<input type="text" parsley-trigger="change"  class="form-control"  name="rekening" 	data-parsley-type="digits" data-parsley-digits-message="Input hanya boleh terdiri dari angka." data-parsley-maxlength="20" placeholder="..." value="">
						</dd>
					</li>
					<li class="list-group-item external field-control hidden">
						<dt>No. NPWP</dt>
						<dd>
						<input type="text" parsley-trigger="change"  class="form-control"  name="npwp" data-parsley-pattern="/^[a-zA-Z0-9\.\- ]*$/" data-parsley-pattern-message="Input hanya boleh terdiri dari huruf/angka, [.-] dan spasi." data-parsley-maxlength="50" placeholder="..." value="">
						</dd>
					</li>
					<li class="separator"></li>
					<li class="list-group-item">
						<dt>Password</dt>
						<dd>
						<div class="input-group parsley-except">
						<input type="password" id="password" parsley-trigger="change" required data-parsley-pattern-message="This field can only contain alphanumeric char, @, #, and _" data-parsley-pattern="/^[A-Za-z0-9@#_]*$/" class="form-control" name="pass" placeholder="..." value="">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						</div>
						</dd>
					</li>
					<li class="list-group-item">
						<dt>Konfirmasi Password</dt>
						<dd>
						<div class="input-group parsley-except">
							<input type="password" parsley-trigger="change" class="form-control" required name="confirm" data-parsley-equalto="#password" placeholder="..." value="">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
						</div>
						</dd>
					</li>
					<li class="list-group-item">
						<dt>Level/Tipe Akun</dt>
						<dd>
			                <select class="form-control level-control" name="level">
			                	<option value="">...</option>
			                    <option value="1">Auditor</option>
			                    <option value="2">Konsultan</option>
			                    <option value="3">Kontraktor</option>
			                    <option value="4">PPTK</option>
			                    <option value="5">Bendahara</option>
			                    <option value="6">PPK</option>
			                    <option value="7">Kepala Dinas</option>
			                </select>
						</dd>
					</li>
				</ul>
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit"
                data-token="<?php echo $_SESSION['req_token']; ?>" 
                data-primary="<?php echo codeGen("b","f"); ?>" 
                data-mode="<?php echo codeGen("7","5"); ?>">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
	$('#popup input[name="rekening"]').mask('0#');

	$('#popup').parsley({
		errorsContainer: function(el) {
		    return el.$element.closest('dd');
		}
	});

	$('.level-control').on('change', function (){
		var that = $(this);

		$('.field-control').addClass('hidden');
		$('.field-control.required .form-control').removeAttr('required');
		$('.field-control .form-control').attr('disabled', '');

		var value = parseInt(that.val());

		if(value === 3){

			$('.external').removeClass('hidden');
			$('.external .form-control').removeAttr('disabled');
			$('.external.required .form-control').attr('required', '');

		}
		else if(value > 3 && value < 8){

			$('.internal').removeClass('hidden');
			$('.internal .form-control').removeAttr('disabled');
			$('.internal.required .form-control').attr('required', '');

		}
	});

    $('#popup').on('submit', function(e){ 
        e.preventDefault();

        var pass = $('#password');
        pass.val(hex_sha512(pass.val()));

        var data = $(this).serialize();
       	var me = $('#popup button[name="submit"]');
       	var that = $('#data');


	    data += "&primary=" + me.attr('data-primary');
	    data += "&token=" + that.attr('data-token');
	    data += "&mode=" + me.attr('data-mode');

	    $.ajax({

	        type: "POST",
	        url: '/dash/usrman/processor',
	        data: data // serializes the form's elements.

	    }).done(function( str ) {

        	$('[type="password"]').val('');

	        var data = JSONParser(str);
	        if(data){
	            $('#data').attr("data-token", data.token).bootstrapTable('refresh');

	            swal({
	                title: data.alert.title,
	                text: data.alert.text,
	                type: data.alert.type,
	                showConfirmButton: data.alert.confirm,
	                timer: data.alert.timer
	            });

	            if(data.alert.type == 'success'){
	        		$('#panel-modal').modal('hide');
	            }
	            else {
	            	pass.val('');
	            }

	        }
		});
	});
</script>