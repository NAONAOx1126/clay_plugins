<?php
/**
 * ### Content.Advertise.Delete
 * 広告を削除する。
 */
class Content_Advertise_Delete extends FrameworkModule{
	function execute($params){
		if(isset($_POST["delete"]) && !empty($_POST["delete"])){
			// ローダーの初期化
			$loader = new PluginLoader("Content");
			$loader->LoadSetting();
			
			// トランザクションの開始
			DBFactory::begin();
			
			try{
				// 渡されたカテゴリIDのインスタンスを生成
				$advertise = $loader->loadModel("AdvertiseModel");
				
				// カテゴリIDを配列に変換
				if(!is_array($_POST["advertise_id"])){
					$_POST["advertise_id"] = array($_POST["advertise_id"]);
				}
				
				// 指定されたカテゴリIDのデータを全削除
				foreach($_POST["advertise_id"] as $advertise_id){
					// カテゴリを削除
					$advertise->findByPrimaryKey($advertise_id);
					$advertise->delete();
				}
				
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit();
				
				unset($_POST["advertise_id"]);
				unset($_POST["delete"]);
				
				// 登録が正常に完了した場合には、ページをリロードする。
				$this->reload();
			}catch(Exception $e){
				DBFactory::rollback();
				unset($_POST["advertise_id"]);
				unset($_POST["delete"]);
				throw $e;
			}
		}
	}
}
?>
