<?php
/**
 * ### Product.Detail
 * 商品の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Product_Detail extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();

		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$product->findByPrimaryKey($_POST["product_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "product")] = $product;
	}
}
?>
