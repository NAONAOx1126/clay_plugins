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
class Form_QuestionSelectModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Form");
		parent::__construct($loader->loadTable("QuestionSelectsTable"), $values);
	}
	
	function findByPrimaryKey($question_select_id){
		$this->findBy(array("question_select_id" => $question_select_id));
	}
	
	function findByQuestionSelect($question_id, $question_select){
		$this->findBy(array("question_id" => $question_id, "question_select" => $question_select));
	}
	
	function findAllByQuestion($question_id, $order = "", $reverse = false){
		return $this->findAllBy(array("question_id" => $question_id), $order, $reverse);
	}
	
	function question(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("QuestionModel");
		$model->findByPrimaryKey($this->question_id);
		return $model;
	}
	
	function answerDetails($order = "", $reverse = false){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("AnswerDetailModel");
		return $model->findAllByQuestionSelect($this->question_select_id, $order, $reverse);
	}
}
