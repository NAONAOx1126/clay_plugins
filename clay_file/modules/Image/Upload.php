<?php
/**
 * ### File.Image.Upload
 * 画像をアップロードを処理するためのクラスです。
 * 標準的なアップロードを想定しているため、画像に限らずアップロードを処理することが可能です。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param mode 処理を実行するトリガーとなるPOSTキー
 * @param key ファイルのCSV形式を特定するためのキー
 * @param skip ヘッダとして読み込みをスキップする行数
 */
class File_Image_Upload extends Clay_Plugin_Module{
	function execute($params){
		// 実行時間制限を解除
		ini_set("max_execution_time", 0);
		
		// ローダーを初期化
		$loader = new Clay_Plugin("File");
		
		$images = array();
		if(is_array($_POST[$params->get("key", "upload")])){
			foreach($_POST[$params->get("key", "upload")] as $key1 => $upload){
				if(is_array($upload)){
					foreach($upload as $key2 => $upload2){
						if($_FILES[$key1]["error"][$key2] == 0){
							// 保存先のディレクトリを構築
							$saveDir = "/".$params->get("base", "upload")."/".sha1("site".$_SERVER["CONFIGURE"]->site_id)."/".$key1."/".$key2."/";
							if(!file_exists($_SERVER["CONFIGURE"]->site_home.$saveDir)){
								mkdir($_SERVER["CONFIGURE"]->site_home.$saveDir, 0777, true);
							}
							// 保存するファイル名を構築
							$info = pathinfo($_FILES[$key1]["name"][$key2]);
							$saveFile = sha1(uniqid($_FILES[$key1]["name"][$key2])).(!empty($info["extension"])?".".$info["extension"]:"");
							// 保存するファイルを移動
							move_uploaded_file($_FILES[$key1]["tmp_name"][$key2], $_SERVER["CONFIGURE"]->site_home.$saveDir.$saveFile);
							// 登録した内容をPOSTに設定
							$_POST[$key1."_name"][$key2] = $_FILES[$key1]["name"][$key2];
							$_POST[$key1][$key2] = CLAY_SUBDIR."/contents/".$_SERVER["SERVER_NAME"].$saveDir.$saveFile;
						}
					}
				}else{
					if($_FILES[$key1]["error"] == 0){
						Clay_Logger::writeDebug(var_export($_FILES, true));
						// 保存先のディレクトリを構築
						$saveDir = "/".$params->get("base", "upload")."/".sha1("site".$_SERVER["CONFIGURE"]->site_id)."/".$key1."/";
						if(!file_exists(CLAY_ROOT."/contents/".$_SERVER["SERVER_NAME"].$saveDir)){
							mkdir(CLAY_ROOT."/contents/".$_SERVER["SERVER_NAME"].$saveDir, 0777, true);
						}
						// 保存するファイル名を構築
						$info = pathinfo($_FILES[$key1]["name"]);
						$saveFile = sha1(uniqid($_FILES[$key1]["name"])).(!empty($info["extension"])?".".$info["extension"]:"");
						// 保存するファイルを移動
						move_uploaded_file($_FILES[$key1]["tmp_name"], CLAY_ROOT."/contents/".$_SERVER["SERVER_NAME"].$saveDir.$saveFile);
						// 登録した内容をPOSTに設定
						$_POST[$key1."_name"] = $_FILES[$key1]["name"];
						$_POST[$key1] = CLAY_SUBDIR."/contents/".$_SERVER["SERVER_NAME"].$saveDir.$saveFile;
					}
				}
			}
			if($params->check("reload")){
				header("Location: ".CLAY_SUBDIR."/".$params->get("reload"));
				exit;
			}
		}
	}
}
?>
