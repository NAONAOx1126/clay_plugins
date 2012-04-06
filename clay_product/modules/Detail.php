<?php
/**
 * ### Product.Detail
 * 商品の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Product_Detail extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$product->findByPrimaryKey($_POST["product_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "product")] = $product;
	}
}
?>
