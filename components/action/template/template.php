<div class="modal fade" id="action_modal_window">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
        <div class="_actionSMSCODE alert alert-danger">
			<button class="close"  type="button">×</button>
			<strong><?=TEMP::$Lang['warning']?>!</strong>
			<span class="_messtext"></span>
		</div>
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


	<div class="col-md-6 col-md-offset-3"><h3 class="text-center"><?=TEMP::$Lang['txt_leaders_table']?></h3></div>
	<div class="col-md-10 col-md-offset-1 _leadrsContainer"></div>

