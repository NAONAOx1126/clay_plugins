<?php
// この機能で使用するモデルクラス
LoadModel("Setting", "Members");
LoadModel("CustomerModel", "Members");
LoadModel("CustomerTypeModel", "Members");

/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Members_Retire extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["retire"])){
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// オプションデータを削除
				$customerOption = new CustomerOptionModel();
				$customerOptions = $customerOption->getOptionArrayByCustomer($_SESSION[CUSTOMER_SESSION_KEY]->customer_id);
				foreach($customerOptions as $option){
					$option->delete();
				}
			
				// 顧客種別を削除
				$customerType = new CustomerTypeModel();
				$customerTypes = $customerType->findAllByCustomer($_SESSION[CUSTOMER_SESSION_KEY]->customer_id);
				foreach($customerTypes as $type){
					$type->delete();
				}
			
				// 顧客データを削除
				$customer = new CustomerModel();
				$customer->findByPrimaryKey($_SESSION[CUSTOMER_SESSION_KEY]->customer_id);
				$customer->delete();
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
	
				// セッションをクリア
				unset($_SESSION[CUSTOMER_SESSION_KEY]);
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
		}
	}
}
?>