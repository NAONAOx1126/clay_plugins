<?php
/**
 * 質問のモデルです。
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
class Form_QuestionModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Form");
		parent::__construct($loader->loadTable("QuestionsTable"), $values);
	}
	
	function findByPrimaryKey($question_id){
		$this->findBy(array("question_id" => $question_id));
	}
	
	function findBySheetCode($sheet_id, $question_code){
		$this->findBy(array("sheet_id" => $sheet_id, "question_code" => $question_code));
	}
	
	function findAllBySheet($sheet_id, $order = "", $reverse = false){
		return $this->findAllBy(array("sheet_id" => $sheet_id), $order, $reverse);
	}
	
	function findAllByQuestionType($question_type_id, $order = "", $reverse = false){
		return $this->findAllBy(array("question_type_id" => $question_type_id), $order, $reverse);
	}
	
	function sheet(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("SheetModel");
		$model->findByPrimaryKey($this->sheet_id);
		return $model;
	}
	
	function questionSelects($order = "sort_order", $reverse = false){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("QuestionSelectModel");
		return $model->findAllByQuestion($this->question_id, $order, $reverse);
	}

	function answerDetails($order = "", $reverse = false){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("AnswerDetailModel");
		return $model->findAllByQuestionSelect($this->question_select_id, $order, $reverse);
	}
}
