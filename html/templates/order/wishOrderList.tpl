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
		<link href="../public/css/main.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{include file='../header.tpl'}
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="list-group">
					  <a href="##" class="list-group-item active">
						订单管理
					  </a>
					  <a href="##" class="list-group-item"><span class="badge">14</span>未处理订单</a>
					  <a href="##" class="list-group-item">历史订单</a>
					  <a href="##" class="list-group-item">完成订单</a>
					</div>
				</div>
				<div class="col-md-9">
					<form class="form-horizontal">
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<h3 class="panel-title">操作</h3>
						  </div>
						  <div class="panel-body">
								<div class="form-group">
									<input type="button" class="btn btn-success" value="更新订单信息" id="updateOrderInfo" />
								</div>
						  </div>
						</div>
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<h3 class="panel-title">订单信息</h3>
						  </div>
						  <div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered">
									<thead>
										<tr class="info">
											<th width="5%">图片</th>
											<th width="20%">标题</th>
											<th width="5%">SKU</th>
											<th width="5%">订单<br />价格</th>
											<th width="5%">订单<br />状态</th>
											<th width="10%">订单<br />地址</th>
											<th width="5%">订单<br />运费</th>
											<th width="5%">订单<br />数量</th>
										</tr>
									</thead>
									<tbody>
										{foreach $orderData as $key => $val}
										<tr>
											<td><img src="{$val.product_image_url}" alt="10006-G" width="100px" class="img-thumbnail"></td>
											<td>{$val.product_name}</td>
											<td>{$val.trueSku}</td>
											<td>{$val.order_total}</td>
											<td>{$val.stateZH}</td>
											<td>{$val.ShippingDetail_country}<br />{$val.ShippingDetail_state}<br />{$val.ShippingDetail_city}</td>
											<td>{$val.shipping_cost}</td>
											<td>{$val.quantity}</td>
										</tr>
										{/foreach}
									</tbody>
									<tfoot>
									</tfoot>
								</table>
							</div>
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/order.js"></script>
	</body>
</html>
