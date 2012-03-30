<?php
/**
 * ### Product.List
 * 商品のリストを取得する。
 * @param item １ページあたりの件数
 * @param delta 現在ページの前後に表示するページ数
 * @param category【カテゴリタイプ】 商品に紐付けするカテゴリ（条件にしない場合は空文字を設定）
 * @param result 結果を設定する配列のキーワード
 */
class Product_List extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Product");
		$loader->LoadSetting();

		// ページャのオプションを設定
		$option = array();
		$option["mode"] = "Sliding";		// 現在ページにあわせて表示するページリストをシフトさせる。
		$option["perPage"] = $params->get("item", "10");			// １ページあたりの件数
		$option["delta"] = $params->get("delta", "3");				// 現在ページの前後に表示するページ番号の数（Slidingの場合は2n+1ページ分表示）
		$option["prevImg"] = "<";			// 前のページ用のテキスト
		$option["nextImg"] = ">";			// 次のページ用のテキスト
		$option["prevAccessKey"] = "*";			// 前のページ用のアクセスキー
		$option["nextAccessKey"] = "#";			// 次のページ用のアクセスキー
		$option["firstPageText"] = "<<"; 	// 最初のページ用のテキスト
		$option["lastPageText"] = ">>";		// 最後のページ用のテキスト
		$option["curPageSpanPre"] = "<font color=\"#000000\">";		// 現在ページのプレフィクス
		$option["curPageSpanPost"] = "</font>";		// 現在ページのサフィックス
		$option["clearIfVoid"] = false;			// １ページのみの場合のページリンクの出力の有無
		
		// カテゴリが選択された場合、カテゴリの商品IDのリストを使う
		$conditions = $_POST;
		if($params->check("category")){
			$category = $loader->LoadModel("CategoryModel");
			$category->findByPrimaryKey($params->get("category"));
			$productCategories = $category->productCategories();
			$conditions["in:product_id"] = array();
			if(is_array($productCategories) && !empty($productCategories)){
				foreach($productCategories as $productCategory){
					$conditions["in:product_id"][] = $productCategory->product_id;
				}
			}
		}
		
		// 商品データを検索する。
		$product = $loader->LoadModel("ProductModel");
		$option["totalItems"] = $product->countBy($conditions);
		$pager = AdvancedPager::factory($option);
		list($from, $to) = $pager->getOffsetByPageId();
		$product->limit($option["perPage"], $from - 1);
		$products = $product->findAllBy($conditions, $this->access->create_time, true);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "products")."_pager"] = $pager;
		$_SERVER["ATTRIBUTES"][$params->get("result", "products")] = $products;
	}
}
?>
