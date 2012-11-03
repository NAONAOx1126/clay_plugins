<?php
class Product_RankingProducts{
	public function execute(){
		// 商品プラグインの初期化
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// 検索条件を設定
		$conditions = array();
		
		// カテゴリ検索条件を追加
		if(isset($_POST["category"])){
			$conditions["in:product_id"] = array("0");
			$productCategory = $loader->LoadModel("ProductCategoryModel");
			$productCategories = $productCategory->findAllByCategory($_POST["category"]);
			if(is_array($productCategories) && !empty($productCategories)){
				foreach($productCategories as $productCategory){
					$conditions["in:product_id"][] = $productCategory->product_id;
				}
			}
		}
		// カテゴリ検索条件２を追加
		if(isset($_POST["category2"])){
			$productCategory = $loader->LoadModel("ProductCategoryModel");
			$productCategorys = $productCategory->findAllByCategory($_POST["category2"]);
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
		// フラグ検索条件を追加
		if(isset($_POST["flag"])){
			$productFlag = $loader->LoadModel("ProductFlagModel");
			$productFlags = $productFlag->findAllByFlag($_POST["flag"]);
			if(is_array($productFlags) && !empty($productFlags)){
				$conditions2["in:product_id"] = array("0");
				foreach($productFlags as $productFlag){
					$conditions2["in:product_id"][] = $productFlag->product_id;
				}
				if(is_array($conditions["in:product_id"])){
					$conditions["in:product_id"] = array_intersect($conditions["in:product_id"], $conditions2["in:product_id"]);
				}else{
					$conditions["in:product_id"] = $conditions2["in:product_id"];
				}
			}
		}
		
		// 商品データを検索する。
		$product = $loader->LoadModel("ProductProfitModel");
		if(isset($_POST["limit"])){
			if(isset($_POST["offset"])){
				$product->limit($_POST["limit"], $_POST["offset"]);
			}else{
				$product->limit($_POST["limit"]);
			}
		}
		$profits = $product->findAllBy($conditions, "efficient_profit", true);
		$result = array();
		foreach($profits as $profit){
			$product = $profit->product();
			$data = $product->toArray();
			$data["isNew"] = $product->isNew();
			$data["sample_data"] = $product->image("sample")->image;
			$data["list_image"] = $product->image("list")->image;
			$data["fmt_price"] = number_format($data["price"]);
			$data["fmt_sale_price"] = number_format($data["sale_price"]);
			$result[] = $data;
		}
		return $result;
	}
}
?>
