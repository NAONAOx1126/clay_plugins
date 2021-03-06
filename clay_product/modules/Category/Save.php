<?php
/**
 * ### Product.Category.Save
 * 商品カテゴリを登録する。
 */
class Product_Category_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin("product");
			
			try{
				// POSTされたデータを元にモデルを作成
				$category = $loader->loadModel("CategoryModel");
				$category->findByPrimaryKey($_POST["category_id"]);
				
				// データを設定
				$category->category_group_id = $_POST["category_group_id"];
				$category->category_type_id = $_POST["category_type_id"];
				$category->category_code = $_POST["category_code"];
				$category->category_name = $_POST["category_name"];
				$category->category_image = $_POST["category_image"];
				$category->description = $_POST["description"];
				$category->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$category->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("product");
				
				unset($_POST["save"]);
			}catch(Exception $e){
				Clay_Database_Factory::rollback("product");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
