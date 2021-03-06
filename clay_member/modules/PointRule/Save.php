<?php
/**
 * 来店管理コードの発行処理
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_PointRule_Save extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$pointRule = $loader->loadModel("PointRuleModel");
		// 既に登録済みか調べる
		$pointRule->findByPrimaryKey($_POST["point_rule_id"]);
		
		// データを登録する。
		// 商品データをモデルに格納して保存する。
		foreach($_POST as $key => $value){
			$pointRule->$key = $value;
		}
		
		if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $pointRule->point_rule_start_time) > 0){
			$pointRule->point_rule_start_time .= " 00:00:00";
		}else{
			$pointRule->point_rule_start_time = null;
		}
		if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $pointRule->point_rule_end_time) > 0){
			$pointRule->point_rule_end_time .= " 23:59:59";
		}else{
			$pointRule->point_rule_end_time = null;
		}
		
		// トランザクションの開始
		Clay_Database_Factory::begin("member");
		
		try{
			// 登録データの保存
			$pointRule->save();
			$_POST["point_rule_id"] = $pointRule->point_rule_id;
			
			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit("member");
				
		}catch(Exception $ex){
			Clay_Database_Factory::rollback("member");
			throw $ex;
		}
	}
}
?>