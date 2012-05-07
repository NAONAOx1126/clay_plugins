<?php
/**
 * ### Member.Contract.Save
 * 契約を登録する。
 */
class Member_Contract_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new PluginLoader("Member");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// POSTされたデータを元にモデルを作成
				$contract = $loader->loadModel("ContractModel");
				$contract->findByPrimaryKey($_POST["contract_id"]);
				
				// データを設定
				$contract->contract_name = $_POST["contract_name"];
				$contract->contract_product_id = $_POST["contract_product_id"];
				$contract->is_initial = $_POST["is_initial"];
				$contract->contract_interval = $_POST["contract_interval"];
				
				// カテゴリを保存
				$contract->save();
						
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
				
				unset($_POST["save"]);
			}catch(Exception $e){
				DBFactory::rollback("member");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
