<?php
/**
 * ### Base.Company.Detail
 * サイトの詳細データを取得する。
 */
class Base_Company_Detail extends FrameworkModule{
	function execute($params){
		// サイトデータを取得する。
		$loader = new PluginLoader();
		$site = $loader->loadModel("SiteModel");
		$site->findByPrimaryKey($_POST["site_id"]);
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "site")] = $site;
	}
}
?>
