<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const character = ref<any>(null)
const isLoading = ref(true)

onMounted(async () => {
  try {
    const res = await axios.get(`/api/characters.php?id=${route.params.id}`)
    character.value = res.data
  } catch (err) {
    console.error(err)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="container detail-container">
    <div class="nav-bar">
      <button class="btn-outline" @click="router.back()">← Kembali</button>
    </div>

    <div v-if="isLoading" class="loading">Memuat detail karakter...</div>
    
    <div v-else-if="!character" class="error">Karakter tidak ditemukan!</div>
    
    <div v-else class="glass-panel character-profile">
      <div class="profile-layout">
        <div class="profile-image">
          <img :src="`/img/${character.image}`" :alt="character.name" />
        </div>
        
        <div class="profile-info">
          <h2>{{ character.name }}</h2>
          
          <div class="tags-container">
            <span v-for="tag in character.tags.split(',')" :key="tag" class="tag-badge">
              {{ tag.trim() }}
            </span>
          </div>
          
          <div class="description-box">
            <h3>Deskripsi</h3>
            <p>{{ character.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.detail-container {
  padding-top: 40px;
}

.nav-bar {
  margin-bottom: 30px;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--glass-border);
  color: var(--text-main);
  padding: 8px 16px;
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateX(-5px);
}

.loading, .error {
  text-align: center;
  font-size: 1.2rem;
  margin-top: 50px;
  color: var(--text-muted);
}

.character-profile {
  padding: 40px;
}

.profile-layout {
  display: flex;
  gap: 40px;
  align-items: flex-start;
}

.profile-image {
  flex-shrink: 0;
  width: 250px;
  height: 250px;
  border-radius: 20px;
  overflow: hidden;
  border: 4px solid var(--glass-border);
  box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}

.profile-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-info {
  flex-grow: 1;
}

.profile-info h2 {
  font-size: 2.5rem;
  color: #fff;
  margin-bottom: 20px;
  border-bottom: 1px solid var(--glass-border);
  padding-bottom: 10px;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 30px;
}

.tag-badge {
  background: rgba(56, 189, 248, 0.15);
  color: var(--primary-color);
  border: 1px solid rgba(56, 189, 248, 0.3);
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 0.9rem;
  font-weight: 600;
}

.description-box h3 {
  font-size: 1.2rem;
  color: #fff;
  margin-bottom: 10px;
}

.description-box p {
  color: var(--text-muted);
  line-height: 1.8;
  font-size: 1.1rem;
}

@media (max-width: 768px) {
  .profile-layout {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .tags-container {
    justify-content: center;
  }
}
</style>
