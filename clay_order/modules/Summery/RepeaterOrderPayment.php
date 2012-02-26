<?php
/**
 * ### Order.Summery.RepeaterOrderPayment
 * 受注データでのサマリを取得する。
 * @param title タイトルに相当するカラムを指定
 * @param summery サマリーに相当するカラムを指定
 * @param result 結果を設定する配列のキーワード
 */
class Order_Summery_RepeaterOrderPayment extends FrameworkModule{
	function execute($params){
		// ローダーを初期化
		$loader = new PluginLoader("Order");
		
		$order = $loader->loadModel("RepeaterOrderPaymentModel");
		
		// パラメータのsortを並び順変更のキーとして利用
		$sortKey = $_POST[$params->get("order", "order")];
		unset($_POST[$params->get("order", "order")]);
		$conditions = array();
		$conditions_new = array("order_repeat" => "0");
		$conditions_repeat = array("gt:order_repeat" => "0");
		foreach($_POST as $key => $value){
			if(!empty($value)){
				$conditions[$key] = $value;
				$conditions_new[$key] = $value;
				$conditions_repeat[$key] = $value;
			}
		}
		
		// 取得する件数の上限をページャのオプションに追加
		$groups = explode(",", $params->get("title"));
		$targets = explode(",", $params->get("summery"));
		$columns = array(":COUNT(DISTINCT order_email):uu:");
		$summerys = $order->summeryBy($groups, $targets, $conditions, $sortKey, $columns);
		foreach($summerys as $index => $summery){
			$summerys[$index]->subtotal = $summery->payment_total;
			$summerys[$index]->payment_name = $summery->payment()->payment_name;
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $summerys;
		$summerys = $order->summeryBy($groups, $targets, $conditions_new, $sortKey, $columns);
		foreach($summerys as $index => $summery){
			$summerys[$index]->subtotal = $summery->payment_total;
			$summerys[$index]->payment_name = $summery->payment()->payment_name;
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")."_new"] = $summerys;
		$summerys = $order->summeryBy($groups, $targets, $conditions_repeat, $sortKey, $columns);
		foreach($summerys as $index => $summery){
			$summerys[$index]->subtotal = $summery->payment_total;
			$summerys[$index]->payment_name = $summery->payment()->payment_name;
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")."_repeat"] = $summerys;
	}
}
?>
