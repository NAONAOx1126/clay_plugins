<?php
/**
 * 指定した顧客にポイントの付与・消費を実行するモジュールです。
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Customer_AddPointUser extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
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
		
	}
}
?>