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
          <RouterLink to="/layanan" class="nav-link active">
            <i class="ri-add-circle-line me-2"></i> Layanan
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/hewan" class="nav-link">
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
      <h3 class="fw-bold mb-2">Kelola Layanan</h3>
      <p class="text-muted mb-3">Tambah, edit, dan hapus daftar layanan</p>

      <!-- FORM TAMBAH -->
      <div class="card shadow-sm mb-4 form-card">
        <div class="card-body">
          <form @submit.prevent="tambahLayanan" enctype="multipart/form-data">
            <!-- Logo -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Logo layanan</label>
              <input
                type="file"
                class="form-control"
                accept="image/*"
                @change="onFileChange"
              />
              <div v-if="previewLogo" class="mt-3 text-center">
                <img
                  :src="previewLogo"
                  alt="Preview"
                  class="img-thumbnail preview-logo rounded-circle shadow-sm"
                />
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

            <button type="submit" class="btn btn-primary w-100 fw-semibold" :disabled="submitting">
              <i class="ri-add-line me-2"></i> Tambah
            </button>
          </form>
        </div>
      </div>

      <!-- DAFTAR LAYANAN -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="fw-bold mb-3">
            <i class="ri-list-settings-line me-2"></i> Daftar Layanan
          </h5>

          <div v-if="loading" class="text-center text-muted py-3">
            <i class="ri-loader-4-line ri-spin me-2"></i> Memuat data...
          </div>

          <div v-else-if="layananList.length === 0" class="text-center text-muted py-3">
            <i class="ri-information-line me-2"></i> Belum ada layanan.
          </div>

          <div v-else class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead style="background-color: #4cffe2;">
                <tr>
                  <th>No</th>
                  <th>Logo</th>
                  <th>Nama Layanan</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in layananList" :key="item.layananId">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <img
                      :src="getImageUrl(item.logo)"
                      alt="logo"
                      class="rounded-circle shadow-sm"
                      width="60"
                      height="60"
                      style="object-fit: cover;"
                    />
                  </td>
                  <td>{{ item.nama_layanan }}</td>
                  <td>{{ item.deskripsi }}</td>
                  <td>
                    <!-- Tombol Edit diarahkan ke halaman update -->
                    <button @click="goToUpdate(item.layananId)" class="btn btn-sm btn-warning me-2">
                      <i class="ri-edit-2-line"></i>
                    </button>
                    <button @click="hapusLayanan(item.layananId)" class="btn btn-sm btn-danger">
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { RouterLink, useRouter } from 'vue-router'

const router = useRouter()

const namaLayanan = ref('')
const deskripsi = ref('')
const fileLogo = ref(null)
const previewLogo = ref(null)
const layananList = ref([])
const loading = ref(false)
const submitting = ref(false)

const API_LAYANAN = 'http://127.0.0.1:8000/api/layanan'



function getImageUrl(path) {
  if (!path) return '/no-image.png'
  if (path.startsWith('http')) return path
  return `http://127.0.0.1:8000/storage/${path}`
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileLogo.value = file
    previewLogo.value = URL.createObjectURL(file)
  }
}

async function fetchLayanan() {
  loading.value = true
  try {
    const res = await axios.get(API_LAYANAN)
    layananList.value = Array.isArray(res.data) ? res.data : res.data.data || []
  } catch (err) {
    console.error('❌ Gagal ambil data layanan:', err)
    alert('Gagal memuat data layanan. Cek console untuk detail.')
  } finally {
    loading.value = false
  }
}

async function tambahLayanan() {
  submitting.value = true
  try {
    const formData = new FormData()
    formData.append('nama_layanan', namaLayanan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileLogo.value) formData.append('logo', fileLogo.value)

    await axios.post(API_LAYANAN, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    alert('✅ Layanan berhasil ditambahkan!')
    resetForm()
    fetchLayanan()
  } catch (err) {
    console.error('Tambah error:', err)
    alert('❌ Gagal menambahkan layanan. ' + (err.response?.data?.message || ''))
  } finally {
    submitting.value = false
  }
}


function goToUpdate(id) {
  router.push(`/update_layanan/${id}`)
}

async function hapusLayanan(id) {
  if (!id) return alert('ID layanan tidak ditemukan!')
  if (!confirm('Yakin ingin menghapus layanan ini?')) return
  try {
    await axios.delete(`${API_LAYANAN}/${id}`)
    alert('✅ Layanan berhasil dihapus!')
    fetchLayanan()
  } catch (err) {
    console.error('Hapus error:', err)
    alert('❌ Gagal menghapus layanan. ' + (err.response?.data?.message || ''))
  }
}

function logout() {
  if (confirm('Yakin ingin logout?')) {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}

function resetForm() {
  namaLayanan.value = ''
  deskripsi.value = ''
  fileLogo.value = null
  previewLogo.value = null
  submitting.value = false
}

onMounted(fetchLayanan)
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
.nav-link i {
  font-size: 18px;
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
.btn-danger {
  font-weight: 600;
}
.form-card {
  max-width: 500px;
}
.preview-logo {
  width: 100px;
  height: 100px;
  object-fit: cover;
}
</style>
