<?php
/**
 * ### Product.Category.Reset
 * 商品カテゴリの選択をクリアする。
 */
class Product_Category_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["category_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
