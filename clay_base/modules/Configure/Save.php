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
 * ### Base.Configure.Save
 * サイトのデータを保存する。
 */
class Base_Configure_Save extends FrameworkModule{
	function execute($params){
		// サイトデータを取得する。
		$loader = new PluginLoader();
		$configure = $loader->loadModel("SiteConfigureModel");
		
		// トランザクションの開始
		DBFactory::begin();
		
		try{
			foreach($_POST["configure"] as $key => $value){
				$configure->findByPrimaryKey($_SERVER["CONFIGURE"]->site_id, $key);
				if($configure->site_id > 0 && $configure->name == $key){
					$configure->value = $value;
				}else{
					$configure = $loader->loadModel("SiteConfigureModel", array("site_id" => $_SERVER["CONFIGURE"]->site_id, "name" => $key, "value" => $value));
				}
				$configure->save();
			}

			// エラーが無かった場合、処理をコミットする。
			DBFactory::commit();
		}catch(Exception $e){
			DBFactory::rollBack();
			throw $e;
		}
	}
}
?>
