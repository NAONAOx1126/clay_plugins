<?php
/**
 * ### Product.Category.Delete
 * 商品カテゴリを削除する。
 */
class Product_Category_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin("product");
			
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
					$category->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("product");
				
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback("product");
				unset($_POST["category_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
