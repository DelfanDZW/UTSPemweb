<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()

const allTags = [
  'Melee', 'Defense', 'Ranged', 'Caster', 'Medic', 
  'Guard', 'Vanguard', 'Sniper', 'Supporter'
]

const selectedTags = ref<string[]>([])
const characters = ref<any[]>([])
const hasSearched = ref(false)

const toggleTag = (tag: string) => {
  const index = selectedTags.value.indexOf(tag)
  if (index > -1) {
    selectedTags.value.splice(index, 1)
  } else {
    selectedTags.value.push(tag)
  }
}

const searchCharacters = async () => {
  hasSearched.value = true
  const tagsQuery = selectedTags.value.join(',')
  try {
    const res = await axios.get(`/api/characters.php?tags=${tagsQuery}`)
    characters.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const logout = () => {
  authStore.logout()
  router.push('/')
}
</script>

<template>
  <div class="container dashboard">
    <div class="header">
      <div>
        <h1>Recruitment Calculator</h1>
        <p>Selamat datang, <strong>{{ authStore.user?.username }}</strong></p>
      </div>
      <button class="danger" @click="logout">Logout</button>
    </div>

    <div class="glass-panel search-section">
      <h3>Pilih Tag Karakter</h3>
      <div class="tag-grid">
        <div 
          v-for="tag in allTags" 
          :key="tag" 
          class="tag-pill" 
          :class="{ active: selectedTags.includes(tag) }"
          @click="toggleTag(tag)"
        >
          {{ tag }}
        </div>
      </div>
      
      <div class="search-action">
        <button @click="searchCharacters" class="btn-large">Cari Karakter</button>
      </div>
    </div>

    <div v-if="hasSearched" class="results-section">
      <h3 class="results-title">Hasil Pencarian ({{ characters.length }})</h3>
      
      <div v-if="characters.length === 0" class="no-results glass-panel">
        <p>Tidak ada karakter yang cocok dengan tag yang dipilih.</p>
      </div>
      
      <div v-else class="character-grid">
        <div 
          v-for="char in characters" 
          :key="char.id" 
          class="character-card glass-panel"
          @click="router.push(`/character/${char.id}`)"
        >
          <div class="char-image-container">
            <img :src="`/img/${char.image}`" :alt="char.name" />
          </div>
          <h4>{{ char.name }}</h4>
          <p class="char-tags">{{ char.tags }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  padding-top: 40px;
  padding-bottom: 40px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  background: var(--glass-bg);
  padding: 20px 30px;
  border-radius: 12px;
  border: 1px solid var(--glass-border);
}

.header h1 {
  font-size: 1.8rem;
  color: #fff;
  margin-bottom: 5px;
}

.search-section {
  margin-bottom: 40px;
}

.search-section h3 {
  margin-bottom: 20px;
  color: #fff;
}

.tag-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 30px;
}

.tag-pill {
  padding: 10px 20px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--glass-border);
  border-radius: 50px;
  cursor: pointer;
  transition: all 0.2s ease;
  user-select: none;
}

.tag-pill:hover {
  background: rgba(255, 255, 255, 0.1);
}

.tag-pill.active {
  background: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
  box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
}

.search-action {
  text-align: center;
}

.btn-large {
  padding: 12px 40px;
  font-size: 1.1rem;
}

.results-title {
  margin-bottom: 20px;
  color: #fff;
  border-left: 4px solid var(--primary-color);
  padding-left: 15px;
}

.no-results {
  text-align: center;
  color: var(--text-muted);
}

.character-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}

.character-card {
  padding: 20px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.character-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
  border-color: rgba(56, 189, 248, 0.5);
}

.char-image-container {
  width: 120px;
  height: 120px;
  margin: 0 auto 15px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid var(--glass-border);
}

.char-image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.character-card h4 {
  color: #fff;
  font-size: 1.2rem;
  margin-bottom: 8px;
}

.char-tags {
  font-size: 0.85rem;
  color: var(--text-muted);
}
</style>
