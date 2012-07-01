<?php
/**
 * ### Member.Contract.List
 * 契約のリストを取得する。
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Contract_List extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
		$loader = new PluginLoader("Member");
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
		$contracts = $contract->findAllBy(array(), $sortOrder, $sortReverse);

		$_SERVER["ATTRIBUTES"][$params->get("result", "contracts")] = $contracts;
	}
}
?>
