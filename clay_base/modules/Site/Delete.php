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
 * ### Base.Site.Delete
 * サイトのデータを削除する。
 */
class Base_Site_Delete extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		$site = $loader->loadModel("SiteModel");
		$site->findByPrimaryKey($_POST["site_id"]);
		
		// トランザクションの開始
		Clay_Database_Factory::begin();
		
		try{
			$site->delete();
					
			// エラーが無かった場合、処理をコミットする。
			Clay_Database_Factory::commit();
		}catch(Exception $e){
			Clay_Database_Factory::rollBack();
			throw $e;
		}
	}
}
?>
