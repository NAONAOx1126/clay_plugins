<?php
/**
 * ### Content.Advertise.Start
 * カバー画像を削除する。
 */
class Content_Advertise_Start extends FrameworkModule{
	function execute($params){
		$loader = new PluginLoader("Content");
		$advertise = $loader->loadTable("AdvertisesTable");
		$select = new DatabaseSelect($advertise);
		$select = $select->addColumn($advertise->_W)->addWhere($advertise->advertise_start_time." <= ?", array(date("Y-m-d H:i:s")));
		$select->addWhere($advertise->advertise_end_time." >= ?", array(date("Y-m-d H:i:s")));
		$advertises = $select->execute();
		foreach($advertises as $data){
			if($_POST[$data["advertise_key"]] == $data["advertise_code"]){
				if(!is_array($_SESSION["ADVERTISE"])){
					$_SESSION["ADVERTISE"] = array("key" => $data["advertise_key"], "code" => $data["advertise_code"]);
					break;
				}
			}
		}
	}
}
?>
