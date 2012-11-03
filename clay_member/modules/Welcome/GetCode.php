<?php
/**
 * 来店管理コードの発行処理
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Welcome_GetCode extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		// 日付の値を取得する。
		$welcomeCode = $loader->loadModel("WelcomeCodeModel");
		$welcomeCode->findByDate(date("Ymd"));
		if(!($welcomeCode->welcome_code_id > 0)){
			// 未発行の場合は発行を行う。
			$welcomeCode->welcome_date = date("Ymd");
			$welcomeCode->welcome_code = "";
			$codes = "0123456789";
			for($i = 0; $i < $params->get("length", 8); $i ++){
				$welcomeCode->welcome_code .= substr($codes, mt_rand(0, strlen($codes) - 1), 1);
			}
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 登録データの保存
				$welcomeCode->save();
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
					
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
			
			// 登録したデータの再取得
			$welcomeCode->findByDate(date("Ymd"));
		}
		// 既に発行済の場合は発行済のコードを渡す
		$_SERVER["ATTRIBUTES"][$params->get("result", "welcome_code")] =  $welcomeCode;
	}
}
?>