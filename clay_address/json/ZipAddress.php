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
 * 郵便番号から住所を取得するためのJSON実装です。
 */
class Address_ZipAddress{
	public function execute(){
		$loader = new Clay_Plugin("address");
		$zip = $loader->loadModel("ZipModel");
		$zip->findByCode($_POST["zip"]);
		return $zip->toArray();
	}
}
