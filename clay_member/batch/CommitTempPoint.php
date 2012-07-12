<?php
/**
 * 確定していないポイントを確定させます。
 *
 * Ex: /usr/bin/php batch.php "Member.CommitTempPoint" <ホスト名>
 */
class Member_CommitTempPoint extends FrameworkModule{
	public function execute($argv){
		// この機能で使用するモデルクラス
		$loader = new PluginLoader("Member");
		$loader->LoadSetting();
		
		// ポイントログモデルから未確定ポイントログを抽出
		$pointLog = $loader->LoadModel("PointLogModel");
		$pointLogs = $pointLog->findAllBy(array("commit_flg" => "0"));
		
		foreach($pointLogs as $pointLog){
			// トランザクションの開始
			DBFactory::begin("member");
			
			try{
				// 新規登録時は登録ポイントを登録
				$customer = $loader->loadModel("CustomerModel");
				$customer->findByPrimaryKey($pointLog->customer_id);
				$customer->point += $pointLog->point;
				$pointLog->commit_flg = 1;
				
				// データを保存する。
				$customer->save();
				$pointLog->save();
						
				// エラーが無かった場合、処理をコミットする。
				DBFactory::commit("member");
			}catch(Exception $ex){
				DBFactory::rollback("member");
				throw $ex;
			}
		}
	}
}
?>
