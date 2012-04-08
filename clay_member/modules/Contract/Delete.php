<?php
/**
 * ### Member.Contract.Delete
 * 契約を削除する。
 */
class Member_Contract_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Member");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("member");
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$contract = $loader->loadModel("ContractModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["contract_id"])){
					$_POST["contract_id"] = array($_POST["contract_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["contract_id"] as $contract_id){
					// カテゴリを削除
					$contract->findByPrimaryKey($contract_id);
					$contract->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["contract_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["contract_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
