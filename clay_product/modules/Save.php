<?php
/**
 * ### Product.Save
 * 商品のデータを登録する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Product_Save extends FrameworkModule{
	function execute($params){
		if(isset($_POST["save"])){
			// 商品情報を登録する。
			$loader = new PluginLoader("Product");
			$loader->LoadSetting();
	
			// トランザクションデータベースの取得
			$db = DBFactory::getConnection("product");
			
			// トランザクションの開始
			$db->beginTransaction();
		
			try{
				// 商品データを検索する。
				$product = $loader->LoadModel("ProductModel");
				if(!empty($_POST["product_id"])){
					$product->findByPrimaryKey($_POST["product_id"]);
				}
				
				// 商品データをモデルに格納して保存する。
				foreach($_POST as $key => $value){
					$product->$key = $value;
				}
				$product->save($db);
				
				// 商品のカテゴリを登録する。
				$categories = $product->productCategories();
				foreach($categories as $category){
					// 登録するカテゴリに含まれない場合は削除
					if(!isset($_POST["category"][$category->category_id])){
						$category->delete($db);
					}
				}
				// カテゴリの要素をInsert Ignoreで登録する。
				if(empty($_POST["category"])) $_POST["category"] = array();
				foreach($_POST["category"] as $category_id){
					$insert = new DatabaseInsertIgnore($loader->LoadModel("ProductCategoriesTable"), $db);
					$insert->execute(array(
						"product_id" => $product->product_id, 
						"category_id" => $category_id, 
						"create_time" => date("Y-m-d H:i:s"), 
						"update_time" => date("Y-m-d H:i:s")
					));
				}

				// 商品のフラグを登録する。
				$flags = $product->productFlags();
				foreach($flags as $flag){
					// 登録するカテゴリに含まれない場合は削除
					if(!isset($_POST["flag"][$flag->flag_id])){
						$flag->delete($db);
					}
				}
				// カテゴリの要素をInsert Ignoreで登録する。
				if(empty($_POST["flags"])) $_POST["flags"] = array();
				foreach($_POST["flags"] as $flag_id){
					$insert = new DatabaseInsertIgnore($loader->LoadModel("ProductFlagsTable"), $db);
					$insert->execute(array(
						"product_id" => $product->product_id, 
						"flag_id" => $flag_id, 
						"create_time" => date("Y-m-d H:i:s"), 
						"update_time" => date("Y-m-d H:i:s")
					));
				}

				// 商品の画像を登録する。
				$images = $product->images();
				// カテゴリの要素をInsert Ignoreで登録する。
				if(empty($_POST["image_name"])) $_POST["image_name"] = array();
				if(empty($_POST["image"])) $_POST["image"] = array();
				foreach($_POST["image_name"] as $image_type => $image_name){
					if(!empty($image_name)){
						// 画像のファイル名を取得
						$image_file = $_POST["image"][$image_type];
						$image = $product->image($image_type);
						if($image->product_id > 0){
							// データが存在する場合にはアップデート
							$image->image_name = $image_name;
							$image->image = $image_file;
							$image->save($db);
						}else{
							// データが存在しない場合は登録する。
							$insert = new DatabaseInsertIgnore($loader->LoadModel("ProductImagesTable"), $db);
							$insert->execute(array(
								"product_id" => $product->product_id, 
								"image_type" => $image_type, 
								"image_name" => $image_name, 
								"image" => $image_file, 
								"create_time" => date("Y-m-d H:i:s"), 
								"update_time" => date("Y-m-d H:i:s")
							));
						}
					}
				}
				
				unset($_POST["save"]);
				
				// エラーが無かった場合、処理をコミットする。
				$db->commit();
			}catch(Exception $e){
				$db->rollBack();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
