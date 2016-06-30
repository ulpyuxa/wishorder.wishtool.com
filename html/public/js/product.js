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
	var errStr	= '';
	if($(this).attr('isPromoted') === 'true') {
		errStr += '这是带钻商品！';
	}
	if(!confirm(errStr+'确定要'+(action === 'online' ? '上架' : '下架')+'此商品吗？')) {
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

$(document).on('click', "button[name='submitBtn']", function(){
	var url = $("input[name='productUrl']").val();
	$("#wishTags").val("");
	$("#itemTags").val("");
	$("#loading-indicator").show();
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=getWishTags&jsonp=1',
		data	: {'productUrl':url},
		dataType: "json",
		success : function (ret) {
			$("#loading-indicator").hide();
			var wishTags	= new Array();
			var wishTagsZh	= new Array();
			var itemTags	= new Array();
			var itemTagsZh	= new Array();
			$.each(ret.data.tags, function(key, val){
				wishTags.push(key);
				wishTagsZh.push(val);
			});
			$.each(ret.data.merchant_tags, function(key, val){
				itemTags.push(key);
				itemTagsZh.push(val);
			});
			$("#wishTags").val(wishTags.join("\n"));
			$("#wishTagsZh").val(wishTagsZh.join("\n"));
			$("#itemTags").val(itemTags.join("\n"));
			$("#itemTagsZh").val(itemTagsZh.join("\n"));
		}
	});
});

$(document).ready(function() {
	var maxHeight = 0;          
	$(".equalize").each(function(){
		if ($(this).height() > maxHeight) { maxHeight = $(this).height(); }
	});         
	$(".equalize").height(maxHeight);
}); 

function getTags(id) {
	var url	= 'https://www.wish.com/c/'+id;
	$("#loading-indicator").show();
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=getWishTags&jsonp=1',
		data	: {'productUrl':url},
		dataType: "json",
		success : function (ret) {
			$("#loading-indicator").hide();
			var tagsEn	= new Array();
			var tagsZh	= new Array();
			$.each(ret.data.tags, function(key, val){
				tagsEn.push(key);
				tagsZh.push(val);
			});
			var html = '<textarea rows="20">'+tagsEn.join("\n")+'</textarea><textarea rows="20">'+tagsZh.join("\n")+'</textarea>"'
			alertify.alert('平台标签', html);
		}
	});
}

function showTags(obj) {
	var tags = $(obj).parent('p').parent('.caption').children("#tags").val();
	alertify.alert('商家标签', tags);
}