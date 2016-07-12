<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>待刊登产品</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../public/alertifyjs/css/alertify.min.css" rel="stylesheet" />
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

				<div>
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">商品查询</h3>
					  </div>
					  <div class="panel-body">
							<form class="form-inline">
								<div class="form-group">
									<label for="templateName" class="control-label">主料号: </label>
									<input type="text" name="spuSn" value="{{$smarty.get.spuSn}}" class="form-control" />
									<input type="submit" name="search" value="搜索" class="btn btn-warning" />
									<input type="hidden" name="act" value="{{$smarty.get.act}}" />
									<input type="hidden" name="mod" value="{{$smarty.get.mod}}" />
								</div>
							</form>
					  </div>
					</div>
					
					<div class="panel panel-primary">
					  <div class="panel-heading">
						<h3 class="panel-title">商品列表</h3>
					  </div>
					  <div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="success">
										<th width="5%">图片</th>
										<th width="10%">料号</th>
										<th width="53%">商品标题</th>
										<th width="5%">价格</th>
										<th width="5%">运费</th>
										<th width="8%">tags</th>
										<th width="10%">操作</th>
									</tr>
								</thead>
								<tbody>
									{{foreach $productData.data as $k => $v}}
										<tr>
											<td>
												<img src="http://img.pics.valsun.cn/v{{$v.photoVersion}}/{{$v.main_image}}" alt="" sourceimg = 'http://img.pics.valsun.cn/v{{$v.photoVersion}}/{{$v.main_image}}' class="img-responsive"/>
											</td>
											<td>{{$v.spuSn}}</td>
											<td>{{$v.name}}</a></td>
											<td>{{$v.price}}</td>
											<td>{{$v.shipping}}</td>
											<td>{{$v.tags}}</td>
											<td>
												<div class="btn-group">
												  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													操作 <span class="caret"></span>
												  </button>
												  <ul class="dropdown-menu">
													<li><a href="/index.php?mod=wishProduct&act=editUploadProduct&spu={{$v.spuSn}}">修改商品详情</a></li>
													<li role="separator" class="divider"></li>
													<li><a href="javascript:void(0)" onclick="delWaitProduct('{{$v.spuSn}}')">删除</a></li>
												  </ul>
												</div>
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
		<script type="text/javascript" src="../public/alertifyjs/alertify.min.js"></script>
		<script src="../public/js/product.js"></script>
		<script src="../public/js/waitProduct.js"></script>
	</body>
</html>
