<?php
/**
 * ### Content.ActivePage.Save
 * アクティブページを登録する。
 */
class Content_ActivePage_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// POSTされたデータを元にモデルを作成
				$activePage = $loader->loadModel("ActivePageKeyModel");
				$activePage->findByPrimaryKey($_POST["active_page_key_id"]);
				
				// データを設定
				$activePage->shop_id = $_POST["shop_id"];
				$activePage->link_key = $_POST["link_key"];
				
				// カテゴリを保存
				$activePage->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				unset($_POST["save"]);
				throw $e;
			}
		}
	}
}
?>
