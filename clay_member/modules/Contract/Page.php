<?php
/**
 * ### Member.Contract.List
 * 契約のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Contract_Page extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new TemplatePager($params->get("_pager_mode", TemplatePager::PAGE_SLIDE), $params->get("_pager_dispmode", TemplatePager::DISPLAY_ATTR), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
		$pager->importTemplates($params);
		
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->get("clear", "0") == "1"){
			if($params->check("sort_key")){
				$_POST = array("search" => array(), $params->get("sort_key") => $_POST[$params->get("sort_key")]);
			}else{
				$_POST = array("search" => array());
			}
		}
		
		// カテゴリデータを検索する。
		$contract = $loader->LoadModel("ContractModel");
		$pager->setDataSize($contract->countBy(array()));
		$contract->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$contracts = $contract->findAllBy(array());
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "contracts")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "contracts")] = $contracts;
	}
}
?>
