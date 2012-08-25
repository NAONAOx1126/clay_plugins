<?php
/**
 * ### Shopping.Product.New
 * 新規入荷商品のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Product_New extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		// ページャの初期化
		$pager = new TemplatePager($params->get("_pager_mode", TemplatePager::PAGE_SLIDE), $params->get("_pager_per_page", 20), $params->get("_pager_displays", 3));
		$pager->importTemplates($params);
		
		// カテゴリが選択された場合、カテゴリの商品IDのリストを使う
		$conditions = array();
		if($params->check("category")){
			$conditions["in:product_id"] = array("0");
			$category = $loader->LoadModel("CategoryModel");
			$category->findByPrimaryKey($params->get("category"));
			$productCategories = $category->productCategories();
			if(is_array($productCategories) && !empty($productCategories)){
				foreach($productCategories as $productCategory){
					$conditions["in:product_id"][] = $productCategory->product_id;
				}
			}
		}
		// 検索条件と並べ替えキー以外を無効化する。
		if($params->check("sort_key")){
			$_POST = array("search" => $conditions, $params->get("sort_key") => $_POST[$params->get("sort_key")]);
		}else{
			$_POST = array("search" => $conditions);
		}
		
		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$pager->setDataSize($product->countBy(array()));
		$product->limit($pager->getPageSize(), $pager->getCurrentFirstOffset());
		$products = $product->findAllBy($conditions, $this->access->create_time, true);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $products;
	}
}
?>
