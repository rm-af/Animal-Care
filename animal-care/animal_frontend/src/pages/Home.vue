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

    <!-- Hero Section -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; height: 100vh; padding-top: 80px;"
    >
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Side Text -->
          <div class="col-md-6 text-white">
            <h1 class="fw-bold mb-4 display-4" style="line-height: 1.3;">
              Ayo Periksa Hewan <br /> Kesayanganmu
            </h1>
            <p class="mb-4 fs-5" style="color: #e6e6e6; max-width: 480px;">
              Cek berkala kesehatan hewan kesayangan anda agar tetap sehat dan happy
            </p>
            <router-link to="/Form_Antrian" class="btn btn-success btn-daftar fw-bold">
              Daftar antrian
            </router-link>
          </div>

          <!-- Right Side Image -->
          <div class="col-md-6 text-center">
            <img
              src="/Ilustrasi.png"
              alt="Ilustrasi Vet Clinic"
              class="img-fluid hero-img"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="py-5 section-bg">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 section-title">Layanan Kami</h2>
        <p class="text-muted mb-5">Peliharaan sehat menghadirkan kebahagiaan abadi</p>

        <div class="row g-4" v-if="layananList.length > 0">
          <div class="col-md-4" v-for="layanan in layananList" :key="layanan.id">
            <div class="card shadow-sm border-0 h-100 text-center p-4">
              <div class="mb-3" v-if="layanan.logo">
                <img :src="layanan.logo" alt="icon" width="50" />
              </div>
              <h5 class="fw-bold section-title">{{ layanan.nama_layanan }}</h5>
              <p class="text-muted small">{{ layanan.deskripsi }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-muted">Belum ada layanan tersedia</p>
      </div>
    </section>

    <!-- Jenis Hewan Section -->
    <section id="hewan" class="py-5 section-bg">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 section-title">Jenis Hewan</h2>
        <p class="text-muted mb-5">Macam-macam hewan peliharaan yang bisa dilayani</p>

        <div class="row g-4" v-if="hewanList.length > 0">
          <div class="col-md-4 col-lg-3" v-for="hewan in hewanList" :key="hewan.id">
            <div class="card shadow-sm border-0 h-100">
              <img v-if="hewan.foto" :src="hewan.foto" class="card-img-top" alt="hewan" />
              <div class="card-body">
                <h6 class="fw-bold section-title">{{ hewan.nama_hewan }}</h6>
                <p class="text-muted small">{{ hewan.deskripsi }}</p>
              </div>
            </div>
          </div>
        </div>
        <p v-else class="text-muted">Belum ada data hewan tersedia</p>
      </div>
    </section>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      layananList: [],
      hewanList: [],
      refreshInterval: null,
    };
  },
  mounted() {
    this.fetchLayanan();
    this.fetchHewan();

    // auto refresh setiap 10 detik biar realtime
    this.refreshInterval = setInterval(() => {
      this.fetchLayanan();
      this.fetchHewan();
    }, 10000);
  },
  beforeUnmount() {
    clearInterval(this.refreshInterval);
  },
  methods: {
    async fetchLayanan() {
      try {
        const res = await axios.get("http://localhost:3000/api/layanan");
        this.layananList = Array.isArray(res.data) ? res.data : [];
      } catch (error) {
        console.error("Gagal fetch layanan:", error);
        this.layananList = [];
      }
    },
    async fetchHewan() {
      try {
        const res = await axios.get("http://localhost:3000/api/hewan");
        this.hewanList = Array.isArray(res.data) ? res.data : [];
      } catch (error) {
        console.error("Gagal fetch hewan:", error);
        this.hewanList = [];
      }
    },
  },
};
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

.card img {
  height: 200px;
  object-fit: cover;
}

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

.hero-img {
  max-width: 95%;
  transform: scale(1.05);
}

.btn-daftar {
  font-size: 1.2rem;
  padding: 14px 40px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}
.btn-daftar:hover {
  background-color: #28a745;
  transform: translateY(-2px);
}

.section-bg {
  background-color: #e8edff;
}
.section-title {
  color: #5b6ef5;
}
</style>
