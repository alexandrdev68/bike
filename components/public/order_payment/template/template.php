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
			        <p><b><?=TEMP::$Lang['bike_number']?></b>: <i data-vtemplate_payment_window="text=id"></i></p>
			        <p><b><?=TEMP::$Lang['store_adress']?></b>: <i data-vtemplate_payment_window="text=store_address"></i></p>
			        <p><b><?=TEMP::$Lang['serial_id']?></b>: <i data-vtemplate_payment_window="text=serial_id"></i></p>
			      </div>
			    </div>
			  </div>
		  </div>
		
		<div class="col-md-7 _auth_register_form notshowing">
		<form data-vtemplate_payment_window="event=submit:on_client_auth_submit,function=scan_inputs:*" class="form-horizontal _client_auth_form">
		  <div class="form-group">
		    <div class="col-xs-4">
			    <label for="InputPhone"><?=TEMP::$Lang['input_phone'].' ('.TEMP::$Lang['text_phone_template'].')'?></label>
			    <input data-vtemplate_payment_window="event=keyup:on_keypress_phone_verife" type="text" name="phone" class="form-control _toscan" id="InputPhone" placeholder="<?=TEMP::$Lang['input_phone']?>">
		    </div>
		  </div>
		  <div class="form-group">
		  	<div class="col-xs-8">
			    <i data-vtemplate_payment_window="text=user.patronymic"></i> 
			    <i data-vtemplate_payment_window="text=user.name"></i> 
			    <i data-vtemplate_payment_window="text=user.surname"></i>
		    </div>
		  </div>
		  <div class="form-group notshowing _register_fields">
		    <div class="col-xs-5">
			    <label for="InputLastname"><?=TEMP::$Lang['input_lastName']?></label>
			    <input type="text" name="lastname" class="form-control _toscan" id="InputLastname" placeholder="<?=TEMP::$Lang['input_lastName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputFirsttname"><?=TEMP::$Lang['input_firstName']?></label>
			    <input type="text" name="firstname" class="form-control _toscan" id="InputFirstname" placeholder="<?=TEMP::$Lang['input_firstName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputSecondname"><?=TEMP::$Lang['input_patronymic']?></label>
			    <input type="text" name="secondname" class="form-control _toscan" id="InputSecondname" placeholder="<?=TEMP::$Lang['input_patronymic']?>">
		    </div>
		  </div>
		  <div class="form-group notshowing _smscode">
		  	<div class="col-xs-5">
			    <label for="InputSMSCode"><?=TEMP::$Lang['input_smscode']?></label>
			    <input type="text" name="smscode" class="form-control" id="InputSMSCode" placeholder="<?=TEMP::$Lang['input_smscode']?>">
		    </div>
		  </div>
		  	<input type="hidden" id="operationType" name="operation">
			<button type="submit" class="notshowing _submit_auth_button btn btn-default"><?=TEMP::$Lang['next_txt']?> <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></button>
		</form>
		</div>
		
		<div data-vtemplate_payment_window="event=onLogin:viewPaymentForm, event=onLogout:hidePaymentForm" class="col-md-7 _payment_form _notshowing">
			<div class="form-group col-xs-10">
			    
				<label for="inputCreditCard"><?=TEMP::$Lang['credit_card_number_txt']?></label>
				<div class="col-xs-10">
				    <input type="hidden" id="inputCreditCard_hidden" name="uCCard">
					<div class="col-xs-3 noLeftRightPadding"><input type="text" tabindex="0" id="inputCreditCard" class="form-control credit_input" name="" maxlength="4"  required></div>
					<div class="col-xs-3 noLeftRightPadding"><input type="text" tabindex="1" class="form-control credit_input" maxlength="4" name="" required></div>
					<div class="col-xs-3 noLeftRightPadding"><input type="text" tabindex="2" class="form-control credit_input" maxlength="4" name="" required></div>
					<div class="col-xs-3 noLeftRightPadding"><input type="text" tabindex="3" class="form-control credit_input" maxlength="4" name="" required></div>
			    </div>
			</div>
		</div><!-- payment form -->
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEMP::$Lang['exit_btn']?></button>
        <button data-vtemplate_payment_window="value=id,event=click:payment_okhandler" disabled type="button" class="btn btn-danger"><?=TEMP::$Lang['pay_booking_text']?></button>
      </div>
    </div>
    </div><!-- /.modal-content -->
 
  </div>
</div>