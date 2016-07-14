<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>商品管理</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<style>
			* { margin:0; padding:0; }
			img { vertical-align:bottom; border:none; }
			body { background:#f0f0f0; height:800px; font-family:Arial;}
			#bigimage { position:absolute; display:none; }
			#bigimage img { width:400px; height:400px; padding:5px; background:#fff; border:1px solid #e3e3e3; }
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{{include file='../header.tpl'}}
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2">
					<div class="list-group">
					  <a href="javascript:void(0)" class="list-group-item active">
						商品管理
					  </a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.count}}</span>商品数量</a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.onlineCount}} & {{$productData.statisticInfo.offlineCount}}</span>在线&下架</a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.approved}} &	{{$productData.statisticInfo.pending}}</span>已批准&待审核</a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.countSave}}</span>收藏数量</a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.countSold}}</span>购买数量</a>
					  <a href="javascript:void(0)" class="list-group-item"><span class="badge">{{$productData.statisticInfo.rejected}}</span>仿品&禁品数量</a>
					</div>
				</div>
				<div class="col-md-10">
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">商品查询</h3>
					  </div>
					  <div class="panel-body">
							<form class="form-inline">
								<div class="form-group">
									<label for="account" class="control-label">上传账号: </label>
									<select class="form-control" name="account" id="account" required>
										<option value="geshan0728" {{if $smarty.get.account == 'geshan0728'}}selected{{/if}}>geshan0728</option>
										<option value="ulpyuxa" {{if $smarty.get.account == 'ulpyuxa'}}selected{{/if}}>ulpyuxa</option>
									</select>
								</div>
								<div class="form-group">
									<label for="templateName" class="control-label">商品ID: </label>
									<input type="text" name="productId" value="{{$smarty.get.productId}}" class="form-control" />
								</div>
								<div class="form-group">
									<label for="templateName" class="control-label">主料号: </label>
									<input type="text" name="spu" value="{{$smarty.get.spu}}" class="form-control" />
									<input type="submit" name="search" value="搜索" class="btn btn-warning" />
									<input type="hidden" name="act" value="{{$smarty.get.act}}" />
									<input type="hidden" name="mod" value="{{$smarty.get.mod}}" />
									<input type="hidden" name="isOnline" value="{{if isset($smarty.get.isOnline)}}{{$smarty.get.isOnline}}{{else}}online{{/if}}" />
									<!-- <input type="button" name="updateBtn" value="更新商品信息" class="btn btn-success" /> -->
								</div>
							</form>
					  </div>
					</div>
					
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">商品列表</h3>
					  </div>
					  <div class="panel-body">
						<ul class="nav nav-tabs">
						  <li role="presentation" {{if $smarty.get.isOnline == 'online'}}class="active"{{/if}}><a href="/index.php?mod=wishProduct&act=wishProductList&isOnline=online"">在线Listing</a></li>
						  <li role="presentation" {{if $smarty.get.isOnline == 'offline'}}class="active"{{/if}}><a href="/index.php?mod=wishProduct&act=wishProductList&isOnline=offline">下线listing</a></li>
						</ul>
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="success">
										<th width="5%">图片</th>
										<th width="10%">料号</th>
										<th width="5%">价格</th>
										<th width="53%">商品标题</th>
										<th width="5%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=saveSold&isOnline=online&order={{$productData.order}}">收藏数量</a></th>
										<th width="5%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=numSold&isOnline=online&order={{$productData.order}}">订单数量</a></th>
										<th width="8%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=reviewStatus&isOnline=online&order={{$productData.order}}">状态</a></th>
										<th width="10%">操作</th>
									</tr>
								</thead>
								<tbody>
									{{foreach $productData.data as $k => $v}}
										<tr>
											<td>
												{{if $v.isPromoted === 'true'}}
													<div style="position: relative;">
													  <div style="position: absolute;">
														<img src="../public/images/golden_diamond.png"/>
													  </div>  
													  <img src="http://thumb.valsun.cn/{{$v.spu}}-G-zxhtestx40.jpg" sourceImg='http://images.wishtool.cn/{{$v.spu}}-G-zxhtest.jpg' class="img-responsive"/>
													</div>  
												{{else}}
												<img src="http://thumb.valsun.cn/{{$v.spu}}-G-zxhtestx40.jpg" sourceImg='http://images.wishtool.cn/{{$v.spu}}-G-zxhtest.jpg' alt="{{$v.spu}}" class="img-responsive">
												{{/if}}
											</td>
											<td>{{$v.spu}}</td>
											<td>${{$v.price}}</td>
											<td><a href="https://www.wish.com/c/{{$v.productId}}" target="_blank">{{$v.title}}</a></td>
											<td>{{$v.saveSold}}</td>
											<td>{{$v.numSold}}</td>
											<td>{{if $v.reviewStatus == 'approved'}}<font color="green">已批准</font>{{else if $v.reviewStatus == 'pending'}}<font color="red">待审核</font>{{else}}已拒绝{{/if}}</td>
											<td>
												<select name="operateProduct" class="form-control" productId="{{$v.productId}}" isPromoted="{{$v.isPromoted}}">
													<option value="">请选择...</option>
													<option value="edit">编辑Listing</option>
													<option value="online">上架</option>
													<option value="offline">下架</option>
												</select>
											</td>
										</tr>
									{{/foreach}}
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					  </div>
					  {{$productData.pagination}}
					</div>
				</div>
			</div>
		</div>
<!-- <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li role="separator" class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div> -->
<!-- Modal -->
		<div class="modal fade bs-example-modal-lg" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			  </div>
			  <div class="modal-body">
				...
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			  </div>
			</div>
		  </div>
		</div>
<!-- Modal -->
		<script src="../public/js/jquery-2.2.2.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/product.js"></script>
	</body>
</html>
