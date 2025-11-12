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
          <RouterLink to="/Hewan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button @click="logout" class="btn btn-danger mt-auto w-100">
        <i class="ri-logout-box-line me-2"></i> Logout
      </button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-4">Dashboard Admin</h3>

      <!-- CARD STATISTIK -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="dashboard-card bg-info text-white">
            <div class="icon-circle"><i class="ri-user-line"></i></div>
            <div class="card-text">
              <h6>Total Pasien</h6>
              <h2>{{ totalPasien }}</h2>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="dashboard-card bg-success text-white">
            <div class="icon-circle"><i class="ri-check-line"></i></div>
            <div class="card-text">
              <h6>Ditangani</h6>
              <h2>{{ sudahDitangani }}</h2>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="dashboard-card bg-danger text-white">
            <div class="icon-circle"><i class="ri-time-line"></i></div>
            <div class="card-text">
              <h6>Belum Ditangani</h6>
              <h2>{{ belumDitangani }}</h2>
            </div>
          </div>
        </div>
      </div>

      
      <div class="mb-3">
        <span class="badge bg-primary text-white p-2">
          History (Selesai): {{ history.length }} data
        </span>
      </div>

      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div v-if="loading" class="p-4 text-center text-muted">Memuat data...</div>
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
                  <th>No HP</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in history" :key="item.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.user?.username || item.nama_pemilik || 'Tidak diketahui' }}</td>
                  <td>{{ item.nama_hewan || item.hewan?.nama_hewan || '-' }}</td>
                  <td>{{ item.hewan?.nama_hewan || '-' }}</td>
                  <td>{{ item.keluhan || '-' }}</td>
                  <td>{{ item.layanan?.nama_layanan || '-' }}</td>
                  <td>{{ item.no_hp || '-' }}</td>
                  <td><span class="badge bg-success">Selesai</span></td>
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
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const antrianData = ref([])
const history = ref([])

const totalPasien = ref(0)
const sudahDitangani = ref(0)
const belumDitangani = ref(0)

const API_ANTRIAN = 'http://localhost:8000/api/antrian'


function logout() {
  if (confirm('Yakin ingin logout?')) {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}


async function fetchData() {
  loading.value = true
  try {
    const res = await axios.get(API_ANTRIAN)
    const data = Array.isArray(res.data) ? res.data : res.data.data || []
    antrianData.value = data


    const menunggu = data.filter(a => (a.status || '').toLowerCase() === 'menunggu')
    const diproses = data.filter(a => (a.status || '').toLowerCase() === 'diproses')
    const selesai = data.filter(a => (a.status || '').toLowerCase() === 'selesai')


    totalPasien.value = data.length
    sudahDitangani.value = diproses.length + selesai.length
    belumDitangani.value = menunggu.length

    // History = antrian yang sudah selesai
    history.value = selesai.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at))
  } catch (err) {
    console.error('❌ Gagal fetch data dashboard:', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)
</script>

<style scoped>
.sidebar { background-color: #4c52c3; width: 230px; }
.logo { width: 70px; border-radius: 50%; }
.nav-link { color: white; padding: 10px 12px; border-radius: 6px; display: flex; align-items: center; gap: 6px; }
.nav-link.active { background-color: rgba(255,255,255,0.2); }
.nav-link:hover { background-color: rgba(255,255,255,0.15); }
.dashboard-card { display: flex; align-items: center; border-radius: 10px; padding: 20px; height: 110px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); transition: 0.3s ease; }
.dashboard-card:hover { transform: translateY(-3px); box-shadow: 0 6px 14px rgba(0,0,0,0.15); }
.icon-circle { width: 65px; height: 65px; border-radius: 50%; background-color: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; margin-right: 15px; }
.icon-circle i { font-size: 32px; color: #fff; }
.card-text h6 { font-size: 16px; }
.card-text h2 { font-size: 32px; margin: 0; }
.table th, .table td { text-align: center; vertical-align: middle; }
</style>
