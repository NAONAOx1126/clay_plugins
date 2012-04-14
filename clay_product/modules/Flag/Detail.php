<?php
/**
 * ### Shopping.Flag.Detail
 * 商品フラグの詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Product_Flag_Detail extends FrameworkModule{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$flag = $loader->LoadModel("FlagModel");
		$flag->findByPrimaryKey($_POST["flag_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "flag")] = $flag;
	}
}
?>
