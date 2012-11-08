<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */
 
 
/**
 * ### Product.Category.Type.Detail
 * 商品カテゴリ区分の詳細を取得する。
 * @param type 抽出するカテゴリの区分
 * @param result 結果を設定する配列のキーワード
 */
class Product_Category_Type_Detail extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$categoryType = $loader->LoadModel("CategoryTypeModel");
		$categoryType->findByPrimaryKey($_POST["category_type_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "category_type")] = $categoryType;
	}
}
 