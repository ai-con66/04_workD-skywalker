<template>
  <section id="usecase-section" class="usecase-section">
    <div class="container">
      <span class="section-subtitle">USE CASES</span>
      <h2>次のようなプロジェクトに最適です</h2>
      <ul class="usecase-list">
        <li>広大な農地での散布作業を省力化・自動化したい</li>
        <li>安全かつ低コストで屋根や外壁、看板の現状を把握したい</li>
        <li>会社紹介やイベント用に、インパクトのある空撮映像が欲しい</li>
        <li>災害時や急斜面など、人が近づくのが危険な場所を確認したい</li>
        <li>ドローンで取得したデータをITシステムと連携・蓄積したい</li>
      </ul>
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

.usecase-section {
  background-color: #f1f5f9;
}

.usecase-list {
  list-style: none;
  padding: 0;
  max-width: 750px;
  margin: 0 auto;
}

.usecase-list li {
  background: white;
  margin-bottom: 16px;
  padding: 18px 24px 18px 48px;
  border-radius: 8px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  position: relative;
  font-weight: 600;
  color: #334155;
}

.usecase-list li::before {
  content: '✓';
  position: absolute;
  left: 20px;
  color: #28a745;
  font-size: 1.3rem;
  font-weight: bold;
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