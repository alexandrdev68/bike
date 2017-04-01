var bike = {
		currentList : {},
		currId : 0,
		rentList : {},
		storeId : null,
		stoppedFullInfo : {},
		reportList : [],
		reportCounter : null,
		reportIds : [],
		logout : function(){
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'logout'},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                window.location.href = '/';
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		    });
		},
		getList : function(from){
			from = from || {
				from_bike_id : 0,
				filter : 'in_store',
				onListResponse : function(){
					
				}
			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_bikes_list_store', 'from_bike_id' : from.from_bike_id, 'filter' : from.filter},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                bike.currentList = response.bikes_list;
		                from.onListResponse();
		                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		findInList : function(id, className, data_name, funct, clickElement){
			clickElement = clickElement || 'td:last-child i';
			id = id || bike.currId;
			funct = funct || function(){return false;};
			$('table.' + className + ' tr').each(function(num){
				//console.log(num + ' - ' + id + ' = ' + $(this).find('td:last-child i').data(String(data_name)));
				if($(this).find(clickElement).data(String(data_name)) == id){
					funct(num);
				}
			});
			//return false;
		},
		del : function(id){
			id = id || bike.currId;
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'bike_delete', 'bid' : id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var userList = $('table._bkListTable tr');
		                user.findInList(id, '_bkListTable', 'bikeid', function(del_num){
			                $(userList[del_num]).fadeOut('slow', function(){
			                	$(userList[del_num]).detach();
			                });
		                });
		                bike.showMainAlert(response.mess);
		                
		            }else if(response.status == 'bad'){
		            	bike.showMainAlert(response.mess, 'error');
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		getDateString : function(date, divider){
			var month = date.getMonth() + 1;
			var day = date.getDate();
			var year = date.getFullYear();
			return month + divider + day + divider + year;
		},
		getDays : function(difference){
			return Math.floor(difference / (1000 * 60 * 60 * 24));
		},
		getTimeString : function(date, divider){
			var days = bike.getDays(date.getTime());
			var hours = date.getUTCHours();
			var minutes = date.getMinutes();
			var seconds = date.getSeconds();
			return (days == 0 ? ' ' : days + ' д. ') + hours + divider + (minutes < 10 ? '0' + minutes : minutes) + divider + (seconds < 10 ? '0' + seconds : seconds);
		},
		getLocalTimeNow : function(){
			var now = new Date();
			return now.getTime();
		},
		stopRent : function(){
			$('div._storeSelect').hide();
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'stop_rent', 'bike_id' : bike.currId, 'store_id' : bike.storeId, 'user_id' : user.currId},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var bikeList = $('table._bkListTableRent tr');
		                bike.findInList(bike.currId, '_bkListTableRent', 'bikeid', function(del_num){
			                //console.log(del_num);
		                	$(bikeList[del_num]).fadeOut('slow', function(){
			                	$(bikeList[del_num]).detach();
			                });
		                }, '._closeRent');
		                bike.storeId = user_prop === null ? null : user_prop.store;


		                bike.stoppedFullInfo = response.fullInfo;
		                bike.stoppedFullInfo.stopTime = response.stopTime;
		                bike.stoppedFullInfo.rent_amount = response.rent_amount;
		                $('div._finalInfoModalWin').modal('show');
		                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		sessionStopped : function(){
			window.location.href = '/';
		},
		showMainAlert : function(text, error, attent, wait){
			wait = wait || 3000;
			error = error || 'no';
			attent = attent || 'Warning!';
			if(error == 'error'){
				$('div._mainWinAlert').addClass('alert-error');
			}else{
				$('div._mainWinAlert').removeClass('alert-error');
			}
			$('div._mainWinAlert strong').text(attent);
			$('div._mainWinAlert span._messtext').text(text);
			$('div._mainWinAlert').slideDown('medium').delay(wait).slideUp('medium');
		},
		getStores : function(funct){
			funct = funct || function(data){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_stores'},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response.stores);                
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		editTable : function(table){
			var tdvalue = '';
			for(var num = 0; num < table.length; num++){
				var fields = $(table[num]).find('td');
				for(var n = 0; n < fields.length; n++){
					if($(fields[n]).data('input_type') !== undefined){
						tdvalue = $(fields[n]).text();
						$(fields[n]).html('<input type="' + $(fields[n]).data('input_type') + '" value="' + tdvalue + '">');
						$(fields[n]).find('input[type="text"]').focusout(function(){
							var value = $(this).find('input[type="text"]').val();
							$(this).find('input[type="text"]').parent().html(value);
						});
					}
				}
			}
		},
		acceptStore : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'accept_stores', 'accepted': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		deleteStore : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'delete_stores', 'deleted': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		getBikeById : function(id, funct, isPublic){
			funct = funct || function(response){
				
			};
			
			if(isPublic === undefined)
				isPublic = false;
			
			sendData = {
					'action' : 'get_bike_by_id', 
					'bike_id': id
			}
			
			if(isPublic){
				sendData.ispublic = true;
				sendData.action = 'get_bike_by_id_public';
			}
				
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: sendData,
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	if(!isPublic)
		            		bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		dayReport : function(storeId, funct){
			storeId = storeId || 'no';
			funct = funct || function(response){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'day_report', 'store_id' : storeId},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		periodReport : function(from, to, storeId, funct){
			storeId = storeId || 'no';
			funct = funct || function(response){

			};
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'period_report', 'store_id' : storeId, 'from' : from , 'to' : to},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		cancelRents : function(table, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'cancel_rents', 'cancel': table},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		numberFormat : function(number){
			number = number.toString();
			if(!/(^[-0-9]{1,}[\.\,]{0,1}[0-9]{0,}$)/.test(number)) return false;
			number = number.split(',').join('.');
			var arNum = number.split('.');
			var numLen = arNum[0].length;
			if(numLen <= 1) return number;
			var numFormatted = [];
			var sep = 3;
			var inc = Math.ceil(numLen / 3 - 1);
			var g = numLen - 1 + inc;
			for(var i = g; i >= 0; i--){
				numFormatted[g] = arNum[0][i - inc];
				sep--;
				if(sep == 0){
					g--;
					numFormatted[g] = ' ';
					sep = 3;
				}
				g--;
			}
			numFormatted = numFormatted.join('');
			return numFormatted += arNum.length == 1 ? '.00' : '.' + arNum[1]; 
		},
		recalcFact : function(fact_time, rent_id, added, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'recalc_fact', 'fact_time' : fact_time, 'rent_id' : rent_id, 'added' : added},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		},
		toType : function(type, value){
			switch(type){
			case 'string' :
				return String(value);
				break;
			case 'integer' :
				return parseInt(value);
				break;
			case 'float' :
				return parseFloat(value);
				break;
			default:
				return value;
			}
		},
		sortArray : function(prod, direct){
			    akk = [];//массив для хранения промежуточных значений
			    for(var c = 0; c < prod.length; c++){
			        akk[0] = bike.toType('integer', prod[c]['amount_source']);
			        //console.log(akk[0]);
			        findtiny = false;//триггер нахождения самого маленького числа в массиве (true, если найдено, по умолчанию - false)
			        for(var i = c; i < prod.length; i++){
			            akk[2] = bike.toType('integer', prod[i]['amount_source']);
			            if(direct){ //если по возрастанию то...
			                if(akk[2] < akk[0]){
			                    findtiny = true;
			                    akk[0] = akk[2];
			                    akk[1] = i;
			                };
			            }else{ //по убыванию...
			                if(akk[2] > akk[0]){
			                    findtiny = true;
			                    akk[0] = akk[2];
			                    akk[1] = i;
			                };
			            }
			        };
			        if(findtiny){
			            a1 = akk[1];
			            akk[3] = prod[c];
			            prod[c] = prod[a1];
			            prod[a1] = akk[3];
			        }
			    };
			    return prod;
			
		},
		buildNavChain : function(params){
			params = params || {};
			params.target = params.target || 'body';
			params.chain = params.chain || {1 : 'curr', 2:2, 3:3, 4:4, 5:5, 6:6, 7:7, 8:8, 9:'>', current:1};
			params.onPageChange = params.onPageChange || function(page){
				
			};
			$(params.target + ' ul li').detach();
			var elChain = '';
			var elAppend = $(params.target + ' ul');
			for(var num in params.chain){
				switch(params.chain[num]){
					case 'curr':
						elChain = '<li class="active"><a data-page="' + params.chain['current'] + '" href="#">' + params.chain['current'] + '</a></li>';
						break;
					case '<':
						elChain = '<li class="disabled"><a data-page="' + (params.chain['current'] - 1) + '" href="#"> \< </a></li>';
						break;
					case '>':
						elChain = '<li class="disabled"><a data-page="' + (params.chain['current'] + 1) + '" href="#"> \> </a></li>';
						break;
					default :
						elChain = '<li class="disabled"><a data-page="' + (params.chain[num]) + '" href="#">' + params.chain[num] + '</a></li>';
						break;
				}
				if(num != 'current') elAppend.append(elChain);
			}
			$(params.target + ' ul li a').on('click', function(event){
				event.preventDefault();
				var clickedPage = $(this).data('page');
				if(params.chain.current == clickedPage) return false;
				params.onPageChange(clickedPage);
			});
		},
		actions_fill : function(offset){
			actions_report.send({
				data : {action : 'get_actions_list', from_user_offset : 0}
			});
		}
};

var user = {
		currentList : {},
		navChain : {},
		interval : 400,
		currId : 0,
		keypressflag : false,
		userInfoInterval : null,
		currentCoordinates : 0,
		keypressedInterval : 400,
		keyIntevalId : null,
		addUserConfirm : false,
		getUsersList : function (from){
			from = from || {
					from_user_id : 0,
					onListResponse : function(){

					}
				};	
			$.ajax({
				        url: window.location,
				        type:"POST",
				        data: {'action' : 'get_users_list', 'from_user_id' : from.from_user_id},
				        dataType: 'json',
				        success: function(response) {
				        	if(response.status == 'ok'){
				                user.currentList = response.users_list;
				                user.navChain = response.nav;
				                from.onListResponse();
				                
				            }else if(response.status == 'session_close'){
		            			bike.sessionStopped();
		            		}else{
				            	
				            }
				        },
				        error: function(response){
				        	
				        }
				});
		},
		cookie : {
				set : function(name, value, mins) {
			        if (mins) {
			            var date = new Date();
			            date.setTime(date.getTime() + (mins * 60 * 1000));
			            var expires = "; expires=" + date.toGMTString();
			        }
			        else var expires = "";
			        document.cookie = name + "=" + value + expires + "; path=/";
			    },
			    get : function(c_name) {
			        if (document.cookie.length > 0) {
			            c_start = document.cookie.indexOf(c_name + "=");
			            if (c_start != -1) {
			                c_start = c_start + c_name.length + 1;
			                c_end = document.cookie.indexOf(";", c_start);
			                if (c_end == -1) {
			                    c_end = document.cookie.length;
			                }
			                return unescape(document.cookie.substring(c_start, c_end));
			            }
			        }
			        return "";
			    },
			    del : function(name){
			    	user.cookie.set(name, '', -1);
			    }
		},
		findLoader : function(oper){
			var loader = $('div._findLoader');
			var container = $('div._findList');
			if(oper == 'show'){
				loader.css('top', container.height() / 2).css('left', container.width() / 2 - 102).show();
				$('div._findShadow').show();
			}else if(oper == 'hide'){
				loader.hide();
				$('div._findShadow').hide();
			}
		},
		findInList : function(id, className, data_name, funct){
			id = id || user.currId;
			funct = funct || function(){return false;};
			$('table.' + className + ' tr').each(function(num){
				//console.log(num + ' - ' + id + ' = ' + $(this).find('td:last-child i').data(String(data_name)));
				if($(this).find('td:last-child i').data(String(data_name)) == id){
					funct(num);
				}
			});
			//return false;
		},
		del : function(id){
			id = id || user.currId;
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'user_delete', 'uid' : id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                var userList = $('table._usListTable tr');
		                user.findInList(id, '_usListTable', 'userid', function(del_num){
			                $(userList[del_num]).fadeOut('slow', function(){
			                	$(userList[del_num]).detach();
			                });
		                });
		                bike.showMainAlert(response.mess);
		                
		            }else if(response.status == 'bad'){
		            	bike.showMainAlert(response.mess, 'error');
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
		},
		find : function(key){
			key = key || {
					word : '',
					onFind : function(finded){

					},
					maxLength : 3,
					responseEnd : function(){
						
					}
				};
			if(key.word.length >= key.maxLength){
				$.ajax({
					url: window.location,
					type:"POST",
					data: {'action' : 'find_user', 'key' : key.word},
					dataType: 'json',
					success: function(response) {
						try{
							key.responseEnd();
						}
						catch (err){
							//console.log(err);
						}
						if(response.status == 'ok'){
					        key.onFind(response.find);
					    }else if(response.status == 'session_close'){
		            		bike.sessionStopped();
		            	}
					},
					error: function(response){
						console.log('error');
					}
				});
			}else return false;
		},
		showInfo : function(klient_id){
			klient_id = klient_id || user.currId;
			//console.log(klient_id);
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_user_info', 'klient_id' : klient_id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                $('div._userFullName span').text(response['info'].name + ' ' + response['info'].surname + ' ' + response['info'].patronymic);
		                $('#myCarousel * ').removeClass('active');
		                $('#myCarousel div._main_foto').addClass('active');
		                $('div._main_foto img').attr('src', response['info'].photo).show();
		                if(!!response['info']['extra_photo'] && !!response['info']['extra_photo'][1]){
		                	$('a._user_info_buttons').show();
		                	$('div._extra_foto img').attr('src', response['info']['extra_photo'][1]).show();
		                }else{
		                	$('a._user_info_buttons').hide();
		                }
		                $('div._userLogin span').text(response['info'].login);
		                $('div._userLive span').text(response['info'].properties === null ? '---' : response['info'].properties.live_place === undefined ? '---' : response['info'].properties.live_place);
		                $('div._userRentBikeInfo span').text(response['info'].bike_id === null ? '---' : response['info'].model + ' Ser.No:' + response['info'].serial_id + ' No:' + response['info'].bike_id);
		                $('div._userRentBikeTime').data('now', response['info'].now * 1000).data('time_start', response['info'].bike_id === null ? 'no' : response['info'].time_start * 1000);
		                if(!!response.info.action_klient && response.info.action_klient !== null) $('div._useractionInfo').removeClass('hidden');
		                else{
		                	if(!$('div._useractionInfo').hasClass('hidden')) $('div._useractionInfo').addClass('hidden');
		                }
		                if(response['info'].properties !== null && response['info'].properties.blackList == 'on') $('div._userBlack').show();
		                else $('div._userBlack').hide();
		                if(response['info'].properties !== null && response['info'].properties.war_veterane == 'yes') $('div._userWarVeterane').show();
		                else $('div._userWarVeterane').hide();
		                $('div._userInfoWin').modal('show');
		                user.userInfoInterval = setInterval(updateTimeOnUserInfo, 1000);
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	
		            }
		        },
		        error: function(response){
		        	
		        }
		});
			
		},
		getById : function(id, funct){
			funct = funct || function(response){
				
			};
			
			$.ajax({
		        url: window.location,
		        type:"POST",
		        data: {'action' : 'get_user_info', 'klient_id': id},
		        dataType: 'json',
		        success: function(response) {
		        	if(response.status == 'ok'){
		                funct(response);              
		            }else if(response.status == 'session_close'){
		            	bike.sessionStopped();
		            }else{
		            	bike.showMainAlert(response.message, 'error');
		            }
		        },
		        error: function(response){
		        	
		        }
			});
		}
};

function tableFromData(params){
    params = params || {};
    if(params.head !== undefined) this.head = params.head;
    if(params.content !== undefined) this.content = params.content;
    else this.content = {};
    if(params.classes !== undefined) this.classes = params.classes;
    else this.classes = '';
    this.counter = false;
    if(params.counter !== undefined) this.counter = params.counter;
    this.rowNum = 0;
    this.table = '';
    this.cellTemp = '';
    
    this.fill = function(data){
        data = data || {};
        this.table = '';
        if(data.length == 0) return false;
        tableFromData.createHead(this);
        for(var d in data){
            this.table += '<tr>';
            if(this.counter){
                this.rowNum++;
                this.table += '<td>' + this.rowNum + '</td>';
            }
            for(var v in this.head){
                if(!!this.content[v]){
                	this.cellTemp = this.content[v].split('#$#');
                	this.cellTemp = this.cellTemp[0] + 
                					(data[d][v] === undefined ? '' : data[d][v]) + 
                					this.cellTemp[1];
                }else{
                	this.cellTemp = (data[d][v] === undefined ? '' : data[d][v]);
                }
            	this.table += '<td>' + this.cellTemp + '</td>';
            }
            this.table += '</tr>';
        }
        this.table += '</tbody></table>';
        this.rowNum = 0;
    };
    
    tableFromData.createHead = function(me){
        me.table = '<table class="' + me.classes + '"><tbody><tr>';
        if(me.counter) me.table += '<th>№</th>';
        for(var v in me.head){
            me.table += '<th>' + me.head[v] + '</th>';
        };
        me.table += '</tr>';
    };
};

function serverRequest(params){
	params = params || {};
    this.type = params.type || 'POST';
    this.events = params.events || 'on';
    this.url = params.url || window.location.href;
    this.query = params.query || '';
    this.data = params.data || '';
    if(params.traditional !== undefined) this.traditional = params.traditional;
    else this.traditional = false;
    if(params.queryDataFormat !== undefined) this.queryDataFormat = params.queryDataFormat;
    else this.queryDataFormat = 'url';
    if(params.processData !== undefined) this.processData = params.processData;
    else this.processData = false;
    this.need_auth = (params.need_auth !== undefined ? params.need_auth : false);
    if(params.contentType !== undefined) this.contentType = params.contentType;
    else this.contentType = false;
    
    if(params.success !== undefined) this.success = params.success;
    else{
    	this.success = function(response){
    		if(response.status == 'ok'){
               
            }else if(response.status == 'session_close'){
            	bike.sessionStopped();
            }else{
            	
            }
	    };
    }
    
    this.error = function(response){
    	//ajax error handler
		
    	console.log('Error on server, try again later');
    };
    
    if(params.error !== undefined) this.error = params.error;
    
    
    
    this.send = function(sparams){
        var self = this;
        sparams = sparams || {};
        if(sparams.type !== undefined) self.type = sparams.type;
        if(sparams.events !== undefined) self.events = sparams.events;
        if(sparams.url !== undefined) self.url = sparams.url;
        if(sparams.data !== undefined) self.data = sparams.data;
        if(sparams.query !== undefined) self.query = sparams.query;
        if(sparams.error !== undefined) self.error = sparams.error;
        if(sparams.success !== undefined) self.success = sparams.success;
        if(sparams.contentType !== undefined) self.contentType = sparams.contentType;
        if((this.queryDataFormat == 'json' || this.queryDataFormat == 'JSON') && self.data !== '') self.data = $.toJSON(self.data);
        var ajax_params = {
    		url: self.url + self.query,
            type: self.type,
            data: self.data,
            dataType : 'json',
            processData : true,
            success: function(response) {
            	self.success(response);
            },
            error: function(response){
            	self.error(response);
            }
        };
        
        if(this.contentType !== false) ajax_params.contentType = this.contentType;
        
        jQuery.ajax(ajax_params);
    };

};

function VTemplate(params){
	params = params || {};
	
	if(!!params.tmpName)
		this.tmpName = params.tmpName;
	else
		return false;
	
	if(!!params.functions)
		this.functions = params.functions;
	
	if(!!params.eventFunctions)
		this.eventFunctions = params.eventFunctions;
	
	VTemplate.prototype.addTextNode = function(element, text){
		var textNode = document.createTextNode(text);
		element.appendChild(textNode);
	};
	
	VTemplate.prototype.textNode = function(element, text){
		if(element.childNodes.length > 0){
			for(var i = 0; i < element.childNodes.length; i++)
				element.removeChild(element.childNodes[i]);
		}
		element.appendChild(document.createTextNode(text));
	};
	
	
	if (document.addEventListener) {
		
		VTemplate.prototype.addEvent = function(elem, type, handler) {
			elem.addEventListener(type, handler, false)
		}
		VTemplate.prototype.removeEvent = function(elem, type, handler) {
			elem.removeEventListener(type, handler, false)
		}
	}else{
		VTemplate.prototype.addEvent = function(elem, type, handler) {
			elem.attachEvent("on" + type, handler)
		}
		VTemplate.prototype.removeEvent = function(elem, type, handler) {
			elem.detachEvent("on" + type, handler)
		}
	}
	
	this.workElement = {};
	this.debugMode = true;
	
	this.afterRender = params.afterRender || function(tempElements){
		
	};
	
	this.init = function(){
		var self = this;
		this.addEvent(window, 'load', function(){self.eventRender.call(self)});
	}
	
	VTemplate.prototype.eventRender = function(element){
		element = element || null;
		var self = this;
		if(element !== null){
			if(element.length === undefined)
				element = [element];
			var tempElements = element;
		}else{
			var tempElements = document.querySelectorAll('[data-vtemplate_' + self.tmpName + ']');
			if(tempElements.length == 0)
				return false;
		}
		var tmpSplit = [];
		var index = 'vtemplate_' + self.tmpName;
		var target = '';
		var targetVariable = '';
		for(var num = 0; num < tempElements.length; num++){
			dataValue = tempElements[num].dataset[index];
			operationSplit = dataValue.split(',');
			for(var op = 0; op < operationSplit.length; op++){
				tmpSplit = operationSplit[op].split('=', 2);
				target = tmpSplit[0];
				targetVariable = tmpSplit[1];
				switch(target){
					case 'event'://event=click:order_click
						var fSplit = targetVariable.split(':', 2);
						if(typeof(self.eventFunctions[fSplit[1]]) != 'function'){
							if(self.debugMode)
								console.log('function: "' + fSplit[1] + '" not set in ' + tempElements[num].outerHTML);
							continue;
						}
						self.addEvent(tempElements[num], fSplit[0], self.eventFunctions[fSplit[1]]);
						break;
				}
			}
			
		}
		self.afterRender(tempElements);
	}
	
	VTemplate.prototype.render = function(data, element){
		element = element || null;
		var self = this;
		if(!!!data){
			if(self.debugMode)
				console.log('data is not defined in template render function. template name: ' + self.tmpName);
			return false;
		}
		if(element !== null){
			if(element.length === undefined)
				element = [element];
			var tempElements = element;
		}else{
			var tempElements = document.querySelectorAll('[data-vtemplate_' + self.tmpName + ']');
			if(tempElements.length == 0)
				return false;
		}
		var tmpSplit = [];
		var dataValue = '';
		var index = 'vtemplate_' + self.tmpName;
		var target = '';
		var targetVariable = '';
		//console.log(tempElements);
		for(var num = 0; num < tempElements.length; num++){
			
			dataValue = tempElements[num].dataset[index];
			operationSplit = dataValue.split(',');
			for(var op = 0; op < operationSplit.length; op++){
				tmpSplit = operationSplit[op].split('=', 2);
				target = tmpSplit[0];
				targetVariable = tmpSplit[1];
				if(target != 'function' && target != 'event'){
					
					targetVariable = 'data.' + targetVariable;
					try{
						if(!!!eval(targetVariable))
							continue;
					
						targetVariable = eval(targetVariable);
					}catch(err){
						if(self.debugMode)
							console.log(err);
						continue;
					}
					
				}
				
				switch(target){
					case 'text':
						self.textNode(tempElements[num], targetVariable);
						break;
					case 'value':
						tempElements[num].value = targetVariable;
						break;
					case 'src':
						tempElements[num].setAttribute('src', targetVariable);
						break;
					case 'function':
						var fSplit = targetVariable.split(':', 2);
						if(fSplit[1] == '*'){
							fSplit[1] = 'data';
						}else{
							fSplit[1] = 'data.' + fSplit[1];
						}
						if(!!!eval(fSplit[1]))
							continue;
						fSplit[1] = eval(fSplit[1]);
						if(typeof(self.functions[fSplit[0]]) != 'function'){
							if(self.debugMode)
								console.log('function: "' + fSplit[0] + '" not set in ' + tempElements[num].outerHTML);
							continue;
						}
						self.workElement = tempElements[num];
						self.functions[fSplit[0]](fSplit[1]);
						break;
				}
			}
			self.afterRender(tempElements);
		};
	}
	this.init();
}

TEMPLATE = {
		showNotice : function(text, type){
			type = type || 'info';
			switch(type){
			case 'info':
				typeColor = 'green';
				break;
			case 'error':
				typeColor = 'red';
				break;
			case 'notice':
				typeColor = 'yellow';
				break;
			case 'message':
				typeColor = 'black';
				break;
			}
			new jBox('Notice', {
				autoClose : 6000,
				color : typeColor,
				stack : true,
				content : text
			});
		}
}