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
 * ### Admin.Logout
 * 管理画面のログアウト処理を実行する。
 * 
 */
class Admin_Logout extends Clay_Plugin_Module{
	function execute($params){
		$loader = new Clay_Plugin("admin");
		$loader->LoadSetting();
		unset($_SESSION["OPERATOR"]);
	}
}
