<?php
/**
 * カテゴリ情報のモデルクラス
 */
class Product_ProductProfitModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Product");
		parent::__construct($loader->loadTable("ProductProfitsTable"), $values);
	}
	
	function findByPrimaryKey($product_id){
		$this->findBy(array("product_id" => $product_id));
	}
	
	function product(){
		$loader = new Clay_Plugin("Product");
		$product = $loader->loadModel("ProductModel");
		$product->findByPrimaryKey($this->product_id);
		return $product;
	}

	public function reconstruct(){
		// データを再構築する。
		$connection = DBFactory::getConnection("order");
		$sql = "SELECT `shop_order_details`.`product_code`";
		$sql .= ", sum(FLOOR(`shop_order_details`.`price` * POW(0.5, FLOOR(datediff(NOW(), `shop_orders`.`order_time`) / 7)))) AS `efficient_profit`";
		$sql .= ", sum(`shop_order_details`.`price`) AS `total_profit`";
		$sql .= " FROM `shop_products` INNER JOIN `shop_order_details` ON `shop_products`.`product_code` = `shop_order_details`.`product_code`";
		$sql .= " INNER JOIN `shop_order_packages` ON `shop_order_details`.`order_package_id` = `shop_order_packages`.`order_package_id`";
		$sql .= " INNER JOIN `shop_orders` ON `shop_order_packages`.`order_id` = `shop_orders`.`order_id` GROUP BY `shop_products`.`product_id`";
		$result = $connection->query($sql);
		$list = $result->fetchAll();
		
		DBFactory::begin("product");
		try{
			$connection = DBFactory::getConnection("product");
			$connection->query("TRUNCATE `shop_product_profits`");
			$sql = "INSERT INTO `shop_product_profits`(`product_code`, `efficient_profit`, `total_profit`) VALUES (?, ?, ?)";
			$connection->query($sql);
			foreach($list as $data){
				$prepare->execute(array($data["product_code"], $data["efficient_profit"], $data["total_profit"]));
			}
			
			$sql = "UPDATE `shop_product_profits`, `shop_products`";
			$sql .= " SET `shop_product_profits`.`product_id` = `shop_products`.`product_id`";
			$sql .= ", `shop_product_profits`.`create_time` = NOW(), `shop_product_profits`.`update_time` = NOW()";
			$sql .= " WHERE `shop_product_profits`.`product_code` = `shop_products`.`product_code`";
			$connection->query($sql);
	
			DBFactory::commit("product");
		}catch(Expception $e){
			DBFactory::rollback("product");
		}
	}
}
?>