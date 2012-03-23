<?php
/**
 * ### Order.Summery.Promotion
 * 受注データでのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_Promotion extends FrameworkModule{
	function execute($params){
		// ローダーを初期化
		$loader = new PluginLoader("Order");
		$ploader = new PluginLoader("Product");
		
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
		$select = new DatabaseSelect($promoDetail);
		$select->addColumn($promoDetail->product_code, "promotion_product_code");
		$select->addColumn($promoDetail->parent_name, "promotion_parent_name")->addColumn($promoDetail->product_name, "promotion_product_name");
		$select->joinInner($promotion, array($promoDetail->product_code." = ".$promotion->promotion_product_code));
		$select->joinLeft($orderDetail, array($orderDetail->product_code." = ".$promotion->product_code, $promoDetail->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$orderDetail->order_time, $orderDetail->order_time." < '".$_POST["next_target"]."-01 00:00:00'"));
		$select->joinLeft($counter, array($orderDetail->product_code." = ".$counter->product_code, $counter->order_email." = ".$orderDetail->order_email, $promoDetail->order_time." < ".$counter->order_time, $counter->order_time." < ".$orderDetail->order_time));
		$select->addColumn($orderDetail->product_code, "product_code");
		$select->addColumn($orderDetail->parent_name, "parent_name")->addColumn($orderDetail->product_name, "product_name");
		$select->addColumn("SUM(CASE WHEN ".$counter->order_id." IS NOT NULL THEN 1 ELSE 0 END)", "product_repeats");
		$select->addColumn($orderDetail->quantity)->addColumn($promoDetail->quantity, "promotion_quantity");
		$select->addColumn($orderDetail->price)->addColumn($promoDetail->price, "promotion_price");
		$select->addColumn($orderDetail->order_time)->addColumn($promoDetail->order_time, "promotion_order_time");
		$select->addWhere($promoDetail->order_time." >= ?", array($_POST["ge:order_time"]))->addWhere($promoDetail->order_time." <= ?", array($_POST["le:order_time"]));
		$select->addGroupBy($promoDetail->product_code)->addGroupBy($orderDetail->product_code);
		$select->addGroupBy($promoDetail->order_email)->addGroupBy($orderDetail->order_code);
		$select->addOrder($promoDetail->order_code);
		$result = $select->execute();
		$summery = array();
		foreach($result as $data){
			if(!empty($data["product_code"])){
				$key = $data["promotion_product_code"].":".$data["product_code"].":".($data["product_repeats"]);
				if(!isset($summery[$key]) || !is_array($summery[$key])){
					$item = array();
					$item["promotion_product_code"] = $data["promotion_product_code"];
					$item["promotion_parent_name"] = $data["promotion_parent_name"];
					$item["promotion_product_name"] = $data["promotion_product_name"];
					$item["product_code"] = $data["product_code"];
					$item["parent_name"] = $data["parent_name"];
					$item["product_name"] = $data["product_name"];
					$item["product_repeats"] = $data["product_repeats"];
					$item["promotion_count"] = 0;
					$item["product_count"] = 0;
					$item["transfer_time"] = 0;
					$summery[$key] = $item;
				}
				$summery[$key]["promotion_count"] ++;
				$summery[$key]["product_count"] ++;
				$summery[$key]["transfer_time"] += (strtotime($data["order_time"]) - strtotime($data["promotion_order_time"])) / 86400;
			}
		}
		foreach($result as $data){
			if(empty($data["product_code"])){
				foreach($summery as $key => $item){
					if($data["promotion_product_code"] == $item["promotion_product_code"]){
						$summery[$key]["promotion_count"] ++;
					}
				}
			}
		}
		foreach($summery as $key => $item){
			if($key != $item["promotion_product_code"].":".$item["product_code"].":0"){
				$summery[$key]["promotion_count"] = $summery[$item["promotion_product_code"].":".$item["product_code"].":0"]["promotion_count"];
			}
		}
		$result = array();
		foreach($summery as $item){
			$result[] = $item;
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $result;
	}
}
?>
