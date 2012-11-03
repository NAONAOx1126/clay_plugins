<?php
/**
 * ### Order.Summery.OrderDetail
 * 受注データでのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_OrderDetail extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Order");
		
		$orderDetail = $loader->loadModel("OrderDetailModel");
		
		// パラメータのorderを並び順変更のキーとして利用
		$sortKey = $_POST[$params->get("order", "order")];
		unset($_POST[$params->get("order", "order")]);
		$conditions = array();
		foreach($_POST as $key => $value){
			if(!empty($value)){
				$conditions[$key] = $value;
			}
		}
		
		// 取得する件数の上限をページャのオプションに追加
		$groups = explode(",", $params->get("title"));
		$targets = explode(",", $params->get("summery"));
		$summerys = $orderDetail->summeryBy($groups, $targets, $conditions);
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $summerys;
	}
}
?>
