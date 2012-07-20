<?php
/**
 * ### Member.Customer.Save
 * 商品のデータを登録する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Member_Customer_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"])){
			// 商品情報を登録する。
			$loader = new PluginLoader("Member");
			$loader->LoadSetting();
	
			// トランザクションの開始
			DBFactory::begin("member");
		
			try{
				// 商品データを検索する。
				$customer = $loader->LoadModel("CustomerModel");
				if(!empty($_POST["customer_id"])){
					$customer->findByPrimaryKey($_POST["customer_id"]);
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
				
				// 商品データをモデルに格納して保存する。
				foreach($_POST as $key => $value){
					$customer->$key = $value;
				}
				
				$customer->save();
				$_POST["customer_id"] = $customer->customer_id;
				
				// 顧客のオプションを登録する。
				if(isset($_POST["option"])){
					$customerOptions = $customer->customerOptions();
					foreach($customerOptions as $customerOption){
						// 登録時にオプションは全て削除
						$customerOption->delete();
					}
					if(is_array($_POST["option"])){
						foreach($_POST["option"] as $name => $value){
							// データを登録
							$insert = new DatabaseInsert($loader->LoadModel("CustomerOptionsTable"));
							$data = array(
								"customer_id" => $customer->customer_id, 
								"option_name" => $name, 
								"option_value" => $value, 
								"create_time" => date("Y-m-d H:i:s"), 
								"update_time" => date("Y-m-d H:i:s")
							);
							$insert->execute($data);
						}
					}
				}
				
				unset($_POST["save"]);
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
			}catch(Exception $e){
				DBFactory::rollback("member");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
