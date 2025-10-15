<template>
  <div>
    <!-- Navbar -->
    <nav
      class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top"
      style="background-color: #5b6ef5; z-index: 1000;"
    >
      <div class="container">
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="#">
          <img src="/animal.png" alt="logo" class="logo-navbar me-2" />
          <span class="fs-4">
            Animal <span class="text-warning">Care</span>
          </span>
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item me-4">
              <router-link to="/" class="nav-link text-white">Home</router-link>
            </li>
            <li class="nav-item me-4">
              <a href="#layanan" class="nav-link text-white">Layanan</a>
            </li>
            <li class="nav-item me-4">
              <a href="#hewan" class="nav-link text-white">Hewan</a>
            </li>
            <li class="nav-item me-4">
              <router-link to="/Antrian" class="nav-link text-white">Antrian</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="btn btn-success ms-3">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Section Antrian -->
    <section class="antrian-section">
      <div class="container">
        <div class="text-white mb-5">
          <h2 class="fw-bold display-5">Daftar Antrian</h2>
          <p class="fs-5" style="max-width: 600px; line-height: 1.6;">
            Jika daftar antrian sudah terbentuk, tunggu hingga status
            <strong>“sudah ditangani”</strong> baru hewan anda sudah ditangani oleh dokter.
          </p>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered align-middle shadow-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pemilik</th>
                <th>Nama Hewan</th>
                <th>Jenis Hewan</th>
                <th>Keluhan</th>
                <th>Layanan</th>
                <th>No. Hp</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in antrian" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>{{ item.nama_pemilik }}</td>
                <td>{{ item.nama_hewan }}</td>
                <td>{{ item.jenis_hewan }}</td>
                <td>{{ item.keluhan }}</td>
                <td>{{ item.layanan }}</td>
                <td>{{ item.no_hp }}</td>
                <td>
                  <span
                    class="badge"
                    :class="{
                      'bg-warning text-dark': item.status === 'Menunggu',
                      'bg-info text-dark': item.status === 'Proses',
                      'bg-success': item.status === 'Selesai'
                    }"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td>
                  <!-- Tombol Edit pakai router-link -->
                  <router-link
                    :to="`/updateantrian/${item.id}`"
                    class="btn btn-sm btn-outline-warning me-2">
                    ✏️ Edit
                  </router-link>

                  <!-- Tombol Hapus -->
                  <button
                    class="btn btn-sm btn-outline-danger"
                    @click="deleteAntrian(item.id)">
                    🗑️ Hapus
                  </button>
                </td>
              </tr>
              <tr v-if="antrian.length === 0">
                <td colspan="9" class="text-center text-muted py-4">
                  Belum ada data antrian.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      antrian: [] // data antrian dari API
    };
  },
   mounted() {
    // Fetch data antrian
    fetch("http://localhost:8000/api/antrian")
      .then(res => res.json())
      .then(data => this.antrian = data)
      .catch(err => console.error(err));
  },
  methods: {
    // Hapus data antrian
    deleteAntrian(id) {
      if (confirm("Yakin ingin menghapus data ini?")) {
        fetch(`http://localhost:8000/api/antrian/${id}`, {
          method: "DELETE",
        })
          .then(res => {
            if (res.ok) {
              this.antrian = this.antrian.filter(item => item.id !== id);
            } else {
              console.error("Gagal menghapus data");
            }
          })
          .catch(err => console.error(err));
      }
    }
  }
};
</script>

<style scoped>
.antrian-section {
  background-color: #5b6ef5;
  padding: 160px 0 80px; /* biar tidak terlalu ke atas */
  min-height: 100vh;
}

/* Tabel */
.table {
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.table thead {
  background: #1de9b6; /* hijau toska terang */
  color: #fff;
  text-align: center;
}

.table thead th {
  font-weight: 600;
  padding: 14px;
  font-size: 15px;
}

.table tbody td {
  text-align: center;
  padding: 12px;
  font-size: 14px;
}

.table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

/* Tombol Aksi */
.btn-outline-warning,
.btn-outline-danger {
  border: none;
}

.logo-navbar {
  height: 56px;
  width: auto;
}

/* Active menu underline */
.nav-link.router-link-active,
.nav-link.router-link-exact-active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
  transition: all 0.3s ease;
}

/* Hover underline effect */
.nav-link {
  position: relative;
  transition: all 0.3s ease;
}
.nav-link::after {
  content: "";
  position: absolute;
  width: 0%;
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: #fff;
  transition: width 0.3s ease;
}
.nav-link:hover::after {
  width: 100%;
}
</style>
