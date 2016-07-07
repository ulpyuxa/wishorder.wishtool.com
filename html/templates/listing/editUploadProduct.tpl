<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
		<title>待上传商品编辑</title>
		<!-- Bootstrap -->
		<link href="../public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">
			<div id = 'nav'>
				{{include file='../header.tpl'}}
			</div>
		</div>
		<div class="container-fluid">
			<div class="panel panel-primary">
			  <div class="panel-heading">
				<h3 class="panel-title">商品编辑</h3>
			  </div>
			  <div class="panel-body">
				<form class='form-horizontal'>
					<div class="form-group">
						<label for="account" class="col-sm-2 control-label">店铺账号</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="account" id="account" value="geshan0728" placeholder="店铺账号" required />
						</div>
					</div>
					<div class="form-group">
						<label for="spu" class="col-sm-2 control-label">主料号</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="spu" id="spu" placeholder="主料号" value='{{$data[0].parent_sku}}' required/>
						</div>
					</div>
					<div class="form-group">
						<label for="title" class="col-sm-2 control-label">标题</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="title" id="title" placeholder="标题" value='{{$data[0].name}}' required/>
						</div>
					</div>
					<div class="form-group">
						<label for="tags" class="col-sm-2 control-label">关键字</label>
						<div class="col-sm-8">
						  <input type="text" class="form-control" name="tags" id="tags" placeholder="关键字" value='{{$data[0].tags}}' required/>
						</div>
					</div>
					<div class="form-group">
						<label for="tags" class="col-sm-2 control-label">描述：</label>
						<div class="col-sm-8">
						  <textarea name="description" class="form-control" placeholder="请填写描述..." rows="10" required>{{$data[0].description}}</textarea>
						</div>
					</div>
					<div class="form-group">
						<input type="hidden" name="main_image" value="{{$data[0].main_image[0]}}" />
						<input type="hidden" name="extra_images" value="" />
						<div class="col-sm-6 col-md-3">
							<div class="thumbnail">
							  <img src="{{$data[0].main_image[0]}}" alt="..." id="mainImage">
							  <div class="caption">
								<h3>主图</h3>
							  </div>
							</div>
						</div>
						<div class="col-md-9" id="extImgDiv">
							{{foreach $data[0].extra_images as $dataKey => $dataVal}}
								  <div class="col-xs-6 col-md-2">
									<div class="thumbnail">
									  <img src="{{$dataVal}}"  style="height:120px" alt="...">
									  <a href="javascrip:void(0)" onclick="setMainImage(this)">设为主图</a>
									</div>
								  </div>
							{{/foreach}}
						</div>
					</div>
					<div class="form-group">
						<table class='table table-hover'>
							<thead>
								<tr class="info">
									<th width="10%">SKU</th>
									<th width="5%">颜色</th>
									<th width="5%">图片</th>
									<th width="5%">尺寸</th>
									<th width="5%">MSRP($)</th>
									<th width="5%">价格($)</th>
									<th width="5%">库存</th>
									<th width="5%">运费</th>
									<th width="10%">运输时间(天数)</th>
									<th width="5%">禁用</th>
									<th width="5%">上架</th>
									<th width="5%">操作</th>
								</tr>
							</thead>
							<tbody>
								{{foreach $data as $skuKey => $skuVal}}
									<tr>
										<td><input type="text" name="sku[]" class="form-control" value="{{$skuVal.sku}}" required/></td>
										<td><input type="text" name="color[]" class="form-control" value="{{$skuVal.color}}" required/></td>
										<td><img src='{{$skuVal.main_image[0]}}' class="img-thumbnail"/><input type="hidden" name="skuImg[]" value="{{$skuVal.main_image[0]}}" /></td>
										<td><input type="text" name="size[]" class="form-control" value="{{$skuVal.size}}" required/></td>
										<td><input type="text" name="msrp[]" class="form-control" value="{{$skuVal.msrp}}" required/></td>
										<td><input type="text" name="price[]" class="form-control" value="{{$skuVal.price}}" required/></td>
										<td><input type="text" name="inventory[]" class="form-control" value="{{$skuVal.inventory}}" required/></td>
										<td><input type="text" name="shipping[]" class="form-control" value="{{$skuVal.shipping}}" required/></td>
										<td><input type="text" name="shipping_time[]" class="form-control" value="{{$skuVal.shipping_time}}" required/></td>
										<td><input type="checkbox" /></td>
										<td><input type="checkbox" /></td>
										<td>删除</td>
									</tr>
								{{/foreach}}
							</tbody>
						</table>
					</div>
					<div class="form-group text-center">
						<input type='button' value="刊登" name="pushBtn" class="btn btn-success"/>
					</div>
				</form>
			  </div>
			</div>
		</div>
		<img src="../public/images/loading.gif" id="loading-indicator" style="display:none" />
		<script src="../public/js/jquery-2.2.2.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
		<script src="../public/js/product.js"></script>
		<script src="../public/js/waitProduct.js"></script>
	</body>
</html>