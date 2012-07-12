<?php
/**
 * ### Member.Customer.Delete
 * 商品を削除する。
 */
class Member_Customer_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Member");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("member");
			
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
				DBFactory::commit("member");
				
				unset($_POST["welcome_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("member");
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
