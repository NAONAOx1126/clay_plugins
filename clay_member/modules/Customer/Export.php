<?php
/**
 * ### Member.Customer.Export
 * 顧客のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Customer_Export extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Member");
		
		$customer = $loader->loadModel("CustomerModel");
		
		// 検索条件を設定する。
		$conditions = array();
		if(is_array($_POST["search"])){
			foreach($_POST["search"] as $key => $value){
				$conditions[$key] = $value;
			}
		}
				
		// パラメータのsortを並び順変更のキーとして利用
		$sortKey = $_POST[$params->get("order", "order")];
		unset($_POST[$params->get("order", "order")]);
		
		// 取得する件数の上限をページャのオプションに追加
		$totalOrders = $customer->countBy($conditions);
		
		// 取得する件数を絞り込み
		$list = array();
		$customer->limit($_SERVER["FILE_CSV_DOWNLOAD"]["LIMIT"], $_SERVER["FILE_CSV_DOWNLOAD"]["OFFSET"]);
		$customers = $customer->findAllBy($conditions, $sortKey);
		$_SERVER["FILE_CSV_DOWNLOAD"]["OFFSET"] += $_SERVER["FILE_CSV_DOWNLOAD"]["LIMIT"];
		foreach($customers as $customer){
			$item = array();
			foreach($customer->toArray() as $name => $value){
				$item[$name] = $value;
			}
			$customerOptions = $customer->customerOptions();
			foreach($customerOptions as $customerOption){
				$item["option_".$customerOptions->option_name] = $customerOptions->option_value;
			}
			$list[] = $item;
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "customers")] = $list;
	}
}
?>
