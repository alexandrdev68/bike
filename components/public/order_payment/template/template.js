var payment_window_vtemplate = new VTemplate({
	tmpName : 'payment_window'
});



payment_window_vtemplate.eventFunctions = {
		payment_okhandler : function(event){
			console.log('okHandler');
		}
}