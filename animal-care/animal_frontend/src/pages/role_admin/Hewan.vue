<template>
  <div class="d-flex min-vh-100 bg-light">
    <!-- SIDEBAR -->
    <aside class="sidebar d-flex flex-column p-3 text-white">
      <div class="text-center mb-4">
        <img src="/animal.png" alt="logo" class="logo mb-2" />
        <h5 class="fw-bold">Animal <span class="text-warning">Care</span></h5>
      </div>

      <ul class="nav flex-column gap-2">
        <li>
          <RouterLink to="/dasboard" class="nav-link">
            <i class="ri-home-8-line me-2"></i> Dashboard
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/antrian_admin" class="nav-link">
            <i class="ri-file-list-3-line me-2"></i> Antrian
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/layanan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Layanan
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/hewan" class="nav-link active">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button @click="logout" class="btn btn-danger mt-auto w-100 fw-semibold">
        <i class="ri-logout-box-line me-2"></i> Logout
      </button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-2">Kelola Hewan</h3>
      <p class="text-muted mb-3">Tambah, edit, dan hapus data hewan peliharaan</p>

      <!-- FORM TAMBAH -->
      <div class="card shadow-sm mb-4 form-card">
        <div class="card-body">
          <form @submit.prevent="tambahHewan" enctype="multipart/form-data">
            <!-- Gambar -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Gambar Hewan</label>
              <input type="file" class="form-control" accept="image/*" @change="onFileChange" />
              <div v-if="previewGambar" class="mt-3 text-center">
                <img
                  :src="previewGambar"
                  alt="Preview"
                  class="img-thumbnail preview-logo rounded-circle shadow-sm"
                />
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

            <button type="submit" class="btn btn-primary w-100 fw-semibold" :disabled="submitting">
              <i class="ri-add-line me-2"></i> Tambah
            </button>
          </form>
        </div>
      </div>

      <!-- DAFTAR HEWAN -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="fw-bold mb-3">
            <i class="ri-list-settings-line me-2"></i> Daftar Hewan
          </h5>

          <div v-if="loading" class="text-center text-muted py-3">
            <i class="ri-loader-4-line ri-spin me-2"></i> Memuat data...
          </div>

          <div v-else-if="hewanList.length === 0" class="text-center text-muted py-3">
            <i class="ri-information-line me-2"></i> Belum ada data hewan.
          </div>

          <div v-else class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead style="background-color: #4cffe2;">
                <tr>
                  <th>No</th>
                  <th>Gambar</th>
                  <th>Nama Hewan</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in hewanList" :key="item.hewanId">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <img
                      :src="getImageUrl(item.gambar)"
                      alt="hewan"
                      class="rounded-circle shadow-sm"
                      width="60"
                      height="60"
                      style="object-fit: cover;"
                    />
                  </td>
                  <td>{{ item.nama_hewan }}</td>
                  <td>{{ item.deskripsi }}</td>
                  <td>
                    <button @click="goToUpdate(item.hewanId)" class="btn btn-sm btn-warning me-2">
                      <i class="ri-edit-2-line"></i>
                    </button>
                    <button @click="konfirmasiHapus(item)" class="btn btn-sm btn-danger">
                      <i class="ri-delete-bin-6-line"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <!-- POPUP -->
    <transition name="fade-scale">
      <div v-if="popup.show" class="popup-overlay">
        <div class="popup-card text-center p-4 rounded-4 shadow-lg">
          <img :src="popup.image" alt="popup" class="popup-img mb-3" />
          <h4 class="fw-bold mb-2">{{ popup.title }}</h4>
          <p class="text-muted">{{ popup.message }}</p>

          <!-- Tombol konfirmasi -->
          <div
            v-if="popup.type === 'confirm'"
            class="d-flex justify-content-center gap-3 mt-3"
          >
            <button class="btn btn-danger fw-bold px-4" @click="hapusHewan(popup.item)">
              Ya
            </button>
            <button class="btn btn-secondary fw-bold px-4" @click="closePopup">
              Tidak
            </button>
          </div>

          <!-- Tombol OK -->
          <button v-else class="btn btn-popup mt-3 fw-bold" @click="closePopup">
            OK
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { RouterLink, useRouter } from 'vue-router'

const router = useRouter()
const namaHewan = ref('')
const deskripsi = ref('')
const fileGambar = ref(null)
const previewGambar = ref(null)
const hewanList = ref([])
const loading = ref(false)
const submitting = ref(false)

const API_HEWAN = 'http://127.0.0.1:8000/api/hewan'

const popup = ref({
  show: false,
  type: '',
  title: '',
  message: '',
  image: '',
  item: null,
})

function getImageUrl(path) {
  if (!path) return '/no-image.png'
  if (path.startsWith('http')) return path
  return `http://127.0.0.1:8000/storage/${path}`
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileGambar.value = file
    previewGambar.value = URL.createObjectURL(file)
  }
}

async function fetchHewan() {
  loading.value = true
  try {
    const res = await axios.get(API_HEWAN)
    hewanList.value = Array.isArray(res.data) ? res.data : res.data.data || []
  } catch (err) {
    console.error('❌ Gagal ambil data hewan:', err)
  } finally {
    loading.value = false
  }
}

async function tambahHewan() {
  submitting.value = true
  try {
    const formData = new FormData()
    formData.append('nama_hewan', namaHewan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileGambar.value) formData.append('gambar', fileGambar.value)

    await axios.post(API_HEWAN, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    popup.value = {
      show: true,
      type: 'info',
      image: '/popup.png',
      title: 'Hewan Berhasil Ditambahkan!',
      message: `Hewan "${namaHewan.value}" telah disimpan.`,
    }
    resetForm()
    fetchHewan()
  } catch (err) {
    console.error('❌ Gagal tambah hewan:', err)
  } finally {
    submitting.value = false
  }
}

function konfirmasiHapus(item) {
  popup.value = {
    show: true,
    type: 'confirm',
    image: '/cancel.png',
    title: 'Hapus Hewan?',
    message: `Yakin ingin menghapus data hewan "${item.nama_hewan}"?`,
    item,
  }
}

async function hapusHewan(item) {
  try {
    await axios.delete(`${API_HEWAN}/${item.hewanId}`)
    popup.value = {
      show: true,
      type: 'info',
      image: '/popup.png',
      title: 'Hewan Berhasil Dihapus!',
      message: `Data hewan "${item.nama_hewan}" telah dihapus.`,
    }
    fetchHewan()
  } catch (err) {
    console.error('❌ Gagal hapus hewan:', err)
  }
}

function closePopup() {
  popup.value.show = false
}

function goToUpdate(id) {
  router.push(`/update_hewan/${id}`)
}

function logout() {
  popup.value = {
    show: true,
    type: 'confirm',
    image: '/cancel.png',
    title: 'Konfirmasi Logout',
    message: 'Yakin ingin keluar dari akun admin?',
    item: { logout: true },
  }
}

function resetForm() {
  namaHewan.value = ''
  deskripsi.value = ''
  fileGambar.value = null
  previewGambar.value = null
}

onMounted(fetchHewan)
</script>

<style scoped>
.sidebar {
  background-color: #4c52c3;
  width: 230px;
}
.logo {
  width: 70px;
  border-radius: 50%;
}
.nav-link {
  color: white;
  padding: 10px 12px;
  border-radius: 6px;
  display: flex;
  align-items: center;
}
.nav-link.active {
  background-color: rgba(255, 255, 255, 0.2);
}
.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15);
}
.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}
.form-card {
  max-width: 500px;
}
.preview-logo {
  width: 100px;
  height: 100px;
  object-fit: cover;
}

/* === POPUP STYLE SAMA SEPERTI LAYANAN === */
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
