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

var public_page_template = new VTemplate({
	tmpName : 'public_page'
});

public_page_template.eventFunctions = {
		order_button_handler : function(event){
			if(event.target.nodeName == 'BUTTON'){
				payment_window_vtemplate.render({
					bike_number : event.target.dataset.value
				});
				console.log('show modal')
				$('div._payment_window').modal('show');
			}
		}
}

function public_init(){

	$('.bike_foto_magnific').magnificPopup({
		type:'image',
		mainClass: 'mfp-with-zoom', // this class is for CSS animation below

		  zoom: {
		    enabled: true, // By default it's false, so don't forget to enable it

		    duration: 300, // duration of the effect, in milliseconds
		    easing: 'ease-in-out', // CSS transition easing function

		    // The "opener" function should return the element from which popup will be zoomed in
		    // and to which popup will be scaled down
		    // By defailt it looks for an image tag:
		    opener: function(openerElement) {
		      // openerElement is the element on which popup was initialized, in this case its <a> tag
		      // you don't need to add "opener" option if this code matches your needs, it's defailt one.
		      return openerElement.is('img') ? openerElement : openerElement.find('img');
		    }
		  }
	});
			
}