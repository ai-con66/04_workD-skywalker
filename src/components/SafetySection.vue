<template>
  <section id="safety-section" class="safety-section">
    <div class="container">
      <span class="section-subtitle">SAFETY & TRUST</span>
      <h2>安全・安心への取り組み</h2>
      <p class="section-intro">私たちは「ITと安全」を最優先に、確実なオペレーションをお約束します。</p>
      <div class="safety-grid">
        <div class="safety-item">
          <h4>✓ 航空法に基づく飛行許可</h4>
          <p>国土交通省の日本全国包括飛行許可を承認済み。法規を遵守した適正な運用を行います。</p>
        </div>
        <div class="safety-item">
          <h4>✓ 万全の対物・対人保険</h4>
          <p>最高○億円規模のドローン専用賠償責任保険に加入。万が一の事態にも迅速に対応します。</p>
        </div>
        <div class="safety-item">
          <h4>✓ プロパイロットによる運航</h4>
          <p>厳しい訓練を受け、高度な操縦技術と安全意識を持つプロのオペレーターが飛行を担当します。</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
// 動的な処理はないため空でOKです
</script>

<style scoped>
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

.safety-section {
  background-color: #ffffff;
  border-top: 1px solid #e2e8f0;
}

.section-intro {
  text-align: center;
  color: #64748b;
  margin-top: -30px;
  margin-bottom: 50px;
}

.safety-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
  max-width: 900px;
  margin: 0 auto;
}

.safety-item {
  background: #f8fafc;
  padding: 24px;
  border-radius: 8px;
}

.safety-item h4 {
  font-size: 1.1rem;
  color: #1e293b;
  margin-bottom: 12px;
  font-weight: bold;
}

.safety-item p {
  font-size: 0.9rem;
  color: #64748b;
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