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
				
				// 購入時は購入ポイントと初回購入ポイントを設定。
				if(!($order->order_id > 0)){
					if(empty($_POST["point"])){
						$_POST["point"] = 0;
					}
					if($params->get("point", "0") == "1"){
						DBFactory::begin("member");
						try{
							$memberLoader = new PluginLoader("Member");
							$rule = $memberLoader->loadModel("PointRuleModel");
							if($order->customer_id > 0){
								$total = 0;
								$preOrders = $order->findAllByCustomer($order->customer_id);
								if(count($preOrders) == 0){
									// 新規登録時は登録ポイントを登録
									$pointLog = $memberLoader->loadModel("PointLogModel");
									$pointLog->add($rule->getAddPoint(Member_PointRuleModel::RULE_FIRST_ORDER), $rule->getRuleName(Member_PointRuleModel::RULE_FIRST_ORDER), false);
								}else{
									foreach($preOrders as $preOrder){
										$total += ($preOrder->subtotal > 0)?$preOrder->subtotal:$preOrder->total;
									}
								}
								$total += ($order->subtotal > 0)?$order->subtotal:$order->total;
								if($total > 0){
									// 新規登録時は登録ポイントを登録
									$pointLog = $memberLoader->loadModel("PointLogModel");
									$pointLog->add($rule->getAddPoint(Member_PointRuleModel::RULE_TOTAL_SALES, $total), $rule->getRuleName(Member_PointRuleModel::RULE_TOTAL_SALES), false);
								}
							}
							// 新規登録時は登録ポイントを登録
							$pointLog = $memberLoader->loadModel("PointLogModel");
							$order->add_point = $rule->getAddPoint(Member_PointRuleModel::RULE_ORDER_SALES, ($order->subtotal > 0)?$order->subtotal:$order->total);
							$pointLog->add($rule->getAddPoint(Member_PointRuleModel::RULE_ORDER_SALES, ($order->subtotal > 0)?$order->subtotal:$order->total), $rule->getRuleName(Member_PointRuleModel::RULE_ORDER_SALES), false);

							// エラーが無かった場合、処理をコミットする。
							DBFactory::commit("member");
						}catch(Exception $e){
							DBFactory::rollback("member");
							throw $e;
						}
					}
				}
		
				$order->save();
				$_POST["order_id"] = $order->order_id;
				
				// 購入後には累積購入ポイントを設定。
				if(empty($_POST["point"])){
					$_POST["point"] = 0;
				}
				if($params->get("point", "0") == "1"){
					$memberLoader = new PluginLoader("Member");
					$rule = $memberLoader->loadModel("PointRuleModel");
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
