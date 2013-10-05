<?php
/**
 * ### Content.Shortcut.Page
 * 新着情報のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param result 結果を設定する配列のキーワード
 */
class Content_Shortcut_Page extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Content");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new Clay_Pager($params->get("_pager_mode", Clay_Pager::PAGE_SLIDE), $params->get("_pager_dispmode", Clay_Pager::DISPLAY_ATTR), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
		$pager->importTemplates($params);
		
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
		$pager->setDataSize($shortcut->countBy(array()));
		$shortcut->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$shortcuts = $shortcut->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "shortcuts")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "shortcuts")] = $shortcuts;
	}
}
?>