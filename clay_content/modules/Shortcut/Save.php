<?php
/**
 * ### Content.Shortcut.Save
 * カバー画像を登録する。
 */
class Content_Shortcut_Save extends Clay_Plugin_Module{
	function execute($params){
		if(isset($_POST["save"]) && !empty($_POST["save"])){
			// ローダーの初期化
			$loader = new Clay_Plugin("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			Clay_Database_Factory::begin();
			
			try{
				// POSTされたデータを元にモデルを作成
				$shortcut = $loader->loadModel("ShortcutModel");
				$shortcut->findByPrimaryKey($_POST["shortcut_id"]);
				
				// データを設定
				$shortcut->shortcut_code = $_POST["shortcut_code"];
				$shortcut->shortcut_type = $_POST["shortcut_type"];
				$shortcut->redirect_url = $_POST["redirect_url"];
				
				// カテゴリを保存
				$shortcut->save();
						
				// エラーが無かった場合、処理をコミットする。
				Clay_Database_Factory::commit();
				
				unset($_POST["save"]);
				
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
