<?php
/**
 * ### Facebook.Group.List
 * 商品のリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Facebook_Group_List extends Clay_Plugin_Module_List{
	function execute($params){
		if($_POST["search"]["active"] == "1"){
			$_POST["search"]["ge:start_time"] = date("Y-m-d 00:00:00");
			$_POST["search"]["le:end_time"] = date("Y-m-d 00:00:00");
		}
		if($params->get("admin_role", "1") != $_SERVER["ATTRIBUTES"]["OPERATOR"]->role_id){
			// 管理者以外の場合は条件に組織IDを設定
			$_POST["search"]["company_id"] = $_SERVER["ATTRIBUTES"]["OPERATOR"]->company_id;
		}
		$this->executeImpl($params, "Facebook", "GroupModel", $params->get("result", "groups"));
	}
}