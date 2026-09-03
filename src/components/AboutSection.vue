<template>
  <section id="about-section" class="about-section">
    <div class="container">
      <span class="section-subtitle">ABOUT</span>
      <h2>どっとこむのドローン事業</h2>
      <p class="lead-text">
        ドローンは単に「空を飛ぶ」ツールではなく、膨大なデータを収集し、作業を劇的に変えるデバイスです。<br>
        熟練オペレーターによる安全飛行から、撮影データの解析・IT活用までワンストップで対応します。<br>
        スマート農業の推進からインフラ点検まで、お客様のニーズに最適なソリューションを提供いたします。
      </p>
    </div>
  </section>
</template>

<script setup lang="ts">
// 動的な処理はないため空でOKです
</script>

<style scoped>
/* セクション全体の上下余白（ここで動画との距離を保ちます） */
section {
  padding: 100px 0;
}

/* サブタイトルスタイル */
.section-subtitle {
  display: block;
  text-align: center;
  font-size: 0.9rem;
  font-weight: bold;
  letter-spacing: 0.15em;
  color: #007bff;
  margin-bottom: 8px;
}

/* 見出しスタイル */
h2 {
  text-align: center;
  font-size: 2.2rem;
  margin-bottom: 48px;
  color: #1a202c;
  font-weight: 700;
}

/* ABOUTセクション固有のスタイル */
.about-section {
  background-color: #f8fafc;
  text-align: center;
}

.lead-text {
  font-size: 1.15rem;
  max-width: 980px;
  margin: 0 auto;
  color: #475569;
  letter-spacing: -0.01em;
}

/* スマホ対応の余白調整 */
@media (max-width: 768px) {
  h2 {
    font-size: 1.6rem;
  }
  section {
    padding: 60px 0;
  }
}
</style>