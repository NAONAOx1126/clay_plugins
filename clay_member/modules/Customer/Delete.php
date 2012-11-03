<?php
/**
 * ### Member.Customer.Delete
 * 商品を削除する。
 */
class Member_Customer_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Member");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$customer = $loader->loadModel("CustomerModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["customer_id"])){
					$_POST["customer_id"] = array($_POST["customer_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["customer_id"] as $customer_id){
					// カテゴリを削除
					$customer->findByPrimaryKey($customer_id);
					foreach($customer->customerOptions() as $customerOption){
						$customerOption->delete();
					}
					foreach($customer->customerDelivers() as $customerDeliver){
						$customerDeliver->delete();
					}
					foreach($customer->pointLogs() as $pointLog){
						$pointLog->delete();
					}
					$customer->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
				
				unset($_POST["customer_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("member");
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
