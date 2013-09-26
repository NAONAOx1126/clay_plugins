<?php
/**
 * ### Order.Page
 * 商品のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Order_Page extends Clay_Plugin_Module_Page{
	function execute($params){
		$this->executeImpl($params, "Order", "OrderModel", $params->get("result", "orders"));
	}
}
?>
