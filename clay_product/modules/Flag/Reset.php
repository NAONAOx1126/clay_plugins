<?php
/**
 * ### Product.Flag.Reset
 * 商品フラグの選択をクリアする。
 */
class Product_Flag_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["flag_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
