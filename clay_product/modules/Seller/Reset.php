<?php
/**
 * ### Product.Seller.Reset
 * 開発会社の選択をクリアする。
 */
class Product_Seller_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["seller_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
