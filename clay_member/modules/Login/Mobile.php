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
class Member_Login_Mobile extends FrameworkModule{
	function execute($params){
		// この機能で使用するモデルクラス
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();

		// アクセスしてきたモバイルIDを取得
		if(!empty($_SERVER["HTTP_X_DCMGUID"])){
			// ドコモID(GUID対応)
			$mobileId = $_SERVER["HTTP_X_DCMGUID"];
		}elseif(!empty($_SERVER["HTTP_X_UP_SUBNO"])){
			// au端末の場合
			$mobileId = $_SERVER["HTTP_X_UP_SUBNO"];
		}elseif(!empty($_SERVER["HTTP_X_JPHONE_UID"])){
			// Softbank端末の場合
			$mobileId = $_SERVER["HTTP_X_JPHONE_UID"];
		}elseif(preg_match("/^.+ser([0-9a-zA-Z]+).*$/", $_SERVER["HTTP_USER_AGENT"], $ua) > 0){
			// ドコモのユーザーエージェントから取得
			$mobileId = $ua[1];
		}elseif(preg_match("/^.+\/SN([0-9a-zA-Z]+).*$/", $_SERVER["HTTP_USER_AGENT"], $ua) > 0){
			// Softbankのユーザーエージェントから取得
			$mobileId = $ua[1];
		}else{
			$mobileId = $_SESSION["MOBILE_GUID"];
		}
		
		// モバイルIDが設定おらず、guidが未設定の場合は、GUID付きのURLにリダイレクトする。
		if(empty($mobileId)){
			if(empty($_POST["guid"])){
				header("Location: ".((strpos($_SERVER["REQUEST_URI"], "?") > 0)?$_SERVER["REQUEST_URI"]."&guid=ON":$_SERVER["REQUEST_URI"]."?guid=ON"));
				exit;
			}
			// guidが設定されていても取得できない場合は、エラーとする。
			throw new InvalidException(array("ログインに失敗しました。"));
		}
		
		// モバイルのGUIDをセッションに保存
		$_SESSION["MOBILE_GUID"] = $mobileId;
		
		// モバイルIDが渡った場合にはユーザ情報を取得する。
		if(!empty($mobileId)){
			// カスタマモデルを使用して顧客情報を取得
			$customer = $loader->LoadModel("CustomerModel");
			$customer->findByMobileId($mobileId);
			
			if(empty($customer->customer_id)){
				// 該当するデータが無い場合はデータを作成
				if($params->get("auto", "0") == "1"){
					// トランザクションの開始
					DBFactory::begin("member");
					
					try{
						// データを登録する。
						$customer->mobile_id = $mobileId;
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
						unset($_POST["regist"]);
						DBFactory::rollback("member");
						throw $ex;
					}
					
					// 再度モバイルIDで検索
					$customer->findByMobileId($mobileId);
				}
			}
			if(!empty($customer->customer_id)){
				$_SESSION[CUSTOMER_SESSION_KEY] = $customer->toArray();
			}
		}
	
		if(empty($_SESSION[CUSTOMER_SESSION_KEY])){
			if($params->get("error")){
				throw new InvalidException(array("ログインに失敗しました"));
			}elseif($params->get("redirect")){
				throw new RedirectException();
			}
		}
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>