<?php
/**
 * ### Member.Customer.Detail
 * 顧客の詳細情報を取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Member_Customer_Detail extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// 商品データを検索する。
		$customer = $loader->LoadModel("CustomerModel");
		$customer->findByPrimaryKey($_POST["customer_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $customer;
	}
}
?>
