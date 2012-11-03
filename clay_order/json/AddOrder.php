<?php
class Order_AddOrder{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 受注データを復元
		if(isset($_POST["point"])){
			$point = $_POST["point"];
		}
		$_POST = (array) json_decode(stripslashes($_POST["order"]));
		
		// 商品情報を登録する。
		$loader = new Clay_Plugin("Order");
		$loader->LoadSetting();
		$memberLoader = new Clay_Plugin("Member");
		$memberLoader->LoadSetting();

		// トランザクションの開始
		Clay_Database_Factory::begin("order");
		Clay_Database_Factory::begin("member");
	
		try{
			// 受注データを検索する。
			$order = $loader->LoadModel("OrderModel");
			
			$order->findByCode($_POST["order_temp_id"]);
			
			if(!($order->order_id > 0)){
				// 受注データをモデルに格納して保存する。
				foreach($_POST as $key => $value){
					switch($key){
						case "order_id":
							continue;
						case "order_temp_id":
							$key = "order_code";
							break;
						case "message":
							$key = "customer_comment";
							break;
						case "order_name01":
							$key = "order_sei";
							break;
						case "order_name02":
							$key = "order_mei";
							break;
						case "order_kana01":
							$key = "order_sei_kana";
							break;
						case "order_kana01":
							$key = "order_mei_kana";
							break;
						case "order_tel01":
							$key = "order_tel1";
							break;
						case "order_tel02":
							$key = "order_tel2";
							break;
						case "order_tel03":
							$key = "order_tel3";
							break;
						case "order_fax01":
							$key = "order_fax1";
							break;
						case "order_fax02":
							$key = "order_fax2";
							break;
						case "order_fax03":
							$key = "order_fax3";
							break;
						case "order_addr01":
							$key = "order_address1";
							break;
						case "order_addr02":
							$key = "order_address2";
							break;
						case "order_zip01":
							$key = "order_zip1";
							break;
						case "order_zip02":
							$key = "order_zip2";
							break;
						case "order_birth":
							$key = "order_birthday";
							break;
					}
					$order->$key = $value;
				}
				
				$order->save();
				$_POST["order_id"] = $order->order_id;
				
				if(is_array($_POST["shippings"]) && !empty($_POST["shippings"])){
					foreach($_POST["shippings"] as $shippingObj){
						$shipping = (array) $shippingObj;
						$shipping["order_id"] = $_POST["order_id"];
						$orderPackage = $loader->LoadModel("OrderPackageModel");
						// 受注データをモデルに格納して保存する。
						foreach($shipping as $key => $value){
							switch($key){
								case "shipping_name01":
									$key = "deliv_sei";
									break;
								case "shipping_name02":
									$key = "deliv_mei";
									break;
								case "shipping_kana01":
									$key = "deliv_sei_kana";
									break;
								case "shipping_kana02":
									$key = "deliv_mei_kana";
									break;
								case "shipping_tel01":
									$key = "deliv_tel1";
									break;
								case "shipping_tel02":
									$key = "deliv_tel2";
									break;
								case "shipping_tel03":
									$key = "deliv_tel3";
									break;
								case "shipping_pref":
									$key = "deliv_pref";
									break;
								case "shipping_zip01":
									$key = "deliv_zip1";
									break;
								case "shipping_zip02":
									$key = "deliv_zip2";
									break;
								case "shipping_addr01":
									$key = "deliv_address1";
									break;
								case "shipping_addr02":
									$key = "deliv_address2";
									break;
								case "shipping_date":
									$key = "delivery_date";
									break;
								case "shipping_time":
									$key = "delivery_time";
									break;
							}
							$orderPackage->$key = $value;
						}
						$orderPackage->save();
						$_POST["order_package_id"] = $orderPackage->order_package_id;
						
						if(is_array($shipping["details"]) && !empty($shipping["details"])){
							foreach($shipping["details"] as $detailObj){
								$detail = (array) $detailObj;
								$detail["order_id"] = $_POST["order_id"];
								$detail["order_package_id"] = $_POST["order_package_id"];
								$orderDetail = $loader->LoadModel("OrderDetailModel");
								foreach($detail as $key => $value){
									switch($key){
										case "classcategory_name1":
											$key = "option1_name";
											break;
										case "classcategory_name2":
											$key = "option2_name";
											break;
									}
									$orderDetail->$key = $value;
								}
								$orderDetail->save();
							}						
						}
					}
				}
				
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
				}
			}

			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit("order");
			Clay_Database_Factory::commit("member");
			if($order->customer_id > 0){
				$customer = $memberLoader->loadModel("CustomerModel");
				$customer->findByPrimaryKey($order->customer_id);
				return $customer->toArray();
			}
		}catch(Exception $e){
			Clay_Database_Factory::rollback("order");
			Clay_Database_Factory::rollback("member");
			throw $e;
		}
		return array();
	}
}
?>
