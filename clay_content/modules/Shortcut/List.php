<?php
/**
 * ### Content.Shortcut.List
 * 新着情報のリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_Shortcut_List extends Clay_Plugin_Module{
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
		$shortcut = $loader->LoadModel("ShortcutModel");
		$shortcuts = $shortcut->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "shortcuts")] = $shortcuts;
	}
}
?>
