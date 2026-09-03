<template>
  <footer class="global-footer">
    <p class="copyright">© 2026 株式会社どっとこむ｜SkyWalker</p>
  </footer>
</template>

<script setup lang="ts">
// 動的な処理はないため空でOKです
</script>

<style scoped>
/* フッター専用のCSSのみ */
.global-footer {
  width: 100%;
  background-color: #ffffff;
  border-top: 1px solid #e2e8f0;
  padding: 12px 0;
  text-align: center;
}

.copyright {
  margin: 0;
  font-size: 0.85rem;
  color: #64748b;
  letter-spacing: 0.05em;
}

/* スマホ対応のメディアクエリ */
@media (max-width: 768px) {
  .global-footer {
    padding: 8px 0;
  }
  .copyright {
    font-size: 0.75rem;
  }
}
</style>