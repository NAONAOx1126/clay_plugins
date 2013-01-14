<?php
/**
 * ### Content.Shortcut.Delete
 * 新着情報を削除する。
 */
class Content_Shortcut_Delete extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$shortcut = $loader->loadModel("ShortcutModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["shortcut_id"])){
					$_POST["shortcut_id"] = array($_POST["shortcut_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["shortcut_id"] as $shortcut_id){
					// カテゴリを削除
					$shortcut->findByPrimaryKey($shortcut_id);
					$shortcut->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST["shortcut_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				Clay_Database_Factory::rollback();
				unset($_POST["shortcut_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
