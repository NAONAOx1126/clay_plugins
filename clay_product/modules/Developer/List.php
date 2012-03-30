<?php
/**
 * ### Product.Developer.List
 * 商品の製造業者のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Product_Developer_List extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		$developer = $loader->loadModel("ProductDeveloperModel");
		$developers = $developer->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "developers")] = $developers;
	}
}
?>
