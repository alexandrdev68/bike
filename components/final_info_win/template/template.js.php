<script>
$(document).ready(final_info_init);

var fact_time = 0;
var rent_id = null;
var added = 0;

function final_info_init(){
	$('div._finalInfoModalWin').on('hide', function(){
		$(this).find('span').text('');
	});

	
	$('div._finalInfoModalWin').on('show', function(){
		fact_time = bike.stoppedFullInfo.stopTime - bike.stoppedFullInfo.time_start;
		rent_id = bike.stoppedFullInfo.rent_id;
		added = bike.stoppedFullInfo.rent_prop.added;
		$('div._closeFullName span').text(bike.stoppedFullInfo.name + ' ' + bike.stoppedFullInfo.surname + ' ' + bike.stoppedFullInfo.patronymic);
		$('div._closeLogin span').text(bike.stoppedFullInfo.login);
		$('div._closeUserPhone span').text(bike.stoppedFullInfo.phone);
		$('div._closeBikeModel span').text(bike.stoppedFullInfo.model);
		$('div._closeBikeSerial span').text(bike.stoppedFullInfo.serial_id);
		$('div._closeBikeNumber span').text(bike.stoppedFullInfo.bike_id);
		$('div._closeRentPaid span').text(bike.getTimeString(new Date(bike.stoppedFullInfo.project_time * 1000), ':'));
		$('div._closeRentFact span').text(bike.getTimeString(new Date(fact_time * 1000), ':'));
		$('div._closeRentAmount span').text(bike.stoppedFullInfo.rent_amount);
		if((bike.stoppedFullInfo.project_time > fact_time) && (bike.stoppedFullInfo.project_time < 86400)) $('button._factRecalc').show();
		else $('button._factRecalc').hide();
	});

	$('button._factRecalc').click(function(){
		bike.recalcFact(fact_time, rent_id, added, function(response){
			$('div._closeRentAmount span').text(response.amount / 100);
			$('button._factRecalc').fadeOut('fast');
		})
	});
}
</script>