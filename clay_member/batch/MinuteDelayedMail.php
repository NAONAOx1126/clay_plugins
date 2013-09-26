<?php
/**
 * 登録から所定の時間経過後にメールを送信するバッチです。
 *
 * Ex: /usr/bin/php batch.php "Member.MinuteDelayedMail" <ホスト名>
 */
class Member_CsvMail extends Clay_Plugin_Module{
	public function execute($argv){
		echo "CSV MAil";
		// ユーザー一覧をCSVで取得する。
		if(($fp = fopen(CLAY_ROOT."/wls_templates/wp_users.csv", "r")) !== FALSE){
			while (($data = fgetcsv($fp)) !== FALSE) {
				print_r($data);
				exit;
				// 登録完了メール送信
				$mail = new Clay_Sendmail();
				
				// メールの送信元（サイトメールアドレス）を設定
				$mail->setFrom("support@lnavi.jp", "LNAVI事務局");
				// メールの送信先を設定
				$mail->setTo($data[4], $data[9]);
				// テキストメールの設定からタイトルと本文を取得
				$mail->setSubject("ウェディングライフサポート　名称変更および全面リニューアルのお知らせ");
				$body = <<<EOF
提携店舗様各位

時下ますます御清栄のこととお慶び申し上げます。 
平素はウェディングライフサポートをご愛顧いただき、誠にありがとうございます。

さて、このたび弊社では、ウェディングライフサポートにご参画頂いている皆様のサービスご利用数を劇的に向上させるべく、会員の入会時オペレーションから、会員へのアプローチ手法、またウェブサイトのリニューアルまでを「全面改革」いたします。

名称も、現在の「ウェディングライフサポート」から、2013年5月17日より、愛（LOVE）のある人生（LIFE）を長く（LONG）ナビゲーションするサイトとして、「LNAVI」（エルナビ）という名前で、生まれ変わります。


「LNAVI」（エルナビ）リニューアルにかかるページ作成につきましては、皆様に再度お手間をおかけしないように、弊社で旧ウェディングライフサポートに入力済みの既存データからの移行を行っております。
後日、担当者様のメールアドレス（本メールの宛先）に、「LNAVI」（エルナビ）の御社専用管理画面と簡易マニュアルをお送りいたしますので、お手数ではございますが、移行後データのご確認だけ、お願い申し上げます。

このたびのリニューアルにつきましては、十分なご案内が出来ておりませんことをお詫び申し上げます。本日以降、順次ご説明に参りたいと思いますので、宜しくお願い申し上げます。

私どもの事業継続は、ひとえに代理店の皆様方のおかげであると感謝申し上げます。今後は今まで以上のサービス利用拡大に努めてまいりますので、ご協力のほどお願い申し上げます。
末筆ながら貴社のますますのご発展、ご活躍をお祈り致します。


【概要】
１、旧名称：ウェディングライフサポート
２、新名称：LNAVI（エルナビ）
３、リニューアル日：2013年5月17日（金）
４、管理画面URLおよび簡易マニュアル送付予定日：2013年5月13日
５、皆様へのお願い事項：４、のURLより、データ移行の内容をご確認ください。
________________________________________
不明点等ございましたら、恐縮ですが、下記までご連絡くださいませ。
株式会社シーマ　03－3567－1175　LNAVI（旧ウェディングライフサポート）担当：花木
EOF;				
				$mail->addBody($body);
				print_r($mail);
				//$mail->send();
			}
		}
	}
}
?>
