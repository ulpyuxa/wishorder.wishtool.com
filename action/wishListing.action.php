<?php
class WishListingAct extends CommonAct{
	public function act_wishListingList() {exit('3333');
		$listingData = AliexListingModel::getListingData();
		print_r($listingData);exit;
		$this->smarty->assign('data', $listingData[0]);
		$this->smarty->display('wishListinglist.tpl');
	}
}