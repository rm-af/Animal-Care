<template>
  <div
    class="container-fluid vh-100 d-flex align-items-center justify-content-center text-white"
    style="background-color: #5b6ef5; padding-top: 80px;"
  >
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
      </div>
    </nav>

    <!-- Konten -->
    <div class="container">
      <div class="row w-100 align-items-center">
        <!-- Kiri -->
        <div class="col-md-6 d-flex flex-column justify-content-center">
          <h1 class="fw-bold display-4 mb-3">Form Login</h1>
          <p class="lead fs-5">
            Silakan login untuk mengakses layanan dan mengelola antrian hewan peliharaan Anda.
          </p>
        </div>

        <!-- Kanan -->
        <div class="col-md-6 d-flex justify-content-center">
          <div class="card p-4 shadow rounded-4" style="min-width: 380px; max-width: 420px;">
            <form @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                  type="email"
                  v-model="form.email"
                  class="form-control"
                  placeholder="Masukkan email"
                  required
                />
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                  type="password"
                  v-model="form.password"
                  class="form-control"
                  placeholder="Masukkan password"
                  required
                />
              </div>

              <button
                type="submit"
                class="btn btn-custom w-100 fw-bold"
                :disabled="loading"
              >
                {{ loading ? "Memproses..." : "Login" }}
              </button>

              <p class="mt-3 text-center">
                Belum punya akun?
                <router-link to="/regis" class="fw-bold text-decoration-none">Daftar</router-link>
              </p>

              <p v-if="errorMessage" class="text-danger text-center mt-2">
                {{ errorMessage }}
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- POPUP ALERT -->
    <transition name="fade-scale">
      <div v-if="showPopup" class="popup-overlay">
        <div class="popup-card text-center p-4 rounded-4 shadow-lg">
          <img src="/popup.png" alt="success" class="popup-img mb-3" />
          <h4 class="fw-bold mb-2">Login Berhasil!</h4>
          <p class="text-muted">Selamat datang kembali di Animal Care.</p>
          <button class="btn btn-popup mt-3 fw-bold" @click="closePopup">Continue</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "LoginForm",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      loading: false,
      errorMessage: "",
      showPopup: false, // popup
    };
  },
  methods: {
    async handleSubmit() {
      this.loading = true;
      this.errorMessage = "";

      try {
        const response = await axios.post("http://127.0.0.1:8000/api/login", this.form);
        const user = response.data.user;
        const token = response.data.access_token;

        if (!user || !token) {
          throw new Error("Login gagal: data user atau token tidak ditemukan.");
        }

        // Simpan user & token
        localStorage.setItem("user", JSON.stringify(user));
        localStorage.setItem("token", token);

        localStorage.setItem("justLoggedIn", "true");


        // Tampilkan popup sukses
        this.showPopup = true;

        // Reset form
        this.form.email = "";
        this.form.password = "";
      } catch (error) {
        console.error("Error saat login:", error.response?.data || error.message);
        this.errorMessage =
          error.response?.data?.message || "Email atau password salah!";
      } finally {
        this.loading = false;
      }
    },
    closePopup() {
      this.showPopup = false;

      const user = JSON.parse(localStorage.getItem("user"));
      if (user && user.role === "Admin") {
        this.$router.push("/dasboard");
      } else {
        this.$router.push("/home");
      }
    },
  },
};
</script>

<style scoped>
.btn-custom {
  background-color: #485ae3ff;
  color: #fff;
  border: none;
  transition: all 0.2s ease-in-out;
}
.btn-custom:hover {
  background-color: #5b6ef5;
  color: #fff;
  transform: scale(1.03);
}
.logo-navbar {
  height: 56px;
  width: auto;
}

/* === POPUP STYLE === */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.popup-card {
  background: white;
  color: #000;
  width: 360px;
  animation: scaleIn 0.4s ease forwards;
}

.popup-img {
  width: 150px;
  height: auto;
  display: block;
  margin: 0 auto;
}

.btn-popup {
  background: linear-gradient(to right, #667eea, #764ba2);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
}

.btn-popup:hover {
  opacity: 0.9;
}

/* === ANIMATIONS === */
.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.3s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@keyframes scaleIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
