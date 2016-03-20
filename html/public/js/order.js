$(document).on("click", '#updateOrderInfo', function(){
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishOrder&act=wishOrderSync&jsonp=1',
		dataType: "json",
		success : function (ret) {
			if(ret.data !== true) {
				alert(ret.errCode + ':' + ret.errMsg);
				return false;
			}
			alert('恭喜，同步到新的订单!');
		}
	});
});

$(document).on('change', "select[name='operate']", function(){
	if($(this).val() === '') {
		return false;
	}
	$('#myModal').modal({
		backdrop: 'static'
	});
	$("input[name='orderId']").val($(this).attr('orderId'));
});