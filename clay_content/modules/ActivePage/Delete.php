<?php
/**
 * ### Content.ActivePage.Delete
 * アクティブページを削除する。
 */
class Content_ActivePage_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$activePage = $loader->loadModel("ActivePageKeyModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["active_page_key_id"])){
					$_POST["active_page_key_id"] = array($_POST["active_page_key_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["active_page_key_id"] as $active_page_key_id){
					// カテゴリを削除
					$activePage->findByPrimaryKey($active_page_key_id);
					$activePage->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST["active_page_key_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				unset($_POST["active_page_key_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
