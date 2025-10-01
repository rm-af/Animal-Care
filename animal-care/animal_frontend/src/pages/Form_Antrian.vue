<template>
  <div>
    <!-- Navbar (sticky) -->
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
              <router-link to="/Antrian" class="nav-link text-white">Antrian</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/login" class="btn btn-danger ms-3">Logout</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Form Antrian Section -->
    <section
      class="d-flex align-items-center"
      style="background-color: #5b6ef5; min-height: 100vh; padding-top: 80px;"
    >
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Side Text -->
          <div class="col-md-6 text-white mb-4 mb-md-0">
            <h2 class="fw-bold mb-3">Form Antrian</h2>
            <p class="text-light">
              Silahkan mengisikan Form Antrian untuk mendapatkan antrian pada saat
              periksa hewan peliharaan anda
            </p>
          </div>

          <!-- Right Side Form -->
          <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-lg border-0 p-3 rounded-4 w-100" style="max-width: 400px;">
              <form @submit.prevent="submitForm">
                <div class="mb-2">
                  <label class="form-label small">Nama Pemilik</label>
                  <input v-model="form.namaPemilik" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">Nama Hewan</label>
                  <input v-model="form.namaHewan" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">Jenis Hewan</label>
                  <input v-model="form.jenisHewan" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">Keluhan</label>
                  <input v-model="form.keluhan" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">Layanan</label>
                  <input v-model="form.layanan" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-2">
                  <label class="form-label small">No. HP</label>
                  <input v-model="form.noHP" type="text" class="form-control rounded-pill" />
                </div>
                <div class="mb-3">
                  <label class="form-label small">Alamat</label>
                  <input v-model="form.alamat" type="text" class="form-control rounded-pill" />
                </div>
                <button
                  type="submit"
                  class="btn w-100 text-white fw-bold"
                  style="background-color: #00a8e8; border-radius: 20px;"
                >
                  Kirim
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
        alamat: "",
      },
    };
  },
  methods: {
    submitForm() {
      console.log("Data dikirim:", this.form);
      alert("Form berhasil dikirim!");
    },
  },
};
</script>

<style scoped>
/* Navbar underline */
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

/* Logo sizing */
.logo-navbar {
  height: 48px;
  width: auto;
}

/* Form responsive adjustment */
@media (max-width: 768px) {
  .card {
    max-width: 100% !important;
    padding: 1.25rem !important;
  }
}
</style>
