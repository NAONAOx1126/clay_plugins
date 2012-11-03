<?php
/**
 * ### Order.Summery.PromotionRepeats
 * リピートサンプル商品でのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_PromotionRepeats extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Order");
		$ploader = new Clay_Plugin("Product");
		
		// テーブルのインスタンスを作成する。
		$promoDetail = $loader->loadTable("RepeaterOrderDetailsTable");
		$promoDetail->setAlias("promo_repeater_order_details");
		$orderDetail = $loader->loadTable("RepeaterOrderDetailsTable");
		$counter = $loader->loadTable("RepeaterOrderDetailsTable");
		$counter->setAlias("counter");
		$promotion = $ploader->loadTable("ProductPromotionsTable");
		
		// ターゲットの前月と次月を取得
		if(isset($_POST["target"])){
			$_POST["last_target"] = date("Y-m", strtotime("-1 month", strtotime($_POST["target"]."-01 00:00:00")));
			$_POST["next_target"] = date("Y-m", strtotime("+1 month", strtotime($_POST["target"]."-01 00:00:00")));
		}
		
		$summery = array();
		
		// 当月のデータを取得する。
		$select = new Clay_Query_Select($promoDetail);
		$select->addColumn($promoDetail->product_code, "promotion_product_code");
		$select->addColumn($promoDetail->parent_name, "promotion_parent_name")->addColumn($promoDetail->product_name, "promotion_product_name");
		$select->joinInner($promotion, array($promoDetail->product_code." = ".$promotion->promotion_product_code));
		$select->joinLeft($orderDetail, array($orderDetail->product_code." = ".$promotion->product_code, $promoDetail->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$orderDetail->order_time, $orderDetail->order_time." < '".$_POST["next_target"]."-01 00:00:00'"));
		$select->joinLeft($counter, array($orderDetail->product_code." = ".$counter->product_code, $counter->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$counter->order_time, $counter->order_time." < ".$orderDetail->order_time));
		$select->addColumn($orderDetail->product_code, "product_code");
		$select->addColumn($orderDetail->parent_name, "parent_name")->addColumn($orderDetail->product_name, "product_name");
		$select->addColumn("SUM(CASE WHEN ".$counter->order_id." IS NOT NULL THEN 1 ELSE 0 END)", "product_repeats");
		$select->addColumn($orderDetail->quantity)->addColumn($orderDetail->price);
		$select->addColumn($promoDetail->quantity, "promotion_quantity")->addColumn($promoDetail->price, "promotion_price");
		$select->addWhere($promoDetail->order_time." < ?", array($_POST["next_target"]."-01 00:00:00"));
		$select->addGroupBy($promoDetail->product_code)->addGroupBy($orderDetail->product_code);
		$select->addGroupBy($promoDetail->order_email)->addGroupBy($orderDetail->order_code);
		$select->addOrder($promoDetail->order_code);
		$result = $select->execute();
		foreach($result as $data){
			if(!empty($data["product_code"])){
				if(!isset($summery[$data["promotion_product_code"]][$data["product_code"]])){
					$summery[$data["promotion_product_code"]][$data["product_code"]] = array("promotion_product_name" => $data["promotion_product_name"], "product_name" => $data["product_name"], $_POST["last_target"] => array(), $_POST["target"] => array());
				}
				if(!isset($summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][$data["product_repeats"]])){
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][$data["product_repeats"]] = array("count" => 0, "quantity" => 0, "price" => 0);
				}
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][$data["product_repeats"] + 1]["count"] ++;
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][$data["product_repeats"] + 1]["quantity"] += $data["quantity"];
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][$data["product_repeats"] + 1]["price"] += $data["price"];
			}
			if($data["product_repeats"] == 0){
				if(!empty($data["product_code"])){
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][0]["count"] ++;
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][0]["quantity"] += $data["promotion_quantity"];
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["target"]][0]["price"] += $data["promotion_price"];
				}else{
					if(is_array($summery[$data["promotion_product_code"]])){
						foreach($summery[$data["promotion_product_code"]] as $product_code => $product){
							$summery[$data["promotion_product_code"]][$product_code][$_POST["target"]][0]["count"] ++;
							$summery[$data["promotion_product_code"]][$product_code][$_POST["target"]][0]["quantity"] += $data["promotion_quantity"];
							$summery[$data["promotion_product_code"]][$product_code][$_POST["target"]][0]["price"] += $data["promotion_price"];
						}
					}
				}
			}
		}
		
		// 前月のデータを取得する。
		$select = new Clay_Query_Select($promoDetail);
		$select->addColumn($promoDetail->product_code, "promotion_product_code");
		$select->addColumn($promoDetail->parent_name, "promotion_parent_name")->addColumn($promoDetail->product_name, "promotion_product_name");
		$select->joinInner($promotion, array($promoDetail->product_code." = ".$promotion->promotion_product_code));
		$select->joinLeft($orderDetail, array($orderDetail->product_code." = ".$promotion->product_code, $promoDetail->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$orderDetail->order_time, $orderDetail->order_time." < '".$_POST["target"]."-01 00:00:00'"));
		$select->joinLeft($counter, array($orderDetail->product_code." = ".$counter->product_code, $counter->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$counter->order_time, $counter->order_time." < ".$orderDetail->order_time));
		$select->addColumn($orderDetail->product_code, "product_code");
		$select->addColumn($orderDetail->parent_name, "parent_name")->addColumn($orderDetail->product_name, "product_name");
		$select->addColumn("SUM(CASE WHEN ".$counter->order_id." IS NOT NULL THEN 1 ELSE 0 END)", "product_repeats");
		$select->addColumn($orderDetail->quantity)->addColumn($orderDetail->price);
		$select->addColumn($promoDetail->quantity, "promotion_quantity")->addColumn($promoDetail->price, "promotion_price");
		$select->addWhere($promoDetail->order_time." < ?", array($_POST["target"]."-01 00:00:00"));
		$select->addGroupBy($promoDetail->product_code)->addGroupBy($orderDetail->product_code);
		$select->addGroupBy($promoDetail->order_email)->addGroupBy($orderDetail->order_code);
		$select->addOrder($promoDetail->order_code);
		$result = $select->execute();
		foreach($result as $data){
			if(!empty($data["product_code"])){
				if(!isset($summery[$data["promotion_product_code"]][$data["product_code"]])){
					$summery[$data["promotion_product_code"]][$data["product_code"]] = array("promotion_product_name" => $data["promotion_product_name"], "product_name" => $data["product_name"], $_POST["last_target"] => array(), $_POST["target"] => array());
				}
				if(!isset($summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][$data["product_repeats"]])){
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][$data["product_repeats"]] = array("count" => 0, "quantity" => 0, "price" => 0);
				}
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][$data["product_repeats"] + 1]["count"] ++;
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][$data["product_repeats"] + 1]["quantity"] += $data["quantity"];
				$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][$data["product_repeats"] + 1]["price"] += $data["price"];
			}
			if($data["product_repeats"] == 0){
				if(!empty($data["product_code"])){
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][0]["count"] ++;
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][0]["quantity"] += $data["promotion_quantity"];
					$summery[$data["promotion_product_code"]][$data["product_code"]][$_POST["last_target"]][0]["price"] += $data["promotion_price"];
				}else{
					if(is_array($summery[$data["promotion_product_code"]])){
						foreach($summery[$data["promotion_product_code"]] as $product_code => $product){
							$summery[$data["promotion_product_code"]][$product_code][$_POST["last_target"]][0]["count"] ++;
							$summery[$data["promotion_product_code"]][$product_code][$_POST["last_target"]][0]["quantity"] += $data["promotion_quantity"];
							$summery[$data["promotion_product_code"]][$product_code][$_POST["last_target"]][0]["price"] += $data["promotion_price"];
						}
					}
				}
			}
		}
		
		// 結果を変数に格納
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $summery;
	}
}
?>
