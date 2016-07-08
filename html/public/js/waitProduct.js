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
			if(typeof(ret['data'][0]['data']['Product']['id']) != undefined) {		//为false表示
				alertify.alert('刊登状态', $("#spu").val() + ':商品上传成功!');
			}
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

function delSku(obj) {
	var rows	= $(obj).parent('td').parent('tr').index();
	$('table>tbody>tr:eq('+rows+')').remove();
}