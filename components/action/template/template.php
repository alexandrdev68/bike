<div class="modal fade" id="action_modal_window">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
        <h4 class="modal-title"><?=TEMP::$Lang['txt_action']?></h4>
      </div>
      <div class="modal-body">
        <form role="form" name="action_confirm">
			<div class="form-group">
			    <label for="smsCode"><?=TEMP::$Lang['txt_sms_code']?></label>
			    <input type="text" class="form-control" id="smsCode" name="sms_code" placeholder="<?=TEMP::$Lang['txt_sms_code']?>">
			    <input type="hidden" name="action" value="find_action_user">
		  	</div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary _action_confirm_btn"><?=TEMP::$Lang['accept_btn']?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<h1>content</h1>
	</div>
</div>