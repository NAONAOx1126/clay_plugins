<?php
/**
 * ### Member.Contract.Detail
 * 契約の詳細情報を取得する。
 * @param type 抽出するカテゴリのタイプ（指定しない場合は全タイプから抽出）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Contract_Detail extends Clay_Plugin_Module{
	function execute($params){
		// 登録されているカテゴリタイプのリストを取得
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$contract = $loader->LoadModel("ContractModel");
		$contract->findByPrimaryKey($_POST["contract_id"]);

		$_SERVER["ATTRIBUTES"][$params->get("result", "contract")] = $contract;
	}
}
?>
