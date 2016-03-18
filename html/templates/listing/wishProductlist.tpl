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
						商品管理
					  </a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.count}</span>商品数量</a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.countSave}</span>收藏数量</a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.countSold}</span>购买数量</a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.pending}</span>待审核数量</a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.approved}</span>在线数量</a>
					  <a href="##" class="list-group-item"><span class="badge"></span>下架数量</a>
					  <a href="##" class="list-group-item"><span class="badge">{$productData.statisticInfo.rejected}</span>仿品&禁品数量</a>
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
										<label for="templateName" class="control-label">商品ID: </label>
										<input type="text" name="productId" value="{$smarty.get.productId}" class="form-control" />
									</div>
									<div class="form-group">
										<label for="templateName" class="control-label">主料号: </label>
										<input type="text" name="spu" value="{$smarty.get.spu}" class="form-control" required />
										<input type="submit" name="search" value="搜索" class="btn btn-warning" />
										<input type="hidden" name="act" value="{$smarty.get.act}" />
										<input type="hidden" name="mod" value="{$smarty.get.mod}" />
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
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr class="success">
										<th width="5%">图片</th>
										<th width="10%">料号</th>
										<th width="64%">商品标题</th>
										<th width="8%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=saveSold&order={$productData.order}">收藏数量</a></th>
										<th width="8%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=numSold&order={$productData.order}">订单数量</a></th>
										<th width="5%"><a href="./index.php?mod=wishProduct&act=wishProductList&orderBy=reviewStatus&order={$productData.order}">状态</a></th>
									</tr>
								</thead>
								<tbody>
									{foreach $productData.data as $k => $v}
										<tr>
											<td><img src="http://thumb.valsun.cn/{$v.spu}-G-zxhtestx40.jpg" alt="{$v.spu}" class="img-thumbnail"></td>
											<td>{$v.spu}</td>
											<td><a href="https://www.wish.com/c/{$v.productId}" target="_blank">{$v.title}</a></td>
											<td>{$v.saveSold}</td>
											<td>{$v.numSold}</td>
											<td>{$v.reviewStatus}</td>
										</tr>
									{/foreach}
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					  </div>
					  {$productData.pagination}
					</div>
				</div>
			</div>
		</div>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/product.js"></script>
	</body>
</html>
