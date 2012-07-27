<?php
/**
 * ### Order.Save
 * 注文のデータを登録する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Order_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"])){
			// 商品情報を登録する。
			$loader = new PluginLoader("Order");
			$loader->LoadSetting();
	
			// トランザクションの開始
			DBFactory::begin("order");
		
			try{
				// 商品データを検索する。
				$order = $loader->LoadModel("OrderModel");
				if(!empty($_POST["order_id"])){
					$order->findByPrimaryKey($_POST["order_id"]);
				}
				
				// 商品データをモデルに格納して保存する。
				foreach($_POST as $key => $value){
					$order->$key = $value;
				}
				
				$order_id_before = $order->order_id;
				$order->save();
				$_POST["order_id"] = $order->order_id;
				
				if(is_array($_POST["products"]) && !empty($_POST["products"])){
					$orderPackage = $loader->LoadModel("OrderPackageModel");
					if(!empty($_POST["order_id"])){
						$orderPackages = $orderPackage->findAllByOrder($_POST["order_id"]);
						if(count($orderPackages) > 0){
							$_POST["order_package_id"] = $orderPackages[0]->order_package_id;
						}else{
							foreach($_POST as $key => $value){
								$orderPackage->$key = $value;
							}
							$orderPackage->save();
							$_POST["order_package_id"] = $orderPackage->order_package_id;
						}
						$orderDetail = $loader->LoadModel("OrderDetailModel");
						if(!empty($_POST["order_package_id"])){
							foreach($_POST["products"] as $product_code => $product){
								$orderDetail->findByPackageProduct($_POST["order_package_id"], $product["product_code"]);
								if($orderDetail->order_detail_id > 0){
									$_POST["products"][$product_code]["order_detail_id"] = $orderDetail->order_detail_id;
								}
							}
							$orderDetails = $orderDetail->findAllByOrderPackage($_POST["order_package_id"]);
							foreach($orderDetails as $detail){
								$detail->delete();
							}
							foreach($_POST["products"] as $product_code => $product){
								$orderDetail = $loader->LoadModel("OrderDetailModel");
								$orderDetail->order_package_id = $_POST["order_package_id"];
								foreach($product as $key => $value){
									$orderDetail->$key = $value;
								}
								$orderDetail->save();
								if($orderDetail->order_detail_id > 0){
									$_POST["products"][$product_code]["order_detail_id"] = $orderDetail->order_detail_id;
								}
							}
						}
					}
				}
				
				// 購入後には累積購入ポイントを設定。
				if(empty($_POST["point"])){
					$_POST["point"] = 0;
				}
				// 最初の設定時のみ
				if($params->get("point", "0") == "1" && !($order_id_before > 0)){
					DBFactory::begin("member");
					try{
						$memberLoader = new PluginLoader("Member");
						$rule = $memberLoader->loadModel("PointRuleModel");
						if($order->customer_id > 0){
							$total_pre = 0;
							$total = 0;
							$preOrders = $order->findAllByCustomer($order->customer_id);
							if(count($preOrders) == 0 || $preOrders[0]->order_id == $order->order_id){
								// 新規購入時は初回購入ポイントを登録
								$pointLog = $memberLoader->loadModel("PointLogModel");
								$pointLog->addCustomerRuledPoint($order->customer_id, $rule, Member_PointRuleModel::RULE_FIRST_ORDER);
							}
							if(count($preOrders) > 0){
								foreach($preOrders as $preOrder){
									if($preOrders[0]->order_id != $order->order_id){
										$total_pre += ($preOrder->subtotal > 0)?$preOrder->subtotal:$preOrder->total;
									}
									$total += ($preOrder->subtotal > 0)?$preOrder->subtotal:$preOrder->total;
								}
							}
							if($total > 0){
								// 購入累計が所定金額を超えた場合にはポイント付与
								$pointLog = $memberLoader->loadModel("PointLogModel");
								$pointLog->addCustomerRuledPoint($order->customer_id, $rule, Member_PointRuleModel::RULE_TOTAL_SALES, $total, $total_pre);
							}
							
							// 購入時には購入金額に応じてポイント付与
							$pointLog = $memberLoader->loadModel("PointLogModel");
							$order->add_point = $rule->getAddPoint(Member_PointRuleModel::RULE_ORDER_SALES, ($order->subtotal > 0)?$order->subtotal:$order->total);
							$pointLog->addCustomerRuledPoint($order->customer_id, $rule, Member_PointRuleModel::RULE_ORDER_SALES, ($order->subtotal > 0)?$order->subtotal:$order->total);
						}

						// エラーが無かった場合、処理をコミットする。
						DBFactory::commit("member");
					}catch(Exception $e){
						DBFactory::rollback("member");
						throw $e;
					}
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("order");
			}catch(Exception $e){
				DBFactory::rollback("order");
				throw $e;
			}
		}
	}
}
?>
