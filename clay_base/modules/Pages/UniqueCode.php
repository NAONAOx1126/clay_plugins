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
 * 一意性をある程度考慮したランダムコードを発行します。
 */
class Base_Pages_UniqueCode extends FrameworkModule{
	function execute($params){
		if($params->check("code")){
			$result = "";
			$codes = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < $params->get("length", "5"); $i ++){
				$result .= substr($codes, mt_rand(0, strlen($codes) - 1), 1);
			}
			if($params->check("original", "0") == "0" || !isset($_POST[$params->get("code")])){
				$_POST[$params->get("code")] = uniqid($result);
			}
		}
	}
}
?>
