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
class Member_Login_Form extends FrameworkModule{
	function execute($params){
		// この機能で使用するモデルクラス
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// カスタマモデルを使用して顧客情報を取得
		$customer = $loader->LoadModel("CustomerModel");
		$customer->findByEmail($_POST["email"]);
		
		if(empty($customer->customer_id)){
			// 該当するデータが無い場合はデータを作成
			if($params->get("auto", "0") == "1"){
				// トランザクションの開始
				DBFactory::begin("member");
				
				try{
					// データを登録する。
					foreach($_POST as $key => $value){
						$customer->$key = $value;
					}
					$customer->save();
					
					// エラーが無かった場合、処理をコミットする。
					DBFactory::commit("member");
				}catch(Exception $ex){
					DBFactory::rollback("member");
					throw $ex;
				}
				
				// 再度モバイルIDで検索
				$customer->findByEmail($_POST["email"]);
			}
		}
		if($customer->customer_id > 0){
			if($params->get("nopass", "0") == "0"){
				// パスワードチェックする。　
				if($customer->password != $_POST["password"]){
					if($params->get("error")){
						throw new InvalidException(array("ログインに失敗しました"));
					}elseif($params->get("redirect")){
						throw new RedirectException();
					}
				}
			}

			$_SESSION[CUSTOMER_SESSION_KEY] = $customer->toArray();
		}
	
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>