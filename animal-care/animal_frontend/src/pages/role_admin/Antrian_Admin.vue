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
          <RouterLink to="/Antrian_Admin" class="nav-link active">
            <i class="ri-file-list-3-line me-2"></i> Antrian
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/Layanan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Layanan
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/hewan" class="nav-link">
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
      <h3 class="fw-bold mb-2">Daftar Antrian Pasien</h3>
      <p class="text-muted mb-3">
        Daftar Antrian Pasien yang akan ditangani oleh Admin
      </p>

      <!-- TOTAL ANTRIAN -->
      <div class="mb-3">
        <span class="badge bg-warning text-dark p-2">
          Total antrian : {{ filteredAntrian.length }}
        </span>
      </div>

      <!-- TABEL ANTRIAN -->
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div v-if="loading" class="p-4 text-center text-muted">
            Memuat data...
          </div>

          <div v-else-if="filteredAntrian.length === 0" class="p-4 text-center text-muted">
            Belum ada data antrian.
          </div>

          <div v-else class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead style="background-color: #4cffe2;">
                <tr>
                  <th>No</th>
                  <th>Nama Pemilik</th>
                  <th>Nama Hewan</th>
                  <th>Jenis Hewan</th>
                  <th>Keluhan</th>
                  <th>Layanan</th>
                  <th>No Hp</th>
                  <th>Alamat</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in filteredAntrian" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.nama_pemilik }}</td>
                  <td>{{ item.nama_hewan }}</td>
                  <td>{{ item.jenis_hewan }}</td>
                  <td>{{ item.keluhan }}</td>
                  <td>{{ item.layanan }}</td>
                  <td>{{ item.no_hp }}</td>
                  <td>
                    <span class="badge bg-warning text-dark">
                      {{ item.status }}
                    </span>
                  </td>
                  <td>
                    <button
                      @click="tanganiAntrian(item)"
                      class="btn btn-sm btn-danger"
                    >
                      Tangani
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
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'

const antrian = ref([])
const loading = ref(false)

const API_ANTRIAN = 'http://localhost:8000/api/antrian'
const API_HISTORY = 'http://localhost:8000/api/history'

// ✅ Hanya tampilkan antrian yang statusnya masih "Menunggu"
const filteredAntrian = computed(() => antrian.value.filter(item => item.status === 'Menunggu'))

// 🔹 Ambil data antrian dari API
async function fetchAntrian() {
  loading.value = true
  try {
    const res = await axios.get(API_ANTRIAN)
    antrian.value = res.data
  } catch (err) {
    console.error('❌ Gagal mengambil data antrian:', err)
  } finally {
    loading.value = false
  }
}

// 🔹 Saat admin klik “Tangani”
async function tanganiAntrian(item) {
  if (!confirm('Apakah pasien ini sudah ditangani?')) return

  try {
    // 1️⃣ Update status antrian jadi "Ditangani" (biar user tahu)
    await axios.put(`${API_ANTRIAN}/${item.id}`, { ...item, status: 'Ditangani' })

    // 2️⃣ Tambahkan data ke tabel history
    await axios.post(API_HISTORY, {
      ...item,
      status: 'Ditangani',
      waktu_selesai: new Date().toISOString(),
    })

    // 3️⃣ Refresh data admin
    await fetchAntrian()

    alert('✅ Antrian berhasil ditangani dan dipindahkan ke history!')
  } catch (err) {
    console.error('❌ Gagal memproses antrian:', err)
    alert('Terjadi kesalahan saat memproses antrian.')
  }
}

// Ambil data awal dan auto refresh
onMounted(() => {
  fetchAntrian()
  setInterval(fetchAntrian, 5000)
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
.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}
.btn-danger {
  font-weight: 600;
}
</style>
