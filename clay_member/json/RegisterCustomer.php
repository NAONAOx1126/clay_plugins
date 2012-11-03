<?php
class Member_RegisterCustomer{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($_POST["customer_id"]);
		if(!($customer->customer_id > 0)){
			// メールアドレスが一致した場合も同一ユーザーとみなす。
			$customer->findByEmail($_POST["email"]);
		}

		foreach($_POST as $key => $value){
			$customer->$key = $value;
		}
		// トランザクションの開始
		Clay_Database_Factory::begin("member");
		
		try{
			// 登録前の顧客IDを保持
			$preCustomerId = $customer->customer_id;
			
			// 登録データの保存
			$customer->save();
			
			// 新規登録時は登録ポイントを設定。
			if(!($preCustomerId > 0)){
				$rule = $loader->loadModel("PointRuleModel");
				
				// 新規登録時は登録ポイントを登録
				$pointLog = $loader->loadModel("PointLogModel");
				$pointLog->addCustomerRuledPoint($customer->customer_id, $rule, Member_PointRuleModel::RULE_ENTRY);
			}
			
			$customer->findByPrimaryKey($customer->customer_id);
			
			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit("member");
				
		}catch(Exception $ex){
			Clay_Database_Factory::rollback("member");
		}
		return $customer->toArray();
	}
}
?>
