<?php
/**
 * 広告のモデルクラス
 */
class Content_AdvertiseModel extends DatabaseModel{
	public function __construct($values = array()){
		$loader = new PluginLoader("Content");
		parent::__construct($loader->loadTable("AdvertisesTable"), $values);
	}
	
	public function findByPrimaryKey($advertise_id){
		$this->findBy(array("advertise_id" => $advertise_id));
	}
	
	public function findAllByKeyCode($advertise_key, $advertise_code, $order = "", $reverse = false){
		return $this->findAllBy(array("advertise_key" => $advertise_key, "advertise_code" => $advertise_code), $order, $reverse);
	}
	
	public function findByLastKeyCode($advertise_key, $advertise_code){
		$advertises = $this->findAllByKeyCode($advertise_key, $advertise_code, "start_time", true);
		if(count($advertises) > 0){
			return $advertises[0];
		}
		return $loader->loadModel("AdvertiseModel");
	}
}
?>