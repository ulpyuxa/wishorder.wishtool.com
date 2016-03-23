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
	if($(this).val() == '') {
		return false;
	}
	$('#myModal').modal({
		backdrop: 'static'
	});
	$("input[name='orderId']").val($(this).attr('orderId'));
});

$(document).on('change', "select[name='transport']", function(){
	var postUrl = $(this).find("option:selected").attr("postUrl");
	$("input[name='trackNumber']").next().remove();		//删除链接
	if($.trim(postUrl).length > 0) {
		$("input[name='trackNumber']").after('<a id="postLink" href="'+postUrl+'" target="_blank">单号查询网址</a>');
	}
});