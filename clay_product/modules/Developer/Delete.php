<?php
/**
 * ### Product.Developer.Delete
 * 開発会社を削除する。
 */
class Product_Developer_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Product");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection();
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$developer = $loader->loadModel("ProductDeveloperModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["developer_id"])){
					$_POST["developer_id"] = array($_POST["developer_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["developer_id"] as $developer_id){
					// カテゴリを削除
					$developer->findByPrimaryKey($developer_id);
					$developer->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["developer_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["developer_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
