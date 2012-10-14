<?php
class Member_RegisterCustomer{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($_POST["customer_id"]);
		if(!($customer->customer_id > 0)){
			// メールアドレスが一致した場合も同一ユーザーとみなす。
			$customer->findByEmail($_POST["email"]);
		}

		// 新規登録時は登録ポイントを設定。
		if(!($customer->customer_id > 0)){
			if(empty($_POST["point"])){
				$_POST["point"] = 0;
			}
			$rule = $loader->loadModel("PointRuleModel");
			
			// 新規登録時は登録ポイントを登録
			$pointLog = $loader->loadModel("PointLogModel");
			$pointLog->addRuledPoint($rule, Member_PointRuleModel::RULE_ENTRY);
		}
		
		foreach($_POST as $key => $value){
			$customer->$key = $value;
		}
		// トランザクションの開始
		DBFactory::begin("member");
		
		try{
			// 登録データの保存
			$customer->save();
			$customer->findByPrimaryKey($customer->customer_id);
			
			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit("member");
				
		}catch(Exception $ex){
			DBFactory::rollback("member");
			throw $ex;
		}
		return $customer->toArray();
	}
}
?>
