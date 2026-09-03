<template>
  <section id="services-section" class="services-section">
    <div class="container">
      <span class="section-subtitle">SERVICES</span>
      <h2>サービス内容</h2>
      <div class="service-grid">
        <div class="service-card">
          <div class="card-icon">🌱</div>
          <h3>スマート農業<br>（農薬散布代行）</h3>
          <p>最新の自動航行ドローンを用い、農薬や肥料の散布を代行します。手作業に比べて作業時間を大幅に短縮（1ヘクタール約10分）できるだけでなく、自動制御による均一な散布により、コスト削減と収穫量の安定を支援します。</p>
        </div>
        <div class="service-card">
          <div class="card-icon">🏗️</div>
          <h3>高所・危険箇所の<br>点検代行</h3>
          <p>足場の設置が必要な屋根点検や、人が立ち入るのが困難な場所（橋梁、看板、法面など）の状況を確認します。高解像度カメラや赤外線カメラにより、目視では気づきにくい劣化を早期に発見。安全性の向上と点検費用の大幅な削減に貢献します。</p>
        </div>
        <div class="service-card">
          <div class="card-icon">🎬</div>
          <h3>プロモーション空撮<br>（4K対応）</h3>
          <p>企業のプロモーション動画や施設紹介、観光PRに。4K高画質での撮影により、地上からは決して得られない迫力の映像を提供します。動画制作事業とも連携し、撮影した素材をそのまま高品質なPR動画として仕上げることも可能です。</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// 動的な処理はないため空でOKです
</script>

<style scoped>
/* 共通セクションスタイル */
section {
  padding: 100px 0;
}

.section-subtitle {
  display: block;
  text-align: center;
  font-size: 0.9rem;
  font-weight: bold;
  letter-spacing: 0.15em;
  color: #007bff;
  margin-bottom: 8px;
}

h2 {
  text-align: center;
  font-size: 2.2rem;
  margin-bottom: 48px;
  color: #1a202c;
  font-weight: 700;
}

/* Services固有のCSS */
.service-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 32px;
}

.service-card {
  background: white;
  padding: 40px 32px;
  border-radius: 12px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
  border-top: 5px solid #007bff;
  transition: transform 0.3s;
}

.service-card:hover {
  transform: translateY(-5px);
}

.card-icon {
  font-size: 2.5rem;
  margin-bottom: 16px;
}

.service-card h3 {
  font-size: 1.4rem;
  margin-bottom: 16px;
  color: #1e293b;
  line-height: 1.4;
}

.service-card p {
  color: #64748b;
  font-size: 0.95rem;
}

@media (max-width: 768px) {
  h2 {
    font-size: 1.6rem;
  }
  section {
    padding: 60px 0;
  }
}
</style>