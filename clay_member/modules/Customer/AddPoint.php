<?php
/**
 * ログインしている顧客にポイントの付与・消費を実行するモジュールです。
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Member_Customer_AddPoint extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		// 設定するポイント
		$point = $params->get("point", $_POST["add_point"]);
		
		if(!empty($point) && is_numeric($point)){
			// トランザクションの開始
			Clay_Database_Factory::begin("member");
			
			try{
				// タイプ設定を追加した場合、ポイントを追加する。
				$_SESSION[CUSTOMER_SESSION_KEY]->point += $point;
				
				// 変更内容をデータベースに反映
				$_SESSION[CUSTOMER_SESSION_KEY]->save();
				
				// ポイントログに書き込み
				$pointLog = $loader->loadModel("PointLogModel");
				$pointLog->add($_SESSION[CUSTOMER_SESSION_KEY]->customer_id, $point);
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("member");
					
			}catch(Exception $ex){
				Clay_Database_Factory::rollback("member");
				throw $ex;
			}
		}
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>