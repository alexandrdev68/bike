<script data-bike="<?=IDENTJS?>">
$(document).ready(userInfo_init);

function updateTimeOnUserInfo(){
	//console.log(bike.rentList.length);
	var time_start = $('div._userRentBikeTime').data('time_start');
	try{
		if(time_start == 'no') return false;
			var now = $('div._userRentBikeTime').data('now');
			$('div._userRentBikeTime').data('now', now + 1000).find('span').text(bike.getTimeString(new Date(now - time_start), ':'));
		
	}catch(error){
		//console.log(error);
	}
	
}

function userInfo_init(){
	$('#myCarousel').carousel({
        interval: false
      })
	$('div._userInfoWin').on('hide', function(){
		$('div._main_foto img, div._extra_foto img').attr('src', ' ').hide();
		clearInterval(user.userInfoInterval);
		$('div._userRentBikeTime span').text('');
	});
	

}
</script>