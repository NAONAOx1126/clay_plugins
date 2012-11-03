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
 * ### Base.Site.Reload
 * サイトのキャッシュファイルをクリアし、再生成させる。
 */
class Base_Site_Reload extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータのキャッシュを削除する。
		foreach(glob(FRAMEWORK_HOME."/cache/*/site_configure.php") as $filename){
			unlink($filename);
		}
	}
}
?>
