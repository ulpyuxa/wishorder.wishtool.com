<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>平台商品列表</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../public/alertifyjs/css/alertify.min.css" rel="stylesheet" />
		<link href="../public/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" />
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{include file='../header.tpl'}
			</div>
		</div>
		<div class="container-fluid">
			<form class="form-group" method="GET">
				<div class="form-inline">
				  <div class="form-group">
					<label for="tags">关键词: </label>
					<input type="text" class="form-control" name="tags" id="tags" size=80 placeholder="请输入关键字搜索" required value="{$smarty.get.tags}">
					<input type="hidden" name="mod" value="{$smarty.get.mod}"/>
					<input type="hidden" name="act" value="{$smarty.get.act}"/>
				  </div>
				  <button type="submit" class="btn btn-default">搜索产品</button>
				</div>
			</form>
			
			<div class="row">
				<div class="from-group">
					{foreach $data as $dataKey => $dataVal}
						<div class="col-sm-6 col-md-3">
							<div class="thumbnail equalize">
							  <img src="{$dataVal.display_picture}" alt="{$dataVal.display_picture}" style="min-height:230px;height:230px;width">
							  <div class="caption">
								<h5>{$dataVal.name}</h3>
								<p>${$dataVal.commerce_product_info.variations[0].price}</p>
								<p>
									<a href="javascript:void(0)" class="btn btn-primary" role="button" onclick="getTags('{$dataVal.id}')">查看wish给的tags</a>
									<a href="javascript:void(0)" class="btn btn-default" role="button" onclick="showTags(this)" >查看此商品的tags</a>
								</p>
								<input type="hidden" id="tags" value="{foreach $dataVal.tags as $tagsKey => $tagsVal}{$tagsVal.name}, {/foreach}" />
								<input type="hidden" id="productId" value="{$dataVal.id}" />
							  </div>
							</div>
						</div>
					{/foreach}
				</div>
			</div>
		</div>
		<script src="../public/js/jquery-2.2.2.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../public/alertifyjs/alertify.min.js"></script>
		<script src="../public/js/product.js"></script>
	</body>
</html>