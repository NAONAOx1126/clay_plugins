<?php
/**
 * 登録から所定の時間経過後にメールを送信するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Member.MinuteDelayedMail" <ホスト名>
 */
class Member_MinuteDelayedMail extends Clay_Plugin_Module{
	public function execute($argv){
		// この機能で使用するモデルクラス
		$baseLoader = new Clay_Plugin();
		$baseLoader->LoadSetting();
		$loader = new Clay_Plugin("Member");
		$loader->LoadSetting();
		
		// 引数を処理
		$template_code_prefix = $argv[0];
		$sendmail_term = $argv[1];
		
		// メールテンプレートを取得
		$mailTemplate = $baseLoader->loadModel("MailTemplateModel");
		$mailTemplate->findByPrimaryKey($template_code_prefix.$sendmail_term);
		$mailHtmlTemplate = $baseLoader->loadModel("MailTemplateModel");
		$mailHtmlTemplate->findByPrimaryKey($template_code_prefix.$sendmail_term."_html");
		
		// テンプレートが取れた場合のみメールを送信する。
		if($mailTemplate->template_code ==  $template_code_prefix.$sendmail_term){
			// 該当の時間に登録時間が一致するユーザーを抽出する。
			$condition = array("ge_create_time" => date("Y-m-d H:00:00", strtotime("-".($sendmail_term + 1)." minute")), "lt_create_time" => date("Y-m-d H:00:00", strtotime("-".($sendmail_term)." minute")));
			
			// カスタマモデルを使用して顧客情報を取得
			$customer = $loader->loadModel("CustomerModel");
			$customers = $customer->findAllBy($condition);
			
			foreach($customers as $customer){
				// 登録完了メール送信
				$mail = new SendMail();
				$smarty = new Template();
				foreach($customer as $name =>$value){
					$smarty->assign($name, $value);
				}
				
				// メールの送信元（サイトメールアドレス）を設定
				$sendMail->setFrom($_SERVER["CONFIGURE"]["SITE"]["site_email"], $_SERVER["CONFIGURE"]["SITE"]["site_name"]);
				// メールの送信先を設定
				$sendMail->setTo($customer["email"], $customer["sei"].$customer["mei"]);
				// テキストメールの設定からタイトルと本文を取得
				$sendMail->setSubject($mailTemplate->subject);
				$body = $smarty->fetch("string:".$mailTemplate->body);
				// HTMLメールのテンプレートから本文を取得
				if($mailHtmlTemplate->template_code == $template_code_prefix.$sendmail_term."_html"){
					$sendMail->addExtBody($body);
					$body = $smarty->fetch("string:".$mailHtmlTemplate->body);
				}
				$sendMail->addBody($body);
				//print_r($sendMail);
				$sendMail->send();
				$sendMail->reply();
			}
		}
	}
}
?>
