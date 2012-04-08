<?php
/**
 * ### Member.Contract.Reset
 * 契約の選択をクリアする。
 */
class Member_Contract_Reset extends FrameworkModule{
	function execute($params){
		if(isset($_POST["reset"]) && !empty($_POST["reset"])){
			unset($_POST["contract_id"]);
			unset($_POST["reset"]);
		}
	}
}
?>
