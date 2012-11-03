<?php
/**
 * ### Member.Welcome.Delete
 * 商品を削除する。
 */
class Member_Welcome_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Member");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin("member");
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$welcome = $loader->loadModel("WelcomeModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["welcome_id"])){
					$_POST["welcome_id"] = array($_POST["welcome_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["welcome_id"] as $welcome_id){
					// カテゴリを削除
					$welcome->findByPrimaryKey($welcome_id);
					$welcome->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("member");
				
				unset($_POST["welcome_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback("member");
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
