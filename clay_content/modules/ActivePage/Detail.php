<?php
/**
 * ### Content.ActivePage.Detail
 * アクティブページの詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$activePage = $loader->LoadModel("ActivePageKeyModel");
		$activePage->findByPrimaryKey($_POST["active_page_key_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "active_page")] = $activePage;
	}
}
?>
