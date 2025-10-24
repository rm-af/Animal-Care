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
          <RouterLink to="/Dasboard" class="nav-link">
            <i class="ri-home-8-line me-2"></i> Dasboard
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/Antrian_Admin" class="nav-link">
            <i class="ri-file-list-3-line me-2"></i> Antrian
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/Layanan" class="nav-link active">
            <i class="ri-add-circle-line me-2"></i> Layanan
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/Hewan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button class="btn btn-danger mt-auto w-100">
        <i class="ri-logout-box-line me-2"></i> Logout
      </button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-2">Kelola Layanan</h3>
      <p class="text-muted mb-3">Tambah, edit, dan hapus daftar layanan</p>

      <!-- FORM TAMBAH / EDIT -->
      <div class="card shadow-sm mb-4" style="max-width: 500px;">
        <div class="card-body">
          <form @submit.prevent="isEditing ? updateLayanan() : tambahLayanan()" enctype="multipart/form-data">
            <!-- Logo -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Logo layanan</label>
              <input
                type="file"
                class="form-control"
                accept="image/*"
                @change="onFileChange"
              />
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
              <i :class="isEditing ? 'ri-edit-2-line' : 'ri-add-line'" class="me-2"></i>
              {{ isEditing ? 'Update' : 'Tambah' }}
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
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Logo</th>
                  <th>Nama Layanan</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in layananList" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <img :src="item.logo_url" alt="logo" width="50" class="rounded" />
                  </td>
                  <td>{{ item.nama_layanan }}</td>
                  <td>{{ item.deskripsi }}</td>
                  <td>
                    <button @click="editLayanan(item)" class="btn btn-sm btn-warning me-2">
                      <i class="ri-edit-2-line"></i>
                    </button>
                    <button @click="hapusLayanan(item.id)" class="btn btn-sm btn-danger">
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
import { RouterLink } from 'vue-router'

const namaLayanan = ref('')
const deskripsi = ref('')
const fileLogo = ref(null)
const previewLogo = ref(null)
const isEditing = ref(false)
const editId = ref(null)

const layananList = ref([])
const loading = ref(false)

const API_LAYANAN = 'http://localhost:3000/api/layanan'

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
    layananList.value = res.data
  } catch (err) {
    console.error('Gagal ambil data layanan:', err)
  } finally {
    loading.value = false
  }
}

async function tambahLayanan() {
  try {
    const formData = new FormData()
    formData.append('nama_layanan', namaLayanan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileLogo.value) {
      formData.append('logo', fileLogo.value)
    }

    await axios.post(API_LAYANAN, formData)
    alert('✅ Layanan berhasil ditambahkan!')
    resetForm()
    fetchLayanan()
  } catch (err) {
    console.error(err)
    alert('❌ Gagal menambahkan layanan')
  }
}

function editLayanan(item) {
  isEditing.value = true
  editId.value = item.id
  namaLayanan.value = item.nama_layanan
  deskripsi.value = item.deskripsi
  previewLogo.value = item.logo_url
}

async function updateLayanan() {
  try {
    const formData = new FormData()
    formData.append('nama_layanan', namaLayanan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileLogo.value) {
      formData.append('logo', fileLogo.value)
    }

    await axios.post(`${API_LAYANAN}/${editId.value}?_method=PUT`, formData)
    alert('✅ Layanan berhasil diperbarui!')
    resetForm()
    fetchLayanan()
  } catch (err) {
    console.error(err)
    alert('❌ Gagal memperbarui layanan')
  }
}

async function hapusLayanan(id) {
  if (!confirm('Yakin ingin menghapus layanan ini?')) return
  try {
    await axios.delete(`${API_LAYANAN}/${id}`)
    alert('✅ Layanan berhasil dihapus!')
    fetchLayanan()
  } catch (err) {
    console.error(err)
    alert('❌ Gagal menghapus layanan')
  }
}

function resetForm() {
  namaLayanan.value = ''
  deskripsi.value = ''
  fileLogo.value = null
  previewLogo.value = null
  isEditing.value = false
  editId.value = null
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
.card {
  border-radius: 10px;
}
.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}
.btn-warning {
  color: white;
  font-weight: 600;
}
</style>
