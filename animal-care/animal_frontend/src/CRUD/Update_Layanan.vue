<template>
  <div class="min-vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
      <h4 class="fw-bold text-center mb-4">Update Layanan</h4>

      <form @submit.prevent="updateLayanan" enctype="multipart/form-data">
        <!-- Logo -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Logo layanan</label>
          <input type="file" class="form-control" accept="image/*" @change="onFileChange" />
          <div v-if="previewLogo" class="mt-2 text-center">
            <img :src="previewLogo" alt="Preview" class="img-thumbnail" width="100" />
          </div>
        </div>

        <!-- Nama Layanan -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama layanan</label>
          <input
            v-model="namaLayanan"
            type="text"
            class="form-control"
            placeholder="Masukkan nama layanan"
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
            placeholder="Masukkan deskripsi layanan"
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
          ✅ Layanan berhasil diperbarui!
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

const namaLayanan = ref('')
const deskripsi = ref('')
const fileLogo = ref(null)
const previewLogo = ref(null)
const showAlert = ref(false)

const id = route.params.id
const API_BASE = 'http://127.0.0.1:8000/api'

// 📸 handle upload logo
function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileLogo.value = file
    previewLogo.value = URL.createObjectURL(file)
  }
}

// 📥 ambil data lama untuk prefill
onMounted(async () => {
  try {
    const res = await axios.get(`${API_BASE}/layanan/${id}`)
    namaLayanan.value = res.data.nama_layanan
    deskripsi.value = res.data.deskripsi
    previewLogo.value = res.data.logo_url
  } catch (err) {
    console.error('Gagal ambil data layanan:', err)
  }
})

// 🚀 update layanan
async function updateLayanan() {
  const formData = new FormData()
  formData.append('nama_layanan', namaLayanan.value)
  formData.append('deskripsi', deskripsi.value)
  if (fileLogo.value) {
    formData.append('logo', fileLogo.value)
  }
  formData.append('_method', 'PUT')

  try {
    const res = await axios.post(`${API_BASE}/Update-layanan/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    if (res.status === 200) {
      showAlert.value = true
      setTimeout(() => {
        showAlert.value = false
        router.push('/Layanan') //
      }, 2000)
    }
  } catch (err) {
    console.error('Gagal update layanan:', err)
    alert('Terjadi kesalahan saat memperbarui layanan')
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
