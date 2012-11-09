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
 * ### Product.Category.Group.List
 * 商品カテゴリグループのリストを取得する。
 * @param type 抽出するカテゴリの区分
 * @param result 結果を設定する配列のキーワード
 */
class Product_Category_Group_List extends Clay_Plugin_Module{
	function execute($params){
		// ローダーの初期化
		$loader = new Clay_Plugin("Product");
		$loader->LoadSetting();
		
		// 並べ替え順序が指定されている場合に適用
		$sortOrder = "";
		$sortReverse = false;
		if($params->check("sort_key")){
			$sortOrder = $_POST[$params->get("sort_key")];
			if(preg_match("/^rev@/", $sortOrder) > 0){
				list($dummy, $sortOrder) = explode("@", $sortOrder);
				$sortReverse = true;
			}
		}
		
		// カテゴリデータを検索する。
		$categoryGroup = $loader->LoadModel("CategoryGroupModel");
		$categoryGroups = $categoryGroup->findAllBy(array(), $sortOrder, $sortReverse);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "category_groups")] = $categoryGroups;
	}
}
 