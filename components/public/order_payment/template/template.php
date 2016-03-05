<div class="modal fade bs-example-modal-lg _payment_window" tabindex="-1" role="dialog" aria-labelledby="orderWindowLabel">
  <div class="modal-dialog modal-lg">
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?=TEMP::$Lang['payment_window_header_text']?></h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=TEMP::$Lang['exit_btn']?></button>
        <button data-vtemplate_payment_window="value=bike_number,event=click:payment_okhandler" type="button" class="btn btn-danger"><?=TEMP::$Lang['pay_booking_text']?></button>
      </div>
    </div><!-- /.modal-content -->
 
  </div>
</div>