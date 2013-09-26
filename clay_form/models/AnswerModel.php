<?php
/**
 * 回答のモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Models
 * @package   Form
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Form_AnswerModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Form");
		parent::__construct($loader->loadTable("AnswersTable"), $values);
	}
	
	function findByPrimaryKey($answer_id){
		$this->findBy(array("answer_id" => $answer_id));
	}
	
	function findByCustomerSheet($customer_id, $sheet_id){
		$this->findBy(array("customer_id" => $customer_id, "sheet_id" => $sheet_id));
	}
	
	function findAllBySheet($sheet_id, $order = "", $reverse = false){
		return $this->findAllBy(array("sheet_id" => $sheet_id), $order, $reverse);
	}
	
	function findAllByCustomer($customer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id), $order, $reverse);
	}
	
	function findAllByCustomerSheet($customer_id, $sheet_id, $order = "", $reverse = false){
		return $this->findAllBy(array("customer_id" => $customer_id, "sheet_id" => $sheet_id), $order, $reverse);
	}
	
	function customer(){
		$loader = new Clay_Plugin("Member");
		$model = $loader->loadModel("CustomerModel");
		$model->findByPrimaryKey($this->customer_id);
		return $model;
	}
	
	function sheet(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("SheetModel");
		$model->findByPrimaryKey($this->sheet_id);
		return $model;
	}
	
	function details($order = "", $reverse = false){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("AnswerDetailModel");
		return $model->findAllByAnswer($this->answer_id, $order, $reverse);
	}
}
