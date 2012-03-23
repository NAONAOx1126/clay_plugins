<?php
/**
 * ### Product.Category.Delete
 * 商品カテゴリを削除する。
 */
class Product_Category_Delete extends FrameworkModule{
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
				$category = $loader->loadModel("CategoryModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["category_id"])){
					$_POST["category_id"] = array($_POST["category_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["category_id"] as $category_id){
					// カテゴリを削除
					$category->findByPrimaryKey($category_id);
					$category->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				throw $e;
			}
		}
	}
}
?>
