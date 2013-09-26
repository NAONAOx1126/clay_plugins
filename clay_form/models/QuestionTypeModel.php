<?php
/**
 * 質問票のモデルです。
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
class Form_QuestionTypeModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Form");
		parent::__construct($loader->loadTable("QuestionTypesTable"), $values);
	}
	
	function findByPrimaryKey($question_type_id){
		$this->findBy(array("question_type_id" => $question_type_id));
	}
	
	function questions($order = "sort_order", $reverse = false){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("QuestionModel");
		return $model->findAllByQuestionType($this->question_type_id, $order, $reverse);
	}
}
