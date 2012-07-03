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
 * チェックしたエラーの内容をコミットして、例外を発生させる。
 */

class Base_Checks_Commit extends FrameworkModule{
	function execute($params){
		if(!empty($_SERVER["ERRORS"])){
			$_SERVER["INPUT_DATA"] = $_POST;
			throw new InvalidException($_SERVER["ERRORS"]);
		}
	}
}
?>
