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
 * ### Product.Category.Type.Delete
 * 商品カテゴリ区分を削除する。
 */
class Product_Category_Type_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Product");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin("product");
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$categoryType = $loader->loadModel("CategoryTypeModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["category_type_id"])){
					$_POST["category_type_id"] = array($_POST["category_type_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["category_type_id"] as $category_type_id){
					// カテゴリを削除
					$categoryType->findByPrimaryKey($category_type_id);
					$categoryType->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit("product");
				
				unset($_POST["category_type_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback("product");
				unset($_POST["category_type_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
 