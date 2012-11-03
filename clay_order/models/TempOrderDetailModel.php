<?php
/**
 * 顧客情報のモデルクラス
 */
class TempOrderDetailModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Order");
		parent::__construct($loader->loadTable("TempOrderDetailsTable"), $values);
	}
	
	function findByPrimaryKey($order_id, $product_id, $option_ids = null, $option2_id = null, $option3_id = null, $option4_id = null, $option5_id = null, $option6_id = null, $option7_id = null, $option8_id = null, $option9_id = null){
		$this->findBy(array("order_id" => $order_id, "product_id" => $product_id, "option1_id" => $option1_id, "option2_id" => $option2_id, "option3_id" => $option3_id, "option4_id" => $option4_id, "option5_id" => $option5_id, "option6_id" => $option6_id, "option7_id" => $option7_id, "option8_id" => $option8_id, "option9_id" => $option9_id));
	}
	
	function findAllByOrderId($order_id){
		return $this->findAll(array("order_id" => $order_id));
	}
}
?>