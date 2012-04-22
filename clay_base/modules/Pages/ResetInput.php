<?php
/**
 * ### Base.Pages.ResetInput
 * 検索条件以外の入力をクリアする。
 */
class Base_Pages_ResetInput extends FrameworkModule{
	function execute($params){
		$_POST = array("search" => $_POST["search"]);
	}
}
?>
