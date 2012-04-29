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
 * ### Base.Forms.TwitterImage
 * Twitter画像をフォームにインポートするモジュールです。
 *
 * @param image POSTに引き継ぐためのキー名
 */
class Base_Forms_TwitterImage extends FrameworkModule{
	function execute($params){
		// twitterとimageのパラメータは必須
		if($params->check("image") && !empty($_SERVER["Twitter"]["user"]->profile_image_url)){
			$image = $params->get("image");
			
			// 画像のデータが未登録で、twitterアカウントが設定されている場合には、画像データにtwitterの画像
			$_POST[$image] = $_SERVER["Twitter"]["user"]->profile_image_url;
		}
	}
}
?>
