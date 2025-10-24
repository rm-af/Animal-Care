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
          <RouterLink to="/Dashboard" class="nav-link active">
            <i class="ri-home-8-line me-2"></i> Dashboard
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
          <RouterLink to="/hewan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button class="btn btn-danger mt-auto w-100">Logout</button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-4">Dashboard Admin</h3>

      <!-- CARD STATISTIK -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="dashboard-card bg-info text-white">
            <div class="icon-circle">
              <i class="ri-user-line"></i>
            </div>
            <div class="card-text">
              <h6 class="fw-semibold mb-1">Total Pasien</h6>
              <h2 class="fw-bold mb-0">{{ totalPasien }}</h2>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="dashboard-card bg-success text-white">
            <div class="icon-circle">
              <i class="ri-check-line"></i>
            </div>
            <div class="card-text">
              <h6 class="fw-semibold mb-1">Ditangani</h6>
              <h2 class="fw-bold mb-0">{{ sudahDitangani }}</h2>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="dashboard-card bg-danger text-white">
            <div class="icon-circle">
              <i class="ri-time-line"></i>
            </div>
            <div class="card-text">
              <h6 class="fw-semibold mb-1">Belum Ditangani</h6>
              <h2 class="fw-bold mb-0">{{ belumDitangani }}</h2>
            </div>
          </div>
        </div>
      </div>

      <!-- HISTORY COUNT -->
      <div class="mb-3">
        <span class="badge bg-primary text-white p-2">
          History: {{ history.length }} data
        </span>
      </div>

      <!-- TABEL HISTORY -->
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div v-if="loading" class="p-4 text-center text-muted">
            Memuat data...
          </div>

          <div v-else-if="history.length === 0" class="p-4 text-center text-muted">
            Belum ada data history.
          </div>

          <div v-else class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
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
                <tr v-for="(item, index) in history" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.nama_pemilik }}</td>
                  <td>{{ item.nama_hewan }}</td>
                  <td>{{ item.jenis_hewan }}</td>
                  <td>{{ item.keluhan }}</td>
                  <td>{{ item.layanan }}</td>
                  <td>{{ item.no_hp }}</td>
                  <td>
                    <span class="badge bg-success">Ditangani</span>
                  </td>
                  <td>
                    <button @click="hapusHistory(item.id)" class="btn btn-sm btn-danger">
                      Hapus
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
import { RouterLink } from 'vue-router'
import axios from 'axios'

// STATE
const history = ref([])
const loading = ref(false)
const totalPasien = ref(0)
const sudahDitangani = ref(0)
const belumDitangani = ref(0)

// API
const API_ANTRIAN = 'http://localhost:8000/api/antrian'
const API_HISTORY = 'http://localhost:8000/api/history'

// FETCH DATA
async function fetchData() {
  try {
    loading.value = true
    const [antrianRes, historyRes] = await Promise.all([
      axios.get(API_ANTRIAN),
      axios.get(API_HISTORY)
    ])

    const antrianData = antrianRes.data
    const historyData = historyRes.data

    totalPasien.value = antrianData.length + historyData.length
    sudahDitangani.value = historyData.length
    belumDitangani.value = antrianData.filter(a => a.status === 'Menunggu').length

    history.value = historyData.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
  } catch (err) {
    console.error('❌ Gagal mengambil data dashboard:', err)
  } finally {
    loading.value = false
  }
}

// HAPUS DATA
async function hapusHistory(id) {
  if (!confirm('Yakin ingin hapus data ini?')) return
  try {
    await axios.delete(`${API_HISTORY}/${id}`)
    history.value = history.value.filter(item => item.id !== id)
    sudahDitangani.value = history.value.length
    totalPasien.value = sudahDitangani.value + belumDitangani.value
  } catch (err) {
    console.error('❌ Gagal menghapus data:', err)
  }
}

// AUTO REFRESH
onMounted(() => {
  fetchData()
  setInterval(fetchData, 5000)
})
</script>

<style scoped>
/* Sidebar */
.sidebar {
  background-color: #4c52c3;
  width: 230px;
}
.logo {
  width: 70px;
  border-radius: 50%;
}

/* Sidebar Links */
.nav-link {
  color: white;
  padding: 10px 12px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.nav-link.active {
  background-color: rgba(255, 255, 255, 0.2);
}
.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15);
}

/* === CARD DESAIN BARU === */
.dashboard-card {
  display: flex;
  align-items: center;
  justify-content: start;
  border-radius: 10px;
  padding: 20px;
  height: 110px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  transition: 0.3s ease;
}
.dashboard-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
}
.icon-circle {
  width: 65px;
  height: 65px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-right: 15px;
}
.icon-circle i {
  font-size: 32px;
  color: #fff;
}
.card-text h6 {
  font-size: 16px;
}
.card-text h2 {
  font-size: 32px;
  margin: 0;
}

/* Table */
.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}
</style>
