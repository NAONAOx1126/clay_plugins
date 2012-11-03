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
 * ### Base.Configure.List
 * サイトデータのリストを取得する。
 */
class Base_Configure_List extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		$configure = $loader->loadModel("SiteConfigureModel");
		$temp = $configure->findAllBySiteId($_SERVER["CONFIGURE"]->site_id);
		$configures = array();
		foreach($temp as $configure){
			$configures[$configure->name] = $configure;
		}
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "configures")] = $configures;
	}
}
?>
