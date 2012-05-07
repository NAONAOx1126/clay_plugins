<?php
/**
 * ### Order.Import
 * 注文情報をインポートするためのクラスです。
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
class Order_Import extends FrameworkModule{
	function execute($params){
		if($params->check("key") && is_array($_SERVER["ATTRIBUTES"][$params->get("key")])){
			try{
				// トランザクションの開始
				DBFactory::begin("order");
				
				// ローダーを初期化
				$loader = new PluginLoader("Order");
					
				// 配送先のリストを取得
				$deliverys = array();
				$delivery = $loader->loadModel("DeliveryModel");
				$result = $delivery->findAllBy(array());
				foreach($result as $data){
					$deliverys[$data->delivery_name] = $data->delivery_id;
				}
				
				// 決済方法のリストを取得
				$payments = array();
				$payment = $loader->loadModel("PaymentModel");
				$result = $payment->findAllBy(array());
				foreach($result as $data){
					$payments[$data->payment_name] = $data->payment_id;
				}
				
				$list = $_SERVER["ATTRIBUTES"][$params->get("key")];
				foreach($list as $index => $data){
					// 半角カナを全角に変換する。
					foreach($data as $key => $value){
						if(!is_array($value)){
							$list[$index][$key] = mb_convert_kana($value);
						}
					}
					
					// 配送方法のテーブルが存在しなければ追加
					if(empty($data["delivery_id"]) && !empty($data["delivery_name"])){
						if(!isset($deliverys[$data["delivery_name"]])){
							$delivery = $loader->loadModel("DeliveryModel");
							$delivery->delivery_name = $data["delivery_name"];
							$delivery->deliv_fee = $data["deliv_fee"];
							$delivery->sort_order = "0";
							$delivery->save();
							$delivery->findBy(array("delivery_name" => $data["delivery_name"]));
							$deliverys[$delivery->delivery_name] = $delivery->delivery_id;
						}
					}
					
					// 決済方法のテーブルが存在しなければ追加
					if(empty($data["payment_id"]) && !empty($data["payment_name"])){
						if(!isset($payments[$data["payment_name"]])){
							$payment = $loader->loadModel("PaymentModel");
							$payment->payment_name = $data["payment_name"];
							$payment->charge = $data["charge"];
							$payment->credit_flg = "0";
							$payment->sort_order = "0";
							$payment->save();
							$payment->findBy(array("payment_name" => $data["payment_name"]));
							$payments[$payment->payment_name] = $payment->payment_id;
						}
					}
				}
				
				// 注文データが注文コードによって一意になるようにリストを絞り込む
				$orderList = array();
				if(!is_array($_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_ORDERS"])){
					$_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_ORDERS"] = array();
				}
				foreach($list as $data){
					if(!isset($_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_ORDERS"][$data["order_code"]])){
						$_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_ORDERS"][$data["order_code"]] = "1";
						$orderList[$data["order_code"]] = $data;
					}
				}
					
				// 注文データを上書き
				$order = $loader->loadModel("OrderModel");
				$orderList = $order->saveAll($orderList);

				// 注文決済データを上書き
				$orderPayment = $loader->loadModel("OrderPaymentModel");
				$orderList = $orderPayment->saveAll($orderList);
				
				// 注文パッケージデータを上書き
				$orderPackage = $loader->loadModel("OrderPackageModel");
				$orderList = $orderPackage->saveAll($orderList);
								
				// 注文詳細データにIDを割り当てるためにIDを割り当て
				foreach($list as $index => $data){
					$list[$index]["order_id"] = $orderList[$data["order_code"]]["order_id"];
					$list[$index]["order_payment_id"] = $orderList[$data["order_code"]]["order_payment_id"];
					$list[$index]["order_package_id"] = $orderList[$data["order_code"]]["order_package_id"];
				}
				
				// 注文詳細データを上書き
				$orderDetail = $loader->loadModel("OrderDetailModel");
				$list = $orderDetail->saveAll($list);
				DBFactory::commit("order");
			}catch(Exception $e){
				DBFactory::rollback("order");
			}
		}
	}
}
?>
