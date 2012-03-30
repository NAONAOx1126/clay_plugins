<?php
/**
 * ### Product.Seller.List
 * 商品の販売業者のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Product_Seller_List extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		$seller = $loader->loadModel("ProductSellerModel");
		$sellers = $seller->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "sellers")] = $sellers;
	}
}
?>
