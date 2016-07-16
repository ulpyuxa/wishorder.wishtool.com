			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="javascript:void(0)">Wish看板</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav">
					<!-- <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
					<li><a href="#">Link</a></li> -->
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">商品管理 <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="/index.php?mod=wishProduct&act=wishProductList&isOnline=online">商品列表</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/index.php?mod=wishProduct&act=uploadProductList">商品待上传列表</a></li>
					  </ul>
					</li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">订单管理 <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="/index.php?mod=wishOrder&act=wishOrderList">订单列表</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/index.php?mod=wishOrder&act=shipNode">发货提醒模板</a></li>
					  </ul>
					</li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">ticket管理 <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="/index.php?mod=wishTicket&act=wishTicketList">ticket列表</a></li>
					  </ul>
					</li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">wish平台商品 <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="/index.php?mod=wishProduct&act=apiProductList">商品搜索</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="/index.php?mod=wishProduct&act=getWishTags">wish tags</a></li>
					  </ul>
					</li>
				  </ul>
				  <ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{if $smarty.cookies.account != ''}}当前账号:{{$smarty.cookies.account}}{{else}}切换账号{{/if}}<span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="javascript:void(0)" onclick="setCookie('geshan0728')">geshan0728</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="javascript:void(0)" onclick="setCookie('ulpyuxa')">ulpyuxa</a></li>
					  </ul>
					</li>
				  </ul>
				</div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
			<!--路径导航-->
			<!-- <ol class="breadcrumb" style="float:left;">
			  <li><a href="#">Home</a></li>
			  <li><a href="#">Library</a></li>
			  <li class="active">Data</li>
			</ol> -->
			<!--路径导航-->
			<script>
				function setCookie(account) {
					document.cookie='account='+account;
					location.reload();
				}
			</script>