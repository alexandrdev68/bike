<div class="modal hide fade _finalInfoModalWin">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4><?=TEMP::$Lang['close_rent_info']?></h4>
  </div>
  <div class="modal-body">
    <div class="row-fluid _closeUserInfo">
      <div class="span12 text-center _closeUserHeaderText"><h5><p class="text-warning"><?=TEMP::$Lang['user_info']?>:</p></h5></div>
      <div class="span3 pull-left _closeFullName"><h5><?=TEMP::$Lang['pib_table']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeLogin"><h5><?=TEMP::$Lang['login_text']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeUserPhone"><h5><?=TEMP::$Lang['input_phone']?>:</h5> <span></span></div>
    </div>

    <div class="row-fluid _closeBikeInfo">
      <div class="span12 text-center _closeBikeHeaderText"><h5><p class="text-warning"><?=TEMP::$Lang['info_about_bike']?>:</p></h5></div>
      <div class="span3 pull-left _closeBikeModel"><h5><?=TEMP::$Lang['model_col']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeBikeSerial"><h5><?=TEMP::$Lang['serial_id']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeBikeNumber"><h5><?=TEMP::$Lang['bike_number']?>:</h5> <span></span></div>
    </div>
    
    <div class="row-fluid _closeRentInfo">
      <div class="span12 text-center _closeBikeHeaderText"><h5><p class="text-warning"><?=TEMP::$Lang['info_about_rent']?>:</p></h5></div>
      <div class="span3 pull-left _closeRentPaid"><h5><?=TEMP::$Lang['paid_rent']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeRentFact"><h5><?=TEMP::$Lang['fact_rent_time']?>:</h5> <span></span></div>
      <div class="span3 pull-left _closeRentAmount"><h5><?=TEMP::$Lang['rent_amount']?>:</h5> <span></span> <?=TEMP::$Lang['currency']?>. <button class="btn btn-danger _factRecalc"><?=TEMP::$Lang['fact_recalc_btn']?></button></div>
    </div>
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn"><?=TEMP::$Lang['exit_btn']?></a>
  </div>
</div>