<?php
/**
 * ログインしている顧客にポイントの付与・消費の予約を実行するモジュールです。
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Customer_AddTempPoint extends FrameworkModule{
	function execute($params){
		// ローダーの初期化
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		// 設定するポイント
		$point = $params->get("point", $_POST["point"]);
		$pointComment = $params->get("point_comment", $_POST["point_comment"]);
		
		if(!empty($point) && is_numeric($point)){
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// ポイントログに書き込み
				$pointLog = $loader->loadModel("PointLogModel");
				$pointLog->add($_SESSION[CUSTOMER_SESSION_KEY]->customer_id, $point, $pointComment, false);
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
				
				// ポイント登録した場合はPOSTから削除
				$_POST["point"] = 0;
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
		}
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>