<?php
/**
 * ### Order.CustomerProducts
 * 注文情報をクリアするためのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   Order
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param key インポートするファイルの形式を特定するためのキー
 */
class Order_CustomerProducts extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Order");
		
		// 受注データを取得する。
		$order = $loader->loadModel("OrderModel");
		if(defined("CUSTOMER_SESSION_KEY") && isset($_SESSION[CUSTOMER_SESSION_KEY]) && $_SESSION[CUSTOMER_SESSION_KEY]->customer_id > 0){
			$orders = $order->findAllByCustomer($_SESSION[CUSTOMER_SESSION_KEY]->customer_id);
		}else{
			$orders = $order->findAllBy(array("gt:customer_id" => 0));
		}
		
		$result = array();
		foreach($orders as $order){
			if(!is_array($result[$order->customer_id])){
				$result[$order->customer_id] = array();
			}
			foreach($order->packages() as $package){
				foreach($package->details() as $detail){
					if(!isset($result[$order->customer_id][$detail->product_id])){
						$result[$order->customer_id][$detail->product_id] = 0;
					}
					$result[$order->customer_id][$detail->product_id] ++;
				}
			}
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer_products")] = $result;
	}
}
?>
