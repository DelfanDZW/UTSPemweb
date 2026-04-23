<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

// If user navigates to /admin directly but is not logged in, they are redirected here with intention to login as admin.
// We can check if previous route or path intention was admin.
const isAdminLogin = ref(route.path.includes('admin') || route.query.role === 'admin')

const username = ref('')
const password = ref('')
const errorMessage = ref('')
const isLoading = ref(false)

const handleLogin = async () => {
  errorMessage.value = ''
  isLoading.value = true
  
  try {
    const res = await axios.post('/api/auth.php?action=login', {
      username: username.value,
      password: password.value
    })
    
    if (res.data.success) {
      const user = res.data.user
      
      // Check role mismatch
      if (isAdminLogin.value && user.role !== 'admin') {
        errorMessage.value = 'Anda tidak memiliki akses Admin!'
        return
      }

      authStore.login(user)
      
      if (user.role === 'admin') {
        router.push('/admin')
      } else {
        router.push('/dashboard')
      }
    }
  } catch (error: any) {
    errorMessage.value = error.response?.data?.message || 'Gagal terhubung ke server'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="auth-container">
    <div class="glass-panel auth-card">
      <div class="tabs">
        <button :class="{ active: !isAdminLogin }" @click="isAdminLogin = false">User</button>
        <button :class="{ active: isAdminLogin }" @click="isAdminLogin = true">Admin</button>
      </div>
      
      <h2>Login {{ isAdminLogin ? 'Admin' : 'Pengguna' }}</h2>
      
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Username</label>
          <input type="text" v-model="username" required placeholder="Masukkan username" />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <input type="password" v-model="password" required placeholder="Masukkan password" />
        </div>
        
        <div v-if="errorMessage" class="error-msg">{{ errorMessage }}</div>
        
        <button type="submit" class="submit-btn" :disabled="isLoading">
          {{ isLoading ? 'Memproses...' : 'Masuk' }}
        </button>
      </form>
      
      <div class="links">
        <span>Belum punya akun?</span>
        <router-link :to="`/register?role=${isAdminLogin ? 'admin' : 'user'}`">Daftar di sini</router-link>
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
