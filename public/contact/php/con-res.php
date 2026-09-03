<?php
session_start();

// __DIR__ は con-res.php が存在するディレクトリ (例: /var/www/public_html/php) を示す
// ../ (php/の外) -> ../ (public_html/の外) -> dccfg/cfg_dc.php
$config_path = __DIR__ . '/../dccfg/cfg_dc.php';

require_once $config_path;

// PHPMailerを手動で読み込むための設定 (←この3行はコメントアウト)
// ※これに使用していたPHPMailerフォルダは該当箇所から削除済み
// require '../../../dccfg/PHPMailer/src/Exception.php'; 
// require '../../../dccfg/PHPMailer/src/PHPMailer.php';
// require '../../../dccfg/PHPMailer/src/SMTP.php';

// ==========================================================
// ★ Composerによる自動読み込み（オートロード）に置き換え ★
require __DIR__ . '/../vendor/autoload.php';
// ==========================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // SMTPクラスも使用する場合に必要

// ヘルパー関数: XSS対策のためのエスケープ処理
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ====== メールヘッダインジェクション対策関数 ======
function sanitize_header_input($str) {
    // ユーザー入力がヘッダの一部として使われる際、改行コードを全て除去する
    return str_replace(["\r", "\n"], '', $str);
}
// =========================================================

// --------------------------------------------------------------------------------
// 1. POSTリクエスト時のCSRFトークン検証 (確認画面へ/送信する)
// --------------------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // トークンがPOSTに含まれているか、セッションに存在するか、値が一致するかを検証
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF攻撃の可能性：トークンが無効または欠落
        // エラーを記録し、フォームに戻す
        header('Location: contact.php'); 
        exit;
    }
}

// --------------------------------------------------------------------------------
// 2. 入力データの取得と入力チェック (POSTメソッド時のみ実行)
// --------------------------------------------------------------------------------

// 修正ボタンからのアクセス(GET)か、送信ボタンからのアクセス(POST)かを区別するために、
// 'action'パラメータ（送信ボタンのname）の有無で処理を分岐させます。

$is_confirmation = ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action']));
$is_send_action = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send');

// 初期データはPOSTから取得。送信時（確認画面から）はセッションから取得する。
$input_data = [];

if ($is_confirmation) {
    // フォームからの初回POST (確認画面へ)

    // 受け取ったデータを取得し、エスケープ処理
    $input_data = [
        'bus'       => isset($_POST['bus']) ? trim($_POST['bus']) : '',
        'name'      => isset($_POST['name']) ? trim($_POST['name']) : '',
        'kana'      => isset($_POST['kana']) ? trim($_POST['kana']) : '',
        'mail'      => isset($_POST['mail']) ? trim($_POST['mail']) : '',
        'tel'       => isset($_POST['tel']) ? trim($_POST['tel']) : '',
        'add'       => isset($_POST['add']) ? trim($_POST['add']) : '',
        'req-con'   => isset($_POST['req-con']) ? $_POST['req-con'] : '',
        'con-con'   => isset($_POST['con-con']) ? trim($_POST['con-con']) : '',
        'pri-pol'   => isset($_POST['pri-pol']) ? trim($_POST['pri-pol']) : '',
    ];

    // エラーチェック（エラーメッセージを格納する配列を初期化）
    $error_messages = [];

    // 必須項目チェック
    if (empty($input_data['name'])) {
        $error_messages['name'] = 'お名前は必須入力です。';
    }
    if (empty($input_data['kana'])) {
        $error_messages['kana'] = 'ふりがなは必須入力です。';
    }
    if (empty($input_data['mail'])) {
        $error_messages['mail'] = 'メールアドレスは必須入力です。';
    } else if (!filter_var($input_data['mail'], FILTER_VALIDATE_EMAIL)) {
        $error_messages['mail'] = 'メールアドレスの形式が正しくありません。';
    }
// 電話番号のバリデーション
if (!empty($input_data['tel'])) {
    // 空でない場合（何か入力された場合）のみ、形式をチェックする
    if (!preg_match('/^\d{2,4}-?\d{2,4}-?\d{3,4}$/', $input_data['tel'])) {
        $error_messages['tel'] = '電話番号の形式が正しくありません。（例: 03-1234-5678）';
    }
}
// 空の場合は、何もしない（＝エラーなしで通過）
    if (empty($input_data['bus'])) {
        $error_messages['bus'] = 'ご用件は必須入力です。';
    }
/*    if (empty($input_data['add'])) {
        $error_messages['add'] = '住所は必須入力です。';
    }
*/
    if (empty($input_data['con-con'])) {
        $error_messages['con-con'] = 'お問い合わせ内容は必須入力です。';
    }
    
    if (empty($input_data['req-con'])) {
        $error_messages['req-con'] = 'ご希望の連絡方法は必須入力です。';
    }

    if (empty($input_data['pri-pol'])) {
        $error_messages['pri-pol'] = '「個人情報保護のお取扱い」にご同意をいただけない場合は送信できません。';
    }
    // =======================================================
    
    // 入力エラーがある場合、セッションにエラーメッセージとデータを格納し、フォームに戻る
    if (!empty($error_messages)) {
        $_SESSION['error_messages'] = $error_messages;
        $_SESSION['form_data'] = $input_data;
        header('Location: contact.php');
        exit;
    }

    // エラーがなければ、セッションに入力データを保存し、確認画面を表示
    $_SESSION['form_data'] = $input_data;
	$display_data = $input_data;

} else if ($is_send_action || ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_SESSION['form_data']))) {
    // 送信ボタンが押された場合、または修正ボタンからのアクセス後にリロードされた場合

    // セッションに保存されたデータを取得
    $input_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

    // セッションデータがない場合は入力フォームに戻す（不正アクセス対策）
    if (empty($input_data)) {
        header('Location: contact.php');
        exit;
    }

    // 表示用のデータを調整（ここでは特に修正はない）
    $display_data = $input_data;

} else {
    // POSTでもGET/sendでもなく、セッションデータもない場合は入力フォームに戻る
    header('Location: contact.php');
    exit;
}


// --------------------------------------------------------------------------------
// 3. メール送信処理 (送信ボタンが押された場合のみ)
// --------------------------------------------------------------------------------

$is_sent = false;
$mail_error = null;

if ($is_send_action) {
    // セッションデータからメールに含めるデータを取得
    $mail_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

    // ====== PHPMailer 設定とメールデータ定義 ======
    $TO_EMAIL = TO_EMAIL; // 定数 TO_EMAIL を使用
    $TO_NAME = '管理者';

    $SENDER_EMAIL = SENDER_EMAIL; // 定数 SENDER_EMAIL を使用
    $SENDER_NAME = 'スカイウォーカー お問い合わせ窓口';

    // SMTP設定 (config.phpの定数をそのまま使用)
    // ===============================================

    // メール本文の作成 (mb_send_mail時と同じ本文を使用)
    // ... ($mail_bodyの定義は既存のものを流用) ...
// --- 1. 表示用の変数を「単独で」記述 ---
    $tel_display = !empty($mail_data['tel']) ? $mail_data['tel'] : '（未入力）';
    $add_display = !empty($mail_data['add']) ? $mail_data['add'] : '（未入力）';

    // --- 2. その変数を使って本文を組み立てる ---
    $mail_body .= "■ご用件: " . $mail_data['bus'] . "\n";
    $mail_body .= "■お名前: " . $mail_data['name'] . "\n";
    $mail_body .= "■ふりがな: " . $mail_data['kana'] . "\n";
    $mail_body .= "■Email: " . $mail_data['mail'] . "\n";
    $mail_body .= "■電話番号: " . $tel_display . "\n"; // ここで改行が確実に入る
    $mail_body .= "■ご住所: " . $add_display . "\n"; // ここで改行が確実に入る
    $mail_body .= "■ご希望の連絡方法: " . $mail_data['req-con'] . "\n";
    $mail_body .= "■お問い合わせ内容:\n" . $mail_data['con-con'] . "\n";

    try {
        // --- 1通目：管理者宛メール送信 ---
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom($SENDER_EMAIL, mb_encode_mimeheader($SENDER_NAME));
        $mail->addAddress($TO_EMAIL, mb_encode_mimeheader($TO_NAME));
        $mail->addReplyTo($mail_data['mail'], mb_encode_mimeheader($mail_data['name'])); 

        $mail->isHTML(false);
        $mail->Subject = mb_encode_mimeheader('【要対応】WEBサイトよりお問い合わせがありました');
        $admin_body .= "---------------------------------------------------\n";
        $admin_body .= $mail_body; // ここで共通データを流し込む
        $admin_body .= "---------------------------------------------------\n\n";
        $admin_body .= "※このメールはWebサイトのフォームから自動送信されました。";

        $mail->Body = $admin_body;

        $is_admin_sent = $mail->send();

        // --- 2通目：ユーザー宛自動返信メール送信の準備 ---
        $mail->clearAllRecipients(); 
        $mail->clearReplyTos(); 
        
        // 【重要】送信元アドレスをお客様への表示用に「no-reply」へ切り替えます
        $mail->setFrom('no-reply@pclifecare.com', mb_encode_mimeheader('スカイウォーカー 送信専用窓口'));
        
        // 返信先も no-reply に設定
        $mail->addReplyTo('no-reply@pclifecare.com', mb_encode_mimeheader('スカイウォーカー 送信専用窓口'));
        
        $mail->addAddress($mail_data['mail'], mb_encode_mimeheader($mail_data['name'])); 
        
        // 件名は会社名を先頭にし、安心感を与えます
        $mail->Subject = mb_encode_mimeheader('【スカイウォーカー】お問い合わせを受け付けました');

        // ユーザー宛本文：構成を「感謝 → 内容 → 注意書き(フッター)」に変更
        $user_body  = "{$mail_data['name']} 様\n\n";
        $user_body .= "この度はお問い合わせいただき、誠にありがとうございます。\n";
        $user_body .= "以下の内容でお問い合わせを受け付けました。\n";
        $user_body .= "担当者より改めて内容を確認の上、ご連絡させていただきますので\n";
        $user_body .= "今しばらくお待ちください。\n\n";
        
        // ユーザー宛の見出しと破線
        $user_body .= "【お問い合わせ内容の控え】\n"; // ユーザー向けには「控え」という言葉が親切です
        $user_body .= "---------------------------------------------------\n";
        $user_body .= $mail_body; // 管理者宛と同じ項目を表示
        $user_body .= "---------------------------------------------------\n\n";

        $user_body .= "スカイウォーカー\n";
        $user_body .= "URL: https://pclifecare.com/SkyWalker/\n\n";
        
        // 注意書きを最後に配置して、突き放した印象を和らげます
        $user_body .= "※このメールはシステムによる自動配信です。\n";
        $user_body .= "※送信専用アドレスのため、直接ご返信いただいてもお答えできませんのでご了承ください。\n";
        
        $mail->Body = $user_body;

        $is_user_sent = $mail->send();

        $is_sent = $is_admin_sent && $is_user_sent;

    } catch (Exception $e) {
        // 送信失敗時のエラー処理	// ユーザーには表示せず、サーバーのログファイルにエラーを記録する
        error_log("PHPMailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());

        $is_sent = false;
        $mail_error = 'メールの送信に失敗しました。時間をおいて再度お試しください。';
    }

    if ($is_sent) {
        // 送信成功
        unset($_SESSION['form_data']); 
        unset($_SESSION['error_messages']); 
		unset($_SESSION['csrf_token']); // ★これもクリア推奨

        $_SESSION['is_form_complete'] = true; // フォーム完了フラグをセット
        
        header('Location: con-comp.php');
        exit;
} else {
        // 送信失敗
        $mail_error = 'メールの送信に失敗しました。時間をおいて再度お試しください。'; // 必要であればエラーメッセージを再設定
        $_SESSION['error_messages']['mail_error'] = $mail_error;
        header('Location: contact.php'); 
        exit;
    }
}
?>

<!doctype html>
<html>

 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/form-r.css">
    <title>お問い合わせ内容の確認 | SkyWalker</title>

    <style>
     footer { margin-top: 20px !important; } /* フッター上の余白を20pxに縮小 */
     #footer-contents { bottom: 56px !important; } /* 55pxよりさらに少し上げて調整 */
     /* 念のため、他ページとロゴ周りの条件を揃える（間隔が変わらないようにする） */
     #footer-contents img { margin-bottom: 4.5px !important; }
     #footer-contents small { line-height: 1.0 !important; }
    </style>

    <?php if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false): ?>
     <script type="module" src="http://localhost:7004/@vite/client"></script>
    <?php endif; ?>

</head>

 <body class="confirmation-page">

 <!-- ここから確認画面 -->
 <main class="flex-shrink-0">
 <div class="container w-75 mt-5 mb-2 box04">
  <div class="row g-2">
   <div class="col-12">
    <div class="box01">
     <div class="container p-4">
      <div class="row">
       <div class="col-12 col-md-10 offset-md-1">

        <div>
         <h2 class="text-center" style="margin-bottom: 10px;">
          <?php if (isset($_GET['status']) && $_GET['status'] === 'complete'): ?>
           <i class="bi bi-check-circle pe-2 text-success"></i>送信完了
          <?php else: ?>
           <i class="bi bi-envelope-check pe-2 text-danger"></i>入力内容の確認
          <?php endif; ?>
         </h2>
         <p class="text-center">
          <?php if (isset($_GET['status']) && $_GET['status'] === 'complete'): ?>
           お問い合わせを送信いたしました。ありがとうございました。
          <?php else: ?>
           下記の内容でお間違いなければ、送信ボタンを押してください。
          <?php endif; ?>
         </p>
        </div>
                                
          <?php if (!isset($_GET['status']) || $_GET['status'] !== 'complete'): ?>
                                    
          <table class="table table-bordered mt-5">
           <tbody>

            <tr>
             <th class="form01">ご用件</th>
             <td class="form02"><?php echo h($display_data['bus']); ?></td>
            </tr>
            <tr>
             <th class="form01">お名前</th>
			 <td class="form02"><?php echo h($display_data['name']); ?></td>
            </tr>
            <tr>
             <th class="form01">ふりがな</th>
             <td class="form02"><?php echo h($display_data['kana']); ?></td>
            </tr>
            <tr>
             <th class="form01">Email</th>
             <td class="form02"><?php echo h($display_data['mail']); ?></td>
            </tr>
            <tr>
             <th class="form01">電話番号</th>
             <td class="form02"><?php echo !empty($display_data['tel']) ? h($display_data['tel']) : '（未入力）'; ?></td>
            </tr>
            <tr>
             <th class="form01">住所</th>
             <td class="form02"><?php echo !empty($display_data['add']) ? h($display_data['add']) : '（未入力）'; ?></td>
            </tr>
            <tr>
             <th class="form01">ご希望の連絡方法</th>
             <td class="form02"><?php echo h($display_data['req-con']); ?></td>
            </tr>
            <tr>
             <th class="form01">お問い合わせ内容</th>
             <td class="form02"><?php echo nl2br(h($display_data['con-con'])); ?></td>
            </tr>
            <tr>
             <th class="form01">プライバシーポリシー</th>
             <td class="form02"><?php echo isset($display_data['pri-pol']) && $display_data['pri-pol'] === 'agree' ? '同意済み' : '未同意'; ?></td>
            </tr>

           </tbody>
          </table>

           <br>

        <div class="text-center mt-4">

          <form action="contact.php" method="get" style="display: inline-block;">
           <button type="submit" class="btn btn-secondary btn-md">修正する</button>
          </form>

          <form action="con-res.php" method="post" style="display: inline-block;">
           <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
           <button type="submit" name="action" value="send" class="btn btn-danger btn-md me-3">送信する</button>
          </form>

        </div>

          <?php else: ?>
        <div class="text-center mt-5">
         <a href="contact.php" class="btn btn-primary btn-md">トップへ戻る</a>
        </div>
          <?php endif; ?>

       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
 </div>
 </main>
  <!-- ここまで確認画面 -->

  <div class="footer-od-bg"></div>
  
  <!-- ここからフッター -->
  <footer class="text-white footer-slim">
    <div class="container text-center">
      <p class="small mb-0 opacity-75">© 2026 株式会社どっとこむ｜SkyWalker</p>
    </div>
  </footer>
   <!-- ここまでフッター -->

  <script src="../js/bootstrap.bundle.min.js"></script>
 </body>

</html>