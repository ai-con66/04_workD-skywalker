<template>
  <header :class="['global-header', { 'is-scrolled': isScrolled, 'is-visible': isMainContentVisible }]">
    <div class="header-container">
      <a href="#" class="logo-link">
        <img :src="isScrolled ? logoDark : logoLight" alt="SkyWalker Logo" class="header-logo" />
      </a>
      <nav class="header-nav">
        <a href="#about-section" class="nav-item">ABOUT</a>
        <a href="#services-section" class="nav-item">SERVICES</a>
        <a href="#safety-section" class="nav-item">SAFETY</a>
        <a href="#usecase-section" class="nav-item">USE CASES</a>
        <a href="#contact-section" class="nav-item">CONTACT</a>
      </nav>
    </div>
  </header>
</template>

<script setup lang="ts">
// App.vueからデータを受け取るための定義 (Props)
defineProps<{
  isScrolled: boolean;
  isMainContentVisible: boolean;
}>();

// 画像のパスはコンポーネント階層に合わせるため '../' になります
import logoLight from '../assets/brand/SkyWalker01.svg';
import logoDark from '../assets/brand/SkyWalker02.svg'; 
</script>

<style scoped>
/* ヘッダー関連のCSSのみ抜粋 */
.global-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 100;
  padding: 40px 0;
  opacity: 0;
  transform: translateY(-30px);
  pointer-events: none;
  transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
              transform 1s cubic-bezier(0.16, 1, 0.3, 1),
              background-color 0.4s, 
              box-shadow 0.4s, 
              padding 0.4s;
}

.global-header.is-visible {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

.global-header.is-scrolled {
  background-color: rgba(255, 255, 255, 0.95);
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
  padding: 15px 0;
}

.header-container {
  width: 100%;
  padding: 0 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-sizing: border-box;
}

.logo-link {
  display: flex;
  align-items: center;
}

.header-logo {
  width: 200px;
  height: auto;
  transition: transform 0.3s, filter 0.4s;
  filter: drop-shadow(0px 2px 8px rgba(0, 0, 0, 0.3));
}

.global-header.is-scrolled .header-logo {
  filter: none;
}

.header-nav {
  display: flex;
  gap: 12px;
}

.nav-item {
  color: #ffffff;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: bold;
  letter-spacing: 0.05em;
  padding: 8px 18px;
  border: 1px solid rgba(255, 255, 255, 0.7);
  border-radius: 4px;
  background-color: transparent;
  transition: all 0.3s ease;
  text-shadow: 0px 1px 4px rgba(0, 0, 0, 0.6);
  box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);
}

.nav-item:hover {
  background-color: rgba(255, 255, 255, 0.15);
  border-color: #ffffff;
}

.global-header.is-scrolled .nav-item {
  color: #1e293b;
  border-color: rgba(30, 41, 59, 0.4);
  text-shadow: none;
  box-shadow: none;
}

.global-header.is-scrolled .nav-item:hover {
  background-color: rgba(30, 41, 59, 0.06);
  border-color: #1e293b;
}

@media (max-width: 768px) {
  .global-header { padding: 10px 0; }
  .header-container {
    padding: 0 10px;
    flex-direction: column;
    gap: 8px;
  }
  .header-logo { width: 120px; }
  .header-nav {
    gap: 3px;
    width: 100%;
    justify-content: center;
  }
  .nav-item {
    font-size: 0.62rem;
    padding: 4px 5px;
    border-radius: 3px;
    letter-spacing: 0;
    white-space: nowrap;
  }
}
</style>