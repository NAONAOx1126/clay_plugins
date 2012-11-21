<?php
/**
 * アクティブページキーのモデルクラス
 */
class Content_ActivePageKeyModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Content");
		parent::__construct($loader->loadTable("ActivePageKeysTable"), $values);
	}
	
	public function findByPrimaryKey($shop_id){
		$this->findBy(array("shop_id" => $shop_id));
	}
	
	public function pages($order = "", $reverse = false){
		$loader = new Clay_Plugin("Content");
		$activePage = $loader->loadModel("ActivePageModel");
		$activePages = $activePage->findAllByShop($this->shop_id, $order, $reverse);
		return $activePages;		
	}
}
?>