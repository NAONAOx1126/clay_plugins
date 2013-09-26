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
class Form_AnswerDetailModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Form");
		parent::__construct($loader->loadTable("AnswerDetailsTable"), $values);
	}
	
	function findByPrimaryKey($answer_detail_id){
		$this->findBy(array("answer_detail_id" => $answer_detail_id));
	}
	
	function findByQuestionAnswer($answer_id, $question_id, $question_select_id = null){
		if($question_select_id == null){
			$this->findBy(array("question_id" => $question_id, "answer_id" => $answer_id));
		}else{
			$this->findBy(array("question_id" => $question_id, "question_select_id" => $question_select_id, "answer_id" => $answer_id));
		}
	}
	
	function findAllByAnswer($answer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("answer_id" => $answer_id), $order, $reverse);
	}
	
	function findAllByQuestion($question_id, $order = "", $reverse = false){
		return $this->findAllBy(array("question_id" => $question_id), $order, $reverse);
	}
	
	function findAllByQuestionAnswer($question_id, $answer_id, $order = "", $reverse = false){
		return $this->findAllBy(array("question_id" => $question_id, "answer_id" => $answer_id), $order, $reverse);
	}
	
	function findAllByQuestionSelect($question_select_id, $order = "", $reverse = false){
		return $this->findAllBy(array("question_select_id" => $question_select_id), $order, $reverse);
	}
	
	function question(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("QuestionModel");
		$model->findByPrimaryKey($this->question_id);
		return $model;
	}
	
	function questionSelect(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("QuestionSelectModel");
		$model->findByPrimaryKey($this->question_select_id);
		return $model;
	}
	
	function answer(){
		$loader = new Clay_Plugin("Form");
		$model = $loader->loadModel("AnswerModel");
		$model->findByPrimaryKey($this->answer_id);
		return $model;
	}
	
}
