<script>
$(document).ready(public_init);

function get_max_height(elements){

	var max = 0;
	var h = 0;
	for(var num in elements){
		if(num == 'length'){
			return max;
		}
		h = $(elements[num]).height();
		if(h > max) max = h;
	}
	return max;
}

function public_init(){

	$('.bike_foto_magnific').magnificPopup({type:'image'});
			
}
</script>