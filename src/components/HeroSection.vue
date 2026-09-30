<template>
  <header class="hero-section">
    <video 
      ref="heroVideoRef"
      class="hero-video"
      src="/videos/swtop01.mp4"
      playsinline
      autoplay
      muted
      @timeupdate="handleTimeUpdate"
    ></video>
    
    <div :class="['hero-text-container', { 'is-visible': isMainContentVisible }]">
      <h1>仰瞰から、鳥瞰へ。</h1>
      <p class="hero-catch">最新のドローン技術とITソリューションを融合。<br>空からの視点で「新しい効率化」を提案します。</p>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';

// App.vueからデータを受け取る
const props = defineProps<{
  isMainContentVisible: boolean;
}>();

// App.vueへ「動画が4秒になったよ！」と知らせる (Emit)
const emit = defineEmits<{
  (e: 'update-visible', value: boolean): void;
}>();

const heroVideoRef = ref<HTMLVideoElement | null>(null);
const LOOP_START_TIME = 4.3;
const END_OFFSET = 0.3; 

const handleTimeUpdate = () => {
  const video = heroVideoRef.value;
  if (video && video.duration) {
    if (!props.isMainContentVisible && video.currentTime >= 4.0) {
      // 親(App.vue)に true を通知する
      emit('update-visible', true);
    }
    if (video.currentTime >= video.duration - END_OFFSET) {
      video.currentTime = LOOP_START_TIME;
    }
  }
};
</script>

<style scoped>
/* ヒーローセクション関連のCSSのみ抜粋 */
.hero-section {
  position: relative;
  height: 90vh;
  min-height: 600px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: white;
  background-color: #0f172a;
}

.hero-video {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  object-fit: cover;
  z-index: 1;
}

.hero-text-container {
  position: absolute;
  bottom: 50px;
  right: 50px;
  z-index: 2;
  text-align: right;
  width: 90%;
  max-width: 800px;
}

.hero-text-container h1,
.hero-text-container .hero-catch {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1),
              transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero-text-container.is-visible h1 {
  opacity: 1;
  transform: translateY(0);
  transition-delay: 0.2s;
}

.hero-text-container.is-visible .hero-catch {
  opacity: 1;
  transform: translateY(0);
  transition-delay: 0.4s;
}

.hero-text-container h1 {
  font-size: 3rem;
  margin-bottom: 20px;
  line-height: 1.4;
  font-weight: 700;
  letter-spacing: 0.1em;
  color: #ffffff;
  text-shadow: 0px 2px 8px rgba(0, 0, 0, 0.8), 0px 4px 20px rgba(0, 0, 0, 0.6);
}

.hero-text-container .hero-catch {
  font-size: 1.25rem;
  margin-bottom: 0;
  color: #f1f5f9;
  line-height: 1.8;
  text-shadow: 0px 2px 6px rgba(0, 0, 0, 0.9);
}

@media (max-width: 768px) {
  .hero-text-container {
    bottom: 80px;
    right: 20px;
    left: 20px;
    width: auto;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .hero-text-container h1 {
    font-size: 1.5rem;
    margin-bottom: 10px;
  }
  .hero-text-container .hero-catch {
    font-size: 0.85rem;
    line-height: 1.6;
    text-align: right;
  }
}
</style>