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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item me-4"><router-link to="/" class="nav-link text-white">Home</router-link></li>
            <li class="nav-item me-4"><router-link to="/layanan" class="nav-link text-white">Layanan</router-link></li>
            <li class="nav-item me-4"><router-link to="/hewan" class="nav-link text-white">Hewan</router-link></li>
            <li class="nav-item me-4"><router-link to="/antrian" class="nav-link text-white">Antrian</router-link></li>
            <li class="nav-item">
              <button v-if="isLoggedIn" @click="logout" class="btn btn-danger ms-3">Logout</button>
              <router-link v-else to="/login" class="btn btn-success ms-3">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Form Update Antrian -->
    <section class="d-flex align-items-center" style="background-color: #5b6ef5; min-height: 100vh; padding-top: 80px;">
      <div class="container">
        <div class="row align-items-center">
          <!-- Kiri -->
          <div class="col-md-6 text-white mb-4 mb-md-0">
            <h2 class="fw-bold display-5 mb-4">Form Update Antrian</h2>
            <p class="text-light fs-5 lh-lg" style="max-width: 480px;">
              Silakan ubah data antrian Anda. Data lama akan otomatis muncul di form ini.
            </p>
          </div>

          <!-- Kanan Form -->
          <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-lg border-0 p-4 rounded-4 w-100" style="max-width: 420px;">
              <form @submit.prevent="updateAntrian">
                <div class="mb-3">
                  <label class="form-label small">Nama Hewan</label>
                  <input v-model="form.nama_hewan" type="text" class="form-control rounded-pill" required />
                </div>

                <div class="mb-3">
                  <label class="form-label small">Jenis Hewan</label>
                  <select v-model.number="form.hewanId" class="form-select rounded-pill" required>
                    <option disabled value="">-- Pilih Jenis Hewan --</option>
                    <option v-for="hewan in jenisHewanList" :key="hewan.hewanId" :value="Number(hewan.hewanId)">
                      {{ hewan.nama_hewan }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label small">Keluhan</label>
                  <input v-model="form.keluhan" type="text" class="form-control rounded-pill" required />
                </div>

                <div class="mb-3">
                  <label class="form-label small">Layanan</label>
                  <select v-model.number="form.layananId" class="form-select rounded-pill" required>
                    <option disabled value="">-- Pilih Layanan --</option>
                    <option v-for="layanan in layananList" :key="layanan.layananId" :value="Number(layanan.layananId)">
                      {{ layanan.nama_layanan }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label small">No. HP</label>
                  <input v-model="form.no_hp" type="text" class="form-control rounded-pill" required />
                </div>

                <button type="submit" class="btn w-100 text-white fw-bold py-2"
                  :disabled="loading" style="background-color: #00a8e8; border-radius: 20px; font-size: 1rem;">
                  {{ loading ? "Menyimpan..." : "Update Data" }}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const id = route.params.id;
const loading = ref(false);
const isLoggedIn = ref(!!localStorage.getItem("token"));


const form = ref({
  nama_hewan: "",
  hewanId: "",
  layananId: "",
  keluhan: "",
  no_hp: "",
});

const jenisHewanList = ref([]);
const layananList = ref([]);

function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  isLoggedIn.value = false;
  alert("Anda telah logout.");
  router.push("/login");
}

async function fetchDropdowns() {
  try {
    const [resHewan, resLayanan] = await Promise.all([
      axios.get("http://127.0.0.1:8000/api/hewan"),
      axios.get("http://127.0.0.1:8000/api/layanan"),
    ]);
    jenisHewanList.value = resHewan.data.data || resHewan.data;
    layananList.value = resLayanan.data.data || resLayanan.data;
  } catch (err) {
    console.error("Gagal memuat dropdown:", err);
  }
}

async function fetchAntrian() {
  try {
    const token = localStorage.getItem("token");
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    const res = await axios.get(`http://127.0.0.1:8000/api/antrian/${id}`, { headers });
    const data = res.data.data || res.data;

    form.value = {
      nama_hewan: data.nama_hewan,
      hewanId: Number(data.hewanId ?? data.hewan?.hewanId),
      layananId: Number(data.layananId ?? data.layanan?.layananId),
      keluhan: data.keluhan,
      no_hp: data.no_hp,
    };
  } catch (err) {
    console.error("Gagal memuat data antrian:", err);
    alert("❌ Tidak dapat memuat data antrian.");
  }
}

async function updateAntrian() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    await axios.put(
      `http://127.0.0.1:8000/api/antrian/${id}`,
      form.value,
      { headers: { Authorization: `Bearer ${token}`, "Content-Type": "application/json" } }
    );
    alert("✅ Data antrian berhasil diperbarui!");
    router.push("/antrian");
  } catch (err) {
    console.error("❌ Gagal update:", err.response || err);
    alert("Terjadi kesalahan saat mengupdate data antrian.");
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  await fetchDropdowns();
  await fetchAntrian();
});
</script>

<style scoped>
.nav-link.router-link-active,
.nav-link.router-link-exact-active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
}
.nav-link {
  position: relative;
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
.logo-navbar {
  height: 56px;
  width: auto;
}
</style>
