<template>
  <div class="vk-loader-container" v-if="loading">
    <div class="vk-loader-overlay">
      <div class="vk-loader-content">
        <!-- VK-style logo animasiyası -->
        <div class="vk-logo-wrapper">
          <svg class="vk-logo" viewBox="0 0 36 36" width="64" height="64">
            <rect x="0" y="0" width="36" height="36" fill="none" />
            <path
              d="M17.5 27.5c-9.5 0-14.5-6.5-15-17.5h5c0.5 7.5 3.5 10.5 6.5 11.5V10h4.5v6.5c3-0.5 6-3.5 7-6.5h4.5c-1.5 4.5-4.5 7.5-7 9 2.5 1.5 6 5 7.5 8.5h-5c-1.5-2.5-3.5-4.5-7-5v5H17.5z"
              fill="currentColor"
              class="vk-logo-path"
            />
          </svg>

          <!-- Dalğa effekti -->
          <div class="vk-wave">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>

        <!-- Yükləmə statusu -->
        <div class="vk-loader-text">
          <span class="vk-status">{{ currentStatus }}</span>
          <span class="vk-dots">
            <span>.</span>
            <span>.</span>
            <span>.</span>
          </span>
        </div>

        <!-- Progress bar -->
        <div class="vk-progress-wrapper">
          <div class="vk-progress-track">
            <div
              class="vk-progress-fill"
              :style="{ width: progress + '%' }"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'

// Type definitions
interface Props {
  loading?: boolean
  statuses?: string[]
  duration?: number
}

interface Emits {
  (e: 'loaded'): void
}

// Props with defaults
const props = withDefaults(defineProps<Props>(), {
  loading: true,
  statuses: () => [
    'Yüklənir',
    'Məlumatlar hazırlanır',
    'Səhifə açılır',
    'Demək olar hazırdır'
  ],
  duration: 4000
})

// Emits
const emit = defineEmits<Emits>()

// State - default status təyin et
const defaultStatus = props.statuses?.[0] || 'Yüklənir'
const statusText = ref<string>(defaultStatus)
const progress = ref<number>(0)
const currentIndex = ref<number>(0)

// Timers
let statusInterval: ReturnType<typeof setInterval> | null = null
let progressInterval: ReturnType<typeof setInterval> | null = null
let finishTimeout: ReturnType<typeof setTimeout> | null = null

// Computed
const currentStatus = computed(() => statusText.value)

// Methods
const updateStatus = (): void => {
  // Statuses arrayinin olduğunu və boş olmadığını yoxla
  if (!props.statuses || props.statuses.length === 0) return

  // Növbəti index-i hesabla
  currentIndex.value = (currentIndex.value + 1) % props.statuses.length

  // Status-u yenilə - əgər undefined olarsa, default istifadə et
  const newStatus = props.statuses[currentIndex.value]
  statusText.value = newStatus ?? defaultStatus
}

const updateProgress = (): void => {
  if (progress.value < 100) {
    const increment = Math.random() * 3 + 1
    progress.value = Math.min(progress.value + increment, 100)
  }
}

const finishLoading = (): void => {
  progress.value = 100
  statusText.value = 'Hazırdır!'

  // Emit et
  setTimeout(() => {
    emit('loaded')
  }, 500)
}

// Lifecycle
onMounted(() => {
  // Status hər 1.2 saniyədən bir dəyişsin
  if (props.statuses && props.statuses.length > 1) {
    statusInterval = setInterval(updateStatus, 1200)
  }

  // Progress hər 50ms-də artsın
  progressInterval = setInterval(updateProgress, 50)

  // Müəyyən vaxtdan sonra yükləmə bitsin
  finishTimeout = setTimeout(finishLoading, props.duration)
})

onUnmounted(() => {
  if (statusInterval) clearInterval(statusInterval)
  if (progressInterval) clearInterval(progressInterval)
  if (finishTimeout) clearTimeout(finishTimeout)
})
</script>

<style scoped>
/* Container */
.vk-loader-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: #f5f6f8;
}

.vk-loader-overlay {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #f5f6f8 0%, #e8e9ec 100%);
}

.vk-loader-content {
  text-align: center;
  padding: 40px;
}

/* VK Logo Animation */
.vk-logo-wrapper {
  position: relative;
  display: inline-block;
  margin-bottom: 30px;
}

.vk-logo {
  color: #4a76a8;
  display: block;
  animation: logoPulse 2s ease-in-out infinite;
}

.vk-logo-path {
  transform-origin: center;
  animation: logoDraw 3s ease-in-out infinite;
}

@keyframes logoDraw {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.7;
    transform: scale(0.95);
  }
}

@keyframes logoPulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

/* Wave Effect */
.vk-wave {
  display: flex;
  justify-content: center;
  gap: 6px;
  margin-top: 15px;
}

.vk-wave span {
  display: inline-block;
  width: 6px;
  height: 6px;
  background: #4a76a8;
  border-radius: 50%;
  animation: wave 1.4s ease-in-out infinite;
}

.vk-wave span:nth-child(1) {
  animation-delay: 0s;
}
.vk-wave span:nth-child(2) {
  animation-delay: 0.2s;
}
.vk-wave span:nth-child(3) {
  animation-delay: 0.4s;
}
.vk-wave span:nth-child(4) {
  animation-delay: 0.6s;
}

@keyframes wave {
  0%, 60%, 100% {
    transform: translateY(0);
    opacity: 0.4;
  }
  30% {
    transform: translateY(-15px);
    opacity: 1;
  }
}

/* Loading Text */
.vk-loader-text {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #2c3e50;
  margin-bottom: 25px;
  min-height: 30px;
}

.vk-status {
  transition: opacity 0.3s ease;
}

.vk-dots {
  display: inline-flex;
  gap: 2px;
}

.vk-dots span {
  animation: dots 1.4s ease-in-out infinite;
  opacity: 0;
}

.vk-dots span:nth-child(1) {
  animation-delay: 0s;
}
.vk-dots span:nth-child(2) {
  animation-delay: 0.2s;
}
.vk-dots span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes dots {
  0%, 80%, 100% {
    opacity: 0;
  }
  40% {
    opacity: 1;
  }
}

/* Progress Bar */
.vk-progress-wrapper {
  max-width: 300px;
  margin: 0 auto;
}

.vk-progress-track {
  width: 100%;
  height: 4px;
  background: #d0d3d9;
  border-radius: 2px;
  overflow: hidden;
  position: relative;
}

.vk-progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #4a76a8, #6c9bd2);
  border-radius: 2px;
  transition: width 0.3s ease;
  position: relative;
}

.vk-progress-fill::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.3),
    transparent
  );
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Responsive */
@media (max-width: 480px) {
  .vk-loader-text {
    font-size: 15px;
  }

  .vk-logo {
    width: 48px;
    height: 48px;
  }

  .vk-progress-wrapper {
    max-width: 200px;
  }
}
</style>
