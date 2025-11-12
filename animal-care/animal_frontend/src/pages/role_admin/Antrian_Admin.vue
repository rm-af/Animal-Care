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
          <RouterLink to="/Dashboard" class="nav-link">
            <i class="ri-home-8-line me-2"></i> Dashboard
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
          <RouterLink to="/Hewan" class="nav-link">
            <i class="ri-add-circle-line me-2"></i> Hewan
          </RouterLink>
        </li>
      </ul>

      <button @click="logout" class="btn btn-danger mt-auto w-100">
        <i class="ri-logout-box-line me-2"></i> Logout
      </button>
    </aside>

    <!-- CONTENT -->
    <main class="flex-grow-1 p-4">
      <h3 class="fw-bold mb-2">Daftar Antrian Pasien</h3>
      <p class="text-muted mb-3">
        Daftar antrian pasien yang sedang dan telah ditangani oleh Admin.
      </p>

      <div class="mb-3">
        <span class="badge bg-warning text-dark p-2">
          Total antrian : {{ antrian.length }}
        </span>
      </div>

      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div v-if="loading" class="p-4 text-center text-muted">Memuat data...</div>
          <div v-else-if="antrian.length === 0" class="p-4 text-center text-muted">
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
                  <th>No HP</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in antrian" :key="getAntrianId(item)">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.user?.username || item.user?.name || 'Tidak diketahui' }}</td>
                  <td>{{ item.nama_hewan || '-' }}</td>
                  <td>{{ item.hewan?.nama_hewan || '-' }}</td>
                  <td>{{ item.keluhan || '-' }}</td>
                  <td>{{ item.layanan?.nama_layanan || '-' }}</td>
                  <td>{{ item.no_hp || '-' }}</td>
                  <td>
                    <span
                      class="badge"
                      :class="{
                        'bg-warning text-dark': item.status?.toLowerCase() === 'menunggu',
                        'bg-primary text-white': item.status?.toLowerCase() === 'diproses',
                        'bg-success text-white': item.status?.toLowerCase() === 'selesai'
                      }"
                    >
                      {{ item.status }}
                    </span>
                  </td>
                  <td>
                    <!-- Tombol Tangani -->
                    <button
                      v-if="item.status.toLowerCase() === 'menunggu'"
                      @click="tanganiAntrian(item)"
                      class="btn btn-sm btn-primary me-1"
                    >
                      Tangani
                    </button>

                    <!-- Tombol Selesai -->
                    <button
                      v-else-if="item.status.toLowerCase() === 'diproses'"
                      @click="selesaiAntrian(item)"
                      class="btn btn-sm btn-success me-1"
                    >
                      Selesai
                    </button>

                    <!-- Tombol Hapus -->
                    <button
                      v-else-if="item.status.toLowerCase() === 'selesai'"
                      @click="hapusAntrian(item)"
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
import { useRouter, RouterLink } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const antrian = ref([])
const loading = ref(false)

const API_ANTRIAN = 'http://127.0.0.1:8000/api/antrian'


const getAntrianId = (item) => item.antrianId || item.id_antrian || item.antrian_id || item.id


async function fetchAntrian() {
  loading.value = true
  try {
    const res = await axios.get(API_ANTRIAN)
    const data = Array.isArray(res.data) ? res.data : res.data.data
    antrian.value = data.map(a => ({
      ...a,
      antrianId: a.antrianId || a.id,
      status: a.status || 'menunggu',
      user: a.user || null,
      hewan: a.hewan || null,
      layanan: a.layanan || null,
    }))
  } catch (err) {
    console.error('❌ Gagal mengambil data antrian:', err)
  } finally {
    loading.value = false
  }
}


async function tanganiAntrian(item) {
  const id = getAntrianId(item)
  if (!confirm('Tandai antrian ini sebagai "Diproses"?')) return
  try {
    await axios.post(`${API_ANTRIAN}/${id}/tangani`)
    item.status = 'diproses'
    alert('✅ Antrian sedang diproses.')
  } catch (err) {
    console.error('❌ Gagal memproses antrian:', err)
    alert('Terjadi kesalahan saat memproses antrian.')
  }
}


async function selesaiAntrian(item) {
  const id = getAntrianId(item)
  if (!confirm('Apakah pasien ini sudah selesai ditangani?')) return
  try {
    await axios.post(`${API_ANTRIAN}/${id}/selesai`)
    item.status = 'selesai'
    alert('✅ Antrian ditandai selesai.')
  } catch (err) {
    console.error('❌ Gagal menyelesaikan antrian:', err)
    alert('Terjadi kesalahan saat menyelesaikan antrian.')
  }
}


async function hapusAntrian(item) {
  const id = getAntrianId(item)
  if (!confirm('Yakin ingin menghapus data antrian ini?')) return
  try {
    await axios.delete(`${API_ANTRIAN}/${id}/hapus-admin`)
    antrian.value = antrian.value.filter(a => getAntrianId(a) !== id)
    alert('🗑️ Data antrian berhasil dihapus.')
  } catch (err) {
    console.error('❌ Gagal menghapus antrian:', err)
    alert('Terjadi kesalahan saat menghapus data antrian.')
  }
}


function logout() {
  if (confirm('Yakin ingin logout?')) {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    router.push('/login')
  }
}

onMounted(fetchAntrian)
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
.btn {
  font-weight: 600;
}
</style>
