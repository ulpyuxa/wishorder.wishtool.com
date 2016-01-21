<?php
class WishListingAct extends CommonAct{
	public function act_wishListingList() {
		//$listingData = AliexListingModel::getListingData();
		//$this->smarty->assign('data', $listingData[0]);
		$this->smarty->display('wishListinglist.tpl');
	}
}