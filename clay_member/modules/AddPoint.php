<?php
// この機能で使用するモデルクラス
LoadModel("Setting", "Members");
LoadModel("CustomerModel", "Members");
LoadModel("CustomerTypeModel", "Members");
LoadModel("PointLogModel", "Members");
LoadModel("TypeModel", "Members");

/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params session 顧客情報を保存するセッション名
 * @params type 設定する顧客種別
 * @params result 顧客情報をページで使うためのキー名
 */
class Members_AddPoint extends FrameworkModule{
	function execute($params){
		// 設定するポイント
		$point = $params->get("point", $_POST["point"]);
		
		if(!empty($point)){
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 決済金額とポイントが一致するかチェック
				if($_POST["payment_charge"] == $point){
					// タイプ設定を追加した場合、ポイントを追加する。
					$_SESSION[CUSTOMER_SESSION_KEY]->point += $point;
					
					// 変更内容をデータベースに反映
					$_SESSION[CUSTOMER_SESSION_KEY]->save();
					$customerTypes = $_SESSION[CUSTOMER_SESSION_KEY]->types;
					foreach($customerTypes as $customerType){
						$customerType->save();
					}
					
					// ポイントログに書き込み
					$pointLog = new PointLogModel();
					$pointLog->save($point);
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
					
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
		}
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "customer")] = $_SESSION[CUSTOMER_SESSION_KEY];
	}
}
?>