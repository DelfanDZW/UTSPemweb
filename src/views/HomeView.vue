<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <div class="home-container">
    <div class="glass-panel text-center">
      <h1 class="title">Arknight Recruitment Calculator</h1>
      <p class="subtitle">Temukan karakter Arknights ideal Anda dengan kombinasi tag.</p>
      
      <div class="action-buttons">
        <button class="btn-large" @click="router.push('/login')">Masuk sebagai Pengguna</button>
        <button class="btn-outline" @click="router.push('/admin')">Portal Admin</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: radial-gradient(circle at top right, #1e293b, #0f172a);
}

.text-center {
  text-align: center;
  max-width: 600px;
  width: 90%;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  background: -webkit-linear-gradient(#38bdf8, #818cf8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 800;
}

.subtitle {
  color: var(--text-muted);
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

.action-buttons {
  display: flex;
  gap: 15px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-large {
  padding: 15px 30px;
  font-size: 1.1rem;
}

.btn-outline {
  background: transparent;
  border: 2px solid var(--primary-color);
  color: var(--primary-color);
  padding: 13px 28px;
  font-size: 1.1rem;
}

.btn-outline:hover {
  background: rgba(56, 189, 248, 0.1);
  color: #fff;
  transform: translateY(-2px);
}
</style>
