<?php
/**
 * ### Member.Customer.Page
 * 商品のリストをページング付きで取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Admin_Operator_Page extends Clay_Plugin_Module_Page{
	function execute($params){
		if($params->check("roles")){
			$_POST["search"]["in:role_id"] = explode(",", $params->get("roles"));
		}
		$this->executeImpl($params, "Admin", "CompanyOperatorModel", $params->get("result", "operators"));
	}
}
?>
