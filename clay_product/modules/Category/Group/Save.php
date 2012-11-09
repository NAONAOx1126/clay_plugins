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
 * ### Product.Category.Group.Save
 * 商品カテゴリグループを登録する。
 */
class Product_Category_Group_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin("product");
			
			try{
				// POSTされたデータを元にモデルを作成
				$category = $loader->loadModel("CategoryGroupModel");
				$category->findByPrimaryKey($_POST["category_group_id"]);
				
				// データを設定
				$category->category_group = $_POST["category_group"];
				$category->sort_order = $_POST["sort_order"];
				
				// カテゴリを保存
				$category->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("product");
				
				unset($_POST["save"]);
			}catch(Exception $e){
				Clay_Database_Factory::rollback("product");
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
 