<?php
/**
 * ### Product.Delete
 * 商品を削除する。
 */
class Product_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Product");
			$loader->LoadSetting();
			
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("product");
			
			// トランザクションの開始
			$db->beginTransaction();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$product = $loader->loadModel("ProductModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["product_id"])){
					$_POST["product_id"] = array($_POST["product_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["product_id"] as $product_id){
					// カテゴリを削除
					$product->findByPrimaryKey($product_id);
					foreach($product->productCategories() as $productCategory){
						$productCategory->delete($db);
					}
					foreach($product->productFlags() as $productFlag){
						$productFlag->delete($db);
					}
					foreach($product->images() as $image){
						$image->delete($db);
					}
					foreach($product->options() as $option){
						$option->delete($db);
					}
					$product->delete($db);
				}
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
				
				unset($_POST["product_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
