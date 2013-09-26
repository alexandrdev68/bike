<script>
$(document).ready(modal_init);

function Modal(header, text, funct){
	this.header = header;
	this.text = text;
	Modal.prototype.show = function(){
		$('h3._modalHeader').text(this.header);
		$('p._modalText').text(this.text);
		$('div._modalWin').modal('show');
		this.init();
	};
	Modal.prototype.hide = function(){
		$('div._modalWin').modal('hide');
	};
	this.confirm = funct;
	this.init = function(){
		$('button._confirmModal').on('click', this.confirm);
		$('div._modalWin').on('hide', function(){
			$('div._storeSelect').hide();
			$('div._storeSelect select[name="bPlace"]').val('');
			bike.storeId = null;
			$('button._confirmModal').off('click');
		});
	};
	//this.init();
}

function modal_init(){
	$('div._storeSelect select').change(function(){
		bike.storeId = $(this).val();
	});
}
</script>