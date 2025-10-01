<template>
  <div>
    <!-- Navbar (sticky) -->
    <nav
      class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top"
      style="background-color: #5b6ef5; z-index: 1000;"
    >
      <div class="container">
        <a
          class="navbar-brand fw-bold text-white d-flex align-items-center"
          href="#"
        >
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

    <!-- Hero Section (full screen) -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; height: 100vh; padding-top: 80px;"
    >
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Side Text -->
          <div class="col-md-6 text-white">
            <h2 class="fw-bold mb-3">Ayo Periksa Hewan Kesayanganmu</h2>
            <p class="mb-4" style="color: #dcdcdc;">
              Cek berkala kesehatan hewan kesanganan anda agar tetap sehat dan happy
            </p>
            <!-- Tombol diarahkan ke /form_Antrian -->
            <router-link
              to="/Form_Antrian"
              class="btn btn-success px-4 py-2 fw-bold"
            >
              Daftar antrian
            </router-link>
          </div>

          <!-- Right Side Image -->
          <div class="col-md-6 text-center">
            <img
              src="/Ilustrasi.png"
              alt="Ilustrasi Vet Clinic"
              class="img-fluid"
              style="max-width: 90%;"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Layanan Section -->
    <section id="layanan" class="py-5" style="background-color: #f5f7ff;">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 text-dark">Layanan Kami</h2>
        <p class="text-muted mb-5">
          Peliharaan sehat menghadirkan kebahagiaan abadi
        </p>

        <!-- Card hanya muncul kalau ada data -->
        <div class="row g-4" v-if="layananList.length > 0">
          <div class="col-md-4" v-for="layanan in layananList" :key="layanan.id">
            <div class="card shadow-sm border-0 h-100 text-center p-4">
              <div class="mb-3" v-if="layanan.icon">
                <img :src="layanan.icon" alt="icon" width="50" />
              </div>
              <h5 class="fw-bold text-primary">{{ layanan.title }}</h5>
              <p class="text-muted small">{{ layanan.description }}</p>
            </div>
          </div>
        </div>
        <!-- Kalau kosong -->
        <p v-else class="text-muted">Belum ada layanan tersedia</p>
      </div>
    </section>

    <!-- Jenis Hewan Section -->
    <section id="hewan" class="py-5" style="background-color: #f5f7ff;">
      <div class="container text-center">
        <h2 class="fw-bold mb-2 text-dark">Jenis Hewan</h2>
        <p class="text-muted mb-5">
          Macam-macam hewan peliharaan yang bisa dilayani
        </p>

        <!-- Card hanya muncul kalau ada data -->
        <div class="row g-4" v-if="hewanList.length > 0">
          <div class="col-md-4 col-lg-3" v-for="hewan in hewanList" :key="hewan.id">
            <div class="card shadow-sm border-0 h-100">
              <img
                v-if="hewan.image"
                :src="hewan.image"
                class="card-img-top"
                alt="hewan"
              />
              <div class="card-body">
                <h6 class="fw-bold text-primary">{{ hewan.name }}</h6>
                <p class="text-muted small">{{ hewan.description }}</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Kalau kosong -->
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
    };
  },
  mounted() {
    // API Layanan
    axios
      .get("") // ganti sesuai endpoint
      .then((res) => {
        this.layananList = Array.isArray(res.data) ? res.data : [];
      })
      .catch(() => {
        this.layananList = [];
      });

    // API Hewan
    axios
      .get("") // ganti sesuai endpoint
      .then((res) => {
        this.hewanList = Array.isArray(res.data) ? res.data : [];
      })
      .catch(() => {
        this.hewanList = [];
      });
  },
};
</script>

<style scoped>
html {
  scroll-behavior: smooth; /* biar smooth pas klik link */
}

.card img {
  height: 200px;
  object-fit: cover;
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

/* Logo navbar biar seimbang */
.logo-navbar {
  height: 56px; /* lebih besar */
  width: auto;
}
</style>
