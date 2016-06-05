<div class="modal hide _userInfoWin">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=TEMP::$Lang['user_info']?></h3>
  </div>
  <div class="modal-body">
    <div class="_userFoto user_foto">
      <img src="" alt="no foto" width="380" height="285" class="img-polaroid">
    
    </div>
    <div class="_userFullName">
      <strong><?=TEMP::$Lang['pib_table']?>:</strong> <span></span>
    </div>
    <div class="">
      <?if(USER::isAdmin()):?><div class="span5 _userLogin"><strong><?=TEMP::$Lang['login_text']?>:</strong> <span></span></div><?endif?>
      <div class="span5 _userLive"><strong><?=TEMP::$Lang['input_live_place']?>:</strong> <span></span></div>
      <div class="span5 _userWarVeterane text-success"><strong><?=TEMP::$Lang['war_veterane']?></strong> <span></span></div>
      <div class="span5 _userBlack text-warning"><h3><?=TEMP::$Lang['add_black_list']?></h3> <span></span></div>
      
    </div>
    
    <div>
      <!-- <div class="span5 _userRentBikeInfo"><strong><?=TEMP::$Lang['on_rent_text']?>:</strong> <span></span></div>
      <div class="span5 _userRentBikeTime"><strong><?=TEMP::$Lang['time_on_rent']?>:</strong> <span></span></div>-->
      <div class="span5 _useractionInfo hidden"><strong class="text-error"><?=TEMP::$Lang['txt_action_bike_for_1_hrn']?>:</strong> <span></span></div>
    </div>
    
  </div>
  <div class="modal-footer">
    <a href="#" data-dismiss="modal" class="btn"><?=TEMP::$Lang['exit_btn']?></a>
  </div>
</div>