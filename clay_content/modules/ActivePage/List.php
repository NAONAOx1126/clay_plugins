<?php
/**
 * ### Content.ActivePage.List
 * アクティブページのリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_List extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();
		
		// 並べ替え順序が指定されている場合に適用
		$sortOrder = "";
		$sortReverse = false;
		if($params->check("sort_key")){
			$sortOrder = $_POST[$params->get("sort_key")];
			if(preg_match("/^rev@/", $sortOrder) > 0){
				list($dummy, $sortOrder) = explode("@", $sortOrder);
				$sortReverse = true;
			}
		}
		
		// カテゴリデータを検索する。
		$activePage = $loader->LoadModel("ActivePageKeyModel");
		$activePages = $activePage->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "active_pages")] = $activePages;
	}
}
?>
