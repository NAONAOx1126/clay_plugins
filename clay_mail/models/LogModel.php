<?php
/**
 * This file is part of CLAY Framework for view-module based system.
 *
 * @author    Naohisa Minagawa <info@clay-system.jp>
 * @copyright Copyright (c) 2010, Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   4.0.0
 */

/**
 * メールログのデータモデルです。
 */
class Mail_LogModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	function __construct($values = array()){
		$loader = new Clay_Plugin("mail");
		parent::__construct($loader->loadTable("Mail_LogsTable"), $values);
	}
	
	/**
	 * 主キーで検索する。
	 */
	function findByPrimaryKey($template_code){
		$this->findBy(array("template_code" => $template_code));
	}
}
