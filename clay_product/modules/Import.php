<?php
/**
 * ### Product.Import
 * 商品情報をインポートするためのクラスです。
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
class Product_Import extends FrameworkModule{
	function execute($params){
		if($params->check("key") && is_array($_SERVER["ATTRIBUTES"][$params->get("key")])){
			try{
				// トランザクションの開始
				DBFactory::begin("product");
				
				// ローダーを初期化
				$loader = new PluginLoader("Product");
				
				$list = $_SERVER["ATTRIBUTES"][$params->get("key")];
				foreach($list as $index => $data){
					// 半角カナを全角に変換する。
					foreach($data as $key => $value){
						if(!is_array($value)){
							$list[$index][$key] = mb_convert_kana($value);
						}
					}
				}
				
				// 商品データが商品コードによって一意になるようにリストを絞り込む
				$productList = array();
				if(!is_array($_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_PRODUCTS"])){
					$_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_PRODUCTS"] = array();
				}
				foreach($list as $data){
					if(!isset($_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_PRODUCTS"][$data["product_code"]])){
						$_SERVER["ATTRIBUTES"][$params->get("key")."_TEMP_PRODUCTS"][$data["product_code"]] = "1";
						$productList[$data["product_code"]] = $data;
					}
				}
					
				// 商品データを上書き
				$product = $loader->loadModel("ProductModel");
				$productList = $product->saveAll($productList);
				
				DBFactory::commit("product");
			}catch(Exception $e){
				DBFactory::rollback("product");
			}
		}
	}
}
?>
