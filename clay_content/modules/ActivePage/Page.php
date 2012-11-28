<?php
/**
 * ### Content.ActivePage.Page
 * アクティブページのリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_Page extends Clay_Plugin_Module{
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
		
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->get("clear", "0") == "1"){
			if($params->check("sort_key")){
				$_POST = array("search" => array(), $params->get("sort_key") => $_POST[$params->get("sort_key")]);
			}else{
				$_POST = array("search" => array());
			}
		}
		
		// カテゴリデータを検索する。
		$activePage = $loader->LoadModel("ActivePageKeyModel");
		$pager->setDataSize($activePage->countBy(array()));
		$activePage->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$activePages = $activePage->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "active_pages")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "active_pages")] = $activePages;
	}
}
?>