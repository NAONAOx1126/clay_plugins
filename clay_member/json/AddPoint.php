<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */
 
class Member_AddPoint{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		if($_POST["customer_id"] > 0){
			$customer = $loader->loadModel("CustomerModel");
			$customer->findByPrimaryKey($_POST["customer_id"]);
		
			if($customer->customer_id > 0){
				// 設定するポイント
				$point = $_POST["point"];
				
				if(!empty($point) && is_numeric($point)){
					// トランザクションの開始
					Clay_Database_Factory::begin("member");
					
					try{
						// タイプ設定を追加した場合、ポイントを追加する。
						$customer->point += $point;
						
						// 変更内容をデータベースに反映
						$customer->save();
						
						// ポイントログに書き込み
						$pointLog = $loader->loadModel("PointLogModel");
						$pointLog->addCustomer($customer->customer_id, $point, $_POST["comment"]);
						
						$customer->findByPrimaryKey($customer->customer_id);
						if($customer->point >= 0){
							// エラーが無く、変更後のポイントが0以上の場合、処理をコミットする。
							Clay_Database_Factory::commit("member");
						}else{
							Clay_Database_Factory::rollback("member");
							$customer->findByPrimaryKey($customer->customer_id);
						}
							
					}catch(Exception $ex){
						Clay_Database_Factory::rollback("member");
					}
				}
			}
		}
		
		return $customer->toArray();
	}
}
?>
