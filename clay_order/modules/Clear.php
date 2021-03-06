<?php
/**
 * ### Order.Clear
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
class Order_Clear extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Order");
		
		// 受注データをクリアする。
		$orders = $loader->loadTable("OrdersTable");
		$truncate = new Clay_Query_Truncate($orders);
		$truncate->execute();
		// 受注セットデータをクリアする。
		$orderPackages = $loader->loadTable("OrderPackagesTable");
		$truncate = new Clay_Query_Truncate($orderPackages);
		$truncate->execute();
		// 受注詳細データをクリアする。
		$orderDetails = $loader->loadTable("OrderDetailsTable");
		$truncate = new Clay_Query_Truncate($orderDetails);
		$truncate->execute();
		// 受注決済データをクリアする。
		$orderPayments = $loader->loadTable("OrderPaymentsTable");
		$truncate = new Clay_Query_Truncate($orderPayments);
		$truncate->execute();
		// 受注ステータスデータをクリアする。
		$orderStatuses = $loader->loadTable("OrderStatusesTable");
		$truncate = new Clay_Query_Truncate($orderStatuses);
		$truncate->execute();
		// 受注データをクリアする。
		$orders = $loader->loadTable("RepeaterOrdersTable");
		$truncate = new Clay_Query_Truncate($orders);
		$truncate->execute();
		// 受注詳細データをクリアする。
		$orderDetails = $loader->loadTable("RepeaterOrderDetailsTable");
		$truncate = new Clay_Query_Truncate($orderDetails);
		$truncate->execute();
		// 受注決済データをクリアする。
		$orderPayments = $loader->loadTable("RepeaterOrderPaymentsTable");
		$truncate = new Clay_Query_Truncate($orderPayments);
		$truncate->execute();
	}
}
?>
