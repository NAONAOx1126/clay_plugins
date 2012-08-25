<?php
/**
 * ### Content.Cover.Page
 * カバー画像のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param result 結果を設定する配列のキーワード
 */
class Content_Cover_Page extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Content");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new TemplatePager($params->get("_pager_mode", TemplatePager::PAGE_SLIDE), $params->get("_pager_dispmode", TemplatePager::DISPLAY_ATTR), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
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
		$cover = $loader->LoadModel("CoverModel");
		$pager->setDataSize($cover->countBy(array()));
		$cover->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$covers = $cover->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "covers")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "covers")] = $covers;
	}
}
?>
