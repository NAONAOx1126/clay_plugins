<?php
/**
 * ### Content.Advertise.Save
 * 広告を登録する。
 */
class Content_Advertise_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// POSTされたデータを元にモデルを作成
				$advertise = $loader->loadModel("AdvertiseModel");
				$advertise->findByPrimaryKey($_POST["contract_id"]);
				
				// データを設定
				$advertise->advertise_key = $_POST["advertise_key"];
				$advertise->advertise_code = $_POST["advertise_code"];
				$advertise->advertise_price = $_POST["advertise_price"];
				$advertise->advertise_name = $_POST["advertise_name"];
				$advertise->advertise_start_time = date("Y-m-d 0:00:00", strtotime($_POST["advertise_start_time"]));
				$advertise->advertise_end_time = date("Y-m-d 23:59:59", strtotime($_POST["advertise_end_time"]));
				
				// カテゴリを保存
				$advertise->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST["save"]);
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
