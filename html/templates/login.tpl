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
	</head>
	<style>
		#logindev{
			position: absolute;
			top: 50%;
			-webkit-transform: translateY(-50%);
			-moz-transform:  translateY(-50%);
			-ms-transform:  translateY(-50%);
			-o-transform:  translateY(-50%);
			transform:  translateY(-50%);

		}
	</style>
	<body>
		<!--<div class="container">
			<div class="col-lg-2 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2" id="logindev">
				<form class="form">
					<h2>Please sign in</h2>
					<label for="inputEmail" class="sr-only">Email address</label>
					<input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
					<label for="inputPassword" class="sr-only">Password</label>
					<input type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
					<div class="checkbox">
						<label>
						<input type="checkbox" value="remember-me"> Remember me
						</label>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
				</form>
			</div>
		</div>-->
		<div id="loginModal" class="modal show">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close">x</button>
				<h1 class="text-center text-primary">登录</h1>
			  </div>
			  <div class="modal-body">
				<form action="" class="form col-md-12 center-block">
				  <div class="form-group">
					<input type="text" class="form-control input-lg" placeholder="电子邮件">
				  </div>
				  <div class="form-group">
					<input type="password" class="form-control input-lg" placeholder="登录密码">
				  </div>
				  <div class="form-group">
					<button class="btn btn-primary btn-lg btn-block">立刻登录</button>
					<span><a href="#">找回密码</a></span>
					<span><a href="#" class="pull-right">注册</a></span>
				  </div>
				</form>
			  </div>
			  <div class="modal-footer">
				
			  </div>
			</div>
		  </div>
		</div>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="../public/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
