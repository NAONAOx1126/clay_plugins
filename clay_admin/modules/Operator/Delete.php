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
 * ### Base.Company.Delete
 * サイトのデータを削除する。
 */
class Admin_Operator_Delete extends Clay_Plugin_Module{
	function execute($params){
		if($_POST["delete"]){
			// サイトデータを取得する。
			$loader = new Clay_Plugin("Admin");
			$operator = $loader->loadModel("CompanyOperatorModel");
			$operator->findByPrimaryKey($_POST["operator_id"]);
			
			// トランザクションデータベースの取得
			Clay_Database_Factory::begin();
			
			try{
				// 組織に関連するオペレータを削除
				$operator->delete();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				$this->removeInput("delete");
			}catch(Exception $e){
				Clay_Database_Factory::rollBack();
				throw $e;
			}
		}
	}
}
