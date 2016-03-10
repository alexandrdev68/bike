<div class="modal fade bs-example-modal-lg _payment_window" tabindex="-1" role="dialog" aria-labelledby="orderWindowLabel">
  <div class="modal-dialog modal-lg">
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=TEMP::$Lang['payment_window_header_text']?></h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-md-5">
			  <div class="">
			    <div class="thumbnail">
			      <img data-vtemplate_payment_window="src=foto" src="..." alt="...">
			      <div class="caption">
			        <h3 data-vtemplate_payment_window="text=model"></h3>
			        <p>...</p>
			        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			      </div>
			    </div>
			  </div>
		  </div>
		
		<div class="col-md-7">
		<form data-vtemplate_payment_window="event=submit:on_client_auth_submit,function=scan_inputs:*" class="form-horizontal _client_auth_form">
		  <div class="form-group">
		    <div class="col-xs-4">
			    <label for="InputPhone"><?=TEMP::$Lang['input_phone'].' ('.TEMP::$Lang['text_phone_template'].')'?></label>
			    <input data-vtemplate_payment_window="event=keyup:on_keypress_phone_verife" type="text" name="phone" class="form-control" id="InputPhone" placeholder="<?=TEMP::$Lang['input_phone']?>">
		    </div>
		    <h4 data-vtemplate_payment_window="text=user.patronymic"></h4>
		    <h4 data-vtemplate_payment_window="text=user.name"></h4>
		    <h4 data-vtemplate_payment_window="text=user.surname"></h4>
		  </div>
		  <div class="form-group disabled _register_fields">
		    <div class="col-xs-5">
			    <label for="InputLastname"><?=TEMP::$Lang['input_lastName']?></label>
			    <input type="text" name="lastname" class="form-control" id="InputLastname" placeholder="<?=TEMP::$Lang['input_lastName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputFirsttname"><?=TEMP::$Lang['input_firstName']?></label>
			    <input type="text" name="firstname" class="form-control" id="InputFirstname" placeholder="<?=TEMP::$Lang['input_firstName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputSecondname"><?=TEMP::$Lang['input_patronymic']?></label>
			    <input type="text" name="secondname" class="form-control" id="InputSecondname" placeholder="<?=TEMP::$Lang['input_patronymic']?>">
		    </div>
		  </div>
			<button type="submit" class="disabled _submit_auth_button"><?=TEMP::$Lang['next_txt']?></button>
		</form>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEMP::$Lang['exit_btn']?></button>
        <button data-vtemplate_payment_window="value=id,event=click:payment_okhandler" disabled type="button" class="btn btn-danger"><?=TEMP::$Lang['pay_booking_text']?></button>
      </div>
    </div><!-- /.modal-content -->
 
  </div>
</div>