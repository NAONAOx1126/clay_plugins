<?php
/**
 * ### File.Csv.AddressValidate
 * ファイルの住所照合を行います。
 * PHP5.3以上での動作のみ保証しています。
 * 動作自体はPHP5.2以上から動作します。
 *
 * @category  Modules
 * @package   File
 * @author    Naohisa Minagawa <info@sweetberry.jp>
 * @copyright 2010-2012 Naohisa Minagawa
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @version   1.0.0
 * @param key ファイルのCSV形式を特定するためのキー
 */
class File_Csv_AddressValidate extends Clay_Plugin_Module{
	
	function execute($params){
		if($params->check("zip") && $params->check("address")){
			$zipIndex = explode(",", $params->get("zip"));
			$addressIndex = explode(",", $params->get("address"));
			
			foreach($_SERVER["ATTRIBUTES"][$params->get("key")] as $row => $data){
				// CSVデータの郵便番号と住所を検索
				$zipcode = "";
				foreach($zipIndex as $index){
					$zipcode .= $data[$index];
				}
				$zipcode = str_replace("-", "", $zipcode);
				
				$address = "";
				foreach($addressIndex as $index){
					$address .= $data[$index];
				}
				$address = mb_convert_kana($address, "n");
				$kanNum = new Clay_Data_KanNumber($address);
				$address = $kanNum->getConvertedText();
				$data[$params->get("fixed_address", "fixed_address")] = $address;
				
				if(!empty($zipcode) && !empty($address)){
					// 郵便番号から住所を検索する。
					$loader = new Clay_Plugin();
					$zip = $loader->loadModel("ZipModel");
					while(strlen($zipcode) < 7){
						$zipcode = "0".$zipcode;
					}
					$zip->findByCode($zipcode);
					$zipAddress = $zip->state.$zip->city.$zip->town;
					if(strpos($zipAddress, "（") > 0){
						$zipAddress = substr($zipAddress, 0, strpos($zipAddress, "（"));
					}
					$kanNum = new Clay_Data_KanNumber($zipAddress);
					$zipAddress = $kanNum->getConvertedText();
					$data[$params->get("fixed_zip_address", "fixed_zip_address")] = $zipAddress;
						
					if(!empty($zipAddress) && $zipAddress == mb_substr($address, 0, mb_strlen($zipAddress)) && mb_strlen($zipAddress) < mb_strlen($address)){
						$data[$params->get("result", "result")] = "○";
					}else{
						$data[$params->get("result", "result")] = "×";
					}
					$_SERVER["ATTRIBUTES"][$params->get("key")][$row] = $data;
				}
			}
		}
	}
}
?>
