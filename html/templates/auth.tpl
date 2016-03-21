<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>账号管理</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../public/css/login.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div id = 'nav'>
				{include file='header.tpl'}
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="list-group">
					  <a href="##" class="list-group-item active">
						范本管理
					  </a>
					  <a href="##" class="list-group-item"><span class="badge">14</span>深圳仓范本</a>
					  <a href="##" class="list-group-item">美国仓范本</a>
					  <a href="##" class="list-group-item">德国仓范本</a>
					</div>
				</div>
				<div class="col-md-9">
					<form class="form-horizontal">
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<h3 class="panel-title">范本信息</h3>
						  </div>
						  <div class="panel-body">
								<div class="form-group">
									<label for="templateName" class="col-sm-2 control-label">范本名称: </label>
									<div class="col-sm-8">
										<input type="text" name="templateName" value="" class="form-control" required />
									</div>
								</div>
								<div class="form-group">
									<label for="templateName" class="col-sm-2 control-label">主料号: </label>
									<div class="col-sm-8">
										<input type="text" name="templateName" value="" class="form-control" required />
									</div>
								</div>
						  </div>
						</div>
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<h3 class="panel-title">SKU信息</h3>
						  </div>
						  <div class="panel-body">
								<table class="table table-hover table-bordered">
									<thead>
										<tr>
											<th>SKU</th>
											<th>图片</th>
											<th>价格</th>
											<th>重量</th>
											<th>数量</th>
											<th>颜色</th>
											<th>尺码</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>3106_B_M</td>
											<td><img src="http://thumb.valsun.cn/10006-G-zxhtestx30.jpg" alt="10006-G" class="img-circle img-thumbnail"></td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
										</tr>
										<tr>
											<td>3106_BL_M</td>
											<td><img src="http://thumb.valsun.cn/10006-G-zxhtestx30.jpg" alt="10006-G" class="img-circle img-thumbnail"></td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
										</tr>
										<tr>
											<td>3106_RR_M</td>
											<td><img src="http://thumb.valsun.cn/10006-G-zxhtestx30.jpg" alt="10006-G" class="img-circle img-thumbnail"></td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
											<td>10.5</td>
										</tr>

									</tbody>
									<tfoot>
									</tfoot>
								</table>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
