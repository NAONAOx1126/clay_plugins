<?php
/**
 * ### Facebook.Themes
 * 商品のリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Facebook_Themes extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Facebook");
		$loader->LoadSetting();

		// テーマを検索する。
		$theme = $loader->LoadModel("ThemeModel");
		$themes = $theme->findAllBy();
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "themes")] = $themes;
	}
}
?>
