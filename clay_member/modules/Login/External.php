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
class Member_Login_External extends FrameworkModule{
	function execute($params){
		if(!empty($_POST["external_id"]) || !isset($_SESSION[CUSTOMER_SESSION_KEY]) || empty($_SESSION[CUSTOMER_SESSION_KEY])){
			// この機能で使用するモデルクラス
			$loader = new PluginLoader("Member");
			$loader->LoadSetting();
	
			// カスタマモデルを使用して顧客情報を取得
			$customer = $loader->LoadModel("CustomerModel");
			$customer->findByExternalId($_POST["external_id"]);
			
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
						
						// 新規登録時は登録ポイントを設定。
						if(empty($_POST["point"])){
							$_POST["point"] = 0;
						}
						$rule = $loader->loadModel("PointRuleModel");
					
						// 新規登録時は登録ポイントを登録
						$_POST["customer_id"] = $customer->customer_id;
						$pointLog = $loader->loadModel("PointLogModel");
						$pointLog->addCustomerRuledPoint($customer->customer_id, $rule, Member_PointRuleModel::RULE_ENTRY);
		
						// エラーが無かった場合、処理をコミットする。
						DBFactory::commit("member");
					}catch(Exception $ex){
						DBFactory::rollback("member");
						throw $ex;
					}
					
					// 再度モバイルIDで検索
					$customer->findByExternalId($_POST["external_id"]);
				}else{
					if($params->get("error")){
						throw new Clay_Exception_Invalid(array("ログインに失敗しました"));
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