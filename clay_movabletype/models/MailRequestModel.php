<?php
/**
 * 契約のモデルクラス
 */
class Movabletype_MailRequestModel extends Clay_Plugin_Model{
	public function __construct($values = array()){
		$loader = new Clay_Plugin("Movabletype");
		parent::__construct($loader->loadTable("MailRequestsTable"), $values);
	}
	
	public function findByPrimaryKey($request_id){
		$this->findBy(array("request_id" => $request_id));
	}
	
	function parts($order = "", $reverse = false){
		$loader = new Clay_Plugin("Movabletype");
		$requestPart = $loader->loadModel("MailRequestPartModel");
		return $requestPart->findAllByRequest($this->request_id, $order, $reverse);
	}
}
?>