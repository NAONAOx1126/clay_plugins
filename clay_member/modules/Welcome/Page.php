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
class Member_Welcome_Page extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new TemplatePager($params->get("_pager_mode", TemplatePager::PAGE_SLIDE), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
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
		
		// メールアドレスに対応する顧客IDのリストを取得する。
		if(!empty($conditions["email"]) || !empty($conditions["customer_name"]) || !empty($conditions["customer_name_kana"])){
			$customer = $loader->loadModel("CustomerModel");
			$customerConditions = array();
			if(!empty($conditions["email"])){
				$customerConditions["part:email"] = $conditions["email"];
			}
			if(!empty($conditions["customer_name"])){
				$customerConditions["part:sei+mei"] = $conditions["customer_name"];
			}
			if(!empty($conditions["customer_name_kana"])){
				$customerConditions["part:sei_kana+mei_kana"] = $conditions["customer_name_kana"];
			}
			$customers = $customer->findAllBy($customerConditions);
			unset($conditions["email"]);
			unset($conditions["customer_name"]);
			unset($conditions["customer_name_kana"]);
			$conditions["in:customer_id"] = array("0");
			if(is_array($customers)){
				foreach($customers as $customer){
					$conditions["in:customer_id"][] = $customer->customer_id;
				}
			}
		}
		
		if($params->check("commit")){
			$conditions["commit_flg"] = $params->get("commit");
		}
		
		// 来店データを検索する。
		$welcome = $loader->LoadModel("WelcomeModel");
		$pager->setDataSize($welcome->countBy($conditions));
		$welcome->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$welcomes = $welcome->findAllBy($conditions, $sortOrder, $sortReverse);

		$_SERVER["ATTRIBUTES"][$params->get("result", "welcomes")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "welcomes")] = $welcomes;
	}
}
?>
