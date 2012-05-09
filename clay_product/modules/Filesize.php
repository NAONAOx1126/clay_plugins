<?php
/**
 * ### Product.Filesize
 * ファイルのダウンロードを行うためのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   Product
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param key ファイルのCSV形式を特定するためのキー
 */
class Product_Filesize extends FrameworkModule{
	function execute($params){
		if($params->check("key")){
			$key = $params->get("key");
			$saveDir = "/".$params->get("base", "upload")."/".sha1("site".$_SERVER["CONFIGURE"]->site_id)."/".$key."/";
			$saveFile = $_SERVER["ATTRIBUTES"]["product"]->image($key)->image;
			$saveFile = str_replace(FRAMEWORK_URL_BASE."/contents/".$_SERVER["SERVER_NAME"].$saveDir, "", $saveFile);
			$uri = FRAMEWORK_SITE_HOME.$saveDir.$saveFile;
			
			// headerを指定
			$sizeName = $key."_size";
			$_SERVER["ATTRIBUTES"]["product"]->$sizeName = filesize($uri);
		}
	}
}
?>