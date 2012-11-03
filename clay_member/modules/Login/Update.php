<?php
/**
 * ログイン情報を更新する。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Login_Update extends Clay_Plugin_Module{
	function execute($params){
		// この機能で使用するモデルクラス
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();

		// カスタマモデルを使用してセッションから顧客情報を取得
		$customer = $loader->LoadModel("CustomerModel", $_SESSION[CUSTOMER_SESSION_KEY]);
		
		if($customer->customer_id > 0){
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
		}
		
		// 変更したデータで更新する。
		$_SESSION[CUSTOMER_SESSION_KEY] = $customer->toArray();
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>