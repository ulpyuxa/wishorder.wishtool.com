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
				<div class="col-md-6 col-md-offset-4">
					<form name="urlForm">
						<div class="form-inline">
							<label for="productUrl" class="control-label">商品URL地址: </label>
							<input type="text" name="productUrl" id="productUrl" value="" class="form-control" />
							<button type="button" name="submitBtn" class="btn btn-primary">提交</button>
						</div>
					</form>
				</div>
				<div id="content" class="col-md-6 col-md-offset-4">
					<div class="form-horizontal">
					  <div class="form-group">
						<label for="wishTags" class="col-sm-2 control-label">wish给的标签</label>
						<div class="col-sm-10">
						  <input type="email" class="form-control" id="wishTags" placeholder="Email">
						</div>
					  </div>
					  <div class="form-group">
						<label for="itemTags" class="col-sm-2 control-label">listing的标签</label>
						<div class="col-sm-10">
						  <input type="email" class="form-control" id="itemTags" placeholder="Email">
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<script src="../public/js/jquery-2.2.2.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/product.js"></script>
	</body>
</html>