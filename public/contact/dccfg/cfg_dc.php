<?php
// config.php (プロジェクトルートに配置)
// ※セキュリティのため、このファイルにはWebから直接アクセスできないようにする。

// 本番ドメインが含まれているかどうかで判定（より確実な方法）
$is_production = (strpos($_SERVER['HTTP_HOST'], 'pclifecare.com') !== false);

// 本番環境「ではない」場合（= ローカル環境）
if (!$is_production) {
    // ====== ローカル環境用 (MailHog) ======
    define('SMTP_HOST', '127.0.0.1');
    define('SMTP_PORT', 1025);
    define('SMTP_USER', '');
    define('SMTP_PASS', '');
    define('SMTP_SECURE', '');      // ローカルは暗号化なし
    define('SMTP_AUTH', false);     // ローカルは認証なし
    
    // ローカルテスト用の宛先（MailHogで受信するので何でもOK）
    define('TO_EMAIL', 'test-admin@example.com');
    define('SENDER_EMAIL', 'test-sender@example.com');

} else {
    // ====== メール送信に必要な SMTP 認証情報 (本番用) ======

    // SMTPホスト名
    define('SMTP_HOST', 'af127.secure.ne.jp');

    // SMTPアカウントのユーザー名 (メールアドレス)
    define('SMTP_USER', 'web_inquiry@pclifecare.com');

    // SMTPアカウントのパスワード
    define('SMTP_PASS', 'Web_Inquiry056');

    // その他の定数
    define('SMTP_PORT', 465); // ポート番号
    define('SMTP_SECURE', 'ssl');   // ※587ポートなら'tls'
    define('SMTP_AUTH', true);      // 本番は通常 true
    define('TO_EMAIL', 'web_inquiry@pclifecare.com'); // 管理者メールアドレス
    define('SENDER_EMAIL', 'no-reply@pclifecare.com'); // 送信元メールアドレス
}
?>