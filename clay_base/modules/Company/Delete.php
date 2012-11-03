<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   3.0.0
 */

/**
 * ### Base.Company.Delete
 * サイトのデータを削除する。
 */
class Base_Company_Delete extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		$company = $loader->loadModel("CompanyModel");
		$company->findByPrimaryKey($_POST["company_id"]);
		
		// トランザクションデータベースの取得
		DBFactory::begin();
		
		try{
			// 組織に関連するオペレータを削除
			foreach($company->operators() as $operator){
				$operator->delete();
			}
			$company->delete();
					
			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit();
		}catch(Exception $e){
			DBFactory::rollBack();
			throw $e;
		}
	}
}
?>
