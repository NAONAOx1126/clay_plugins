<?php
/**
 * ### Shopping.Category.Detail
 * 商品カテゴリの詳細情報を取得する。
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Product_Category_Detail extends FrameworkModule{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$category = $loader->LoadModel("CategoryModel");
		$category->findByPrimaryKey($_POST["category_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "category")] = $category;
	}
}
?>
