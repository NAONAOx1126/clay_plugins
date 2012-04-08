<?php
/**
 * ### Product.Seller.Delete
 * 開発会社を削除する。
 */
class Product_Seller_Delete extends FrameworkModule{
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
				$seller = $loader->loadModel("ProductSellerModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["seller_id"])){
					$_POST["seller_id"] = array($_POST["seller_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["seller_id"] as $seller_id){
					// カテゴリを削除
					$seller->findByPrimaryKey($seller_id);
					$seller->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["seller_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["seller_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
