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
 * ### Product.Category.Group.Detail
 * 商品カテゴリグループの詳細を取得する。
 * @param type 抽出するカテゴリの区分
 * @param result 結果を設定する配列のキーワード
 */
class Product_Category_Group_Detail extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// カテゴリデータを検索する。
		$categoryGroup = $loader->LoadModel("CategoryGroupModel");
		$categoryGroup->findByPrimaryKey($_POST["category_group_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "category_group")] = $categoryGroup;
	}
}
 