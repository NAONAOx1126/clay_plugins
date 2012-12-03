<?php
/**
 * ### Content.ActivePage.ShopList
 * アクティブページのショップリストを取得する。
 * @param result 結果を設定する配列のキーワード
 */
class Content_ActivePage_Product extends Clay_Plugin_Module{
	function execute($params){
		if($shops->shops == ""){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// ショップデータを検索する。
			if($_SERVER["CLIENT_DEVICE"]->isFuturePhone()){
				$activePage = $loader->LoadModel("ActiveMobilePageModel");
			}else{
				$activePage = $loader->LoadModel("ActivePageModel");
			}
			$activePage->findByPrimaryKey($_POST["entry_id"]);

			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// カテゴリを保存
				$activePage->access_count ++;
				$activePage->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
			}

			// リンク先URLを取得
			if($_SERVER["CLIENT_DEVICE"]->isFuturePhone()){
				switch($_SERVER["CLIENT_DEVICE"]->getDeviceType()){
					case "DoCoMo":
						$activePage->link_url = $activePage->link_url_docomo;
						break;
					case "au":
						$activePage->link_url = $activePage->link_url_au;
						break;
					case "Softbank":
						$activePage->link_url = $activePage->link_url_softbank;
						break;
				}
			}else{
				$activePage->link_url .= $activePage->key()->link_key;
			}
			
			$_SERVER["ATTRIBUTES"][$params->get("result", "product")] = $activePage->toArray();
		}
	}
}
?>
