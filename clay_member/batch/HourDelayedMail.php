<?php
/**
 * 登録から所定の時間経過後にメールを送信するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Member.HourDelayedMail" <ホスト名>
 */
class Member_HourDelayedMail extends Clay_Plugin_Module{
	public function execute($argv){
		// この機能で使用するモデルクラス
		$baseLoader = new Clay_Plugin();
		$baseLoader->LoadSetting();
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		// 引数を処理
		$template_code_prefix = $argv[0];
		$Clay_Sendmail_term = $argv[1];
		
		// メールテンプレートを取得
		$mailTemplate = $baseLoader->loadModel("MailTemplateModel");
		$mailTemplate->findByPrimaryKey($template_code_prefix.$Clay_Sendmail_term);
		$mailHtmlTemplate = $baseLoader->loadModel("MailTemplateModel");
		$mailHtmlTemplate->findByPrimaryKey($template_code_prefix.$Clay_Sendmail_term."_html");
		
		// テンプレートが取れた場合のみメールを送信する。
		if($mailTemplate->template_code ==  $template_code_prefix.$Clay_Sendmail_term){
			// 該当の時間に登録時間が一致するユーザーを抽出する。
			$condition = array("ge_create_time" => date("Y-m-d H:00:00", strtotime("-".($Clay_Sendmail_term + 1)." hour")), "lt_create_time" => date("Y-m-d H:00:00", strtotime("-".($Clay_Sendmail_term)." hour")));
			
			// カスタマモデルを使用して顧客情報を取得
			$customer = $loader->loadModel("CustomerModel");
			$customers = $customer->findAllBy($condition);
			
			foreach($customers as $customer){
				// 登録完了メール送信
				$mail = new Clay_Sendmail();
				$smarty = new Template();
				foreach($customer as $name =>$value){
					$smarty->assign($name, $value);
				}
				
				// メールの送信元（サイトメールアドレス）を設定
				$Clay_Sendmail->setFrom($_SERVER["CONFIGURE"]["SITE"]["site_email"], $_SERVER["CONFIGURE"]["SITE"]["site_name"]);
				// メールの送信先を設定
				$Clay_Sendmail->setTo($customer["email"], $customer["sei"].$customer["mei"]);
				// テキストメールの設定からタイトルと本文を取得
				$Clay_Sendmail->setSubject($mailTemplate->subject);
				$body = $smarty->fetch("string:".$mailTemplate->body);
				// HTMLメールのテンプレートから本文を取得
				if($mailHtmlTemplate->template_code == $template_code_prefix.$Clay_Sendmail_term."_html"){
					$Clay_Sendmail->addExtBody($body);
					$body = $smarty->fetch("string:".$mailHtmlTemplate->body);
				}
				$Clay_Sendmail->addBody($body);
				//print_r($Clay_Sendmail);
				$Clay_Sendmail->send();
				$Clay_Sendmail->reply();
			}
		}
	}
}
?>
