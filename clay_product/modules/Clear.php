<?php
/**
 * ### Product.Clear
 * 商品情報をクリアするためのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   Product
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param key インポートするファイルの形式を特定するためのキー
 */
class Product_Clear extends Clay_Plugin_Module{
	function execute($params){
		// ローダーを初期化
		$loader = new Clay_Plugin("Product");
		
		// 商品データをクリアする。
		$products = $loader->loadTable("ProductsTable");
		$truncate = new Clay_Query_Truncate($products);
		$truncate->execute();
		// 商品区分データをクリアする。
		$productTypes = $loader->loadTable("ProductTypesTable");
		$truncate = new Clay_Query_Truncate($productTypes);
		$truncate->execute();
		// 商品カテゴリデータをクリアする。
		$productCategories = $loader->loadTable("ProductCategoriesTable");
		$truncate = new Clay_Query_Truncate($productCategories);
		$truncate->execute();
		// 商品フラグデータをクリアする。
		$productFlags = $loader->loadTable("ProductFlagsTable");
		$truncate = new Clay_Query_Truncate($productFlags);
		$truncate->execute();
		// 商品製造元データをクリアする。
		$productDevelopers = $loader->loadTable("ProductDevelopersTable");
		$truncate = new Clay_Query_Truncate($productDevelopers);
		$truncate->execute();
		// 商品販売元データをクリアする。
		$productSellers = $loader->loadTable("ProductSellersTable");
		$truncate = new Clay_Query_Truncate($productSellers);
		$truncate->execute();
		// 商品収益データをクリアする。
		$productProfits = $loader->loadTable("ProductProfitsTable");
		$truncate = new Clay_Query_Truncate($productProfits);
		$truncate->execute();
		// 商品画像データをクリアする。
		$productImages = $loader->loadTable("ProductImagesTable");
		$truncate = new Clay_Query_Truncate($productImages);
		$truncate->execute();
		// 商品オプションデータをクリアする。
		$productOptions = $loader->loadTable("ProductOptionsTable");
		$truncate = new Clay_Query_Truncate($productOptions);
		$truncate->execute();
	}
}
?>
