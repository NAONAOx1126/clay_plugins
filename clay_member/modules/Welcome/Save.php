<?php
/**
 * 来店管理コードの発行処理
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Welcome_Save extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		// 既に来店済みか調べる
		$welcome = $loader->loadModel("WelcomeModel");
		$welcome->findByWelcomeCustomer(date("Ymd"), $_SESSION[CUSTOMER_SESSION_KEY]["customer_id"]);
		
		if(!($welcome->welcome_id > 0)){
			// 未登録の場合は登録する。
			$welcome->welcome_date = date("Ymd");
			$welcome->customer_id = $_SESSION[CUSTOMER_SESSION_KEY]["customer_id"];
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 登録データの保存
				$welcome->save();
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
					
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
			
			// 登録したデータの再取得
			$welcome->findByWelcomeCustomer(date("Ymd"), $_SESSION[CUSTOMER_SESSION_KEY]["customer_id"]);
		}
		// 既に発行済の場合は発行済のコードを渡す
		$_SERVER["ATTRIBUTES"][$params->get("result", "welcome")] =  $welcome;
	}
}
?>