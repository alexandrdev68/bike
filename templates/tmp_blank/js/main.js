var ACQ = {
		response : null,
		ttn_number : null,
		repeat : false,
		amount_to_transfer : 0,
		comission_percent : 0, 
		amount_formatted : 0,
		source : 'ccard',
		shadowStart : function(){
			$('div._shadow').fadeTo('fast', 0.8).height($(document).height());
			var loader = $('div._preloader');
			var messPosX = ($('div.content').width() + $(document).scrollLeft()) / 2 - $(loader).width() / 2;
			var messPosY = ($('div.content').height() + $(document).scrollTop()) / 2 - ($(loader).height());
            $(loader).css('top', messPosY).css('left', messPosX).show();
		},
		shadowEnd : function(){
			$('div._shadow').fadeOut('fast');
			$('div._preloader').hide();
		},
		getObjLength : function(obj){
			if(typeof(obj) === "object"){
				var count = 0;
				for(var hash in obj) count++;
				return count;
			}else return false;
		},
		get_status : function(){
			autoscrolling = ACQ.repeat === true ? 'go' : 'stop';
			$('div.mist-express-progress').removeClass('hidden').show();
			$('div._payment_status').css('width', '0%');
			setTimeout(function(){
					ACQ.go_ajax({'status' : 'get_status', 'uttn' : ACQ.ttn_number}, function(response){						
						if(Number(response.step) < 700) 
							$('div._payment_status').css('width', (response.step * 20) + '%');
						if(response.step == "5") {
							ACQ.repeat = false;	
							autoscrolling = 'stop';
							ACQ.showMessage('Операція пройшла успішно');
						}else if(Number(response.step) > 700){
							ACQ.repeat = false;	
							autoscrolling = 'stop';
							ACQ.showMessage('Операція зазнала невдачі на ' + (Number(response.step) - 699) + ' кроці');
						}
					});
					if(autoscrolling == 'go'){
						setTimeout(arguments.callee, 6000);
					}
				}, 6000);
		},
		go_ajax : function(data, funct, error){
			funct = funct || function(response){
				
			};
			error = error || function(response){
				
			};
			data = data || {};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: data,
		        dataType: 'json',
		        success: function(response) {
				    if(response.status == '000' || response.status == '0'){
				    	funct(response);
			        }else if(response.status !== undefined && response.status == 'bad'){
			        	
			        }else{
			        	error(response);
			        }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		getRate : function(value, rate){
			return ((value / 100) * rate).toFixed(2);
		},
		getAmountText : function(){
			var text = [];
			var comission = Number(ACQ.getRate(ACQ.amount_to_transfer, ACQ.comission_percent));
			var amount_with_comission = ACQ.amount_to_transfer + comission;
			text[0] = (ACQ.amount_to_transfer / 100).toFixed(2);
			text[1] = '';
			if(comission != 0){
				text[0] += ' + комісія ' + ACQ.comission_percent + '%(' + (comission / 100).toFixed(2) + ' грн.)';
				text[1] = (amount_with_comission / 100).toFixed(2) + ' грн.';
			}else text[0] += ' грн.';
			return text;
		},
		fillInfoWidget : function(info, type){
			var noValue = '';
			type = type || 'paid';
			if(type == 'prepaid'){
				$('._infoWidget').removeClass('hidden').show();
				$('._toHide').hide();
			}else if(type == 'paid'){
				$('._infoWidget').removeClass('hidden').show();
				$('._toHide').show();
				if(!!info){
					$('._infoWidget td._sendNumber').text(!!info.ClientsShipmentRef ? info.ClientsShipmentRef : noValue);
					$('._infoWidget td._sender').text(!!info.SenderFIO ? info.SenderFIO : noValue);
					$('._infoWidget td._senderPhone').text(!!info.SenderTel ? info.SenderTel : noValue);
					$('._infoWidget td._recipient').text(!!info.ReceiverFIO ? info.ReceiverFIO : noValue);
					$('._infoWidget td._recipientPhone').text(!!info.ReceiverTel ? info.ReceiverTel : noValue);
				}
				
			}else if(type == 'noFind'){
				$('._infoWidget').removeClass('hidden').show();
				$('._toHide').hide();
			}else if(type == 'noArrears'){
				$('._infoWidget').removeClass('hidden').show();
				$('._toHide').hide();
			}
		},
		showMessage : function(message){
			var element = $('table._messagePayment');
			if(element.hasClass('hidden'))
				element.removeClass('hidden').show();
			$('._messageView').html(message);
		},
		hideMessage : function(){
			var element = $('table._messagePayment');
			if(!element.hasClass('hidden'))
				element.addClass('hidden').hide();
		}
};