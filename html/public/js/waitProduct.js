$(document).on("click", 'input[name="pushBtn"]', function(){
	extraImages();	//组装描述图
	$("#loading-indicator").show();
	var account = $("input[name='account']").val();
	$.ajax({
		type	: "POST",
		async	: true,
		url		: './json.php?mod=wishProduct&act=saveWaitProduct&jsonp=1',
		data	: $('form').serialize(),
		dataType: "json",
		success : function (ret) {
			$("#loading-indicator").hide();
			if(typeof(ret.errCode) != undefined && ret.errCode > 0) {
				alertify.alert("刊登状态", ret.errMsg);
				return false;
			}
			if(typeof(ret['data'][0]['data']['Product']['id']) != undefined) {		//为false表示
				alertify.alert('刊登状态', $("#spu").val() + ':商品上传成功! 系统将跳转到待刊登列表页面', 
					function(){
						window.location.href="/index.php?mod=wishProduct&act=uploadProductList&account="+account;
					}
				);
				
			} else {
				alertify.alert('刊登状态', $("#spu").val() + ':商品上传失败, 请重试!');
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
	var rowCount= $(obj).parent('td').parent('tr').parent("tbody").children('tr').length;
	if(rowCount === 1) {
		alertify.error('最后一个SKU不能删除！');
		return ;
	}
	$('table>tbody>tr:eq('+rows+')').remove();
}

function selectImages(obj) {
	var idx = $(obj).parent("td").parent("tr").index();
	$("#snapIdx").val(idx);	//记录临时图片的行编号
	$("#imgSelect").modal();
}

$(document).on('dblclick', '#snapImg', function(){
	var imgUrl	= $(this).attr('src');
	var idx		= $("#snapIdx").val();
	var row		= $("table>tbody>tr").length;
	for(i = idx; i < row; i++) {
		$("table>tbody>tr:eq("+i+") td:eq(2) img").attr('src', imgUrl);
		$("table>tbody>tr:eq("+i+") td:eq(2) input").val(imgUrl);
	}
	$("#imgSelect").modal('hide');
});

function delWaitProduct(spuSn) {
	alertify.confirm('删除待上传料号:'+spuSn, "确定删除此料号，并且不再上传此料号吗？",
		function(){
			$("#loading-indicator").show();
			$.ajax({
				type	: "POST",
				async	: true,
				url		: './json.php?mod=wishProduct&act=delWaitProduct&jsonp=1',
				data	: {'spuSn':spuSn},
				dataType: "json",
				success : function (ret) {
					$("#loading-indicator").hide();
					if(ret['data']) {		//为false表示
						alertify.alert('成功', '料号:'+spuSn+', 删除成功');
						location.reload();
					} else {
						alertify.alert('失败', '料号:'+spuSn+', 删除失败');
					}
				}
			});
		}, 
		function(){
			//alertify.alert('料号:'+spuSn+', 删除失败');
		}
	);

}
function setPrice(obj){
	var idx		= $(obj).parent("th").index();
	var price	= $('table>tbody>tr:eq(0)>td:eq('+idx+') input').val();
	alertify.prompt("批量设置价格", "请输入价格", price,
		function(evt, value ){
			var rows = $("table>tbody>tr").length;
			for(i = 0; i < rows; i++) {
				$("table>tbody>tr:eq("+i+") td:eq("+idx+") input").val(value);
			}
		},
		function(){
		}
	);
}