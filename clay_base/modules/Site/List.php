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
 * ### Base.Site.List
 * サイトデータのリストを取得する。
 */
class Base_Site_List extends FrameworkModule{
	function execute($params){
		// サイトデータを取得する。
		$loader = new PluginLoader();
		$site = $loader->loadModel("SiteModel");
		
		$condition = array();
		if(!isset($_POST["search"]) && is_array($_POST["search"])){
			$condition = $_POST["search"];
		}
		
		$sites = $site->findAllBy($condition);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "sites")] = $sites;
	}
}
?>
