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
 * ### Base.Administrator.Logout
 * マスター管理画面のログアウト処理を実行する。
 */
class Base_Administrator_Logout extends Clay_Plugin_Module{
	function execute($params){
		unset($_SESSION["ADMINISTRATOR"]);
	}
}
