<?php
class Product_Product{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$product->findByProductCode($_POST["product_code"]);
		if($product->product_id > 0){
			return $product->toArray();
		}
		return array();
	}
}
?>
