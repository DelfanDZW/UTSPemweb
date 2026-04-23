<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import axios from 'axios'

const router = useRouter()
const authStore = useAuthStore()

const characters = ref<any[]>([])
const isEditing = ref(false)
const showForm = ref(false)

const allTags = [
  'Melee', 'Defense', 'Ranged', 'Caster', 'Medic', 
  'Guard', 'Vanguard', 'Sniper', 'Supporter'
]

// Form State
const formId = ref<number | null>(null)
const formName = ref('')
const formTags = ref<string[]>([])
const formDescription = ref('')
const formImage = ref<File | null>(null)

const loadCharacters = async () => {
  try {
    const res = await axios.get('/api/characters.php')
    characters.value = res.data
  } catch (err) {
    console.error(err)
  }
}

onMounted(() => {
  loadCharacters()
})

const handleImageUpload = (event: any) => {
  formImage.value = event.target.files[0]
}

const resetForm = () => {
  formId.value = null
  formName.value = ''
  formTags.value = []
  formDescription.value = ''
  formImage.value = null
  isEditing.value = false
  showForm.value = false
  // Reset file input manually if needed
}

const openAddForm = () => {
  resetForm()
  showForm.value = true
}

const editCharacter = (char: any) => {
  formId.value = char.id
  formName.value = char.name
  formTags.value = char.tags.split(',').map((t: string) => t.trim())
  formDescription.value = char.description
  formImage.value = null
  isEditing.value = true
  showForm.value = true
}

const submitForm = async () => {
  const formData = new FormData()
  if (isEditing.value && formId.value) formData.append('id', formId.value.toString())
  formData.append('name', formName.value)
  formData.append('tags', formTags.value.join(','))
  formData.append('description', formDescription.value)
  if (formImage.value) {
    formData.append('image', formImage.value)
  }

  try {
    await axios.post('/api/characters.php', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    resetForm()
    loadCharacters()
  } catch (err) {
    console.error(err)
    alert('Gagal menyimpan karakter')
  }
}

const deleteCharacter = async (id: number) => {
  if (confirm('Apakah Anda yakin ingin menghapus karakter ini?')) {
    try {
      await axios.delete(`/api/characters.php?id=${id}`)
      loadCharacters()
    } catch (err) {
      console.error(err)
      alert('Gagal menghapus karakter')
    }
  }
}

const logout = () => {
  authStore.logout()
  router.push('/')
}
</script>

<template>
  <div class="container admin-dashboard">
    <div class="header">
      <div>
        <h1>Admin Dashboard</h1>
        <p>Pengelolaan Karakter Arknights</p>
      </div>
      <div class="header-actions">
        <button v-if="!showForm" @click="openAddForm" class="btn-success">Tambah Karakter</button>
        <button class="danger" @click="logout">Logout</button>
      </div>
    </div>

    <!-- Form Tambah/Edit -->
    <transition name="fade">
      <div v-if="showForm" class="glass-panel form-panel">
        <h2>{{ isEditing ? 'Edit Karakter' : 'Tambah Karakter Baru' }}</h2>
        <form @submit.prevent="submitForm">
          <div class="form-grid">
            <div class="form-group">
              <label>Nama Karakter</label>
              <input type="text" v-model="formName" required placeholder="Ex: SilverAsh" />
            </div>
            
            <div class="form-group">
              <label>Unggah Gambar</label>
              <input type="file" accept="image/*" @change="handleImageUpload" :required="!isEditing" />
              <small v-if="isEditing" class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>
            
            <div class="form-group full-width">
              <label>Pilih Tag</label>
              <div class="checkbox-grid">
                <label v-for="tag in allTags" :key="tag" class="custom-checkbox">
                  <input type="checkbox" :value="tag" v-model="formTags" />
                  <span class="checkmark"></span>
                  {{ tag }}
                </label>
              </div>
            </div>
            
            <div class="form-group full-width">
              <label>Deskripsi Karakter</label>
              <textarea v-model="formDescription" rows="4" required></textarea>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn-outline" @click="resetForm">Batal</button>
            <button type="submit">{{ isEditing ? 'Simpan Perubahan' : 'Tambah Karakter' }}</button>
          </div>
        </form>
      </div>
    </transition>

    <!-- Data Grid Karakter -->
    <div v-if="!showForm" class="glass-panel">
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Nama</th>
              <th>Tags</th>
              <th>Deskripsi Singkat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="characters.length === 0">
              <td colspan="5" class="text-center text-muted py-4">Belum ada karakter yang terdaftar.</td>
            </tr>
            <tr v-for="char in characters" :key="char.id">
              <td width="80">
                <div class="img-preview">
                  <img :src="`/img/${char.image}`" :alt="char.name" />
                </div>
              </td>
              <td><strong>{{ char.name }}</strong></td>
              <td>
                <div class="tag-list">
                  <span v-for="tag in char.tags.split(',')" :key="tag" class="small-tag">{{ tag.trim() }}</span>
                </div>
              </td>
              <td><div class="desc-truncate">{{ char.description }}</div></td>
              <td width="150">
                <div class="action-cell">
                  <button class="btn-sm btn-edit" @click="editCharacter(char)">Edit</button>
                  <button class="btn-sm danger" @click="deleteCharacter(char.id)">Hapus</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-dashboard {
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
  color: #fff;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.btn-success {
  background-color: #10b981;
}

.btn-success:hover {
  background-color: #059669;
}

.form-panel {
  margin-bottom: 30px;
}

.form-panel h2 {
  margin-bottom: 25px;
  color: #fff;
  border-bottom: 1px solid var(--glass-border);
  padding-bottom: 10px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.full-width {
  grid-column: 1 / -1;
}

.checkbox-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 10px;
  background: rgba(0,0,0,0.1);
  padding: 15px;
  border-radius: 8px;
}

.custom-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: var(--text-main);
}

.custom-checkbox input {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  background: rgba(255,255,255,0.1);
  border: 1px solid var(--glass-border);
  border-radius: 4px;
  position: relative;
}

.custom-checkbox input:checked ~ .checkmark {
  background: var(--primary-color);
  border-color: var(--primary-color);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid var(--glass-border);
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--text-muted);
  color: var(--text-muted);
}

.btn-outline:hover {
  background: rgba(255,255,255,0.1);
  color: #fff;
  transform: none;
}

/* Data Table Styles */
.table-responsive {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th, .data-table td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid var(--glass-border);
}

.data-table th {
  font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase;
  font-size: 0.85rem;
  letter-spacing: 1px;
}

.data-table tbody tr {
  transition: background 0.2s;
}

.data-table tbody tr:hover {
  background: rgba(255,255,255,0.02);
}

.img-preview {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  overflow: hidden;
  background: #000;
}

.img-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.tag-list {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.small-tag {
  background: rgba(255,255,255,0.1);
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
}

.desc-truncate {
  max-width: 250px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-muted);
  font-size: 0.9rem;
}

.action-cell {
  display: flex;
  gap: 8px;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 0.85rem;
}

.btn-edit {
  background: #f59e0b;
}
.btn-edit:hover { background: #d97706; }

.text-muted { color: var(--text-muted); }
.py-4 { padding-top: 2rem; padding-bottom: 2rem; }
.text-center { text-align: center; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
