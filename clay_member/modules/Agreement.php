<?php
/**
 * ユーザーの同意を求める
 */
class Member_Agreement extends FrameworkModule{
	function execute($param){
		if($_POST["agreement"] == "1"){
			$_SESSION["SITE_AGREEMENT"] = "1";
		}
		if($_SESSION["SITE_AGREEMENT"] != "1"){
			throw new Clay_Exception_Invalid(array("このページにアクセスするためには同意が必要です。"));
		}
	}
}
?>
