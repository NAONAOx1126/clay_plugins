<?php
/**
 * 来店管理コードの発行処理
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Welcome_CheckCode extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		// 日付の値を取得する。
		$welcomeCode = $loader->loadModel("WelcomeCodeModel");
		$welcomeCode->findByDate(date("Ymd"));
		
		// 入力したコードと取得したコードが一致しない場合は例外発行
		if($welcomeCode->welcome_code != $_POST["welcome_code"]){
			throw new InvalidException(array("コードが正しくありません。"));
		}
	}
}
?>