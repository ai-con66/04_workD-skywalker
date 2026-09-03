<?php

session_start();

// 送信成功フラグ（例: 'is_form_complete'）をセッションでチェックする
if (!isset($_SESSION['is_form_complete']) || !$_SESSION['is_form_complete']) {
// フラグがなければ、このページへの直アクセスとみなし、入力フォームへ戻す
header('Location: contact.php');
exit;
}

// 完了画面の表示が許可されたので、セッションフラグをクリアする
// これにより、ブラウザバックや再アクセスしても再度入力フォームに戻される
unset($_SESSION['is_form_complete']);

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
    <title>お問い合わせ送信完了 | SkyWalker</title>

   <style>
    footer { margin-top: 20px !important; } /* フッター上の余白を20pxに縮小 */
    .box01 { margin-bottom: 0 !important; } /* 白いボックス下の余白をなくす */
   </style>

    <?php if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false): ?>
     <script type="module" src="http://localhost:7004/@vite/client"></script>
    <?php endif; ?>

</head>

 <body>
 <main class="flex-shrink-0">
 <div class="container mt-5">
  <div class="row justify-content-center">
   <div class="col-lg-8">
    <div class="box01 p-5 shadow-lg">
     <div class="text-center">

      <h1 class="mb-4">お問い合わせの送信が完了しました</h1>
      <p class="lead">この度はお問い合わせいただき、誠にありがとうございます。</p>
      <p>お客様宛に自動返信メールを送信いたしましたので、ご確認ください。</p>
      <p>担当者より改めてご連絡させていただきますので、今しばらくお待ちください。</p>
                    
     <div class="text-center mt-5 mb-5">
      <button type="button" onclick="window.close()" class="btn-close-window">
        画面を閉じてサイトへ戻る
      </button>
     </div>

     </div>
    </div>
   </div>
  </div>
 </div>
 </main>

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