<?php
/**
 * ### Member.Customer.Page
 * 商品のリストをページング付きで取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Member_PointRule_Page extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new TemplatePager($params->get("_pager_mode", TemplatePager::PAGE_SLIDE), $params->get("_pager_dispmode", TemplatePager::DISPLAY_ATTR), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
		$pager->importTemplates($params);
		
		// カテゴリが選択された場合、カテゴリの商品IDのリストを使う
		$conditions = array();
		if(is_array($_POST["search"])){
			foreach($_POST["search"] as $key => $value){
				if(!empty($value)){
					$conditions[$key] = $value;
				}
			}
		}
		
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->get("clear", "0") == "1"){
			if($params->check("sort_key")){
				$_POST = array("search" => $conditions, $params->get("sort_key") => $_POST[$params->get("sort_key")]);
			}else{
				$_POST = array("search" => $conditions);
			}
		}
				
		// 並べ替え順序が指定されている場合に適用
		$sortOrder = "";
		$sortReverse = false;
		if($params->check("sort_key")){
			$sortOrder = $_POST[$params->get("sort_key")];
			if(empty($sortOrder)){
				$sortOrder = "create_time";
				$sortReverse = true;
			}elseif(preg_match("/^rev@/", $sortOrder) > 0){
				list($dummy, $sortOrder) = explode("@", $sortOrder);
				$sortReverse = true;
			}
		}
		
		// 商品データを検索する。
		$pointRule = $loader->LoadModel("PointRuleModel");
		$pager->setDataSize($pointRule->countBy($conditions));
		$pointRule->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$pointRules = $pointRule->findAllBy($conditions, $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "point_rules")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "point_rules")] = $pointRules;
	}
}
?>
