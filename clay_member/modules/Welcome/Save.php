<?php
/**
 * 来店管理コードの発行処理
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Welcome_Save extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$welcome = $loader->loadModel("WelcomeModel");
		// 既に登録済みか調べる
		$welcome->findByPrimaryKey($_POST["welcome_id"]);
		if(!($welcome->welcome_id > 0)){
			// 既に来店済みか調べる
			$welcome->findByWelcomeCustomer(date("Ymd"), $_SESSION[CUSTOMER_SESSION_KEY]["customer_id"]);
		}
		
		// データを登録する。
		$welcome->welcome_date = date("Ymd");
		$welcome->customer_id = $_SESSION[CUSTOMER_SESSION_KEY]["customer_id"];
		$oldCommit = $welcome->commit_flg;
		$welcome->commit_flg = $params->get("commit", "1");
		// 商品データをモデルに格納して保存する。
		foreach($_POST as $key => $value){
			$welcome->$key = $value;
		}
		
		// トランザクションの開始
		DBFactory::begin("member");
		
		try{
			// 新規登録時は来店ポイントを設定。
			if(!($welcome->welcome_id > 0) || ($oldCommit == "0" && $welcome->commit_flg == "1")){
				if(empty($_POST["point"])){
					$_POST["point"] = 0;
				}
				$rule = $loader->loadModel("PointRuleModel");
					
				// 新規登録時は登録ポイントを登録
				$customer = $welcome->customer();
				$pointLog = $loader->loadModel("PointLogModel");
				$pointLog->addRuledPoint($rule, Member_PointRuleModel::RULE_WELCOME);
				
				// 来店処理した日時を設定
				$welcome->commit_time = date("Y-m-d H:i:s");
			}
		
			// 登録データの保存
			$welcome->save();
			$_POST["welcome_id"] = $welcome->welcome_id;
			
			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit("member");
				
		}catch(Exception $ex){
			DBFactory::rollback("member");
			throw $ex;
		}
	}
}
?>