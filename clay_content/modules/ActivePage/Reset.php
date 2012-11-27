<?php
/**
 * ### Content.ActivePage.Reset
 * アクティブページの選択をクリアする。
 */
class Content_ActivePage_Reset extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["active_page_key_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
