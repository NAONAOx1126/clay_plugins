<?php
class Member_LoginCustomer{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByEmail($_POST["email"]);
		
		if($customer->customer_id > 0){
			// パスワードチェックする。　
			if($customer->password != $_POST["password"]){
				// パスワードが違う場合はユーザー情報を作り直し空にする
				$customer = $loader->loadModel("CustomerModel");
			}
		}
		
		return $customer->toArray();
	}
}
?>
