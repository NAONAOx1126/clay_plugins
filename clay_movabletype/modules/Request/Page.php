<?php
/**
 * ### Movabletype.Request.Page
 * MTのリクエストのリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Movabletype_Request_Page extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Movabletype");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new Clay_Pager($params->get("_pager_mode", Clay_Pager::PAGE_SLIDE), $params->get("_pager_dispmode", Clay_Pager::DISPLAY_ATTR), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
		$pager->importTemplates($params);

		// カテゴリが選択された場合、カテゴリの商品IDのリストを使う
		$conditions = array();
		if($params->check("type")){
			$conditions["store_type"] = $params->get("type");
		}
		if(is_array($_POST["search"])){
			foreach($_POST["search"] as $key => $value){
				if(!empty($value)){
					$conditions[$key] = $value;
				}
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
		$request = $loader->LoadModel("MailRequestModel");
		$pager->setDataSize($request->countBy($conditions));
		$request->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$requests = $request->findAllBy($conditions, $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "requests")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "requests")] = $requests;
	}
}
?>
