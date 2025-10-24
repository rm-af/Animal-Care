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
              <router-link to="/layanan" class="nav-link text-white">Layanan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/hewan" class="nav-link text-white">Hewan</router-link>
            </li>
            <li class="nav-item me-4">
              <router-link to="/antrian" class="nav-link text-white">Antrian</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="btn btn-success ms-3">Login</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Form Update Antrian -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; min-height: 100vh; padding-top: 80px;"
    >
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Side -->
          <div class="col-md-6 text-white mb-4 mb-md-0">
            <h2 class="fw-bold display-5 mb-4">Form Update Antrian</h2>
            <p class="text-light fs-5 lh-lg" style="max-width: 480px;">
              Silahkan ubah data antrian anda jika terdapat kesalahan atau perubahan informasi.
              Data sebelumnya akan otomatis ditampilkan.
            </p>
          </div>

          <!-- Right Side -->
          <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-lg border-0 p-4 rounded-4 w-100" style="max-width: 420px;">
              <form @submit.prevent="updateAntrian">
                <div class="mb-3">
                  <label class="form-label small">Nama Pemilik</label>
                  <input v-model="form.namaPemilik" type="text" class="form-control rounded-pill" required />
                </div>
                <div class="mb-3">
                  <label class="form-label small">Nama Hewan</label>
                  <input v-model="form.namaHewan" type="text" class="form-control rounded-pill" required />
                </div>
                <div class="mb-3">
                  <label class="form-label small">Jenis Hewan</label>
                  <select v-model="form.jenisHewan" class="form-select rounded-pill" required>
                    <option disabled value="">-- Pilih Jenis Hewan --</option>
                    <option v-for="hewan in jenisHewanList" :key="hewan.id" :value="hewan.nama">
                      {{ hewan.nama }}
                    </option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label small">Keluhan</label>
                  <input v-model="form.keluhan" type="text" class="form-control rounded-pill" required />
                </div>
                <div class="mb-3">
                  <label class="form-label small">Layanan</label>
                  <select v-model="form.layanan" class="form-select rounded-pill" required>
                    <option disabled value="">-- Pilih Layanan --</option>
                    <option v-for="layanan in layananList" :key="layanan.id" :value="layanan.nama">
                      {{ layanan.nama }}
                    </option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label small">No. HP</label>
                  <input v-model="form.noHP" type="text" class="form-control rounded-pill" required />
                </div>
                <button
                  type="submit"
                  class="btn w-100 text-white fw-bold py-2"
                  :disabled="loading"
                  style="background-color: #00a8e8; border-radius: 20px; font-size: 1rem;"
                >
                  {{ loading ? "Mengupdate..." : "Update" }}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      form: {
        namaPemilik: "",
        namaHewan: "",
        jenisHewan: "",
        keluhan: "",
        layanan: "",
        noHP: "",
      },
      jenisHewanList: [],
      layananList: [],
      loading: false,
    };
  },
  methods: {
    async mountedData() {
      const id = this.$route.params.id;
      try {
        // Ambil dropdown jenis hewan & layanan
        const [resHewan, resLayanan] = await Promise.all([
          axios.get("http://localhost:8000/api/jenis_hewan"),
          axios.get("http://localhost:8000/api/layanan"),
        ]);
        this.jenisHewanList = resHewan.data;
        this.layananList = resLayanan.data;

        // Ambil data antrian berdasarkan ID
        const resAntrian = await axios.get(`http://localhost:8000/api/antrian/${id}`);
        const data = resAntrian.data;

        // Isi form dengan data lama
        this.form = {
          namaPemilik: data.namaPemilik,
          namaHewan: data.namaHewan,
          jenisHewan: data.jenisHewan,
          keluhan: data.keluhan,
          layanan: data.layanan,
          noHP: data.noHP,
        };
      } catch (err) {
        console.error(err);
        alert("❌ Gagal memuat data antrian!");
      }
    },

    async updateAntrian() {
      this.loading = true;
      const id = this.$route.params.id;
      try {
        await axios.put(`http://localhost:8000/api/antrian/${id}`, this.form);
        alert("✅ Data antrian berhasil diperbarui!");
        this.$router.push("/Antrian");
      } catch (err) {
        console.error(err);
        alert("❌ Terjadi kesalahan saat mengupdate data antrian");
      } finally {
        this.loading = false;
      }
    },
  },
  mounted() {
    this.mountedData();
  },
};
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
