<?php
/**
 * ### Content.Cover.Reset
 * カバー画像の選択をクリアする。
 */
class Content_Cover_Reset extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["news_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
