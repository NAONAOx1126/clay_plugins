<?php
/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Login_Android extends FrameworkModule{
	function execute($params){
		// この機能で使用するモデルクラス
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// アクセスしてきた端末の情報を取得
		$android = array();
		if(!empty($_SERVER["HTTP_X_ANDROID_ID"])){
			// Android　ID
			$android["customer_code"] = $_SERVER["HTTP_X_ANDROID_ID"];
		}
		if(!empty($_SERVER["HTTP_X_MOBILE_ID"])){
			// 端末ID
			$android["mobile_id"] = $_SERVER["HTTP_X_MOBILE_ID"];
		}
		if(!empty($_SERVER["HTTP_X_PHONE_ID"]) && strlen(str_replace("-", "", $_SERVER["HTTP_X_PHONE_ID"])) == 11){
			// 電話番号
			$android["tel1"] = substr(str_replace("-", "", $_SERVER["HTTP_X_PHONE_ID"]), 0, 3);
			$android["tel2"] = substr(str_replace("-", "", $_SERVER["HTTP_X_PHONE_ID"]), 3, 4);
			$android["tel3"] = substr(str_replace("-", "", $_SERVER["HTTP_X_PHONE_ID"]), 7, 4);
		}
		if(!empty($_SERVER["HTTP_X_GOOGLE_ID"])){
			// Google PlayのID
			$android["email"] = $_SERVER["HTTP_X_GOOGLE_ID"];
		}
		
		// 端末の情報でチェック対象にするキーのリストを取得
		$checkers = explode(",", $params->get("check", "customer_code"));
		
		// 入力チェック
		$conditions = array();
		foreach($checkers as $checker){
			if(empty($android[$checker])){
				throw new Clay_Exception_Invalid(array($checker."が取得できませんでした。"));
			}else{
				$conditions[$checker] = $android[$checker];
			}
		}
		
		// 該当のデータを取得できるか調べる
		$customer = $loader->LoadModel("CustomerModel");
		$customer->findBy($conditions);
		if(!($customer->customer_id > 0)){
			if($params->get("auto", "0") == "1"){
				// 認証がNGだった場合でauto=1だった場合は自動登録
				foreach($android as $key => $value){
					$customer->$key = $value;
				}
				
				// トランザクションの開始
				DBFactory::begin("member");
				
				try{
					// データを保存
					$customer->save();
					
					// エラーが無かった場合、処理をコミットする。
					DBFactory::commit("member");
				}catch(Exception $e){
					DBFactory::rollback("member");
					throw $e;
				}
			}
		}
		
		if($customer->customer_id > 0){
			$_SESSION[CUSTOMER_SESSION_KEY] = $customer;
			$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
		}else{
			throw new Clay_Exception_Invalid(array("ログインに失敗しました"));
		}
	}
}
?>