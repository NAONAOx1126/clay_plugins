<?php
/**
 * ### Base.Pref.List
 * 都道府県のリストを取得する。
 */
class Base_Pref_List extends FrameworkModule{
	function execute($params){
		// モデルの初期化
		$loader = new PluginLoader();
		$pref = $loader->loadModel("PrefModel");
		
		$prefs = $pref->findAllBy();
		
		$_SERVER["ATTRIBUTES"][$params->get("result", "prefs")] = $prefs;
	}
}
?>
