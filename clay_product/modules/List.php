<?php
/**
 * ### Product.List
 * 商品のリストを取得する。
 * @param category 検索条件とするカテゴリ
 * @param category2 検索条件とするカテゴリ
 * @param flag 検索条件とするフラグ
 * @param result 結果を設定する配列のキーワード
 */
class Product_List extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		// カテゴリが選択された場合、カテゴリの商品IDのリストを使う
		$conditions = array();
		if(is_array($_POST["search"])){
			foreach($_POST["search"] as $key => $value){
				if(!empty($value)){
					$conditions[$key] = $value;
				}
			}
		}
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->get("clear", "0") == "1"){
			if($params->check("sort_key")){
				$_POST = array("search" => $conditions, $params->get("sort_key") => $_POST[$params->get("sort_key")]);
			}else{
				$_POST = array("search" => $conditions);
			}
		}
		if($params->check("category")){
			$conditions["in:product_id"] = array("0");
			$productCategory = $loader->LoadModel("ProductCategoryModel");
			$productCategories = $productCategory->findAllByCategory($params->get("category"));
			if(is_array($productCategories) && !empty($productCategories)){
				foreach($productCategories as $productCategory){
					$conditions["in:product_id"][] = $productCategory->product_id;
				}
			}
		}
		if(isset($_POST["category_id"]) && $_POST["category_id"] > 0){
			$productCategory = $loader->LoadModel("ProductCategoryModel");
			$productCategorys = $productCategory->findAllByCategory($_POST["category_id"]);
			if(is_array($productCategorys) && !empty($productCategorys)){
				$conditions2["in:product_id"] = array("0");
				foreach($productCategorys as $productCategory){
					$conditions2["in:product_id"][] = $productCategory->product_id;
				}
				if(is_array($conditions["in:product_id"])){
					$conditions["in:product_id"] = array_intersect($conditions["in:product_id"], $conditions2["in:product_id"]);
				}else{
					$conditions["in:product_id"] = $conditions2["in:product_id"];
				}
			}
		}
		if($params->check("flag")){
			$productFlag = $loader->LoadModel("ProductFlagModel");
			$productFlags = $productFlag->findAllByFlag($params->get("flag"));
			if(is_array($productFlags) && !empty($productFlags)){
				$conditions2["in:product_id"] = array("0");
				foreach($productFlags as $productFlag){
					$conditions2["in:product_id"][] = $productFlag->product_id;
				}
				if(is_array($condition["in:product_id"])){
					$conditions["in:product_id"] = array_intersect($conditions["in:product_id"], $conditions2["in:product_id"]);
				}else{
					$conditions["in:product_id"] = $conditions2["in:product_id"];
				}
			}
		}

		// 並べ替え順序が指定されている場合に適用
		$sortOrder = "";
		$sortReverse = false;
		if($params->check("sort_key")){
			$sortOrder = $_POST[$params->get("sort_key")];
			if(empty($sortOrder)){
				$sortOrder = "create_time";
				$sortReverse = true;
			}elseif(preg_match("/^rev@/", $sortOrder) > 0){
				list($dummy, $sortOrder) = explode("@", $sortOrder);
				$sortReverse = true;
			}
		}
		
		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$products = $product->findAllBy($conditions, $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $products;
	}
}
?>
