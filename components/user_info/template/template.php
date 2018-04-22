<style>

	.rent_info_container {
		overflow:auto;
		max-height:25vh;
	}
	
	.special_height {
		max-height:70vh;
	}

</style>
<div class="modal hide _userInfoWin">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?=TEMP::$Lang['user_info']?></h3><small class="span5 _extend_info text-warning"><span></span></small>
  </div>
  <div class="underline"></div>
  <div class="modal-body special_height">
    
	<div id="myCarousel" data-interval="false" class="carousel slide">
		<!-- <ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
		</ol> -->
		<!-- Carousel items -->
		<div class="carousel-inner">
			<div class="active item _main_foto">
				<img alt="no foto" src="">
				<div class="carousel-caption custom_carusel_opacity">
					<h4><?=TEMP::$Lang['main_photo']?></h4>
					<p></p>
				</div>
			</div>
			<div class="_extra_foto item">
				<img alt="no foto" src="">
				<div class="carousel-caption custom_carusel_opacity">
					<h4><?=TEMP::$Lang['extra_photo']?></h4>
					<p></p>
				</div>
			</div>
		</div>
		<!-- Carousel nav -->
		<a class="carousel-control _user_info_buttons left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control _user_info_buttons right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>
    
    <!-- <div class="_userFoto user_foto">
      <img src="" alt="no foto" width="380" height="285" class="img-polaroid">
    
    </div> -->
    <div class="_userFullName">
      <strong><?=TEMP::$Lang['pib_table']?>:</strong> <span></span>
    </div>
    <div class="">
      <?if(USER::isAdmin()):?><div class="span5 _userLogin"><strong><?=TEMP::$Lang['login_text']?>:</strong> <span></span></div><?endif?>
      <div class="span5 _userLive"><strong><?=TEMP::$Lang['input_live_place']?>:</strong> <span></span></div>
      <div class="span5 _userWarVeterane text-success"><strong><?=TEMP::$Lang['war_veterane']?></strong> <span></span></div>
      <div class="span5 _userBlack text-warning"><h3><?=TEMP::$Lang['add_black_list']?></h3> <span></span></div>
      <div class="underline"></div>
      <a href="#rent_info" class="_rent_info">Інформація про прокат</a>
    </div>
    <div class="underline"></div>
    <div class="rent_info_container">
    	
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