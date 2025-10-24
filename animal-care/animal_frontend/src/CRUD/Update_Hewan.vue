<template>
  <div class="min-vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
      <h4 class="fw-bold text-center mb-4">Update Hewan</h4>

      <form @submit.prevent="updateHewan" enctype="multipart/form-data">
        <!-- Foto Hewan -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Foto Hewan</label>
          <input type="file" class="form-control" accept="image/*" @change="onFileChange" />
          <div v-if="previewFoto" class="mt-2 text-center">
            <img :src="previewFoto" alt="Preview" class="img-thumbnail" width="100" />
          </div>
        </div>

        <!-- Nama Hewan -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Hewan</label>
          <input
            v-model="namaHewan"
            type="text"
            class="form-control"
            placeholder="Masukkan nama hewan"
            required
          />
        </div>

        <!-- Deskripsi -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea
            v-model="deskripsi"
            rows="4"
            class="form-control"
            placeholder="Masukkan deskripsi hewan"
            required
          ></textarea>
        </div>

        <!-- Tombol -->
        <button type="submit" class="btn btn-primary w-100 fw-semibold">
          Update
        </button>
      </form>

      <!-- Alert -->
      <transition name="fade">
        <div
          v-if="showAlert"
          class="alert alert-success text-center mt-4"
          role="alert"
        >
          ✅ Data hewan berhasil diperbarui!
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const namaHewan = ref('')
const deskripsi = ref('')
const fileFoto = ref(null)
const previewFoto = ref(null)
const showAlert = ref(false)

const id = route.params.id
const API_BASE = 'http://127.0.0.1:8000/api'

// 📸 handle upload foto
function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileFoto.value = file
    previewFoto.value = URL.createObjectURL(file)
  }
}

// 📥 ambil data lama
onMounted(async () => {
  try {
    const res = await axios.get(`${API_BASE}/hewan/${id}`)
    namaHewan.value = res.data.nama_hewan
    deskripsi.value = res.data.deskripsi
    previewFoto.value = res.data.foto_url
  } catch (err) {
    console.error('Gagal ambil data hewan:', err)
  }
})

// 🚀 update hewan
async function updateHewan() {
  const formData = new FormData()
  formData.append('nama_hewan', namaHewan.value)
  formData.append('deskripsi', deskripsi.value)
  if (fileFoto.value) {
    formData.append('foto', fileFoto.value)
  }
  formData.append('_method', 'PUT')

  try {
    const res = await axios.post(`${API_BASE}/Update-hewan/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    if (res.status === 200) {
      showAlert.value = true
      setTimeout(() => {
        showAlert.value = false
        router.push('/Hewan')
      }, 2000)
    }
  } catch (err) {
    console.error('Gagal update hewan:', err)
    alert('Terjadi kesalahan saat memperbarui data hewan')
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.8s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
