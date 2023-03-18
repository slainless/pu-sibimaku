<?php

$param['field'] = array('name' => 'dp_addendum', 'result' => 'info_addendum');
$param['table'] = $dash;
$param['where'] = array('name' => 'id', 'value' => $post[0], 'type' => 'i');

$result = $exec->select($param);

$result['info_addendum'] = cta($result['info_addendum'], true);

?>
<div class="panel panel-color panel-primary">
    <div class="panel-heading">
        <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true" >×</button>
        <h3 class="panel-title">Addendum Kontrak</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <form method="POST" action="" id="popup" data-parsley-validate="" class="form-horizontal">
            	<div id="group-wrapper">
	            <?php
	            $y = count($result['info_addendum']);
				for($x=0;$x<$y;$x++): ?>
					<div id="group-<?php echo $x + 1; ?>">
						<div class="form-group">
							<label class="col-sm-3 control-label">No. #0<?php echo $x + 1; ?></label>
							<div class="col-sm-9 m-t-5"><input type="text" parsley-trigger="change" class="form-control" name="data_t040[]" placeholder="..." value="<?php if(isset($result['info_addendum'][$x][1])) echoalt($result['info_addendum'][$x][0], ""); else echo ""; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Tanggal #0<?php echo $x + 1; ?></label>
							<div class="col-sm-9 m-t-5"><input type="text" parsley-trigger="change" class="form-control" name="data_t041[]" placeholder="..." value="<?php if(isset($result['info_addendum'][$x][1])) echoalt(str_replace("-", "/", $result['info_addendum'][$x][1]), ""); else echo ""; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Nilai #0<?php echo $x + 1; ?></label>
							<div class="col-sm-9 m-t-5">
								<div class="input-group parsley-except">
			                    	<span class="input-group-addon"><span>RP</span></span>
			                    	<input type="text" parsley-trigger="change" class="form-control" name="data_t042[]" placeholder="..." value="<?php if(isset($result['info_addendum'][$x][1])) echoalt($result['info_addendum'][$x][2], "-"); else echo "-"; ?>">
			                    	<span class="input-group-addon">00</span>
		               			</div>
							</div>
						</div>
					</div>
				<?php
				endfor;
				?>
		        </div>
		        <div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button class="btn-sm btn-inverse waves-effect waves-light btn-add" type="button"><i class="md md-add"></i></button>
		                <button class="btn-sm btn-danger waves-effect waves-light btn-delete" type="button"><i class="md md-remove"></i></button>
		            </div>
		        </div>
				<script>
					$('#popup').parsley();
					$('#popup input[name="data_t042[]"]').mask('999.000.000.000.000', {reverse: true});
					$('#popup input[name="data_t041[]"]').datepicker({
                	autoclose: true,
                	todayHighlight: true,
                	format: 'dd/mm/yyyy'
                	});

                	var counter = <?php echo $y + 1; ?>;
		            var limit = 6;

		            $('.btn-add').on('click', function() {

		                var template = "<div id='group-" + counter + "'><div class='form-group'><label class='col-sm-3 control-label'>No. #0" + counter + "</label><div class='col-sm-9 m-t-5'><input type='text' required parsley-trigger='change' class='form-control' name='data_t040[]' placeholder='...' value=''></div></div><div class='form-group'><label class='col-sm-3 control-label'>Tanggal #0" + counter + "</label><div class='col-sm-9 m-t-5'><input type='text' required parsley-trigger='change' class='form-control' name='data_t041[]' placeholder='...' value=''></div></div><div class='form-group'><label class='col-sm-3 control-label'>Nilai #0" + counter + "</label><div class='col-sm-9 m-t-5'><div class='input-group parsley-except'><span class='input-group-addon'><span>RP</span></span><input type='text' parsley-trigger='change' required class='form-control' name='data_t042[]' placeholder='...' value=''><span class='input-group-addon'>00</span></div></div></div></div>";

		                if(counter < limit){
		                    counter++;
		                    $('#group-wrapper').append(template);
		                    $('#popup input[name="data_t041[]"]').datepicker({
		                	autoclose: true,
		                	todayHighlight: true,
		                	format: 'dd/mm/yyyy'
		                	});
		                }
		                else {

		                }
		            });

		            $('.btn-delete').on('click', function() {

		                if(counter != 2){
		                    counter--;
		                    $('#group-' + counter).remove();
		                }
		                else {

		                }

		            });
				</script>
                <input type="hidden" value="<?php echo codeGen($post[0],"",1); ?>" name="status">
                <input type="hidden" value="<?php echo codeGen("7","3"); ?>" name="device">
                <input type="hidden" value="<?php echo codeGen("1","2"); ?>" name="device_statistic">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="id">
                <input type="hidden" value="<?php echo codeGen(random_int(1,9),random_int(1,9)); ?>" name="data_mark">
                <button type="submit" class="btn btn-primary waves-effect waves-light m-t-15 pull-right" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>