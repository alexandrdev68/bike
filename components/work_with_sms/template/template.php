<div class="row-fluid disabled _smsManage">
	
	<div class="tab-pane" id="_usSMSListPage">
		<div class="navbar _usersSMSPaging">
			<div class="navbar-inner text-center">
				<div class="pagination _usersSMSNavChain" style="margin:5px 0 0;">
				  <ul>
				    <li><a href="#">Prev</a></li>
				    <li class="disabled"><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
				    <li><a href="#">3</a></li>
				    <li><a href="#">4</a></li>
				    <li><a href="#">5</a></li>
				    <li><a href="#">Next</a></li>
				  </ul>
				</div>
			</div>
		</div>
		<div class="usSMSListContainer _usSMSContnr">
			<table class="table table-striped _usSMSListTable">
				<tr>
					<th><?=TEMP::$Lang['pos_number']?></th>
					<th>UID</th>
					<th>Login</th>
					<th><?=TEMP::$Lang['pib_table']?></th>
					<th><?=TEMP::$Lang['input_phone']?></th>
					<th><?=TEMP::$Lang['input_level']?></th>
					<th></th>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav">
				<li class="">
					<div class="btn-group dropup _selectallsmsbtn">
						<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
						    <?=TEMP::$Lang['send_sms_all_btn']?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="#all_not_action"><?=TEMP::$Lang['sms_text_5']?></a></li>
						</ul>
					</div>
				</li>
				<li class="divider-vertical"></li>
				<li class="" style="margin-bottom: 5px;">
					<button class="btn btn-danger _sendsmsbtn"><?=TEMP::$Lang['send_sms_selected']?> <i class=" icon-envelope"></i></button>
				</li>
			</ul>
				
			<div class="progress progress-success _sms_progressbar span4" style="display:none;margin:5px">
				<div class="bar" style="width: 0%;"></div>
			</div>
				
		</div>
	</div>
	
	<div class="modal hide fade _sendSMSResselerModal">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3><?=TEMP::$Lang['sms_resseler_text_header']?></h3>
	  </div>
	  <div class="modal-body">
	    <p class="text-error"><?=TEMP::$Lang['sms_text_1']?><span class="_smsCount"></span> <?=TEMP::$Lang['sms_text_2']?></p>
	    <p><label><?=TEMP::$Lang['sms_text_field']?></label><textarea name="sms_text" rows="3"></textarea></p>
	    <p><input name="translit" type="checkbox" checked value="on"> <?=TEMP::$Lang['sms_text_3']?></p>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn _closeSMSModal"><?=TEMP::$Lang['exit_btn']?></a>
	    <a href="#" class="btn btn-primary _sendSMSResselerProcess"><?=TEMP::$Lang['sms_text_4']?></a>
	  </div>
	</div>
</div>