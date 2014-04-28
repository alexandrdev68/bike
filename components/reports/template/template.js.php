<script>
$(document).ready(reports_init);

function reportData(data, checked){
	checked = checked || '';
	if(reportData.num === undefined) reportData.num = 1;
	reportData.getNum = function(){
		return reportData.num++;
	};
	this.html = '<tr class="_rInfo"><td>' + reportData.getNum() + '</td>' +
	'<td>' + data.model + '</td>' +
	'<td>' + data.id + '</td>' +
	'<td>' + data.serial_id + '</td>' +
	'<td>' + bike.getTimeString(new Date((data.time_end - data.time_start) * 1000), ':') + '</td>' +
	'<td>' + bike.getTimeString(new Date(data.project_time * 1000), ':') + '</td>' +
	'<td class="_rentAmount" data-rent_amount="' + (parseFloat(data.amount) / 100) + '">' + 
	bike.numberFormat(parseFloat(data.amount) / 100) + '</td>' +
	'<?if(USER::isAdmin()):?><td class="_calcFlag"><input data-rent_id="' + data.rent_id + '" type="checkbox" ' + checked + '></td><?endif?></tr>';
}


function dayReport(){
	bike.dayReport($('li._storeReportSelect a').data('store_id'), function(response){
		$('table._reportList tr._rInfo').detach();
		for(var rep in response.rents){
			var rpRow = new reportData(response.rents[rep]);
			$('div._reportsView table._reportList').append(rpRow.html);
		};
		$('div._reportsView table._reportList').append('<tr class="_rInfo"><th><?=TEMP::$Lang['summ_text']?></th><th></th><th></th><th></th><th></th><th></th><th>' + bike.numberFormat(calc_report_summ()) + '</th><?if(USER::isAdmin()):?><th></th><?endif?></tr>')
		reportData.num = 1;
		$('input._mainReportChckBox').attr('checked', false);
	});
}

function calc_report_summ(){
	var table = $('table._reportList td._rentAmount');
	var summ = 0;
	for(var i = 0; i < table.length; i++){
		summ += parseFloat($(table[i]).data('rent_amount'));
	}
	return summ;
}

function get_canceled_rents(){
	var canceled = $('td._calcFlag input');
	var table = [];
	var c = 0;
	for(var i = 0; i < canceled.length; i++){
		if($(canceled[i]).prop('checked') !== false){
			table[c] = $(canceled[i]).data('rent_id');
			c++;
		}
	}
	return table.length == 0 ? false : table;
}

function periodReport(){
	var store_id = $('li._storeReportSelect a').data('store_id');
	bike.periodReport(date_from.valueOf() / 1000, date_to.valueOf() / 1000, store_id, function(response){
		$('table._reportList tr._rInfo').detach();
		for(var rep in response.rents){
			var rpRow = new reportData(response.rents[rep]);
			$('div._reportsView table._reportList').append(rpRow.html);
		};
		$('div._reportsView table._reportList').append('<tr class="_rInfo"><th><?=TEMP::$Lang['summ_text']?></th><th></th><th></th><th></th><th></th><th></th><th>' + bike.numberFormat(calc_report_summ()) + '</th><?if(USER::isAdmin()):?><th></th><?endif?></tr>')
		reportData.num = 1;
		$('input._mainReportChckBox').attr('checked', false);
	});
}

function bikesReport(){
	var chkdBikes = document.querySelectorAll('td input[name="selectReportBike"]:checked');
	if(chkdBikes === undefined || chkdBikes.length == 0) return false;
	bike.reportIds = [];
	for(var i = 0; i < chkdBikes.length; i++){
		bike.reportIds[i] = chkdBikes[i].dataset.id;
	}
	bike.reportCounter = bike.reportIds.length - 1;
	bikes_report.send({
		data : {action : 'bike_report', bike_id : bike.reportIds[bike.reportCounter]}
	});
}

var bikes_report = new serverRequest({
	url : '/',
	dataType : 'json',
	success : function(response){
		bike.reportList[bike.reportCounter] = response.report;
		response.report.rent_time = bike.getTimeString(new Date(response.report.rent_time * 1000), ':');
		//console.log(response.report.rent_time);
		bike.reportCounter--;
		if(bike.reportCounter < 0){
				bike_report.fill(bike.reportList);
				$('div.report_container').html(bike_report.table);
				return false;
		}
		bikes_report.send({
			data : {action : 'bike_report', bike_id : bike.reportIds[bike.reportCounter]}
		});
	}
});
var date_now = new Date();
var date_from = new Date(date_now.getFullYear(), date_now.getMonth(), date_now.getDate());
var date_to = new Date(date_now.getFullYear(), date_now.getMonth(), date_now.getDate());
var bikes_list = new tableFromData({
	head : {adress : "<?=TEMP::$Lang['store_adress']?>",
			model : "<?=TEMP::$Lang['model_col']?>",
			serial_id : "<?=TEMP::$Lang['serial_id']?>",
			id : "<input type='checkbox' name='selectReportBike'>"
	},
	content : {
		id : '<input type="checkbox" data-id="#$#" name="selectReportBike">'
	},
	classes : 'table table-striped _reportBikesTable',
	counter : true
});
var bike_report = new tableFromData({
	head : {
			model : "<?=TEMP::$Lang['model_col']?>",
			serial_id : "<?=TEMP::$Lang['serial_id']?>",
			rent_time : "<?=TEMP::$Lang['time_on_rent']?>",
			amount : "<?=TEMP::$Lang['txt_summ']?>"
	},
	classes : 'table table-striped _reportBike_on_rentTable'
});

function reports_init(){
	$('ul._reportChange a').click(function(event){
		event.preventDefault();
		$('button._reportType').data('type', $(this).attr('href')).find('span._reportText').text($(this).data('report-text'));

		var select_type = $(this).attr('href');
		switch (select_type){
			case '#_dayReport':
				$('._reportFromPeriod').hide();
				$('table._reportList').show();
				$('table._reportList tr._rInfo').detach();
				$('div.report_container').hide();
				break;
			case '#_periodReport':
				$('._reportFromPeriod').show();
				$('table._reportList').show();
				$('table._reportList tr._rInfo').detach();
				$('div.report_container').hide();
				break;
			case '#_aboutBikes':
				$('table._reportList tr._rInfo').detach();
				$('._reportFromPeriod').hide();
				$('table._reportList').hide();
				bike.getList({
					onListResponse : function(){
						bikes_list.fill(bike.currentList);
						$('div.report_container').html(bikes_list.table).show();
						$('th input[name="selectReportBike"]').on('change', function(){
							$('td input[name="selectReportBike"]').prop('checked', $(this).prop('checked'));
						});
					},
					from_bike_id : 0,
					filter : 'in_store'
				});
		}
		
		$('div.btn-group.open').removeClass('open');
	});

	if(bike.storeId === null){
		$('li._storeReportSelect').show();
	}else $('li._storeReportSelect a').data('store_id', bike.storeId);

	$('li._storeReportSelect ul a').click(function(event){
		event.preventDefault();
		$('a span._storeReportText').text($(this).text()).parent().data('store_id', $(this).data('value'));

	});

	$('._reportdateFrom').datepicker('setValue', date_from);
	$('._reportdateTo').datepicker('setValue', date_to);
	var checkin = $('._reportdateFrom').datepicker({
		onRender: function(date){
			return date.valueOf() > date_to.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() > checkout.date.valueOf() || ev.date.valueOf() >= date_now.valueOf()){
			checkin.setValue(date_from);
		}else{
			date_from = new Date(ev.date);
		}
		checkin.hide();
	}).data('datepicker');
	
	var checkout = $('._reportdateTo').datepicker({
		onRender: function(date){
			return date.valueOf() > date_now.valueOf() || date.valueOf() < date_from.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev){
		if (ev.date.valueOf() < checkin.date.valueOf() || ev.date.valueOf() >= date_now.valueOf()){
			checkout.setValue(date_to);
		}else{
			date_to = new Date(ev.date);
		}
		checkout.hide();
	}).data('datepicker');
	
	$('._reportFromPeriod').hide();

	
	$('button._reportType').click(function(){
		var action_type = $(this).data('type');
		switch (action_type){
			case '#_dayReport':
				dayReport();
				break;
			case '#_periodReport':
				periodReport();
				break;
			case '#_aboutBikes':
				bikesReport();
				$('div.report_container').html('');
				break;
		}
	});

	$('input._mainReportChckBox').click(function(){
		$('td._calcFlag input').prop('checked', $(this).prop('checked'));
	});

	$('li button._cancelRents').click(function(){
		var table = get_canceled_rents();
		if(table === false) return false;
		bike.cancelRents(table, function(response){
			dayReport();
		});
	});

}
</script>