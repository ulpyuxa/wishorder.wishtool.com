$(document).on("click", 'input[name="updateBtn"]', function(){
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=getWishProduct&jsonp=1',
		dataType: "json",
		success : function (ret) {
			if(ret.data !== true) {
				alert(ret.errCode + ':' + ret.errMsg);
				return false;
			}
			alert('所有商品已经同步完成!');
		}
	});
});

$(document).on('change', 'select[name="operateProduct"]', function(){
	var productId	= $(this).attr('productId');
	var action		= $(this).val();
	if(action === '') {
		return false;
	}
	if(!confirm('确定要'+(action === 'online' ? '上架' : '下架')+'此商品吗？')) {
		return false;
	}
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=operateProduct&jsonp=1',
		data	: {'productId':productId,'action':action},
		dataType: "json",
		success : function (ret) {
			if(ret.data !== true) {
				alert(ret.errCode + ':' + ret.errMsg);
				return false;
			}
			alert('商品'+(action === 'online' ? '上架' : '下架')+'成功!');
		}
	});
});