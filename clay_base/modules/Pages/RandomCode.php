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
 * ランダムな英数からなるコードを発行します。一意性は保証されません。
 */
class Base_Pages_RandomCode extends FrameworkModule{
	function execute($params){
		if($params->check("code")){
			$result = "";
			$codes = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < $params->get("length", "20"); $i ++){
				$result .= substr($codes, mt_rand(0, strlen($codes) - 1), 1);
			}
			$_POST[$params->get("code")] = $result;
		}
	}
}
?>
