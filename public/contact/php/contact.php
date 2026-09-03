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
    <link rel="stylesheet" href="../css/form.css">
    <title>お問い合わせ | SkyWalker</title>

    <style>
     footer { margin-top: 20px !important; } /* フッター上の余白を20pxに縮小 */
     .box04 { margin-bottom: 0 !important; } /* 白いボックス下の余白をなくす */
    </style>

    <?php if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false): ?>
     <script type="module" src="http://localhost:7004/@vite/client"></script>
    <?php endif; ?>

</head>

<body>

<?php
// PHPのセッションを開始
session_start();

// スマホ判定用の簡易関数
function is_mobile() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    return (preg_match('/(iPhone|Android.*Mobile|Windows Phone)/', $user_agent));
}

// ====== CSRFトークンの生成とセッションへの保存 ======
if (empty($_SESSION['csrf_token'])) {
    // トークンがなければ、安全なランダムバイトから新しいトークンを生成
    // PHP 7.0以降で利用可能なrandom_bytes関数を使用
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
// ========================================================

// セッションからエラーメッセージとフォームデータを取得
$error_messages = isset($_SESSION['error_messages']) ? $_SESSION['error_messages'] : []; 
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// 取得後はセッションデータをクリア（ページ再読み込み時やフォームに移動した際にエラーが残らないように）
unset($_SESSION['error_messages']); 
unset($_SESSION['form_data']);

// フォームの初期値を制御するヘルパー関数
function get_initial_data($key, $data) {
    return isset($data[$key]) ? htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8') : '';
}

// フォームデータを簡単に取得するヘルパー関数
function get_data($key, $data) {
    return isset($data[$key]) ? htmlspecialchars($data[$key], ENT_QUOTES, 'UTF-8') : '';
}

// 個別エラーメッセージを取得するヘルパー関数
function get_error($key, $error_messages) {
    if (isset($error_messages[$key])) {
        return '<div class="error-message">' . htmlspecialchars($error_messages[$key], ENT_QUOTES, 'UTF-8') . '</div>';
    }
    return '';
}

// フォームの入力項目に`is-invalid`クラスを追加するか判定するヘルパー関数
function is_invalid($key, $error_messages) {
    return isset($error_messages[$key]) ? ' is-invalid' : '';
}
?>

<div class="container-xl my-5 box04">
  <div class="row justify-content-center g-2">

   <div class="col-12 col-md-9">
    <div class="box01">
     <div class="container py-4 px-3 py-md-5 px-md-5">
      <div class="row">
       <div class="col-10 offset-1 col-md-10 offset-md-1">

        <div>
         <h2 class="text-center" style="margin-bottom: 10px;"><i class="bi bi-envelope pe-2 text-danger"></i>お問い合わせ</h2>
         <p class="text-center">下記フォームに必要事項を入力後、<br class="d-md-none">確認ボタンを押してください。<br>
         <span class="required-mark">＊</span>の項目は必須です。</p>
        </div>
                            
        <form class="row g-3 mt-5" action="con-res.php" method="post" autocomplete="off">
         <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

         <div class="row mb-3">
          <label for="inputSubject" class="col-md-3 col-form-label">ご用件<span class="required-mark">＊</span></label>
          <div class="col-md-7">
           <select id="inputSubject" name="bus" class="form-select<?php echo is_invalid('bus', $error_messages); ?>">
            <option value="" selected>選択してください</option>

            <optgroup label="一般">
              <option value="このサイトについて" <?php echo (get_initial_data('bus', $form_data) === 'このサイトについて') ? 'selected' : ''; ?>>このサイトについて</option>
              <option value="その他" <?php echo (get_initial_data('bus', $form_data) === 'その他') ? 'selected' : ''; ?>>その他</option>
            </optgroup>

            <?php if (!is_mobile()): ?>
             <option disabled> </option>
            <?php endif; ?>

            <optgroup label="サービス">
              <option value="ドローン事業" <?php echo (get_initial_data('bus', $form_data) === 'ドローン事業') ? 'selected' : ''; ?>>ドローン事業</option>
            </optgroup>

           </select>
           <?php echo get_error('bus', $error_messages); ?>
          </div>
         </div>

         <div class="col-md-6">
          <label for="inputName" class="form-label">お名前<small>(苗字だけでも可)</small><span class="required-mark">＊</span></label>
          <input type="text" class="form-control<?php echo is_invalid('name', $error_messages); ?>" id="inputName" name="name" placeholder="例）山田 太郎" value="<?php echo get_initial_data('name', $form_data); ?>">
          <?php echo get_error('name', $error_messages); ?>
         </div>

         <div class="col-md-6">
          <label for="inputSubName" class="form-label">ふりがな<span class="required-mark">＊</span></label>
          <input type="text" class="form-control<?php echo is_invalid('kana', $error_messages); ?>" id="inputSubName" name="kana" placeholder="例）やまだ たろう" value="<?php echo get_initial_data('kana', $form_data); ?>">
          <?php echo get_error('kana', $error_messages); ?>
         </div>

         <div class="col-md-6">
          <label for="inputEmail" class="form-label">Email<small>(半角英数字)</small><span class="required-mark">＊</span></label>
          <input type="email" class="form-control<?php echo is_invalid('mail', $error_messages); ?>" id="inputEmail" name="mail" placeholder="例）sample@example.com" value="<?php echo get_initial_data('mail', $form_data); ?>">
          <?php echo get_error('mail', $error_messages); ?>
         </div>

         <div class="col-md-6">
          <label for="InputPhone" class="form-label">電話番号<small>(半角数字・ハイフンなし)</small></label>
          <input type="tel" class="form-control<?php echo is_invalid('tel', $error_messages); ?>" id="InputPhone" name="tel" placeholder="例）0311112222" value="<?php echo get_initial_data('tel', $form_data); ?>">
          <?php echo get_error('tel', $error_messages); ?>
         </div>

         <div class="col-12">
          <label for="inputAddress" class="form-label">住所</label>
          <input type="text" class="form-control<?php echo is_invalid('add', $error_messages); ?>" id="inputAddress" name="add" placeholder="例）東京都千代田区〇〇 1-2-3" value="<?php echo get_initial_data('add', $form_data); ?>">
          <?php echo get_error('add', $error_messages); ?>
         </div>

         <fieldset class="row mt-3">
          <legend class="col-form-label col-sm-4 pt-0">ご希望の連絡方法</legend>
           <div class="col-sm-8">

            <?php
            // チェックされた値を取得
            $req_con_checked = get_initial_data('req-con', $form_data) ? get_initial_data('req-con', $form_data) : 'メール';
            $options = ['メール', '電話', 'どちらでも'];
            foreach ($options as $option):
            ?>

          <div class="form-check">
           <input class="form-check-input" type="radio" name="req-con" id="gridRadios<?php echo $option; ?>" value="<?php echo $option; ?>" <?php echo ($req_con_checked === $option) ? 'checked' : ''; ?>>
           <label class="form-check-label" for="gridRadios<?php echo $option; ?>">
            <?php echo $option; ?>
           </label>
          </div>
           <?php endforeach; ?>
           <?php echo get_error('req-con', $error_messages); ?>
          </div>
         </fieldset>
                                
         <div class="col-12">
          <label for="InputTextarea" class="form-label">お問い合わせ内容<span class="required-mark">＊</span></label>
          <textarea class="form-control<?php echo is_invalid('con-con', $error_messages); ?>" id="InputTextarea" name="con-con" rows="8" placeholder="内容をご記入ください。"><?php echo get_initial_data('con-con', $form_data); ?></textarea>
          <?php echo get_error('con-con', $error_messages); ?>
         </div>

         <div class="privacy-container text-center mt-4">
          <p class="mb-0 privacy-text" style="color: #555;">個人情報のお取り扱いについて、<a href="javascript:void(0)" class="text-danger fw-bold link-custom privacy-link" data-bs-toggle="modal" data-bs-target="#privacyModal">プライバシーポリシー</a>をご確認いただき、ご同意の上でお申し込みください。</p>
         </div>

         <div class="col-12 mt-1"> 
           <div class="text-center">
             <div class="form-check form-check-custom d-inline-block" style="white-space: nowrap;">
               <input class="form-check-input<?php echo is_invalid('pri-pol', $error_messages); ?>" type="checkbox" name="pri-pol" id="privacyPolicyCheck" value="agree" <?php echo get_initial_data('pri-pol', $form_data) ? 'checked' : ''; ?>>
               <label class="form-check-label" for="privacyPolicyCheck">
                <span class="fw-bold">"個人情報のお取り扱い"</span> に同意する
               </label>
             </div>
           </div>
           <div class="text-center">
             <?php echo get_error('pri-pol', $error_messages); ?>
           </div>
         </div>

         <div class="col-12 text-center mt-5">
          <button type="button" class="btn btn-secondary btn-md" onclick="location.reload()">リセット</button>
          <button type="submit" class="btn btn-danger btn-md">確認画面へ</button>
         </div>

        </form>

       </div>
      </div>
     </div>
    </div>
   </div>

  </div></div><div class="container-fluid w-75">
  <div class="border-top bdrc01 border-3 mt-5" style="padding:5px;"></div>
  </div>

  <div class="text-center my-5">
    <button type="button" onclick="window.close()" class="btn-close-window">
        <i class="bi bi-x-circle me-1"></i> 画面を閉じる
    </button>
  </div>


  <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="privacyModalLabel">個人情報の取り扱いについて<br class="d-md-none">（プライバシーポリシー）</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 p-md-5">
          
          <p class="mb-4">株式会社どっとこむ（以下「当社」）は、お問い合わせに際してお預かりする個人情報を、以下の通り適切に取り扱います。</p>

          <h3 class="h5 fw-bold mt-4 mb-2 border-bottom pb-2">1. 個人情報の利用目的</h3>
          <p class="mb-3">当社は、取得した個人情報を以下の目的の範囲内で利用し、ご本人の同意なく目的外の利用はいたしません。</p>
          <ul class="ps-4 mb-4">
            <li>お客様からのお問い合わせ、ご相談への回答および資料送付のため</li>
            <li>当社サービスの提供、連絡、打ち合わせ等の営業活動のため</li>
            <li>ご希望いただいたサービス（ドローン事業等）の実施のため</li>
          </ul>

          <h3 class="h5 fw-bold mt-4 mb-2 border-bottom pb-2">2. 第三者提供について</h3>
          <p class="mb-4">当社は、法令に基づく場合を除き、事前にお客様の同意を得ることなく個人情報を第三者に提供することはありません。</p>

          <h3 class="h5 fw-bold mt-4 mb-2 border-bottom pb-2">3. 安全管理措置について</h3>
          <p class="mb-4">当社は、個人情報の漏えい、滅失、き損などのリスクに対して、合理的な安全対策を講じて防止に努めます。</p>

          <h3 class="h5 fw-bold mt-4 mb-2 border-bottom pb-2">4. 個人情報の開示・訂正・削除について</h3>
          <p class="mb-4">お客様がご自身の個人情報の開示、訂正、削除等を希望される場合は、下記のお問い合わせ先までご連絡ください。<br class="d-none d-md-inline">ご本人であることを確認の上、速やかに対応いたします。</p>

          <hr class="my-5">
          
          <div class="bg-light p-4 rounded">
            <h4 class="h6 fw-bold mb-3">【個人情報に関するお問い合わせ先】</h4>
            <p class="mb-1">窓口：個人情報お問合せ担当</p>
            <p class="mb-1">責任者：個人情報保護管理者</p>
            <p class="mb-1">住所：〒160-0023 東京都新宿区西新宿7-5-11 岡山ビル8F</p>
            <p class="mb-1">TEL：03-3364-4580</p>
            <p class="mb-0">FAX：03-3364-4581</p>
          </div>

          <div class="text-center mt-5">
            <p class="small text-muted">
            より詳細な規約（個人情報保護方針・公表事項等）については、<a href="../../html/privacy.html" target="_blank" rel="noopener noreferrer" class="text-danger fw-bold">こちらのページ</a>をご確認ください。<br>※別ウィンドウになります。
            </p>
          </div>
          
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">閉じる</button>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-od-bg"></div>
  
  <footer class="text-white footer-slim">
    <div class="container text-center">
      <p class="small mb-0 opacity-75">© 2026 株式会社どっとこむ｜SkyWalker</p>
    </div>
  </footer>

   <script src="../js/bootstrap.bundle.min.js"></script>

 </body>
</html>