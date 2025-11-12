<template>
  <div class="container py-5">
    <h3 class="fw-bold mb-3 text-center">Edit Layanan</h3>

    <form
      @submit.prevent="updateLayanan"
      enctype="multipart/form-data"
      class="shadow p-4 bg-white rounded"
    >
      <div class="mb-3">
        <label class="form-label fw-semibold">Logo Layanan</label>
        <input type="file" class="form-control" @change="onFileChange" accept="image/*" />
        <div v-if="previewLogo" class="mt-3 text-center">
          <img
            :src="previewLogo"
            class="img-thumbnail rounded-circle shadow-sm"
            width="120"
            height="120"
          />
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Layanan</label>
        <input
          v-model="namaLayanan"
          type="text"
          class="form-control"
          required
          placeholder="Masukkan nama layanan"
        />
      </div>

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

      <button type="submit" class="btn btn-primary w-100 fw-semibold" :disabled="submitting">
        <i class="ri-edit-2-line me-2"></i>
        {{ submitting ? 'Menyimpan...' : 'Update Layanan' }}
      </button>
    </form>

    <!-- POPUP SUKSES (sama seperti update antrian) -->
    <transition name="fade-scale">
      <div v-if="showPopup" class="popup-overlay">
        <div class="popup-card text-center p-4 rounded-4 shadow-lg">
          <img src="/popup.png" alt="success" class="popup-img mb-3" />
          <h4 class="fw-bold mb-2">Layanan Berhasil Diperbarui!</h4>
          <p class="text-muted">Perubahan data layanan telah disimpan dengan sukses.</p>
          <button class="btn btn-popup mt-3 fw-bold" @click="goToLayanan">Lihat Layanan</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const id = route.params.id
const API_BASE = 'http://localhost:8000/api'

const namaLayanan = ref('')
const deskripsi = ref('')
const fileLogo = ref(null)
const previewLogo = ref(null)
const submitting = ref(false)
const showPopup = ref(false)

async function fetchDetail() {
  try {
    const res = await axios.get(`${API_BASE}/layanan/${id}`)
    const data = res.data.data || res.data
    namaLayanan.value = data.nama_layanan
    deskripsi.value = data.deskripsi
    previewLogo.value = data.logo
      ? `http://localhost:8000/storage/${data.logo}`
      : '/no-image.png'
  } catch (err) {
    console.error('❌ Gagal ambil data layanan:', err)
    alert('Gagal memuat data layanan.')
  }
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileLogo.value = file
    previewLogo.value = URL.createObjectURL(file)
  }
}

async function updateLayanan() {
  submitting.value = true
  try {
    const formData = new FormData()
    formData.append('nama_layanan', namaLayanan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileLogo.value) formData.append('logo', fileLogo.value)

    await axios.post(`${API_BASE}/layanan/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    // tampilkan popup
    showPopup.value = true
  } catch (err) {
    console.error('❌ Gagal update layanan:', err.response?.data || err.message)
    alert(`❌ Gagal memperbarui layanan: ${err.response?.data?.message || err.message}`)
  } finally {
    submitting.value = false
  }
}

function goToLayanan() {
  showPopup.value = false
  router.push('/layanan')
}

onMounted(fetchDetail)
</script>

<style scoped>
.container {
  max-width: 600px;
}

/* === POPUP === */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.popup-card {
  background: white;
  color: #000;
  width: 360px;
  animation: scaleIn 0.4s ease forwards;
}

.popup-img {
  width: 150px;
  height: auto;
  display: block;
  margin: 0 auto;
}

.btn-popup {
  background: linear-gradient(to right, #667eea, #764ba2);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
}

.btn-popup:hover {
  opacity: 0.9;
}

/* === Animasi === */
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.3s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
