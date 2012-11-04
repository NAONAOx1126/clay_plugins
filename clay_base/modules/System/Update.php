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
 * ### Base.System.Update
 * システムをアップデートする。
 */
class Base_System_Update extends Clay_Plugin_Module{
	function execute($params){
		// サイトデータを取得する。
		$loader = new Clay_Plugin();
		
		// 実行するディレクトリを取得する。
		switch($_POST["type"]){
			case "base":
			default:
				$target = CLAY_ROOT;
				break;
			case "plugin":
			case "plugins":
				$target = CLAY_PLUGINS_ROOT;
				break;
			case "site":
			case "sites":
			case "template":
			case "templates":
				$target = $_SERVER["CONFIGURE"]->site_home;
				break;
		}
		
		// 処理を実行する。
		$result = array("command" => "cd ".$target." 2>&1; git --git-dir=.git pull 2>&1", $result["return"] => 0, $result["output"] => array());
		exec($result["command"], $result["output"], $result["return"]);
		$_SERVER["ATTRIBUTES"]["updates"] = $result;
	}
}
?>
