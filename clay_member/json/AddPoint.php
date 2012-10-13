<?php
class Member_AddPoint{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		if($_POST["customer_id"] > 0){
			$customer = $loader->loadModel("CustomerModel");
			$customer->findByPrimaryKey($_POST["customer_id"]);
		
			if($customer->customer_id > 0){
				// 設定するポイント
				$point = $params->get("point", $_POST["point"]);
				
				if(!empty($point) && is_numeric($point)){
					// トランザクションの開始
					DBFactory::begin("member");
					
					try{
						// タイプ設定を追加した場合、ポイントを追加する。
						$customer->point += $point;
						
						// 変更内容をデータベースに反映
						$customer->save();
						
						// ポイントログに書き込み
						$pointLog = $loader->loadModel("PointLogModel");
						$pointLog->addCustomer($customer->customer_id, $point, $_POST["comment"]);
						
						// エラーが無かった場合、処理をコミットする。
						DBFactory::commit("member");
							
					}catch(Exception $ex){
						DBFactory::rollback("member");
						throw $ex;
					}
				}
			}
		}
		
		return $customer->toArray();
	}
}
?>
