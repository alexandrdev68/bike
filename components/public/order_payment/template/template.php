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
			      <img src="..." alt="...">
			      <div class="caption">
			        <h3>Thumbnail label</h3>
			        <p>...</p>
			        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			      </div>
			    </div>
			  </div>
		  </div>
		
		<div class="col-md-7">
		<form class="form-horizontal">
		  <div class="form-group">
		    <div class="col-xs-4">
			    <label for="InputPhone"><?=TEMP::$Lang['input_phone'].' ('.TEMP::$Lang['text_phone_template'].')'?></label>
			    <input type="text" name="phone" class="form-control" id="InputPhone" placeholder="<?=TEMP::$Lang['input_phone']?>">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-xs-5">
			    <label for="InputLastname"><?=TEMP::$Lang['input_lastName']?></label>
			    <input type="email" name="lastname" class="form-control" id="InputLastname" placeholder="<?=TEMP::$Lang['input_lastName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputFirsttname"><?=TEMP::$Lang['input_firstName']?></label>
			    <input type="email" name="firstname" class="form-control" id="InputFirstname" placeholder="<?=TEMP::$Lang['input_firstName']?>">
		    </div>
		    <div class="col-xs-5">
			    <label for="InputSecondtname"><?=TEMP::$Lang['input_patronymic']?></label>
			    <input type="email" name="secondname" class="form-control" id="InputSecondname" placeholder="<?=TEMP::$Lang['input_patronymic']?>">
		    </div>
		  </div>
		  <button type="submit" class="btn btn-default"><?=TEMP::$Lang['sms_text_4']?></button>
		</form>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEMP::$Lang['exit_btn']?></button>
        <button data-vtemplate_payment_window="value=bike_number,event=click:payment_okhandler" disabled type="button" class="btn btn-danger"><?=TEMP::$Lang['pay_booking_text']?></button>
      </div>
    </div><!-- /.modal-content -->
 
  </div>
</div>