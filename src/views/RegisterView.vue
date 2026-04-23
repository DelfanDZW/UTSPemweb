<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()

const isAdminRegister = ref(route.query.role === 'admin')

const username = ref('')
const password = ref('')
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const handleRegister = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  isLoading.value = true
  
  try {
    const res = await axios.post('/api/auth.php?action=register', {
      username: username.value,
      password: password.value,
      role: isAdminRegister.value ? 'admin' : 'user'
    })
    
    if (res.data.success) {
      successMessage.value = 'Pendaftaran berhasil! Mengalihkan ke login...'
      setTimeout(() => {
        router.push({ path: '/login', query: { role: isAdminRegister.value ? 'admin' : 'user' } })
      }, 1500)
    }
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Pendaftaran gagal'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="auth-container">
    <div class="glass-panel auth-card">
      <div class="tabs">
        <button :class="{ active: !isAdminRegister }" @click="isAdminRegister = false">User</button>
        <button :class="{ active: isAdminRegister }" @click="isAdminRegister = true">Admin</button>
      </div>
      
      <h2>Daftar {{ isAdminRegister ? 'Admin' : 'Pengguna' }} Baru</h2>
      
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label>Username</label>
          <input type="text" v-model="username" required placeholder="Pilih username" />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <input type="password" v-model="password" required minlength="4" placeholder="Buat password" />
        </div>
        
        <div v-if="errorMessage" class="error-msg">{{ errorMessage }}</div>
        <div v-if="successMessage" class="success-msg">{{ successMessage }}</div>
        
        <button type="submit" class="submit-btn" :disabled="isLoading">
          {{ isLoading ? 'Mendaftar...' : 'Daftar' }}
        </button>
      </form>
      
      <div class="links">
        <span>Sudah punya akun?</span>
        <router-link :to="`/login?role=${isAdminRegister ? 'admin' : 'user'}`">Masuk di sini</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.auth-card {
  width: 100%;
  max-width: 400px;
}

.tabs {
  display: flex;
  margin-bottom: 20px;
  background: rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  padding: 5px;
}

.tabs button {
  flex: 1;
  background: transparent;
  color: var(--text-muted);
  box-shadow: none;
  padding: 8px;
}

.tabs button.active {
  background: var(--surface-color);
  color: var(--primary-color);
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.tabs button:hover:not(.active) {
  transform: none;
  color: #fff;
}

h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #fff;
}

.submit-btn {
  width: 100%;
  margin-top: 10px;
}

.error-msg {
  color: var(--danger);
  font-size: 0.9rem;
  margin-bottom: 15px;
  text-align: center;
}

.success-msg {
  color: #22c55e;
  font-size: 0.9rem;
  margin-bottom: 15px;
  text-align: center;
}

.links {
  margin-top: 20px;
  text-align: center;
  font-size: 0.9rem;
}

.links a {
  margin-left: 5px;
  font-weight: 600;
}
</style>
