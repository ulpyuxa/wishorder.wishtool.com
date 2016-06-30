<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>tags生成</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{include file='../header.tpl'}
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div id="content" class="col-md-6 col-md-offset-3">
					<div class="form-horizontal">
					  <div class="form-group">
						<label for="wishTags" class="col-sm-3 control-label">商品URL地址:</label>
						<div class="col-sm-9">
						  <input type="text" name="productUrl" id="productUrl" value="" class="form-control" />
						  <button type="button" name="submitBtn" class="btn btn-primary">提交</button>
						</div>
					  </div>
					  <div class="form-group">
						<label for="wishTags" class="col-sm-3 control-label">wish给的标签</label>
						<div class="col-sm-9 form-inline">
						  <textarea id="wishTags" class="form-control" rows="20" cols="25"></textarea>
						  <textarea id="wishTagsZh" class="form-control" rows="20" cols="25"></textarea>
						</div>
					  </div>
					  <div class="form-group">
						<label for="itemTags" class="col-sm-3 control-label">listing的标签</label>
						<div class="col-sm-9 form-inline">
						  <textarea id="itemTags" class="form-control" rows="10" cols="25"></textarea>
						  <textarea id="itemTagsZh" class="form-control" rows="10" cols="25"></textarea>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<img src="../public/images/loading.gif" id="loading-indicator" style="display:none" />
		<script src="../public/js/jquery-2.2.2.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/product.js"></script>
	</body>
</html>