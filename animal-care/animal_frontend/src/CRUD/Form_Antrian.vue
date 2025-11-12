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
              <router-link to="/" class="nav-link text-white">Home</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/layanan" class="nav-link text-white">Layanan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/hewan" class="nav-link text-white">Hewan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/antrian" class="nav-link text-white">Antrian</router-link>
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

    <!-- Form Antrian -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; min-height: 100vh; padding-top: 80px;"
    >
      <div class="container">
        <div class="row align-items-center">

          <div class="col-md-6 text-white mb-4 mb-md-0">
            <h2 class="fw-bold display-5 mb-4">Form Antrian</h2>
            <p class="text-light fs-5 lh-lg" style="max-width: 480px;">
              Silakan isi form di bawah ini untuk mendapatkan nomor antrian pemeriksaan
              hewan peliharaan Anda.
            </p>
          </div>


          <div class="col-md-6 d-flex justify-content-center">
            <div
              class="card shadow-lg border-0 p-4 rounded-4 w-100"
              style="max-width: 420px;"
            >
              <form @submit.prevent="submitForm">
                <div class="mb-3">
                  <label class="form-label small">Nama Hewan</label>
                  <input
                    v-model="form.nama_hewan"
                    type="text"
                    class="form-control rounded-pill"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label class="form-label small">Jenis Hewan</label>
                  <select
                    v-model.number="form.hewanId"
                    class="form-select rounded-pill"
                    required
                  >
                    <option disabled value="">-- Pilih Jenis Hewan --</option>
                    <option
                      v-for="hewan in jenisHewanList"
                      :key="hewan.hewanId"
                      :value="hewan.hewanId"
                    >
                      {{ hewan.nama_hewan }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label small">Keluhan</label>
                  <input
                    v-model="form.keluhan"
                    type="text"
                    class="form-control rounded-pill"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label class="form-label small">Layanan</label>
                  <select
                    v-model.number="form.layananId"
                    class="form-select rounded-pill"
                    required
                  >
                    <option disabled value="">-- Pilih Layanan --</option>
                    <option
                      v-for="layanan in layananList"
                      :key="layanan.layananId"
                      :value="layanan.layananId"
                    >
                      {{ layanan.nama_layanan }}
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label small">No. HP</label>
                  <input
                    v-model="form.no_hp"
                    type="text"
                    class="form-control rounded-pill"
                    required
                  />
                </div>

                <button
                  type="submit"
                  class="btn w-100 text-white fw-bold py-2"
                  :disabled="loading"
                  style="background-color: #00a8e8; border-radius: 20px; font-size: 1rem;"
                >
                  {{ loading ? "Mengirim..." : "Kirim" }}
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
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const isLoggedIn = ref(!!localStorage.getItem("token"));
const loading = ref(false);

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


async function fetchDropdownData() {
  try {
    const [resHewan, resLayanan] = await Promise.all([
      axios.get("http://127.0.0.1:8000/api/hewan"),
      axios.get("http://127.0.0.1:8000/api/layanan"),
    ]);

    jenisHewanList.value = resHewan.data.data || resHewan.data;
    layananList.value = resLayanan.data.data || resLayanan.data;
  } catch (err) {
    console.error("Gagal mengambil data dropdown:", err);
  }
}


async function submitForm() {
  const token = localStorage.getItem("token");
  const userData = localStorage.getItem("user");

  if (!token || !userData) {
    alert("⚠️ Harap login terlebih dahulu.");
    router.push("/login");
    return;
  }

  const user = JSON.parse(userData);
  const userId = user.id || user.userId;

  if (!userId) {
    alert("⚠️ Data user tidak valid, silakan login ulang.");
    router.push("/login");
    return;
  }

  loading.value = true;

  try {
    const payload = {
      userId: parseInt(userId),
      hewanId: parseInt(form.value.hewanId),
      layananId: parseInt(form.value.layananId),
      nama_hewan: form.value.nama_hewan,
      keluhan: form.value.keluhan,
      no_hp: form.value.no_hp,
    };

    console.log("Payload dikirim:", payload);

    const res = await axios.post("http://127.0.0.1:8000/api/antrian", payload, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    if (res.status === 200 || res.status === 201) {
      alert("✅ Antrian berhasil ditambahkan!");

      
      res.data.data.user = { name: user.name };

      router.push("/antrian");
    }
  } catch (err) {
    console.error("Gagal menambahkan antrian:", err.response || err);
    const message =
      err.response?.data?.message ||
      (err.response?.data?.errors
        ? Object.values(err.response.data.errors).flat().join(", ")
        : err.message);
    alert("❌ Gagal menambahkan antrian: " + message);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchDropdownData();
  isLoggedIn.value = !!localStorage.getItem("token");
});
</script>

<style scoped>
.nav-link.router-link-active,
.nav-link.router-link-exact-active {
  border-bottom: 2px solid #fff;
  font-weight: bold;
  transition: all 0.3s ease;
}
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
.logo-navbar {
  height: 56px;
  width: auto;
}
</style>
