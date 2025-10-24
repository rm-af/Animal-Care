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
          <RouterLink to="/Layanan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Layanan
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/Hewan" class="nav-link active">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button class="btn btn-danger mt-auto w-100">
        <i class="ri-logout-circle-line me-1"></i> Logout
      </button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-2">Kelola Hewan</h3>
      <p class="text-muted mb-3">Tambah, edit, dan hapus daftar jenis hewan</p>

      <!-- FORM TAMBAH / EDIT -->
      <div class="card shadow-sm mb-4" style="max-width: 500px;">
        <div class="card-body">
          <form @submit.prevent="isEditing ? updateHewan() : tambahHewan()" enctype="multipart/form-data">
            <!-- Foto Hewan -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Foto hewan</label>
              <input
                type="file"
                class="form-control"
                accept="image/*"
                @change="onFileChange"
              />
              <div v-if="previewFoto" class="mt-2 text-center">
                <img :src="previewFoto" alt="Preview" class="img-thumbnail" width="100" />
              </div>
            </div>

            <!-- Nama Hewan -->
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama hewan</label>
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
              {{ isEditing ? 'Update' : 'Tambah' }}
            </button>
          </form>
        </div>
      </div>

      <!-- DAFTAR HEWAN -->
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Daftar Hewan</h5>

          <div v-if="loading" class="text-center text-muted py-3">
            Memuat data...
          </div>
          <div v-else-if="hewanList.length === 0" class="text-center text-muted py-3">
            Belum ada hewan.
          </div>
          <div v-else class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Foto</th>
                  <th>Nama Hewan</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in hewanList" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>
                    <img :src="item.foto_url" alt="foto hewan" width="50" class="rounded" />
                  </td>
                  <td>{{ item.nama_hewan }}</td>
                  <td>{{ item.deskripsi }}</td>
                  <td>
                    <button @click="editHewan(item)" class="btn btn-sm btn-warning me-2">Edit</button>
                    <button @click="hapusHewan(item.id)" class="btn btn-sm btn-danger">Hapus</button>
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

// State form
const namaHewan = ref('')
const deskripsi = ref('')
const fileFoto = ref(null)
const previewFoto = ref(null)
const isEditing = ref(false)
const editId = ref(null)

// State data
const hewanList = ref([])
const loading = ref(false)

// Ganti dengan URL API kamu
const API_HEWAN = 'http://localhost:3000/api/hewan'

// Upload foto handler
function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    fileFoto.value = file
    previewFoto.value = URL.createObjectURL(file)
  }
}

// Ambil data
async function fetchHewan() {
  try {
    loading.value = true
    const res = await axios.get(API_HEWAN)
    hewanList.value = res.data
  } catch (err) {
    console.error('Gagal ambil data hewan:', err)
  } finally {
    loading.value = false
  }
}

// Tambah data
async function tambahHewan() {
  try {
    const formData = new FormData()
    formData.append('nama_hewan', namaHewan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileFoto.value) {
      formData.append('foto', fileFoto.value)
    }

    await axios.post(API_HEWAN, formData)
    alert('✅ Hewan berhasil ditambahkan!')
    resetForm()
    fetchHewan()
  } catch (err) {
    console.error('Gagal tambah hewan:', err)
    alert('❌ Terjadi kesalahan saat menambahkan hewan.')
  }
}

// Edit data (isi form)
function editHewan(item) {
  isEditing.value = true
  editId.value = item.id
  namaHewan.value = item.nama_hewan
  deskripsi.value = item.deskripsi
  previewFoto.value = item.foto_url
}

// Update data
async function updateHewan() {
  try {
    const formData = new FormData()
    formData.append('nama_hewan', namaHewan.value)
    formData.append('deskripsi', deskripsi.value)
    if (fileFoto.value) {
      formData.append('foto', fileFoto.value)
    }

    await axios.post(`${API_HEWAN}/${editId.value}?_method=PUT`, formData)
    alert('✅ Hewan berhasil diperbarui!')
    resetForm()
    fetchHewan()
  } catch (err) {
    console.error('Gagal update hewan:', err)
    alert('❌ Terjadi kesalahan saat memperbarui data.')
  }
}

// Hapus data
async function hapusHewan(id) {
  if (!confirm('Yakin ingin menghapus hewan ini?')) return
  try {
    await axios.delete(`${API_HEWAN}/${id}`)
    alert('🗑️ Hewan berhasil dihapus!')
    fetchHewan()
  } catch (err) {
    console.error('Gagal hapus hewan:', err)
    alert('❌ Terjadi kesalahan saat menghapus hewan.')
  }
}

// Reset form
function resetForm() {
  namaHewan.value = ''
  deskripsi.value = ''
  fileFoto.value = null
  previewFoto.value = null
  isEditing.value = false
  editId.value = null
}

onMounted(() => {
  fetchHewan()
})
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
