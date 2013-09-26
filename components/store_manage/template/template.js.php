<script>
$(document).ready(store_manage_init);

function storeData(data, checked){
	checked = checked || '';
	if(storeData.num === undefined) storeData.num = 1;
	storeData.getNum = function(){
		return storeData.num++;
	};
	this.html = '<tr class="_sInfo"><td>' + storeData.getNum() + '</td>' +
	'<td class="_storeAdress" data-input_type="text">' + (checked == '' ? data.adress : '<input type="text" value="' + data.adress + '">') + '</td>' +
	'<td class="_editFlag" data-store_id="' + data.id + '"><input type="checkbox" ' + checked + '></td></tr>';
}

function storeInputsRemove(){
	var rows = $('table._storeManageList tr._sInfo');
	var cols = {};
	for(var num = 0; num < rows.length; num++){
		if($(rows[num]).find('input[type="text"]').val() !== undefined){
			var value = $(rows[num]).find('input[type="text"]').val();
			$(rows[num]).find('td._storeAdress').html(value);
		};
	}
}

var storeModal = {};
var deleteRows = [];

function storesRefresh(){
	$('table._storeManageList tr._sInfo').detach();
	bike.getStores(function(data){
		for(var num in data){
			storeRow = new storeData(data[num]);
			$('table._storeManageList').append(storeRow.html);
		}
		storeData.num = 1;
	});
}

function store_manage_init(){
	$('button._storesManage').click(function(){
		$('div._viewPort span div.disabled').hide();
		$('div._storeManage').fadeIn('fast');
		var storeRow;
		storesRefresh();
	});

	$('div._storeManage button._editbtn').click(function(){
		//$(this).attr('disabled', true);
		var rows = $('table._storeManageList tr._sInfo');
		var editRows = [];
		var rowCount = 0;
		for(var num = 0; num < rows.length; num++){
			if($(rows[num]).find('td._editFlag').find('input').prop('checked') !== false){
				editRows[rowCount] = rows[num];
				rowCount++;
			}
		}
		if(editRows.length == 0){
			//$(this).attr('disabled', false);
			return false;
		}
		bike.editTable(editRows);
	});

	$('div._storeManage button._addbtn').click(function(){
		var newRow = new storeData({'id': 'new', 'adress': ''}, 'checked');
		$('table._storeManageList').append(newRow.html);
		$('table._storeManageList tr:last-child').focusout(function(){
			var value = $(this).find('input').val();
			$(this).find('td._storeAdress').html(value);
		});
		
	});

	$('div._storeManage button._acceptbtn').click(function(){
		storeInputsRemove();
		var rows = $('table._storeManageList tr._sInfo');
		var acceptRows = [];
		var rowCount = 0;
		for(var num = 0; num < rows.length; num++){
			if($(rows[num]).find('td._editFlag').find('input').prop('checked') !== false){
				acceptRows[rowCount] = {'store_id' : $(rows[num]).find('td._editFlag').data('store_id'), 'adress' : $(rows[num]).find('td._storeAdress').text()};
				rowCount++;
			}
		}
		if(acceptRows.length == 0){
			return false;
		}

		bike.acceptStore(acceptRows, function(result){
			bike.showMainAlert(result.message);
			storesRefresh();
		});
	});

	storeModal = new Modal('<?=TEMP::$Lang['store_delete']?>', "<?=TEMP::$Lang['store_del_text']?>", 
			function(){
				bike.deleteStore(deleteRows, function(result){
					bike.showMainAlert(result.message);
					storesRefresh();
					deleteRows = [];
				});
				storeModal.hide();
			});
	
	$('div._storeManage button._deletebtn').click(function(){
		storeInputsRemove();
		var rows = $('table._storeManageList tr._sInfo');
		var rowCount = 0;
		for(var num = 0; num < rows.length; num++){
			if($(rows[num]).find('td._editFlag').find('input').prop('checked') !== false){
				deleteRows[rowCount] = {'store_id' : $(rows[num]).find('td._editFlag').data('store_id')};
				rowCount++;
			}
		}
		if(deleteRows.length == 0){
			return false;
		}

		storeModal.show();
	});
}
</script>