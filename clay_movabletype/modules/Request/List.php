<?php
/**
 * ### Movabletype.Request.List
 * MTのリクエストのリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Movabletype_Request_List extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Movabletype");
		$loader->LoadSetting();

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
		$requests = $request->findAllBy($conditions, $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "requests")] = $requests;
	}
}
?>
