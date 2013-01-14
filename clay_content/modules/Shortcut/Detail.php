<?php
/**
 * ### Content.Shortcut.Detail
 * 新着情報の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_Shortcut_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$shortcut = $loader->LoadModel("ShortcutModel");
		$shortcut->findByPrimaryKey($_POST["shortcut_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "shortcut")] = $shortcut;
	}
}
?>
