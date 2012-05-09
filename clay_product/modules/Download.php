<?php
/**
 * ### Product.Download
 * ファイルのダウンロードを行うためのクラスです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param key ファイルのCSV形式を特定するためのキー
 */
class Product_Download extends FrameworkModule{
	function execute($params){
		if($params->check("key")){
			// データ一括取得のため、処理期限を無効化
			ini_set("max_execution_time", 0);
			
			// アウトプットバッファをクリア
			ob_end_clean();
			
			$key = $params->get("key");
			$saveDir = "/".$params->get("base", "upload")."/".sha1("site".$_SERVER["CONFIGURE"]->site_id)."/".$key."/";
			$saveFile = $_SERVER["ATTRIBUTES"]["product"]->image($key)->image;
			$saveFile = str_replace(FRAMEWORK_URL_BASE."/contents/".$_SERVER["SERVER_NAME"].$saveDir, "", $saveFile);
			$uri = FRAMEWORK_SITE_HOME.$saveDir.$saveFile;
			
			// headerを指定
			$size = filesize($uri);
			$mime_type = mime_content_type($uri);
			header("Content-Length: ".$size);
			header("Content-type: ".$mime_type);
			
			// ファイルを出力
			echo file_get_contents($uri);
			exit;
		}
	}
}
?>
