<?php
/**
 * ### Member.Customer.Detail
 * 顧客の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Member_PointRule_Detail extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();

		// 商品データを検索する。
		$pointRule = $loader->LoadModel("PointRuleModel");
		$pointRule->findByPrimaryKey($_POST["point_rule_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "point_rule")] = $pointRule;
	}
}
?>
