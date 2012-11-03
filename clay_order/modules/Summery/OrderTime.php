<?php
/**
 * ### Order.Summery.OrderTime
 * 受注データでのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_OrderTime extends Clay_Plugin_Module{
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
		$targets = explode(",", $params->get("summery"));
		$summerys = $order->summeryBy(array(":HOUR(:order_time:):order_time:"), array("subtotal", "total"), $conditions, "order_time");
		$result = array();
		if(!isset($_POST["time"]) || !is_array($_POST["time"]) || empty($_POST["time"])){
			$_POST["time"] = array("6", "9", "12", "15", "18", "21");
		}
		$_POST["time"][] = "24";
		print_r($_POST);
		foreach($summerys as $summery){
			if(in_array($summery->order_time, $_POST["time"])){
				foreach($_POST["time"] as $i => $time){
					if($time == $summery->order_time){
						$summery->order_time_text = $summery->order_time.":00〜".($_POST["time"][$i + 1] - 1).":59";
						$result[$i] = $summery;
					}
				}
			}else{
				foreach($_POST["time"] as $i => $time){
					if($time < $summery->order_time && $summery->order_time < $_POST["time"][$i+1]){
						if(!isset($result[$i])){
							$summery->order_time = $time;
							$summery->order_time_text = $summery->order_time.":00〜".($_POST["time"][$i + 1] - 1).":59";
							$result[$i] = $summery;
						}else{
							$result[$i]->count += $summery->count;
							$result[$i]->subtotal += $summery->subtotal;
							$result[$i]->total += $summery->total;
						}
					}
				}
			}
		}
		$_SERVER["ATTRIBUTES"]["times"] = $_POST["time"];
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $result;
	}
}
?>
