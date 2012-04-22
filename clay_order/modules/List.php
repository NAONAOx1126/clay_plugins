<?php
/**
 * ### Order.List
 * 商品のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Order_List extends FrameworkModule{
	function execute($params){
		// ローダーを初期化
		$loader = new PluginLoader("Order");
		
		$order = $loader->loadModel("OrderModel");
		
		// 商品コード・作品名・商品名による検索
		$condition = array();
		if(!empty($_POST["product_code"]) || !empty($_POST["parent_name"]) || !empty($_POST["product_name"])){
			$cond = array();
			if($_POST["product_code"]){
				
			}
		}
		
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
		$option["totalItems"] = $order->countBy($conditions);
		
		// ページャーのインスタンスを作成
		$pager = AdvancedPager::factory($option);
		
		// 最終的に出力するページオブジェクトを生成
		$page = array();
		// ページャーからリンクをコピー
		$page["links"] = $pager->links;
		$page["links_object"] = $pager->getLinks();

		// オプションから該当件数を取得
		$page["totalItems"] = $options["totalItems"];

		// ページ数をページャーから取得
		$page["page_numbers"] = array(
			"current" => $pager->getCurrentPageID(),
			"total"   => $pager->numPages()
		);

		// 現在のページにおけるデータ全体に対するインデックスを取得
		list($page["from"], $page["to"]) = $pager->getOffsetByPageId();

		// 現在のページの実件数を取得
		$page["limit"] = $page["to"] - $page["from"] +1;
		
		// 取得する件数を絞り込み
		$order->limit($options["perPage"], $page["from"]-1);
		$page["data"] = $order->findAllBy($conditions, $sortKey);

		$_SERVER["ATTRIBUTES"][$params->get("result", "orders")] = $page;
	}
}
?>
