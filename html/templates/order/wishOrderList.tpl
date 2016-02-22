<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>订单管理</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{include file='../header.tpl'}
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="list-group">
					  <a href="##" class="list-group-item active">
						订单管理
					  </a>
					  <a href="./index.php?mod=wishOrder&act=wishOrderList&state=ALL" class="list-group-item"><span class="badge">{$orderCount.sum}</span>全部订单</a>
					  <a href="./index.php?mod=wishOrder&act=wishOrderList&state=APPROVED" class="list-group-item"><span class="badge">{$orderCount.APPROVED}</span>新订单</a>
					  <a href="./index.php?mod=wishOrder&act=wishOrderList&state=SHIPPED" class="list-group-item"><span class="badge">{$orderCount.SHIPPED}</span>已发货订单</a>
					  <a href="./index.php?mod=wishOrder&act=wishOrderList&state=REFUNDED" class="list-group-item"><span class="badge">{$orderCount.REFUNDED}</span>已退货订单</a>
					</div>
				</div>
				<div class="col-md-10">
					<form class="form-horizontal">
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<h3 class="panel-title">操作</h3>
						  </div>
						  <div class="panel-body">
								<div class="form-inline">
									<!-- <input type="button" class="btn btn-success" value="更新订单信息" id="updateOrderInfo" />
									<select class="form-control js-example-basic-multiple" multiple="multiple">
									  <option value="AL">Alabama</option>
									  <option value="WY">Wyoming</option>
									</select> -->
								</div>
								<div class="form-group">
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
										<tr class="success">
											<th width="5%">图片</th>
											<th width="10%">SKU</th>
											<th width="35%">标题</th>
											<th width="5%">订单<br />价格</th>
											<th width="5%">订单<br />状态</th>
											<th width="15%">订单<br />地址</th>
											<th width="5%">订单<br />运费</th>
											<th width="5%">订单<br />数量</th>
											<th width="5%">下单<br />时间</th>
											<th width="10%">操作</th>
										</tr>
									</thead>
									<tbody>
										{foreach $orderData.data as $key => $val}
										<tr>
											<td><img src="{$val.product_image_url}" alt="{$val.trueSku}" width="40px" class="img-thumbnail"></td>
											<td>{$val.trueSku}</td>
											<td>{$val.product_name}  [{$val.order_id}]</td>
											<td>{$val.order_total}</td>
											<td>{$val.stateZH}</td>
											<td>国家：{$val.ShippingDetail_country}<br />省：{$val.ShippingDetail_state}<br />市/区：{$val.ShippingDetail_city}</td>
											<td>{$val.shipping_cost}</td>
											<td>{$val.quantity}</td>
											<td>{$val.order_time|date_format:"%D %T"}</td>
											<td>
												<select class="form-control">
													<option>请选择...</option>
													<option value="uploadTrackNumber">上传跟踪号</option>
												</select>
											</td>
										</tr>
										{/foreach}
									</tbody>
									<tfoot>
									</tfoot>
								</table>
							</div>
							{$orderData.pageHtml}
						  </div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
