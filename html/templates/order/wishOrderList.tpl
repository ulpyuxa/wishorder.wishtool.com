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
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">操作</h3>
					  </div>
					  <div class="panel-body">
						<form class="form-inline">
							<div class="form-group">
								<label class="control-label">子料号：</label>
								<input type="text" name="sku" class="form-control" value="{$smarty.get.sku}"/>
							</div>
							<div class="form-group">
								<label class="control-label">主料号：</label>
								<input type="text" name="spu" class="form-control" required value="{$smarty.get.spu}"/>
							</div>
							<div class="form-group">
								<input type="submit" name="search" value="搜索" class="btn btn-warning" />
								<input type="hidden" name="act" value="{$smarty.get.act}" />
								<input type="hidden" name="mod" value="{$smarty.get.mod}" />
							</div>
						</form>
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
											<select class="form-control" name="operate" orderId="{$val.order_id}">
												<option value="">请选择...</option>
												<option value="uploadTrackNumber">上传跟踪号</option>
												<!-- <option value="disableOrder">取消订单</option> -->
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
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <form name="tracknumberForm" action="/index.php?mod=wishOrder&act=fulfillOrder" method="POST">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">上传快递单号</h4>
				  </div>
				  <div class="modal-body">
					<div class="form-group form-inline">
						<label for="orderId" class="col-sm-3 control-label">订单号：</label>
						<input class="form-control" name="orderId" required readonly/>
					</div>
					<div class="form-group form-inline">
						<label for="transport" class="col-sm-3 control-label">运输方式：</label>
						<select class="form-control" name="transport" required>
							<option value="">请选择运输方式...</option>
							{foreach $postMethod as $key => $val}
								<option value="{$val.post_en}">{if empty($val.post_zh)}{$val.post_en}{else}{$val.post_zh}[{$val.post_en}]{/if}</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group form-inline">
						<label for="trackNumber" class="col-sm-3 control-label">跟踪号：</label>
						<input class="form-control" name="trackNumber" required/>
					</div>
					<div class="form-group">
						<label for="shipNote" class="col-sm-3 control-label">买家须知：</label>
						<textarea class="form-control" name="shipNote" required></textarea>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="submit" class="btn btn-primary">开始上传</button>
				  </div>
			  </form>
			</div>
		  </div>
		</div>
		<!-- End Modal-->
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/order.js"></script>
	</body>
</html>
