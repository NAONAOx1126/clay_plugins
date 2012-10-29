<?php
class Member_RetireCustomer{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		$customer = $loader->loadModel("CustomerModel");
		$customer->findByPrimaryKey($_POST["customer_id"]);
		
		// トランザクションの開始
		DBFactory::begin("member");
		
		try{
			if($customer->customer_id > 0 && $customer->email == $_POST["email"]){
				// メールアドレスとIDが一致した場合は、アカウントを削除
				$customer->delete();
			}
			
			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit("member");
				
		}catch(Exception $ex){
			DBFactory::rollback("member");
		}
		return $customer->toArray();
	}
}
?>
