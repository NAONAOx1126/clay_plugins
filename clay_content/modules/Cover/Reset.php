<?php
/**
 * ### Content.Cover.Reset
 * カバー画像の選択をクリアする。
 */
class Content_Cover_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["cover_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
