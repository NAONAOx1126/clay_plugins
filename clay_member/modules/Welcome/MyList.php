<?php
/**
 * ### Member.Customer.MyList
 * 自分の商品のリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Member_Welcome_MyList extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		$conditions = array();
		$conditions["operator_id"] = $_SESSION["OPERATOR"]["operator_id"];
		if($params->check("commit")){
			$conditions["commit_flg"] = $params->get("commit");
		}
		
		if($params->check("today")){
			$conditions["ge:reserve_start"] = date("Y-m-d 00:00:00");
			$conditions["le:reserve_start"] = date("Y-m-d 23:59:59");
		}
		
		// 商品データを検索する。
		$welcome = $loader->LoadModel("WelcomeModel");
		$welcomes = $welcome->findAllBy($conditions, "reserve_start");
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "welcomes")] = $welcomes;
	}
}
?>
