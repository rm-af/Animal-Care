<template>
  <div class="container py-5">
    <h3 class="fw-bold mb-3 text-center">Edit Data Hewan</h3>

    <form
      @submit.prevent="updateHewan"
      enctype="multipart/form-data"
      class="shadow p-4 bg-white rounded"
    >

      <div class="mb-3">
        <label class="form-label fw-semibold">Gambar Hewan</label>
        <input type="file" class="form-control" @change="onFileChange" accept="image/*" />
        <div v-if="previewGambar" class="mt-3 text-center">
          <img
            :src="previewGambar"
            class="img-thumbnail rounded-circle shadow-sm"
            width="120"
            height="120"
            alt="Preview Gambar"
          />
        </div>
      </div>


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


      <button type="submit" class="btn btn-primary w-100 fw-semibold" :disabled="submitting">
        <i class="ri-edit-2-line me-2"></i>
        {{ submitting ? 'Menyimpan...' : 'Update Hewan' }}
      </button>
    </form>
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


const namaHewan = ref('')
const deskripsi = ref('')
const fileGambar = ref(null)
const previewGambar = ref(null)
const submitting = ref(false)


async function fetchDetail() {
  try {
    const res = await axios.get(`${API_BASE}/hewan/${id}`)
    const data = res.data.data || res.data

    namaHewan.value = data.nama_hewan
    deskripsi.value = data.deskripsi
    previewGambar.value = data.gambar
      ? `http://localhost:8000/storage/${data.gambar}`
      : '/no-image.png'
  } catch (err) {
    console.error('❌ Gagal ambil data hewan:', err)
    alert('Gagal memuat data hewan.')
  }
}


function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileGambar.value = file
    previewGambar.value = URL.createObjectURL(file)
  }
}


async function updateHewan() {
  submitting.value = true
  try {
    const formData = new FormData()
    formData.append('nama_hewan', namaHewan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileGambar.value) formData.append('gambar', fileGambar.value)

    const res = await axios.post(`${API_BASE}/hewan/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    console.log('✅ Respons backend:', res.data)
    alert('✅ Data hewan berhasil diperbarui!')
    router.push('/hewan')
  } catch (err) {
    console.error('❌ Gagal update hewan:', err.response?.data || err.message)
    alert(`❌ Gagal memperbarui data hewan: ${err.response?.data?.message || err.message}`)
  } finally {
    submitting.value = false
  }
}


onMounted(fetchDetail)
</script>

<style scoped>
.container {
  max-width: 600px;
}
</style>
