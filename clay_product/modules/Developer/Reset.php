<?php
/**
 * ### Product.Developer.Reset
 * 開発会社の選択をクリアする。
 */
class Product_Developer_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["developer_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
