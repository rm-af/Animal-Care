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
          <span class="fs-4">Animal <span class="text-warning">Care</span></span>
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
              <router-link to="/" class="nav-link text-white nav-anim" exact>Home</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/layanan" class="nav-link text-white nav-anim">Layanan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/hewan" class="nav-link text-white nav-anim">Hewan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/antrian" class="nav-link text-white nav-anim">Antrian</router-link>
            </li>
            <li class="nav-item">
              <button v-if="isLoggedIn" @click="logout" class="btn btn-danger ms-3">
                Logout
              </button>
              <router-link v-else to="/login" class="btn btn-success ms-3">Login</router-link>
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
            Setelah mendaftar, status akan <strong>“Menunggu”</strong>.<br />
            Jika dokter sedang menangani, status akan berubah menjadi
            <strong>“Diproses”</strong>.<br />
            Jika sudah selesai, status berubah menjadi <strong>“Selesai”</strong>.
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
                <th>No. HP</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in antrian" :key="item.antrianId">
                <td>{{ index + 1 }}</td>
                <td>{{ item.user?.username || getUserName(item.userId) || "Tidak diketahui" }}</td>
                <td>{{ item.nama_hewan }}</td>
                <td>{{ item.hewan?.nama_hewan || '-' }}</td>
                <td>{{ item.keluhan || "-" }}</td>
                <td>{{ item.layanan?.nama_layanan || item.nama_layanan || "-" }}</td>
                <td>{{ item.no_hp || "-" }}</td>
                <td>
                  <span
                    class="badge"
                    :class="{
                      'bg-warning text-dark': item.status?.toLowerCase() === 'menunggu',
                      'bg-primary text-white': item.status?.toLowerCase() === 'diproses',
                      'bg-success text-white': item.status?.toLowerCase() === 'selesai'
                    }"
                  >
                    {{ item.status || '-' }}
                  </span>
                </td>

                <!-- ======================== -->
                <!-- 🔥 A K S I  D I P E R B A R U I -->
                <!-- ======================== -->
                <td>
                  <!-- STATUS MENUNGGU -->
                  <template v-if="item.status?.toLowerCase() === 'menunggu'">
                    <router-link
                      :to="{ name: 'Update_Antrian', params: { id: item.antrianId } }"
                      class="text-warning me-3 fs-5"
                      title="Edit"
                    >
                      <i class="ri-edit-2-line"></i>
                    </router-link>
                    <i
                      class="ri-delete-bin-2-line text-danger fs-5"
                      style="cursor: pointer;"
                      title="Batalkan"
                      @click="showDeletePopup(item.antrianId)"
                    ></i>
                  </template>

                  <!-- STATUS SUDAH SELESAI & BELUM BAYAR -->
                  <template
                    v-else-if="item.status?.toLowerCase() === 'selesai' && item.status_pembayaran !== 'sukses'"
                  >
                    <button class="btn btn-primary btn-sm" @click="goToPayment(item.antrianId)">
                      Bayar
                    </button>
                  </template>

                  <!-- STATUS SELESAI & SUDAH BAYAR -->
                  <template
                    v-else-if="item.status?.toLowerCase() === 'selesai' && item.status_pembayaran === 'sukses'"
                  >
                    <span class="badge bg-success text-white">Lunas</span>
                  </template>

                  <!-- STATUS DIPROSES -->
                  <template v-else>
                    <span class="text-muted small fst-italic">Tidak tersedia</span>
                  </template>
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

    <!-- Popup Batalkan -->
    <div v-if="popupVisible" class="popup-overlay">
      <div class="popup-card">
        <img src="/cancel.png" alt="Cancel Icon" class="popup-icon" />
        <h4 class="fw-bold mb-2">Batalkan Antrian?</h4>
        <p class="text-secondary mb-4">Apakah Anda yakin ingin membatalkan antrian ini?</p>
        <div class="d-flex justify-content-center gap-3">
          <button class="btn btn-secondary px-4" @click="popupVisible = false">Tidak</button>
          <button class="btn btn-danger px-4" @click="confirmDelete">Ya, Batalkan</button>
        </div>
      </div>
    </div>

    <!-- Popup Sukses -->
    <div v-if="successPopup" class="popup-overlay">
      <div class="popup-card">
        <img src="/popup.png" alt="Success Icon" class="popup-icon" />
        <h4 class="fw-bold mb-2 text-success">Antrian Dibatalkan</h4>
        <p class="text-secondary mb-4">Antrian berhasil dibatalkan.</p>
        <button class="btn btn-primary px-4" @click="successPopup = false">OK</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      antrian: [],
      users: [],
      refreshInterval: null,
      isLoggedIn: false,
      popupVisible: false,
      successPopup: false,
      deleteTargetId: null,
    };
  },
  mounted() {
    this.checkLogin();
    this.fetchAntrian();
    this.fetchUsers();
    this.refreshInterval = setInterval(this.fetchAntrian, 5000);
  },
  beforeUnmount() {
    clearInterval(this.refreshInterval);
  },
  methods: {
    async fetchAntrian() {
      try {
        const token = localStorage.getItem("token");
        const headers = token ? { Authorization: `Bearer ${token}` } : {};
        const res = await axios.get("http://127.0.0.1:8000/api/antrian", { headers });

        const user = JSON.parse(localStorage.getItem("user"));
        const userId = user?.id || user?.userId;

        const data = (res.data.data || res.data).map(a => ({
          ...a,
          antrianId: a.antrianId || a.id,
          userId: a.userId || a.user_id || a.user?.id || null,
          hewanId: a.hewanId || a.hewan_id || a.hewan?.id || null,
        }));

        this.antrian = data.filter(a => String(a.userId) === String(userId));
      } catch (err) {
        console.error("❌ Gagal memuat data antrian:", err);
      }
    },

    async fetchUsers() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/user");
        this.users = res.data.data || res.data;
      } catch (err) {
        console.warn("ℹ️ Tidak bisa memuat user:", err);
      }
    },

    getUserName(userId) {
      const user = this.users.find(u => u.id === userId || u.userId === userId);
      return user ? user.username || user.name : null;
    },

    showDeletePopup(id) {
      this.deleteTargetId = id;
      this.popupVisible = true;
    },

    async confirmDelete() {
      try {
        const token = localStorage.getItem("token");
        await axios.delete(`http://127.0.0.1:8000/api/antrian/${this.deleteTargetId}`, {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.antrian = this.antrian.filter(a => a.antrianId !== this.deleteTargetId);
        this.popupVisible = false;
        this.successPopup = true;
      } catch (err) {
        console.error("❌ Gagal membatalkan antrian:", err);
        alert("Terjadi kesalahan saat membatalkan antrian.");
      }
    },

    checkLogin() {
      const token = localStorage.getItem("token");
      if (!token) this.$router.push("/login");
      this.isLoggedIn = !!token;
    },

    logout() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.isLoggedIn = false;
      alert("Berhasil logout!");
      this.$router.push("/login");
    },

    // 🔥 METHOD BARU UNTUK MIDTRANS
    goToPayment(id) {
      this.$router.push(`/payment/${id}`);
    },
  },
};
</script>

<style scoped>
.antrian-section {
  background-color: #5b6ef5;
  padding: 160px 0 80px;
  min-height: 100vh;
}
.table {
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.table thead {
  background: #1de9b6;
  color: #000;
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
  color: #000;
}
.table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}
.badge {
  font-size: 13px;
  padding: 8px 12px;
  border-radius: 8px;
}
.ri-edit-2-line:hover {
  color: #e7b700;
  transform: scale(1.1);
  transition: 0.2s;
}
.ri-delete-bin-2-line:hover {
  color: #ff3333;
  transform: scale(1.1);
  transition: 0.2s;
}
.logo-navbar {
  height: 56px;
  width: auto;
}
.nav-anim {
  position: relative;
  padding-bottom: 6px;
  font-weight: 500;
  transition: color 0.3s ease;
}
.nav-anim::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: #ffffff;
  transition: width 0.3s ease;
}
.nav-anim:hover::after,
.nav-anim.router-link-active::after {
  width: 100%;
}
.nav-anim.router-link-active {
  font-weight: 600;
  color: #fff !important;
}
.nav-anim:hover {
  color: #ffffff !important;
  text-shadow: 0 0 6px rgba(255, 255, 255, 0.7);
}

/* Popup */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
.popup-card {
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  text-align: center;
  max-width: 380px;
  width: 90%;
  animation: popIn 0.3s ease;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}
.popup-icon {
  width: 90px;
  margin-bottom: 15px;
}
@keyframes popIn {
  from {
    opacity: 0;
    transform: scale(0.7);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
