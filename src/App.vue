<template>
  <div class="skywalker-lp">
    
    <GlobalHeader 
      :is-scrolled="isScrolled" 
      :is-main-content-visible="isMainContentVisible" 
    />
    
    <HeroSection 
      :is-main-content-visible="isMainContentVisible"
      @update-visible="isMainContentVisible = $event"
    />

    <AboutSection />
    <ServicesSection />
    <SafetySection />
    <UseCaseSection />
    <ContactSection />
    <GlobalFooter />

    <!-- トップへ戻るボタン -->
    <button 
      :class="['scroll-top-btn', { 'is-visible': showScrollTop, 'is-clicked': isClicked }]" 
      @click="scrollToTop"
      aria-label="ページトップへ戻る"
    >
      <img 
        :src="logo01" 
        alt="トップへ戻る" 
        class="scroll-top-img"
      />
    </button>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

// 各セクションのコンポーネント呼び出し
import GlobalHeader from './components/GlobalHeader.vue';
import HeroSection from './components/HeroSection.vue';
import AboutSection from './components/AboutSection.vue';
import ServicesSection from './components/ServicesSection.vue';
import SafetySection from './components/SafetySection.vue';
import UseCaseSection from './components/UseCaseSection.vue';
import ContactSection from './components/ContactSection.vue';
import GlobalFooter from './components/GlobalFooter.vue';

// トップへ戻るボタン用SVG
import logo01 from './assets/brand/logo01.svg';

// メインコンテンツ表示フラグ (HeaderとHeroで共有)
const isMainContentVisible = ref(false);

// スクロール状態の管理
const isScrolled = ref(false);
const showScrollTop = ref(false);
const isClicked = ref(false);

const handleScroll = () => {
  const heroHeight = window.innerHeight * 0.9;
  
  // スクロール後にヘッダーのデザインを切り替える
  isScrolled.value = window.scrollY > (heroHeight - 80);
  
  // 300px以上スクロールしたらボタンを表示
  const shouldShow = window.scrollY > 300;
  showScrollTop.value = shouldShow;

  if (window.scrollY === 0 || !shouldShow) {
    isClicked.value = false;
  }
};

const scrollToTop = () => {
  isClicked.value = true;

  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* スムーススクロール */
html {
  scroll-behavior: smooth;
}

.skywalker-lp {
  font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
  color: #333;
  line-height: 1.8;
  background-color: #ffffff;
}

.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 24px;
}

/* トップへ戻るボタン */
.scroll-top-btn {
  position: fixed;
  bottom: 40px;
  right: 40px;
  width: 36px;
  height: 36px;
  background: transparent;
  border: none;
  padding: 0;
  cursor: pointer;
  z-index: 99;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  pointer-events: none;
  transform: translateY(20px);
  transition: opacity 0.3s, transform 0.3s;
}

.scroll-top-btn.is-visible {
  opacity: 1;
  pointer-events: auto;
  transform: translateY(0);
}

.scroll-top-btn:hover {
  transform: translateY(-4px);
}

.scroll-top-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

/* プロペラ回転アニメーション */
@keyframes spin-clockwise {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spin-counterclockwise {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(-360deg); }
}

.scroll-top-btn:hover .scroll-top-img {
  animation: spin-clockwise 0.8s linear infinite;
}

.scroll-top-btn.is-clicked .scroll-top-img {
  animation: spin-counterclockwise 0.8s linear infinite;
}

@media (max-width: 768px) {
  .scroll-top-btn {
    bottom: 20px;
    right: 20px;
    width: 25px;
    height: 25px;
  }
}
</style>

<style>
html, body {
  margin: 0;
  padding: 0;
}
</style>