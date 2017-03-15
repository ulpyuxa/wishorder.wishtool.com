<?php
include __DIR__.'/common.php';

$argv[1]	= 'ulpyuxa';
$account	= $argv[1];
if(empty($account)) {
	exit('请输入账号!');
}

$expression = date('z', time()) % 2;

$sql	= 'select count(*) as counts from ws_product where account="'.$account.'" and isPromoted = "false"';
$ret	= sqlQuery($sql);
$pages	= ceil($ret[0]['counts']/100);

$wishProductApi	= new WishProductApi($account, 1);
for($i = 0; $i < $pages; $i++) {
	$sql	= 'select * from ws_product where account="'.$account.'" and isPromoted = "false" limit '.($i*100).',100';
	$ret	= sqlQuery($sql);
	foreach($ret as $itemKey => $itemVal) {
		$num		= substr(md5($itemVal['spu']), 0, 1);
		$sql		= 'select * from ws_product_'.$num.' where productId="'.$itemVal['productId'].'"';
		$itemRet	= sqlQuery($sql);
		foreach($itemRet as $detailKey	=> $detailVal) {
			if($expression === 1) {
				$price	= $detailVal['variantsPrice'] - 0.01;
			} else {
				$price	= $detailVal['variantsPrice'] + 0.01;
			}
			$data		= array(
				'sku'	=> $detailVal['variantsSku'],
				'price'	=> $price,
			);
			$wishRet	= $wishProductApi->variantUpdate($data);
			var_dump($wishRet);exit;
			$wishRet	= json_decode($wishRet, true);
			if($wishRet['code'] === 0) {
				$sql	= 'update ws_product_'.$num.' set variantsPrice='.$price.' where productId="'.$itemVal['productId'].'" and variantsSku="'.$detailVal['variantsSku'].'"';
				echo $sql, PHP_EOL;
				$query	= $dbConn->query($sql);
			}
		}
	}
}
echo '全部修改完成！';