<?php
/**
 * 回答のモデルです。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Models
 * @package   Identify
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 */
class Identify_NodeModel extends Clay_Plugin_Model{
	function __construct($values = array()){
		$loader = new Clay_Plugin("Identify");
		parent::__construct($loader->loadTable("NodesTable"), $values);
	}
	
	function findByPrimaryKey($node_id){
		$this->findBy(array("node_id" => $node_id));
	}
}
