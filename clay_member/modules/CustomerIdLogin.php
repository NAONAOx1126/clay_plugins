<?php
// この機能で使用するモデルクラス
LoadModel("Setting", "Members");
LoadModel("CustomerModel", "Members");
LoadModel("CustomerTypeModel", "Members");
LoadModel("CustomerOptionModel", "Members");

/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Members_CustomerIdLogin extends Clay_Plugin_Module{
	function execute($params){
		// カスタマモデルを使用して顧客情報を取得
		$customer = new CustomerModel();
		$customer->findByPrimaryKey($_POST[$params->get("key", "customer_id")]);
		
		if(!empty($customer->customer_id)){
			$_SESSION[CUSTOMER_SESSION_KEY] = $customer;
			$customerType = new CustomerTypeModel();
			$_SESSION[CUSTOMER_SESSION_KEY]->types = $customerType->findAllByCustomer($customer->customer_id);
			$customerOption = new CustomerOptionModel();
			$customerOptions = $customerOption->getOptionArrayByCustomer($customer->customer_id);
			foreach($customerOptions as $name => $option){
				$_SESSION[CUSTOMER_SESSION_KEY]->$name = $option->option_value;
			}
		}
		
		if(empty($_SESSION[CUSTOMER_SESSION_KEY])){
			if($params->get("error")){
				throw new Clay_Exception_Invalid(array("ログインに失敗しました"));
			}elseif($params->get("redirect")){
				throw new RedirectException();
			}
		}
				
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>