<?php
/**
 * ### Member.Customer.Save
 * 商品のデータを登録する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Customer_Save extends Clay_Plugin_Module_Save{
	function execute($params){
		if(!empty($_POST["pair_id"]) && empty($_POST[$this->key_prefix."pair_id"])){
			$_POST[$this->key_prefix."pair_id"] = $_POST["pair_id"];
		}
		$this->executeImpl("Member", "CustomerModel", "customer_id");
	}
}
?>
