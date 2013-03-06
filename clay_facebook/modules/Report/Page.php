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
class Facebook_Report_Page extends Clay_Plugin_Module_Page{
	function execute($params){
		if($params->get("admin_role", "1") != $_SERVER["ATTRIBUTES"]["OPERATOR"]->role_id){
			// 管理者以外の場合は条件に組織IDを設定
			$_POST["search"]["company_id"] = $_SERVER["ATTRIBUTES"]["OPERATOR"]->company_id;
		}
		$this->executeImpl($params, "Facebook", "ReportModel", $params->get("result", "reports"));
	}
}
?>
