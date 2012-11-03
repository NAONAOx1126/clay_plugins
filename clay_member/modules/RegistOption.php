<?php
// この機能で使用するモデルクラス
LoadModel("Setting", "Members");
LoadModel("CustomerOptionModel", "Members");

/**
 * 携帯の個体番号でのログインを実行するモジュールです。
 *
 * @params error ログイン失敗時に遷移するページのテンプレートパス
 * @params redirect ログイン成功時にリダイレクトするURL
 * @params session 顧客情報を保存するセッション名
 * @params auto 1を設定すると、携帯の個体番号が渡っていた場合、自動でユーザー情報を作成する
 * @params result 顧客情報をページで使うためのキー名
 */
class Members_RegistOption extends Clay_Plugin_Module{
	function execute($params){
		if($params->check("option")){
			if(isset($_POST["regist"]) && !empty($_SESSION["INPUT_DATA"])){
				// 画像キーを取得
				$optionKey = $params->get("option");
			
				// トランザクションの開始
				Clay_Database_Factory::begin("member");
				
				try{
					// 顧客データモデルを初期化
					$values = array();
					$option = new CustomerOptionModel(array("customer_id" => $_SERVER["ATTRIBUTES"][$params->get("result", "customer")]->customer_id, "option_name" => $optionKey));
					$option->findByPrimaryKey($_SERVER["ATTRIBUTES"][$params->get("result", "customer")]->customer_id, $optionKey);
					$option->option_value = $_SESSION["INPUT_DATA"][$optionKey];
					
					// 画像データを登録する。
					$option->save();
					
					// エラーが無かった場合、処理をコミットする。
					Clay_Database_Factory::commit("member");
	
					// 結果を登録する。
					$_SESSION[CUSTOMER_SESSION_KEY]->$optionKey = $_SESSION["INPUT_DATA"][$optionKey];
					$_SERVER["ATTRIBUTES"][$params->get("result", "customer")]= $_SESSION[CUSTOMER_SESSION_KEY];					
				}catch(Exception $ex){
					Clay_Database_Factory::rollback("member");
					exit;
					throw $ex;
				}
			}
		}
	}
}
?>