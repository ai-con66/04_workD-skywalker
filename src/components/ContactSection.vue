<template>
  <section id="contact-section" class="contact-section">
    <div class="container text-center">
      <span class="section-subtitle white">CONTACT</span>
      <h2>お問い合わせ</h2>
      <p>ドローンの活用について、まずはお気軽にご相談ください。</p>
      
      <div class="test-area">
        <button class="btn-style outline-btn" @click="count++">
          システムテスト: {{ count }} 回
        </button>
      </div>

      <a :href="contactUrl" target="_blank" rel="noopener noreferrer" class="btn-style link-btn">
        お問い合わせフォームを開く
      </a>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

const count = ref(0);

const contactUrl = computed(() => {
  const baseUrl = import.meta.env.BASE_URL;
  const phpPath = 'contact/php/contact.php';
  return baseUrl.endsWith('/') ? `${baseUrl}${phpPath}` : `${baseUrl}/${phpPath}`;
});
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

.section-subtitle.white {
  color: #a0aec0;
}

h2 {
  text-align: center;
  font-size: 2.2rem;
  margin-bottom: 48px;
  color: white;
  font-weight: 700;
}

.contact-section {
  background-color: #0f172a;
  color: white;
}

.text-center {
  text-align: center;
}

.test-area {
  margin: 32px 0;
}

/* ボタン共通スタイル */
.btn-style {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 16px 48px;
  font-size: 1.1rem;
  font-weight: bold;
  cursor: pointer;
  border: none;
  border-radius: 50px;
  text-decoration: none;
  transition: background-color 0.3s, transform 0.2s;
}

.btn-style:active {
  transform: scale(0.98);
}

.link-btn {
  background-color: #28a745;
  color: white;
  box-shadow: 0 4px 14px rgba(40, 167, 69, 0.4);
}

.link-btn:hover {
  background-color: #218838;
}

.outline-btn {
  background-color: transparent;
  border: 2px solid white;
  color: white;
  padding: 10px 32px;
  font-size: 1rem;
}

.outline-btn:hover {
  background-color: white;
  color: #0f172a;
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