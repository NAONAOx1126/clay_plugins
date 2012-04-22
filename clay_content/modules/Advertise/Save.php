<?php
/**
 * ### Content.Advertise.Save
 * 広告を登録する。
 */
class Content_Advertise_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new PluginLoader("Content");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("content");
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// POSTされたデータを元にモデルを作成
				$advertise = $loader->loadModel("AdvertiseModel");
				$advertise->findByPrimaryKey($_POST["contract_id"]);
				
				// データを設定
				$advertise->advertise_key = $_POST["advertise_key"];
				$advertise->advertise_code = $_POST["advertise_code"];
				$advertise->advertise_price = $_POST["advertise_price"];
				$advertise->advertise_name = $_POST["advertise_name"];
				$advertise->advertise_start_time = $_POST["advertise_start_time"];
				$advertise->advertise_end_time = $_POST["advertise_end_time"];
				
				// カテゴリを保存
				$advertise->save($db);
						
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["save"]);
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
