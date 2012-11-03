<?php
/**
 * ### Order.Export
 * 商品のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Order_Export extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Order");
		
		$order = $loader->loadModel("OrderModel");
		
		// パラメータのsortを並び順変更のキーとして利用
		$sortKey = $_POST[$params->get("order", "order")];
		unset($_POST[$params->get("order", "order")]);
		$conditions = array();
		foreach($_POST as $key => $value){
			if(!empty($value)){
				$conditions[$key] = $value;
			}
		}
		
		// 取得する件数の上限をページャのオプションに追加
		$totalOrders = $order->countBy($conditions);
		
		// 取得する件数を絞り込み
		$list = array();
		$order->limit($_SERVER["FILE_CSV_DOWNLOAD"]["LIMIT"], $_SERVER["FILE_CSV_DOWNLOAD"]["OFFSET"]);
		$orders = $order->findAllBy($conditions, $sortKey);
		$_SERVER["FILE_CSV_DOWNLOAD"]["OFFSET"] += $_SERVER["FILE_CSV_DOWNLOAD"]["LIMIT"];
		foreach($orders as $order){
			$item = array();
			$orderPayments = $order->payments();
			$payment = $orderPayments[0]->payment();
			foreach($payment->toArray() as $key => $value){
				$item[$key] = $value;
			}
			foreach($orderPayments[0]->toArray() as $key => $value){
				$item[$key] = $value;
			}
			$orderPackages = $order->packages();
			foreach($orderPackages as $orderPackage){
				$delivery = $orderPackage->delivery();
				foreach($delivery->toArray() as $key => $value){
					$item[$key] = $value;
				}
				$details = $orderPackage->details();
				foreach($details as $detail){
					foreach($detail->toArray() as $key => $value){
						$item[$key] = $value;
					}
					foreach($orderPackage->toArray() as $key => $value){
						$item[$key] = $value;
					}
					foreach($order->toArray() as $key => $value){
						$item[$key] = $value;
					}
					$list[] = $item;
				}
			}
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $list;
	}
}
?>
