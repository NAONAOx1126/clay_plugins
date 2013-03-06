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
 * モバイルドメイン情報のデータモデルです。
 */
class Mobile_DomainModel extends Clay_Plugin_Model{
	/**
	 * コンストラクタ
	 */
	public function __construct($values = array()){
		$loader = new Clay_Plugin("mobile");
		parent::__construct($loader->loadTable("DomainsTable"), $values);
	}
	
	/**
	 * 主キーでデータを取得する。
	 */
	public function findByPrimaryKey($mobile_domain_id){
		$this->findBy(array("mobile_domain_id" => $mobile_domain_id));
	}
	
	/**
	 * モバイルドメインでデータを取得する。
	 */
	public function findByMobileDomain($mobile_domain){
		$this->findBy(array("mobile_domain" => $mobile_domain));
	}
}
