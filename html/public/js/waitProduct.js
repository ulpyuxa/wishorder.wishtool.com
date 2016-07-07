$(document).on("click", 'input[name="pushBtn"]', function(){
	extraImages();	//组装描述图
	$("#loading-indicator").show();
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=saveWaitProduct&jsonp=1',
		data	: $('form').serialize(),
		dataType: "json",
		success : function (ret) {
			$("#loading-indicator").hide();
		}
	});
});

function setMainImage(obj) {
	var mainImg	= $("#mainImage").attr('src');
	var url	= $(obj).prev().attr('src');
	$("#mainImage").attr('src', url);
	$(obj).prev().attr('src', mainImg);
	$('input[name="main_image"]').val(url);
}

function extraImages() {
	var extImg	= new Array();
	$.each($("#extImgDiv").find('img'), function(key, val){
		extImg.push($(val).attr("src"));
	});
	$("input[name='extra_images']").val(extImg.join('|'));
}

function delImages() {
	
}