<?php
class Checkin_PlayMusic{
	// 更新系の処理のため、キャッシュを無効化
	public $disable_cache = true;
	
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Checkin");
		$loader->LoadSetting();
		
		// トランザクションの開始
		Clay_Database_Factory::begin("checkin");
		
		try{
			$log = $loader->loadModel("LogModel");
			$log->log_type = CHECKIN_LOG_TYPE_MUSIC;
			$log->log_data = serialize($_POST);	
			$log->save();
			
			// エラーが無く、変更後のポイントが0以上の場合、処理をコミットする。
			Clay_Database_Factory::commit("checkin");
			return array("result" => "OK");
		}catch(Exception $ex){
			Clay_Database_Factory::rollback("checkin");
			return array("result" => "NG", "error" => $ex->getMessage());
		}
	}
}
?>
