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
          <RouterLink to="/Dasboard" class="nav-link active">
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
          <RouterLink to="/hewan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button class="btn btn-danger mt-auto w-100">Logout</button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <!-- STATISTIK CARD -->
      <div class="row g-3 mb-4">
        <!-- Total Pasien -->
        <div class="col-md-4">
          <div class="card stat-card bg-info text-white shadow-sm p-3 d-flex align-items-center">
            <div class="icon-circle me-3">
              <i class="ri-user-line"></i>
            </div>
            <div>
              <h5 class="mb-1 fw-semibold">Total Pasien</h5>
              <h2 class="fw-bold">{{ totalPasien }}</h2>
            </div>
          </div>
        </div>

        <!-- Ditangani -->
        <div class="col-md-4">
          <div class="card stat-card bg-success text-white shadow-sm p-3 d-flex align-items-center">
            <div class="icon-circle me-3">
              <i class="ri-check-line"></i>
            </div>
            <div>
              <h5 class="mb-1 fw-semibold">Ditangani</h5>
              <h2 class="fw-bold">{{ sudahDitangani }}</h2>
            </div>
          </div>
        </div>

        <!-- Belum Ditangani -->
        <div class="col-md-4">
          <div class="card stat-card bg-danger text-white shadow-sm p-3 d-flex align-items-center">
            <div class="icon-circle me-3">
              <i class="ri-time-line"></i>
            </div>
            <div>
              <h5 class="mb-1 fw-semibold">Belum Ditangani</h5>
              <h2 class="fw-bold">{{ belumDitangani }}</h2>
            </div>
          </div>
        </div>
      </div>

      <!-- HISTORY COUNT -->
      <div class="mb-2">
        <span class="badge bg-warning text-dark p-2">
          History : {{ history.length }}
        </span>
      </div>

      <!-- TABLE HISTORY -->
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
                  <td>{{ item.alamat }}</td>
                  <td><span class="badge bg-success">Ditangani</span></td>
                  <td>
                    <button
                      @click="hapusHistory(item.id)"
                      class="btn btn-sm btn-danger"
                    >
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

const history = ref([])
const loading = ref(false)

const totalPasien = ref(0)
const sudahDitangani = ref(0)
const belumDitangani = ref(0)

const API_HISTORY = 'http://localhost:3000/api/history'

async function fetchHistory() {
  try {
    loading.value = true
    const res = await fetch(API_HISTORY)
    const data = await res.json()
    history.value = data
    updateTotal()
  } catch (err) {
    console.error('Gagal ambil data history:', err)
  } finally {
    loading.value = false
  }
}

async function hapusHistory(id) {
  if (!confirm('Yakin ingin hapus data ini?')) return
  try {
    await fetch(`${API_HISTORY}/${id}`, { method: 'DELETE' })
    history.value = history.value.filter(item => item.id !== id)
    updateTotal()
  } catch (err) {
    console.error('Gagal hapus data:', err)
  }
}

function updateTotal() {
  totalPasien.value = history.value.length
  sudahDitangani.value = history.value.length
  belumDitangani.value = 0
}

onMounted(() => {
  fetchHistory()
})
</script>

<style scoped>
/* --- Sidebar --- */
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
  gap: 6px;
}
.nav-link i {
  font-size: 1.2rem;
}
.nav-link.active {
  background-color: rgba(255, 255, 255, 0.2);
}
.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.15);
}
.btn-danger {
  font-weight: 600;
}

/* --- Statistik Card --- */
.stat-card {
  border-radius: 14px;
  display: flex;
  flex-direction: row;
  align-items: center;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  cursor: default;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.icon-circle {
  width: 65px;
  height: 65px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.icon-circle i {
  font-size: 32px;
  color: #fff;
}

.stat-card h5 {
  font-size: 18px;
  letter-spacing: 0.3px;
}

.stat-card h2 {
  font-size: 30px;
  margin: 0;
}

/* --- Table --- */
.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}
</style>
