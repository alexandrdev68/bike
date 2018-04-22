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
      });
	console.log($('a._rent_info'));
	$('a._rent_info').on('click', function(event){
		event.preventDefault();
		
		$.ajax({
	        url: window.location,
	        type:"POST",
	        data: {'action' : 'get_user_rent_history', user_id: $(event.target).data('client_id')},
	        dataType: 'json',
	        success: function(response) {
	        	if(response.status == 'ok'){
	        		var html = '<table class="table-striped"><thead><tr><th>Номер</th><th>Велосипед</th><th>Дата прокату(старт)</th><th>Тривалість прокату</th><th>Вартість прокату</th></tr></thead><tbody>';
	        		for(var index = 0; index < response.rent_list.length; index++){
	        			
	        			html += '<tr><td>' + (index + 1) + '</td>';
	        			html += '<td>' + response.rent_list[index].model + ' (' + response.rent_list[index].serial_id + ')</td>';
	        			html += '<td>' + response.rent_list[index].time_start + '</td>';
	        			html += '<td>' + response.rent_list[index].rent_period + '</td>';
	        			html += '<td>' + response.rent_list[index].amount + 'грн. </td>';
	        			
	        		}
	        		html += '</tbody></table>';
	        		html += '<b>Всього: <i>' + response.rent_summ + ' грн.</i></b>';
	        		$('div.rent_info_container').html(html);
	        	}
	        },
	        error: function(error){
	        	console.log(error);
	        }
	        		
		});
	});
	$('div._userInfoWin').on('hide', function(){
		$('div._main_foto img, div._extra_foto img').attr('src', ' ').hide();
		clearInterval(user.userInfoInterval);
		$('div._userRentBikeTime span').text('');
		$('._extend_info span').text('');
		$('._rent_info').data('client_id', '');
		$('div.rent_info_container').html('');
	});
	

}
</script>